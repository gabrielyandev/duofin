<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\InstallmentGroup;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Workspace
        $workspace = Workspace::create([
            'name' => 'Finanças Compartilhadas',
            'invite_code' => 'DUOFIN01',
        ]);

        // 2. Dois usuários vinculados ao mesmo Workspace
        $gabriel = User::create([
            'name' => 'Gabriel Dev',
            'email' => 'gabriel@duofin.test',
            'password' => Hash::make('password'),
            'current_workspace_id' => $workspace->id,
        ]);

        $maria = User::create([
            'name' => 'Maria Silva',
            'email' => 'maria@duofin.test',
            'password' => Hash::make('password'),
            'current_workspace_id' => $workspace->id,
        ]);

        // Vincular ao workspace com limite de 2 membros
        $workspace->users()->attach($gabriel->id, ['role' => 'owner']);
        $workspace->users()->attach($maria->id, ['role' => 'partner']);

        // 3. Categorias essenciais
        $categoriesData = [
            ['name' => 'Moradia', 'type' => 'expense', 'color' => '#6366f1', 'icon' => 'home'],
            ['name' => 'Mercado', 'type' => 'expense', 'color' => '#10b981', 'icon' => 'shopping-cart'],
            ['name' => 'Saúde', 'type' => 'expense', 'color' => '#ef4444', 'icon' => 'activity'],
            ['name' => 'Transporte', 'type' => 'expense', 'color' => '#f59e0b', 'icon' => 'car'],
            ['name' => 'Lazer', 'type' => 'expense', 'color' => '#8b5cf6', 'icon' => 'film'],
            ['name' => 'Assinaturas', 'type' => 'expense', 'color' => '#06b6d4', 'icon' => 'tv'],
            ['name' => 'Salário', 'type' => 'income', 'color' => '#10b981', 'icon' => 'briefcase'],
            ['name' => 'Investimentos', 'type' => 'income', 'color' => '#3b82f6', 'icon' => 'trending-up'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::create([
                'workspace_id' => $workspace->id,
                'name' => $cat['name'],
                'type' => $cat['type'],
                'color' => $cat['color'],
                'icon' => $cat['icon'],
            ]);
        }

        // 4. Duas contas financeiras
        $nubank = Account::create([
            'workspace_id' => $workspace->id,
            'name' => 'Nubank Principal',
            'initial_balance' => 5400.00,
            'color' => '#820ad1',
        ]);

        $itau = Account::create([
            'workspace_id' => $workspace->id,
            'name' => 'Itau Corrente',
            'initial_balance' => 2300.00,
            'color' => '#ec7000',
        ]);

        $now = Carbon::now();

        // 5. Transações à vista recentes
        // Receitas do mês atual
        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $gabriel->id,
            'category_id' => $categories['Salário']->id,
            'account_id' => $itau->id,
            'description' => 'Salario Gabriel',
            'amount' => 7500.00,
            'due_date' => $now->copy()->startOfMonth()->addDays(4)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(4)->toDateString(),
            'type' => 'income',
            'payment_method' => 'pix',
            'status' => 'paid',
        ]);

        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $maria->id,
            'category_id' => $categories['Salário']->id,
            'account_id' => $nubank->id,
            'description' => 'Salario Maria',
            'amount' => 6800.00,
            'due_date' => $now->copy()->startOfMonth()->addDays(4)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(4)->toDateString(),
            'type' => 'income',
            'payment_method' => 'pix',
            'status' => 'paid',
        ]);

        // Despesas à vista do mês atual (Pagas e Pendentes)
        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $gabriel->id,
            'category_id' => $categories['Moradia']->id,
            'account_id' => $itau->id,
            'description' => 'Aluguel do Apartamento',
            'amount' => 2400.00,
            'due_date' => $now->copy()->startOfMonth()->addDays(9)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(9)->toDateString(),
            'type' => 'expense',
            'payment_method' => 'pix',
            'status' => 'paid',
        ]);

        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $maria->id,
            'category_id' => $categories['Mercado']->id,
            'account_id' => $nubank->id,
            'description' => 'Compras do Mes no Supermercado',
            'amount' => 850.40,
            'due_date' => $now->copy()->startOfMonth()->addDays(11)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(11)->toDateString(),
            'type' => 'expense',
            'payment_method' => 'credit_card',
            'status' => 'paid',
        ]);

        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $gabriel->id,
            'category_id' => $categories['Lazer']->id,
            'account_id' => $nubank->id,
            'description' => 'Jantar Restaurante Italiano',
            'amount' => 280.00,
            'due_date' => $now->copy()->startOfMonth()->addDays(15)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(15)->toDateString(),
            'type' => 'expense',
            'payment_method' => 'credit_card',
            'status' => 'paid',
        ]);

        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $maria->id,
            'category_id' => $categories['Transporte']->id,
            'account_id' => $nubank->id,
            'description' => 'Abastecimento Carro',
            'amount' => 195.00,
            'due_date' => $now->copy()->startOfMonth()->addDays(18)->toDateString(),
            'paid_at' => $now->copy()->startOfMonth()->addDays(18)->toDateString(),
            'type' => 'expense',
            'payment_method' => 'debit',
            'status' => 'paid',
        ]);

        // Despesas com vencimento nos próximos 7 dias (para testar o card de próximos vencimentos)
        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $maria->id,
            'category_id' => $categories['Assinaturas']->id,
            'account_id' => $nubank->id,
            'description' => 'Netflix e Spotify Familiar',
            'amount' => 89.90,
            'due_date' => $now->copy()->addDays(2)->toDateString(),
            'paid_at' => null,
            'type' => 'expense',
            'payment_method' => 'credit_card',
            'status' => 'pending',
        ]);

        Transaction::create([
            'workspace_id' => $workspace->id,
            'user_id' => $gabriel->id,
            'category_id' => $categories['Saúde']->id,
            'account_id' => $itau->id,
            'description' => 'Plano Odontologico Casal',
            'amount' => 135.00,
            'due_date' => $now->copy()->addDays(4)->toDateString(),
            'paid_at' => null,
            'type' => 'expense',
            'payment_method' => 'pix',
            'status' => 'pending',
        ]);

        // 6. Duas compras parceladas realistas (6x e 12x)
        // Parcela A: Notebook Dell Work (12x de R$ 350,00 = R$ 4.200,00) iniciada há 2 meses
        $notebookGroup = InstallmentGroup::create([
            'workspace_id' => $workspace->id,
            'total_amount' => 4200.00,
            'total_installments' => 12,
            'description' => 'Notebook Dell Inspiron',
            'created_by' => $gabriel->id,
        ]);

        $notebookStartDate = $now->copy()->subMonths(2)->startOfMonth()->addDays(14);
        for ($i = 1; $i <= 12; $i++) {
            $dueDate = $notebookStartDate->copy()->addMonthsNoOverflow($i - 1);
            $isPast = $dueDate->isPast() && ! $dueDate->isCurrentMonth();
            $isPaid = $isPast || ($i === 1);

            Transaction::create([
                'workspace_id' => $workspace->id,
                'user_id' => $gabriel->id,
                'category_id' => $categories['Moradia']->id,
                'account_id' => $nubank->id,
                'installment_group_id' => $notebookGroup->id,
                'description' => 'Notebook Dell Inspiron',
                'amount' => 350.00,
                'due_date' => $dueDate->toDateString(),
                'paid_at' => $isPaid ? $dueDate->toDateString() : null,
                'type' => 'expense',
                'payment_method' => 'credit_card',
                'status' => $isPaid ? 'paid' : 'pending',
                'installment_number' => $i,
                'total_installments' => 12,
            ]);
        }

        // Parcela B: Geladeira Frost Free (6x de R$ 600,00 = R$ 3.600,00) iniciada há 1 mês
        $geladeiraGroup = InstallmentGroup::create([
            'workspace_id' => $workspace->id,
            'total_amount' => 3600.00,
            'total_installments' => 6,
            'description' => 'Geladeira Frost Free Inox',
            'created_by' => $maria->id,
        ]);

        $geladeiraStartDate = $now->copy()->subMonths(1)->startOfMonth()->addDays(19);
        for ($i = 1; $i <= 6; $i++) {
            $dueDate = $geladeiraStartDate->copy()->addMonthsNoOverflow($i - 1);
            $isPast = $dueDate->isPast() && ! $dueDate->isCurrentMonth();
            $isPaid = $isPast || ($i === 1);

            Transaction::create([
                'workspace_id' => $workspace->id,
                'user_id' => $maria->id,
                'category_id' => $categories['Moradia']->id,
                'account_id' => $itau->id,
                'installment_group_id' => $geladeiraGroup->id,
                'description' => 'Geladeira Frost Free Inox',
                'amount' => 600.00,
                'due_date' => $dueDate->toDateString(),
                'paid_at' => $isPaid ? $dueDate->toDateString() : null,
                'type' => 'expense',
                'payment_method' => 'credit_card',
                'status' => $isPaid ? 'paid' : 'pending',
                'installment_number' => $i,
                'total_installments' => 6,
            ]);
        }
    }
}
