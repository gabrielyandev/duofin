<?php

namespace App\Models;

use App\Models\Scopes\WorkspaceScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([WorkspaceScope::class])]
class InstallmentGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workspace_id',
        'total_amount',
        'total_installments',
        'description',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'total_installments' => 'integer',
        ];
    }

    /**
     * Workspace this installment group belongs to.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * User who created this installment group.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * All transactions belonging to this installment group.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->orderBy('installment_number');
    }

    /**
     * Formatted total amount in BRL.
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'R$ '.number_format((float) $this->total_amount, 2, ',', '.');
    }
}
