<?php

namespace App\Models;

use App\Models\Scopes\WorkspaceScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([WorkspaceScope::class])]
class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workspace_id',
        'name',
        'initial_balance',
        'color',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'initial_balance' => 'decimal:2',
        ];
    }

    /**
     * Workspace this account belongs to.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Transactions tied to this account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate current balance based on paid transactions.
     */
    public function getCurrentBalanceAttribute(): float
    {
        $paidIncome = (float) $this->transactions()
            ->where('type', 'income')
            ->where('status', 'paid')
            ->sum('amount');

        $paidExpense = (float) $this->transactions()
            ->where('type', 'expense')
            ->where('status', 'paid')
            ->sum('amount');

        return (float) $this->initial_balance + $paidIncome - $paidExpense;
    }

    /**
     * Formatted initial balance in Brazilian Reais.
     */
    public function getFormattedInitialBalanceAttribute(): string
    {
        return 'R$ '.number_format((float) $this->initial_balance, 2, ',', '.');
    }

    /**
     * Formatted current balance in Brazilian Reais.
     */
    public function getFormattedCurrentBalanceAttribute(): string
    {
        return 'R$ '.number_format($this->current_balance, 2, ',', '.');
    }
}
