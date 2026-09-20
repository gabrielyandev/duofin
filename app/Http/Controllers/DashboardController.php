<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Workspace;
use App\Services\DashboardMetricsService;
use App\Services\WorkspaceInvitationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the financial dashboard.
     */
    public function index(Request $request, DashboardMetricsService $metricsService, WorkspaceInvitationService $invitationService): View
    {
        $user = $request->user();
        $workspace = $user?->currentWorkspace;

        if (! $workspace && $user) {
            $workspace = $user->workspaces()->first();
            if ($workspace) {
                $user->current_workspace_id = $workspace->id;
                $user->save();
            } else {
                $firstName = explode(' ', trim($user->name))[0];
                $workspace = Workspace::create([
                    'name' => 'Finanças de '.$firstName,
                    'invite_code' => $invitationService->generateUniqueInviteCode(),
                ]);
                $workspace->users()->attach($user->id, ['role' => 'owner']);
                $user->current_workspace_id = $workspace->id;
                $user->save();
            }
        }

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $metrics = $metricsService->getMetrics($workspace, $year, $month);
        $categories = Category::orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();

        return view('dashboard', [
            'metrics' => $metrics,
            'categories' => $categories,
            'accounts' => $accounts,
            'currentYear' => $year,
            'currentMonth' => $month,
        ]);
    }
}
