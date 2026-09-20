<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Services\WorkspaceInvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function __construct(
        protected WorkspaceInvitationService $invitationService
    ) {}

    /**
     * Switch active workspace for the authenticated user.
     */
    public function switch(Request $request, Workspace $workspace): RedirectResponse
    {
        $user = $request->user();

        if (! $user->isInWorkspace($workspace)) {
            abort(403, 'Você não pertence a este workspace.');
        }

        $user->current_workspace_id = $workspace->id;
        $user->save();

        return redirect()->back()->with('success', "Workspace alternado para {$workspace->name}.");
    }

    /**
     * Join an existing workspace via invite code.
     */
    public function join(Request $request): RedirectResponse
    {
        $request->validate([
            'invite_code' => ['required', 'string', 'min:8', 'max:8'],
        ], [
            'invite_code.required' => 'Informe o código de convite.',
            'invite_code.min' => 'O código de convite deve ter 8 caracteres.',
            'invite_code.max' => 'O código de convite deve ter 8 caracteres.',
        ]);

        $workspace = $this->invitationService->joinWorkspace($request->user(), $request->input('invite_code'));

        return redirect()->route('dashboard')->with('success', "Você entrou no workspace {$workspace->name} com sucesso!");
    }

    /**
     * Regenerate the invite code for the current workspace.
     */
    public function regenerateInviteCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $workspace = $user->currentWorkspace;

        if (! $workspace) {
            abort(404, 'Workspace não encontrado.');
        }

        if (! $user->isOwnerOfCurrentWorkspace()) {
            abort(403, 'Apenas o proprietário do workspace pode gerar um novo código.');
        }

        $newCode = $this->invitationService->regenerateInviteCode($workspace);

        return redirect()->back()->with('success', "Novo código de convite gerado: {$newCode}");
    }

    /**
     * Show workspace settings & members.
     */
    public function settings(Request $request): View
    {
        $workspace = $request->user()->currentWorkspace;
        $members = $workspace->users()->get();

        return view('workspaces.settings', [
            'workspace' => $workspace,
            'members' => $members,
        ]);
    }

    /**
     * Update workspace details.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $workspace = $request->user()->currentWorkspace;
        $workspace->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->back()->with('success', 'Configurações do workspace atualizadas.');
    }
}
