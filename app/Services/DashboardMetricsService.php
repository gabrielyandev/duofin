<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Workspace;
use Carbon\Carbon;

class DashboardMetricsService
{
    /**
     * Compute all dashboard metrics for the given workspace and reference date.
     *
     * @return array<string, mixed>
     */
    public function getMetrics(Workspace $workspace, int $year, int $month): array
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // 1. Current Realized Balance across all accounts in the workspace
        $accounts = Account::where('workspace_id', $workspace->id)->get();
        $totalAccountBalance = $accounts->sum(fn ($acc) => $acc->current_balance);

        // 2. Month transactions query
        $monthQuery = Transaction::where('workspace_id', $workspace->id)
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);

        $monthTransactions = (clone $monthQuery)->with(['category', 'account', 'user'])->get();

        $monthIncomePaid = (float) $monthTransactions
            ->where('type', 'income')
            ->where('status', 'paid')
            ->sum('amount');

        $monthIncomePending = (float) $monthTransactions
            ->where('type', 'income')
            ->where('status', 'pending')
            ->sum('amount');

        $monthExpenses = (float) $monthTransactions
            ->where('type', 'expense')
            ->sum('amount');

        $monthExpensesPaid = (float) $monthTransactions
            ->where('type', 'expense')
            ->where('status', 'paid')
            ->sum('amount');

        $monthExpensesPending = (float) $monthTransactions
            ->where('type', 'expense')
            ->where('status', 'pending')
            ->sum('amount');

        $monthNetBalance = ($monthIncomePaid + $monthIncomePending) - $monthExpenses;

        // 3. 6-Month Future Installment & Expense Projection
        $projections = [];
        $maxMonthExpense = 1.0; // avoid divide by zero

        for ($i = 0; $i < 6; $i++) {
            $projDate = $startOfMonth->copy()->addMonths($i);
            $pStart = $projDate->copy()->startOfMonth()->toDateString();
            $pEnd = $projDate->copy()->endOfMonth()->toDateString();

            $pExpenses = (float) Transaction::where('workspace_id', $workspace->id)
                ->where('type', 'expense')
                ->whereBetween('due_date', [$pStart, $pEnd])
                ->sum('amount');

            $pInstallments = (float) Transaction::where('workspace_id', $workspace->id)
                ->where('type', 'expense')
                ->whereNotNull('installment_group_id')
                ->whereBetween('due_date', [$pStart, $pEnd])
                ->sum('amount');

            if ($pExpenses > $maxMonthExpense) {
                $maxMonthExpense = $pExpenses;
            }

            $monthNames = [
                1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez',
            ];

            $projections[] = [
                'label' => ($monthNames[$projDate->month] ?? $projDate->format('M')).'/'.$projDate->format('y'),
                'year' => $projDate->year,
                'month' => $projDate->month,
                'is_current' => ($projDate->year === (int) now()->year && $projDate->month === (int) now()->month),
                'is_selected' => ($projDate->year === $year && $projDate->month === $month),
                'total_expense' => $pExpenses,
                'installment_expense' => $pInstallments,
                'formatted_total' => 'R$ '.number_format($pExpenses, 2, ',', '.'),
                'formatted_installments' => 'R$ '.number_format($pInstallments, 2, ',', '.'),
            ];
        }

        // Add bar height percentage to projections
        foreach ($projections as &$proj) {
            $proj['bar_percentage'] = $maxMonthExpense > 0
                ? min(100, max(12, round(($proj['total_expense'] / $maxMonthExpense) * 100)))
                : 12;
        }
        unset($proj);

        // 4. Expenses by Category
        $categoryExpenses = [];
        $groupedCategories = $monthTransactions->where('type', 'expense')->groupBy('category_id');

        foreach ($groupedCategories as $categoryId => $txs) {
            $category = $txs->first()->category ?? Category::find($categoryId);
            $catTotal = (float) $txs->sum('amount');
            $percentage = $monthExpenses > 0 ? round(($catTotal / $monthExpenses) * 100, 1) : 0;

            $categoryExpenses[] = [
                'id' => $categoryId,
                'name' => $category ? $category->name : 'Sem categoria',
                'color' => $category->color ?? '#64748b',
                'icon' => $category->icon ?? 'tag',
                'total' => $catTotal,
                'percentage' => $percentage,
                'formatted_total' => 'R$ '.number_format($catTotal, 2, ',', '.'),
            ];
        }

        // Sort categories by highest expense
        usort($categoryExpenses, fn ($a, $b) => $b['total'] <=> $a['total']);

        // 5. "Quem gastou mais" (Expenses registered by Member A vs Member B)
        $members = $workspace->users()->get();
        $spendingByMember = [];
        $totalMemberExpenses = 0.0;

        foreach ($members as $member) {
            $userExpenses = (float) $monthTransactions
                ->where('type', 'expense')
                ->where('user_id', $member->id)
                ->sum('amount');

            $totalMemberExpenses += $userExpenses;

            $spendingByMember[] = [
                'user_id' => $member->id,
                'name' => $member->name,
                'initials' => $member->initials,
                'role' => $member->pivot?->role ?? 'partner',
                'total' => $userExpenses,
                'formatted_total' => 'R$ '.number_format($userExpenses, 2, ',', '.'),
            ];
        }

        // Calculate member percentages
        foreach ($spendingByMember as &$memberData) {
            $memberData['percentage'] = $totalMemberExpenses > 0
                ? round(($memberData['total'] / $totalMemberExpenses) * 100, 1)
                : 50.0;
        }
        unset($memberData);

        // 6. Upcoming due dates in next 7 days
        $today = now()->startOfDay();
        $in7Days = now()->copy()->addDays(7)->endOfDay();

        $upcomingBills = Transaction::where('workspace_id', $workspace->id)
            ->where('type', 'expense')
            ->where('status', 'pending')
            ->whereBetween('due_date', [$today->toDateString(), $in7Days->toDateString()])
            ->with(['category', 'account', 'user'])
            ->orderBy('due_date', 'asc')
            ->get();

        return [
            'year' => $year,
            'month' => $month,
            'month_label' => self::getMonthLabel($month).' '.$year,
            'total_account_balance' => $totalAccountBalance,
            'formatted_account_balance' => 'R$ '.number_format($totalAccountBalance, 2, ',', '.'),
            'month_income_paid' => $monthIncomePaid,
            'formatted_month_income_paid' => 'R$ '.number_format($monthIncomePaid, 2, ',', '.'),
            'month_expenses' => $monthExpenses,
            'formatted_month_expenses' => 'R$ '.number_format($monthExpenses, 2, ',', '.'),
            'month_expenses_paid' => $monthExpensesPaid,
            'formatted_month_expenses_paid' => 'R$ '.number_format($monthExpensesPaid, 2, ',', '.'),
            'month_expenses_pending' => $monthExpensesPending,
            'formatted_month_expenses_pending' => 'R$ '.number_format($monthExpensesPending, 2, ',', '.'),
            'month_net_balance' => $monthNetBalance,
            'formatted_month_net_balance' => 'R$ '.number_format($monthNetBalance, 2, ',', '.'),
            'projections' => $projections,
            'category_expenses' => $categoryExpenses,
            'spending_by_member' => $spendingByMember,
            'upcoming_bills' => $upcomingBills,
            'members' => $members,
        ];
    }

    public static function getMonthLabel(int $month): string
    {
        $months = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        return $months[$month] ?? '';
    }
}
