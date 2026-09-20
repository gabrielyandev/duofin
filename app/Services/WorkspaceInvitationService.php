<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WorkspaceInvitationService
{
    /**
     * Generate a unique 8-character uppercase alphanumeric code.
     */
    public function generateUniqueInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Workspace::where('invite_code', $code)->exists());

        return $code;
    }

    /**
     * Join a user to a workspace using an invite code.
     * Enforces the maximum 2-member limit per workspace.
     *
     * @throws ValidationException
     */
    public function joinWorkspace(User $user, string $inviteCode): Workspace
    {
        $code = Str::upper(trim($inviteCode));

        $workspace = Workspace::where('invite_code', $code)->first();

        if (! $workspace) {
            throw ValidationException::withMessages([
                'invite_code' => 'Código de convite inválido ou não encontrado.',
            ]);
        }

        // Check if user is already in this workspace
        if ($workspace->users()->where('users.id', $user->id)->exists()) {
            // Already a member, just activate workspace
            $user->current_workspace_id = $workspace->id;
            $user->save();

            return $workspace;
        }

        // Inviolable rule: strictly maximum 2 members per workspace
        $memberCount = $workspace->users()->count();
        if ($memberCount >= 2) {
            throw ValidationException::withMessages([
                'invite_code' => 'Este workspace já atingiu a capacidade máxima de 2 membros vinculados.',
            ]);
        }

        // Attach as partner
        $workspace->users()->attach($user->id, ['role' => 'partner']);

        // Switch user's active workspace
        $user->current_workspace_id = $workspace->id;
        $user->save();

        return $workspace;
    }

    /**
     * Regenerate a workspace's invite code.
     */
    public function regenerateInviteCode(Workspace $workspace): string
    {
        $newCode = $this->generateUniqueInviteCode();
        $workspace->update(['invite_code' => $newCode]);

        return $newCode;
    }
}
