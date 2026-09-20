<?php

namespace App\Http\Controllers;

use App\Actions\CreateTransactionAction;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\InstallmentGroup;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions with filters.
     */
    public function index(Request $request): View
    {
        $workspace = $request->user()->currentWorkspace;

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $categoryId = $request->input('category_id');
        $accountId = $request->input('account_id');
        $status = $request->input('status');
        $type = $request->input('type');
        $search = $request->input('search');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Transaction::with(['category', 'account', 'user'])
            ->whereBetween('due_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if (! empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (! empty($accountId)) {
            $query->where('account_id', $accountId);
        }

        if (! empty($status) && in_array($status, ['paid', 'pending'])) {
            $query->where('status', $status);
        }

        if (! empty($type) && in_array($type, ['expense', 'income'])) {
            $query->where('type', $type);
        }

        if (! empty($search)) {
            $query->where('description', 'like', '%'.$search.'%');
        }

        $transactions = $query->orderBy('due_date', 'desc')->paginate(20)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'categories' => $categories,
            'accounts' => $accounts,
            'year' => $year,
            'month' => $month,
            'selectedCategoryId' => $categoryId,
            'selectedAccountId' => $accountId,
            'selectedStatus' => $status,
            'selectedType' => $type,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(CreateTransactionRequest $request, CreateTransactionAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return redirect()->back()->with('success', 'Transação cadastrada com sucesso.');
    }

    /**
     * Toggle the status between 'paid' and 'pending'.
     */
    public function updateStatus(Request $request, Transaction $transaction): RedirectResponse
    {
        // Workspace check
        if ($transaction->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        if ($transaction->status === 'paid') {
            $transaction->status = 'pending';
            $transaction->paid_at = null;
            $message = 'Transação marcada como pendente.';
        } else {
            $transaction->status = 'paid';
            $transaction->paid_at = now()->toDateString();
            $message = 'Transação marcada como paga.';
        }

        $transaction->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $transaction->status,
                'paid_at' => $transaction->paid_at?->format('d/m/Y'),
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        $deleteScope = $request->input('delete_scope', 'only_this');

        if ($deleteScope === 'all_future' && $transaction->installment_group_id && $transaction->installment_number) {
            $groupId = $transaction->installment_group_id;
            $installmentNumber = $transaction->installment_number;

            Transaction::where('installment_group_id', $groupId)
                ->where('installment_number', '>=', $installmentNumber)
                ->delete();

            // If no more transactions exist in the installment group, remove the group
            $remaining = Transaction::where('installment_group_id', $groupId)->count();
            if ($remaining === 0) {
                InstallmentGroup::where('id', $groupId)->delete();
            }

            return redirect()->back()->with('success', 'Parcela e parcelas futuras excluídas com sucesso.');
        }

        $groupId = $transaction->installment_group_id;
        $transaction->delete();

        if ($groupId) {
            $remaining = Transaction::where('installment_group_id', $groupId)->count();
            if ($remaining === 0) {
                InstallmentGroup::where('id', $groupId)->delete();
            }
        }

        return redirect()->back()->with('success', 'Transação excluída com sucesso.');
    }
}
