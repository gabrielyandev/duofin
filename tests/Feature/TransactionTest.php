<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\InstallmentGroup;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceInvitationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected Workspace $workspace;

    protected User $user;

    protected Category $category;

    protected Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workspace = Workspace::create([
            'name' => 'Finanças Teste',
            'invite_code' => 'TESTCODE',
        ]);

        $this->user = User::create([
            'name' => 'Gabriel Dev',
            'email' => 'gabriel@teste.com',
            'password' => bcrypt('password'),
            'current_workspace_id' => $this->workspace->id,
        ]);

        $this->workspace->users()->attach($this->user->id, ['role' => 'owner']);

        $this->category = Category::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Mercado',
            'type' => 'expense',
            'color' => '#10b981',
            'icon' => 'shopping-cart',
        ]);

        $this->account = Account::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Nubank Principal',
            'initial_balance' => 1000.00,
            'color' => '#820ad1',
        ]);
    }

    /**
     * 1. Criação de despesa simples à vista.
     */
    public function test_creates_simple_cash_transaction(): void
    {
        $response = $this->actingAs($this->user)->post(route('transactions.store'), [
            'description' => 'Compras no Mercado',
            'amount' => '150,50',
            'due_date' => '2026-09-20',
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'type' => 'expense',
            'payment_method' => 'pix',
            'status' => 'paid',
            'is_installment' => '0',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $tx = Transaction::where('description', 'Compras no Mercado')->first();
        $this->assertNotNull($tx);
        $this->assertEquals($this->workspace->id, $tx->workspace_id);
        $this->assertEquals($this->user->id, $tx->user_id);
        $this->assertEquals(150.50, (float) $tx->amount);
        $this->assertEquals('2026-09-20', $tx->due_date->format('Y-m-d'));
        $this->assertEquals('expense', $tx->type);
        $this->assertEquals('paid', $tx->status);
        $this->assertNull($tx->installment_group_id);
        $this->assertNull($tx->installment_number);
    }

    /**
     * 2. Divisão matemática correta e geração dos registros de parcelamento no tempo.
     */
    public function test_creates_installment_transactions_with_precise_cent_division_and_dates(): void
    {
        // Total de R$ 100,00 dividido em 3 parcelas
        // Deve resultar em: Parcela 1 = 33,34, Parcela 2 = 33,33, Parcela 3 = 33,33
        // Soma = 100,00 (sem perda de centavos)
        // Data base: 31 de janeiro de 2026 (testa addMonthsNoOverflow para fevereiro)
        $response = $this->actingAs($this->user)->post(route('transactions.store'), [
            'description' => 'Notebook Trabalho',
            'amount' => '100,00',
            'due_date' => '2026-01-31',
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'type' => 'expense',
            'payment_method' => 'credit_card',
            'status' => 'pending',
            'is_installment' => '1',
            'total_installments' => 3,
        ]);

        $response->assertRedirect();

        // Verifica grupo de parcelamento
        $this->assertDatabaseHas('installment_groups', [
            'workspace_id' => $this->workspace->id,
            'total_amount' => 100.00,
            'total_installments' => 3,
            'description' => 'Notebook Trabalho',
            'created_by' => $this->user->id,
        ]);

        $group = InstallmentGroup::where('description', 'Notebook Trabalho')->first();
        $this->assertNotNull($group);

        $transactions = Transaction::where('installment_group_id', $group->id)
            ->orderBy('installment_number')
            ->get();

        $this->assertCount(3, $transactions);

        // Parcela 1: absorve o centavo excedente
        $this->assertEquals(1, $transactions[0]->installment_number);
        $this->assertEquals(3, $transactions[0]->total_installments);
        $this->assertEquals(33.34, (float) $transactions[0]->amount);
        $this->assertEquals('2026-01-31', $transactions[0]->due_date->format('Y-m-d'));

        // Parcela 2: fevereiro (addMonthsNoOverflow não estoura para março)
        $this->assertEquals(2, $transactions[1]->installment_number);
        $this->assertEquals(33.33, (float) $transactions[1]->amount);
        $this->assertEquals('2026-02-28', $transactions[1]->due_date->format('Y-m-d'));

        // Parcela 3: março
        $this->assertEquals(3, $transactions[2]->installment_number);
        $this->assertEquals(33.33, (float) $transactions[2]->amount);
        $this->assertEquals('2026-03-31', $transactions[2]->due_date->format('Y-m-d'));

        // Soma total exata
        $sum = $transactions->sum('amount');
        $this->assertEquals(100.00, (float) $sum);
    }

    /**
     * 3. Isolamento de dados entre workspaces distintos (WorkspaceScope).
     */
    public function test_workspace_isolation_prevents_cross_tenant_data_access(): void
    {
        // Transação no Workspace 1
        $txWorkspace1 = Transaction::create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'description' => 'Gasto Secreto Workspace 1',
            'amount' => 500.00,
            'due_date' => '2026-09-20',
            'type' => 'expense',
            'status' => 'paid',
        ]);

        // Criar Workspace 2 com Usuário 2
        $workspace2 = Workspace::create([
            'name' => 'Outro Casal',
            'invite_code' => 'OUTRO002',
        ]);

        $user2 = User::create([
            'name' => 'Outro Usuário',
            'email' => 'outro@teste.com',
            'password' => bcrypt('password'),
            'current_workspace_id' => $workspace2->id,
        ]);

        $workspace2->users()->attach($user2->id, ['role' => 'owner']);

        // Como Usuário 2 no Workspace 2, o Global Scope oculta transações do Workspace 1
        $this->actingAs($user2);

        $visibleTransactions = Transaction::all();
        $this->assertCount(0, $visibleTransactions);

        // Usuário 2 não consegue localizar nem modificar a transação do Workspace 1 (retorna 404 devido ao WorkspaceScope)
        $updateResponse = $this->patch(route('transactions.update-status', $txWorkspace1));
        $updateResponse->assertNotFound();

        // Usuário 2 não consegue deletar transação do Workspace 1 (retorna 404 devido ao WorkspaceScope)
        $deleteResponse = $this->delete(route('transactions.destroy', $txWorkspace1));
        $deleteResponse->assertNotFound();
    }

    /**
     * 4. Limite inviolável de no máximo 2 membros por workspace.
     */
    public function test_workspace_invitation_enforces_maximum_two_members(): void
    {
        // Adicionar segundo membro (parceiro) ao workspace
        $partner = User::create([
            'name' => 'Maria Silva',
            'email' => 'maria@teste.com',
            'password' => bcrypt('password'),
            'current_workspace_id' => $this->workspace->id,
        ]);
        $this->workspace->users()->attach($partner->id, ['role' => 'partner']);

        $this->assertEquals(2, $this->workspace->users()->count());

        // Terceiro usuário tenta entrar com o código de convite
        $thirdUser = User::create([
            'name' => 'Terceiro Intruso',
            'email' => 'intruso@teste.com',
            'password' => bcrypt('password'),
        ]);

        $invitationService = app(WorkspaceInvitationService::class);

        $this->expectException(ValidationException::class);
        $invitationService->joinWorkspace($thirdUser, $this->workspace->invite_code);
    }

    /**
     * 5. Exclusão inteligente de parcelas futuras.
     */
    public function test_smart_deletion_removes_current_and_future_installments(): void
    {
        $this->actingAs($this->user);

        $group = InstallmentGroup::create([
            'workspace_id' => $this->workspace->id,
            'total_amount' => 500.00,
            'total_installments' => 5,
            'description' => 'Curso de Especialização',
            'created_by' => $this->user->id,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Transaction::create([
                'workspace_id' => $this->workspace->id,
                'user_id' => $this->user->id,
                'category_id' => $this->category->id,
                'account_id' => $this->account->id,
                'installment_group_id' => $group->id,
                'description' => 'Curso de Especialização',
                'amount' => 100.00,
                'due_date' => Carbon::now()->addMonthsNoOverflow($i - 1)->toDateString(),
                'type' => 'expense',
                'status' => 'pending',
                'installment_number' => $i,
                'total_installments' => 5,
            ]);
        }

        $installment3 = Transaction::where('installment_group_id', $group->id)
            ->where('installment_number', 3)
            ->first();

        $response = $this->delete(route('transactions.destroy', $installment3), [
            'delete_scope' => 'all_future',
        ]);

        $response->assertRedirect();

        // Parcelas 1 e 2 devem continuar existindo
        $this->assertDatabaseHas('transactions', [
            'installment_group_id' => $group->id,
            'installment_number' => 1,
        ]);
        $this->assertDatabaseHas('transactions', [
            'installment_group_id' => $group->id,
            'installment_number' => 2,
        ]);

        // Parcelas 3, 4 e 5 foram excluídas
        $this->assertDatabaseMissing('transactions', [
            'installment_group_id' => $group->id,
            'installment_number' => 3,
        ]);
        $this->assertDatabaseMissing('transactions', [
            'installment_group_id' => $group->id,
            'installment_number' => 4,
        ]);
        $this->assertDatabaseMissing('transactions', [
            'installment_group_id' => $group->id,
            'installment_number' => 5,
        ]);
    }
}
