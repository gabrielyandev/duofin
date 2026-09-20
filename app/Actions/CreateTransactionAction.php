<?php

namespace App\Actions;

use App\Models\InstallmentGroup;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreateTransactionAction
{
    /**
     * Execute the action to create a single transaction or an installment group.
     *
     * @param  array<string, mixed>  $data
     * @return array<Transaction>
     */
    public function execute(User $user, array $data): array
    {
        $workspaceId = $user->current_workspace_id;
        if (! $workspaceId) {
            throw new InvalidArgumentException('O usuário não possui um workspace ativo selecionado.');
        }

        $isInstallment = ! empty($data['is_installment']) && filter_var($data['is_installment'], FILTER_VALIDATE_BOOLEAN);
        $totalInstallments = isset($data['total_installments']) ? (int) $data['total_installments'] : 1;
        $amount = (float) str_replace(',', '.', (string) $data['amount']);
        $initialDueDate = Carbon::parse($data['due_date']);
        $status = $data['status'] ?? 'pending';
        $paidAt = ! empty($data['paid_at']) ? Carbon::parse($data['paid_at']) : ($status === 'paid' ? now() : null);

        return DB::transaction(function () use ($user, $workspaceId, $data, $isInstallment, $totalInstallments, $amount, $initialDueDate, $status, $paidAt) {
            if (! $isInstallment || $totalInstallments < 2) {
                $transaction = Transaction::create([
                    'workspace_id' => $workspaceId,
                    'user_id' => $user->id,
                    'category_id' => $data['category_id'],
                    'account_id' => $data['account_id'],
                    'installment_group_id' => null,
                    'description' => $data['description'],
                    'amount' => $amount,
                    'due_date' => $initialDueDate->toDateString(),
                    'paid_at' => $paidAt?->toDateString(),
                    'type' => $data['type'] ?? 'expense',
                    'payment_method' => $data['payment_method'] ?? 'pix',
                    'status' => $status,
                    'installment_number' => null,
                    'total_installments' => null,
                ]);

                return [$transaction];
            }

            // Installment logic
            $installmentGroup = InstallmentGroup::create([
                'workspace_id' => $workspaceId,
                'total_amount' => $amount,
                'total_installments' => $totalInstallments,
                'description' => $data['description'],
                'created_by' => $user->id,
            ]);

            $totalCents = (int) round($amount * 100);
            $baseCents = intdiv($totalCents, $totalInstallments);
            $remainderCents = $totalCents % $totalInstallments;

            $createdTransactions = [];

            for ($i = 1; $i <= $totalInstallments; $i++) {
                $installmentAmountInCents = ($i === 1) ? ($baseCents + $remainderCents) : $baseCents;
                $installmentAmount = $installmentAmountInCents / 100;
                $installmentDueDate = $initialDueDate->copy()->addMonthsNoOverflow($i - 1);

                // First installment gets the selected status; future installments default to pending
                $installmentStatus = ($i === 1) ? $status : 'pending';
                $installmentPaidAt = ($i === 1 && $installmentStatus === 'paid') ? ($paidAt?->toDateString() ?? now()->toDateString()) : null;

                $createdTransactions[] = Transaction::create([
                    'workspace_id' => $workspaceId,
                    'user_id' => $user->id,
                    'category_id' => $data['category_id'],
                    'account_id' => $data['account_id'],
                    'installment_group_id' => $installmentGroup->id,
                    'description' => $data['description'],
                    'amount' => $installmentAmount,
                    'due_date' => $installmentDueDate->toDateString(),
                    'paid_at' => $installmentPaidAt,
                    'type' => $data['type'] ?? 'expense',
                    'payment_method' => $data['payment_method'] ?? 'credit_card',
                    'status' => $installmentStatus,
                    'installment_number' => $i,
                    'total_installments' => $totalInstallments,
                ]);
            }

            return $createdTransactions;
        });
    }
}
