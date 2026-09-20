<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'current_workspace_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Workspaces this user is part of.
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * The currently active workspace for this user.
     */
    public function currentWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }

    /**
     * Transactions created by this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if user is in a given workspace.
     */
    public function isInWorkspace(Workspace $workspace): bool
    {
        return $this->workspaces()->where('workspaces.id', $workspace->id)->exists();
    }

    /**
     * Check if user is the owner of the current workspace.
     */
    public function isOwnerOfCurrentWorkspace(): bool
    {
        if (! $this->current_workspace_id) {
            return false;
        }

        $pivot = $this->workspaces()->where('workspaces.id', $this->current_workspace_id)->first()?->pivot;

        return $pivot && $pivot->role === 'owner';
    }

    /**
     * Get initials of user name (e.g. "Gabriel Silva" -> "GS").
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = '';

        if (! empty($words[0])) {
            $initials .= mb_substr($words[0], 0, 1);
        }

        if (count($words) > 1 && ! empty(end($words))) {
            $initials .= mb_substr(end($words), 0, 1);
        }

        return mb_strtoupper($initials);
    }
}
