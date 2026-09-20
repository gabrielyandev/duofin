<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index(Request $request): View
    {
        $accounts = Account::orderBy('name')->get();

        return view('accounts.index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Store a newly created account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'initial_balance' => ['required', 'numeric'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        Account::create([
            'workspace_id' => $request->user()->current_workspace_id,
            'name' => $validated['name'],
            'initial_balance' => (float) $validated['initial_balance'],
            'color' => $validated['color'],
        ]);

        return redirect()->back()->with('success', 'Conta bancária criada com sucesso.');
    }

    /**
     * Update the specified account.
     */
    public function update(Request $request, Account $account): RedirectResponse
    {
        if ($account->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'initial_balance' => ['required', 'numeric'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        $account->update([
            'name' => $validated['name'],
            'initial_balance' => (float) $validated['initial_balance'],
            'color' => $validated['color'],
        ]);

        return redirect()->back()->with('success', 'Conta bancária atualizada com sucesso.');
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Request $request, Account $account): RedirectResponse
    {
        if ($account->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        $account->delete();

        return redirect()->back()->with('success', 'Conta bancária excluída com sucesso.');
    }
}
