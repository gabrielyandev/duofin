<?php

namespace App\Models;

use App\Models\Scopes\WorkspaceScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([WorkspaceScope::class])]
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workspace_id',
        'user_id',
        'category_id',
        'account_id',
        'installment_group_id',
        'description',
        'amount',
        'due_date',
        'paid_at',
        'type',
        'payment_method',
        'status',
        'installment_number',
        'total_installments',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_at' => 'date',
            'amount' => 'decimal:2',
            'installment_number' => 'integer',
            'total_installments' => 'integer',
        ];
    }

    /**
     * Workspace this transaction belongs to.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * User who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category of this transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Account of this transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Installment group if this transaction is part of an installment plan.
     */
    public function installmentGroup(): BelongsTo
    {
        return $this->belongsTo(InstallmentGroup::class);
    }

    /**
     * Helper to check if this transaction is an installment.
     */
    public function isInstallment(): bool
    {
        return ! is_null($this->installment_group_id) || ! is_null($this->installment_number);
    }

    /**
     * Description formatted with installment number if applicable, e.g. "Notebook (03/10)".
     */
    public function getDisplayDescriptionAttribute(): string
    {
        if ($this->isInstallment() && $this->installment_number && $this->total_installments) {
            $current = sprintf('%02d', $this->installment_number);
            $total = sprintf('%02d', $this->total_installments);

            return "{$this->description} ({$current}/{$total})";
        }

        return $this->description;
    }

    /**
     * Formatted amount in Brazilian Reais.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'R$ '.number_format((float) $this->amount, 2, ',', '.');
    }

    /**
     * Check if transaction is marked as paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if transaction is marked as pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is an expense.
     */
    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }

    /**
     * Check if transaction is an income.
     */
    public function isIncome(): bool
    {
        return $this->type === 'income';
    }
}
