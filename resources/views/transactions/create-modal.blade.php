@php
    $modalCategories = \App\Models\Category::orderBy('name')->get();
    $modalAccounts = \App\Models\Account::orderBy('name')->get();
@endphp

<div 
    x-show="createTxModalOpen" 
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    x-cloak
    x-data="{
        type: 'expense',
        isInstallment: false,
        rawAmount: '',
        installments: 2,
        paymentMethod: 'credit_card',
        status: 'paid',
        description: '',
        categoryId: '',
        accountId: '',
        
        get estimatedInstallment() {
            let clean = String(this.rawAmount).replace(/[^\d,\.]/g, '').replace(',', '.');
            let amt = parseFloat(clean) || 0;
            let count = parseInt(this.installments) || 2;
            if (count < 2) count = 2;
            let val = amt / count;
            return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        },
        
        get formattedTotal() {
            let clean = String(this.rawAmount).replace(/[^\d,\.]/g, '').replace(',', '.');
            let amt = parseFloat(clean) || 0;
            return amt.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        },

        incrementInstallments() {
            if (this.installments < 48) this.installments++;
        },

        decrementInstallments() {
            if (this.installments > 2) this.installments--;
        }
    }"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="createTxModalOpen" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
            @click="createTxModalOpen = false"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div 
            x-show="createTxModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-zinc-100 sm:my-8 relative"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div class="flex items-center gap-3">
                    <div 
                        class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors shadow-xs"
                        :class="type === 'expense' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'"
                    >
                        <template x-if="type === 'expense'">
                            <x-lucide-arrow-down-left class="w-5 h-5" />
                        </template>
                        <template x-if="type === 'income'">
                            <x-lucide-arrow-up-right class="w-5 h-5" />
                        </template>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900" x-text="type === 'expense' ? 'Nova Despesa' : 'Nova Receita'"></h3>
                        <p class="text-xs text-zinc-500">Adicione uma transação para o orçamento conjunto</p>
                    </div>
                </div>
                <button @click="createTxModalOpen = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 rounded-lg hover:bg-zinc-100 transition">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('transactions.store') }}" class="mt-5 space-y-4">
                @csrf

                <!-- Type Selector (Despesa / Receita) -->
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-zinc-100/80 rounded-2xl border border-zinc-200/50">
                    <button 
                        type="button" 
                        @click="type = 'expense'" 
                        :class="type === 'expense' ? 'bg-white text-zinc-900 shadow-xs font-bold' : 'text-zinc-500 hover:text-zinc-800 font-medium'"
                        class="py-2.5 text-xs rounded-xl transition text-center flex items-center justify-center gap-1.5"
                    >
                        <x-lucide-arrow-down-left class="w-4 h-4 text-rose-500" />
                        <span>Despesa</span>
                    </button>
                    <button 
                        type="button" 
                        @click="type = 'income'; isInstallment = false" 
                        :class="type === 'income' ? 'bg-white text-zinc-900 shadow-xs font-bold' : 'text-zinc-500 hover:text-zinc-800 font-medium'"
                        class="py-2.5 text-xs rounded-xl transition text-center flex items-center justify-center gap-1.5"
                    >
                        <x-lucide-arrow-up-right class="w-4 h-4 text-emerald-500" />
                        <span>Receita</span>
                    </button>
                    <input type="hidden" name="type" :value="type">
                </div>

                <!-- Expense Mode: À Vista vs Parcelado -->
                <template x-if="type === 'expense'">
                    <div class="flex items-center justify-between p-3 bg-zinc-50 rounded-2xl border border-zinc-200/80">
                        <div>
                            <span class="text-xs font-bold text-zinc-900 block">Forma de Lançamento</span>
                            <span class="text-[11px] text-zinc-500" x-text="isInstallment ? 'Dividido em parcelas nos meses futuros' : 'Lançamento único à vista'"></span>
                        </div>
                        <div class="inline-flex rounded-xl p-1 bg-zinc-200/70">
                            <button 
                                type="button" 
                                @click="isInstallment = false"
                                :class="!isInstallment ? 'bg-white text-zinc-900 shadow-xs font-bold' : 'text-zinc-600'"
                                class="px-3 py-1 text-xs rounded-lg transition"
                            >
                                À Vista
                            </button>
                            <button 
                                type="button" 
                                @click="isInstallment = true; paymentMethod = 'credit_card'"
                                :class="isInstallment ? 'bg-white text-zinc-900 shadow-xs font-bold' : 'text-zinc-600'"
                                class="px-3 py-1 text-xs rounded-lg transition"
                            >
                                Parcelado
                            </button>
                        </div>
                    </div>
                </template>
                <input type="hidden" name="is_installment" :value="isInstallment ? '1' : '0'">

                <!-- Description & Amount -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_description" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            Descrição
                        </label>
                        <input 
                            type="text" 
                            name="description" 
                            id="modal_description" 
                            x-model="description"
                            required 
                            placeholder="Ex: Mercado Semanal, Aluguel"
                            class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 placeholder-zinc-400"
                        />
                    </div>

                    <div>
                        <label for="modal_amount" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            <span x-text="isInstallment ? 'Valor Total da Compra' : 'Valor'"></span>
                        </label>
                        <div class="relative rounded-xl">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-zinc-400 text-xs font-bold">R$</span>
                            </div>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01" 
                                name="amount" 
                                id="modal_amount" 
                                required 
                                x-model="rawAmount"
                                placeholder="0,00"
                                class="w-full pl-9 text-sm font-semibold rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 placeholder-zinc-400"
                            />
                        </div>
                    </div>
                </div>

                <!-- Dynamic Installments Section (Active when isInstallment is true) -->
                <div 
                    x-show="type === 'expense' && isInstallment" 
                    x-transition
                    class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/90 space-y-3"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-calendar-days class="w-4 h-4 text-amber-600" />
                            <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Divisão de Parcelas</span>
                        </div>
                        <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-amber-200/90 text-amber-900 font-mono" x-text="installments + 'x'"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                        <div>
                            <label class="block text-xs text-amber-800 font-semibold mb-1">Quantidade de Parcelas</label>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    @click="decrementInstallments()" 
                                    class="w-8 h-8 rounded-lg bg-white border border-amber-300 text-amber-800 font-bold flex items-center justify-center hover:bg-amber-100 transition"
                                >
                                    -
                                </button>
                                <input 
                                    type="number" 
                                    name="total_installments" 
                                    min="2" 
                                    max="48" 
                                    x-model="installments" 
                                    class="w-full text-center text-sm font-bold rounded-xl border-amber-300 bg-white focus:border-amber-500 focus:ring-amber-500"
                                />
                                <button 
                                    type="button" 
                                    @click="incrementInstallments()" 
                                    class="w-8 h-8 rounded-lg bg-white border border-amber-300 text-amber-800 font-bold flex items-center justify-center hover:bg-amber-100 transition"
                                >
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="p-3 bg-white rounded-xl border border-amber-200/80 shadow-xs">
                            <div class="text-[11px] text-zinc-500 font-medium">Estimativa por Parcela</div>
                            <div class="text-base font-bold text-zinc-900 mt-0.5" x-text="estimatedInstallment"></div>
                            <div class="text-[10px] text-amber-700 font-medium">Ajuste de centavos incluso na 1ª parcela</div>
                        </div>
                    </div>
                </div>

                <!-- Category & Account -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_category_id" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            Categoria
                        </label>
                        <select 
                            name="category_id" 
                            id="modal_category_id" 
                            x-model="categoryId"
                            required 
                            class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option value="">Selecione uma categoria...</option>
                            @foreach ($modalCategories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }} ({{ $cat->type === 'expense' ? 'Despesa' : 'Receita' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="modal_account_id" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            Conta Bancária
                        </label>
                        <select 
                            name="account_id" 
                            id="modal_account_id" 
                            x-model="accountId"
                            required 
                            class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option value="">Selecione a conta...</option>
                            @foreach ($modalAccounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->name }} ({{ $acc->formatted_current_balance }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Due Date & Payment Method -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_due_date" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            <span x-text="isInstallment ? 'Vencimento da 1ª Parcela' : 'Data de Vencimento'"></span>
                        </label>
                        <input 
                            type="date" 
                            name="due_date" 
                            id="modal_due_date" 
                            value="{{ date('Y-m-d') }}" 
                            required 
                            class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500"
                        />
                    </div>

                    <div>
                        <label for="modal_payment_method" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                            Forma de Pagamento
                        </label>
                        <select 
                            name="payment_method" 
                            id="modal_payment_method" 
                            x-model="paymentMethod"
                            required 
                            class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option value="pix">PIX</option>
                            <option value="credit_card">Cartão de Crédito</option>
                            <option value="debit">Cartão de Débito</option>
                            <option value="cash">Dinheiro em Espécie</option>
                        </select>
                    </div>
                </div>

                <!-- Status (Pago vs Pendente) -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                        Status do Lançamento
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label 
                            class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition"
                            :class="status === 'paid' ? 'border-emerald-500 bg-emerald-50/60 text-emerald-950 font-bold' : 'border-zinc-200 text-zinc-600'"
                        >
                            <input type="radio" name="status" value="paid" x-model="status" class="text-emerald-600 focus:ring-emerald-500">
                            <div class="text-xs">
                                <div>Pago / Recebido</div>
                                <div class="text-[10px] text-zinc-400 font-normal">Realizado imediatamente</div>
                            </div>
                        </label>
                        <label 
                            class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition"
                            :class="status === 'pending' ? 'border-amber-500 bg-amber-50/60 text-amber-950 font-bold' : 'border-zinc-200 text-zinc-600'"
                        >
                            <input type="radio" name="status" value="pending" x-model="status" class="text-amber-600 focus:ring-amber-500">
                            <div class="text-xs">
                                <div>Pendente / Futuro</div>
                                <div class="text-[10px] text-zinc-400 font-normal">Agendado para o vencimento</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="createTxModalOpen = false" 
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-zinc-100 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition flex items-center gap-1.5"
                    >
                        <x-lucide-check class="w-4 h-4 text-emerald-400" />
                        <span>Confirmar Lançamento</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
