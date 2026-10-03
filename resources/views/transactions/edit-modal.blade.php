@php
    $modalCategories = \App\Models\Category::orderBy('name')->get();
    $modalAccounts = \App\Models\Account::orderBy('name')->get();
@endphp

<!-- Edit Transaction Modal -->
<div 
    x-show="editTxModalOpen" 
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="editTxModalOpen" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
            @click="editTxModalOpen = false; window.haptic('light')"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div 
            x-show="editTxModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-lg p-6 sm:p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-zinc-200"
        >
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-zinc-100 text-zinc-800 flex items-center justify-center">
                        <x-lucide-pencil class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900">Editar Despesa / Transação</h3>
                        <p class="text-xs text-zinc-500">Altere o valor, vencimento, conta ou categoria</p>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="editTxModalOpen = false; window.haptic('light')"
                    class="p-2 text-zinc-400 hover:text-zinc-600 rounded-xl transition"
                >
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <form :action="editTxAction" method="POST" class="mt-6 space-y-4" onsubmit="window.haptic('success')">
                @csrf
                @method('PUT')

                <!-- Type Selector (Expense / Income) -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Tipo</label>
                    <div class="grid grid-cols-2 gap-2 p-1 bg-zinc-100 rounded-2xl">
                        <button 
                            type="button"
                            @click="editTxData.type = 'expense'; window.haptic('light')"
                            :class="editTxData.type === 'expense' ? 'bg-white text-rose-600 shadow-xs' : 'text-zinc-500 hover:text-zinc-800'"
                            class="py-2 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                        >
                            <x-lucide-arrow-down-left class="w-3.5 h-3.5" />
                            <span>Despesa</span>
                        </button>
                        <button 
                            type="button"
                            @click="editTxData.type = 'income'; window.haptic('light')"
                            :class="editTxData.type === 'income' ? 'bg-white text-emerald-600 shadow-xs' : 'text-zinc-500 hover:text-zinc-800'"
                            class="py-2 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                        >
                            <x-lucide-arrow-up-right class="w-3.5 h-3.5" />
                            <span>Receita</span>
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="editTxData.type">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Descrição</label>
                    <input 
                        type="text" 
                        name="description" 
                        x-model="editTxData.description"
                        required 
                        class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500" 
                        placeholder="Ex: Supermercado, Aluguel, Farmácia"
                    >
                </div>

                <!-- Amount and Due Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Valor (R$)</label>
                        <div class="relative rounded-2xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 font-bold text-xs">
                                R$
                            </div>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="amount" 
                                x-model="editTxData.amount"
                                required 
                                class="w-full pl-10 text-sm font-extrabold text-zinc-900 rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono" 
                                placeholder="0,00"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Data de Vencimento</label>
                        <input 
                            type="date" 
                            name="due_date" 
                            x-model="editTxData.due_date"
                            required 
                            class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                    </div>
                </div>

                <!-- Category and Account Selectors -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Categoria</label>
                        <select 
                            name="category_id" 
                            x-model="editTxData.category_id"
                            required 
                            class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white"
                        >
                            @foreach ($modalCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Conta / Banco</label>
                        <select 
                            name="account_id" 
                            x-model="editTxData.account_id"
                            required 
                            class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white"
                        >
                            @foreach ($modalAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status and Payment Method -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Status</label>
                        <select 
                            name="status" 
                            x-model="editTxData.status"
                            required 
                            class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white"
                        >
                            <option value="pending">Pendente (a pagar)</option>
                            <option value="paid">Pago / Concluído</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Forma de Pagamento</label>
                        <select 
                            name="payment_method" 
                            x-model="editTxData.payment_method"
                            class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white"
                        >
                            <option value="pix">PIX</option>
                            <option value="credit_card">Cartão de Crédito</option>
                            <option value="debit_card">Cartão de Débito</option>
                            <option value="cash">Dinheiro em Espécie</option>
                            <option value="bank_transfer">Transferência / Boleto</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-zinc-100">
                    <button 
                        type="button" 
                        @click="editTxModalOpen = false; window.haptic('light')" 
                        class="px-5 py-2.5 rounded-2xl text-xs font-bold text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition flex items-center gap-2"
                    >
                        <x-lucide-check class="w-4 h-4 text-emerald-400" />
                        <span>Salvar Lançamento</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
