<x-app-layout>
    @php
        $totalIncome = $transactions->where('type', 'income')->where('status', 'paid')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $periodBalance = $totalIncome - $totalExpense;
    @endphp

    <div class="w-full max-w-full overflow-hidden" x-data="{ 
        deleteModalOpen: false, 
        deleteTxId: null, 
        deleteTxDescription: '', 
        deleteTxIsInstallment: false,
        deleteActionUrl: '',
        
        confirmDelete(id, description, isInstallment, url) {
            this.deleteTxId = id;
            this.deleteTxDescription = description;
            this.deleteTxIsInstallment = isInstallment;
            this.deleteActionUrl = url;
            this.deleteModalOpen = true;
        }
    }">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-200 w-full min-w-0">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900 truncate">Extrato & Lançamentos</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">Histórico completo de despesas e receitas do casal</p>
            </div>
            <button 
                @click="createTxModalOpen = true"
                class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition self-start sm:self-auto shrink-0"
            >
                <x-lucide-plus class="w-4 h-4 text-emerald-400" />
                <span>Nova Transação</span>
            </button>
        </div>

        <!-- Period Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mt-6 w-full max-w-full">
            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-zinc-200/80 shadow-xs min-w-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 block truncate">Lançamentos</span>
                <div class="text-base sm:text-lg lg:text-xl font-extrabold text-zinc-900 mt-1 truncate">{{ $transactions->total() }}</div>
            </div>
            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-zinc-200/80 shadow-xs min-w-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block truncate">Entradas Pagas</span>
                <div class="text-base sm:text-lg lg:text-xl font-extrabold text-emerald-600 mt-1 truncate">R$ {{ number_format($totalIncome, 2, ',', '.') }}</div>
            </div>
            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-zinc-200/80 shadow-xs min-w-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 block truncate">Saídas no Mês</span>
                <div class="text-base sm:text-lg lg:text-xl font-extrabold text-rose-600 mt-1 truncate">R$ {{ number_format($totalExpense, 2, ',', '.') }}</div>
            </div>
            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-zinc-200/80 shadow-xs min-w-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 block truncate">Saldo do Extrato</span>
                <div class="text-base sm:text-lg lg:text-xl font-extrabold mt-1 truncate {{ $periodBalance >= 0 ? 'text-zinc-900' : 'text-rose-600' }}">
                    R$ {{ number_format($periodBalance, 2, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Filters Form -->
        <form method="GET" action="{{ route('transactions.index') }}" class="p-3.5 sm:p-5 bg-white rounded-3xl border border-zinc-200/80 shadow-xs mt-6 space-y-4 w-full max-w-full overflow-hidden">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-3 w-full">
                <!-- Year -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Ano</label>
                    <select name="year" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50">
                        @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Month -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Mês</label>
                    <select name="month" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50">
                        @php
                            $months = [
                                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                            ];
                        @endphp
                        @foreach ($months as $mNum => $mName)
                            <option value="{{ $mNum }}" {{ $month == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Categoria</label>
                    <select name="category_id" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50 truncate">
                        <option value="">Todas</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Account -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Conta</label>
                    <select name="account_id" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50 truncate">
                        <option value="">Todas</option>
                        @foreach ($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $selectedAccountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50">
                        <option value="">Todos</option>
                        <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Pago / Recebido</option>
                        <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pendente</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="min-w-0 w-full">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Tipo</label>
                    <select name="type" class="w-full min-w-0 max-w-full text-xs font-semibold rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50">
                        <option value="">Todos</option>
                        <option value="expense" {{ $selectedType === 'expense' ? 'selected' : '' }}>Despesa</option>
                        <option value="income" {{ $selectedType === 'income' ? 'selected' : '' }}>Receita</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-zinc-100 min-w-0">
                <div class="relative w-full sm:max-w-sm min-w-0">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Buscar por descrição..." 
                        class="w-full text-xs rounded-xl border-zinc-200 pl-8 focus:border-emerald-500 focus:ring-emerald-500 bg-zinc-50/50 min-w-0"
                    />
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-zinc-400">
                        <x-lucide-search class="w-3.5 h-3.5" />
                    </div>
                </div>

                <div class="flex items-center gap-2 justify-end shrink-0">
                    <a href="{{ route('transactions.index') }}" class="px-3 py-2 text-xs text-zinc-500 hover:text-zinc-800 font-bold transition">
                        Limpar
                    </a>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold transition shadow-xs">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        <!-- Transactions Container (Responsive: Card List on Mobile/Tablet, Table on Desktop) -->
        <div class="mt-6 bg-white rounded-3xl border border-zinc-200/80 shadow-xs overflow-hidden w-full max-w-full">
            
            <!-- 1. MOBILE & TABLET CARD LIST (Hidden on Desktop lg:, No Horizontal Scroll!) -->
            <div class="block lg:hidden divide-y divide-zinc-100">
                @forelse ($transactions as $tx)
                    <div class="p-3.5 sm:p-4 space-y-2.5 hover:bg-zinc-50/60 transition">
                        <!-- Top Row: Date, Category and Status Toggle -->
                        <div class="flex items-center justify-between gap-2 min-w-0">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="text-xs font-bold text-zinc-900 shrink-0">{{ $tx->due_date->format('d/m/Y') }}</span>
                                @if ($tx->category)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold truncate min-w-0 max-w-[120px]" style="background-color: {{ $tx->category->color }}15; color: {{ $tx->category->color }}">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $tx->category->color }}"></span>
                                        <span class="truncate">{{ $tx->category->name }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Status Button Toggle -->
                            <form method="POST" action="{{ route('transactions.update-status', $tx) }}" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition shadow-xs {{ $tx->isPaid() ? 'bg-emerald-100/90 text-emerald-900' : 'bg-amber-100/90 text-amber-900' }}"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $tx->isPaid() ? 'bg-emerald-600' : 'bg-amber-600' }}"></span>
                                    <span>{{ $tx->isPaid() ? 'Pago' : 'Pendente' }}</span>
                                </button>
                            </form>
                        </div>

                        <!-- Middle Row: Description, Installment and Amount -->
                        <div class="flex items-start justify-between gap-2.5 min-w-0">
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-bold text-zinc-900 leading-snug break-words">
                                    {{ $tx->description }}
                                </div>
                                @if ($tx->isInstallment())
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 font-mono">
                                            Parcela {{ $tx->installment_number }}/{{ $tx->total_installments }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm sm:text-base font-extrabold {{ $tx->isIncome() ? 'text-emerald-600' : 'text-zinc-900' }}">
                                    {{ $tx->isIncome() ? '+' : '-' }} {{ $tx->formatted_amount }}
                                </span>
                            </div>
                        </div>

                        <!-- Bottom Row: Account, User Initials, and Delete Button -->
                        <div class="flex items-center justify-between pt-1.5 text-[11px] text-zinc-500 border-t border-zinc-100/70 min-w-0">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-medium text-zinc-700 truncate max-w-[110px]">{{ $tx->account?->name ?? 'Conta' }}</span>
                                <span class="text-zinc-300 shrink-0">&bull;</span>
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div class="w-5 h-5 rounded-full bg-zinc-800 text-white text-[9px] font-bold flex items-center justify-center shrink-0">
                                        {{ $tx->user?->initials ?? '?' }}
                                    </div>
                                    <span class="text-zinc-600 truncate max-w-[90px]">{{ $tx->user?->name }}</span>
                                </div>
                            </div>

                            <button 
                                type="button" 
                                @click="confirmDelete('{{ $tx->id }}', '{{ addslashes($tx->display_description) }}', {{ $tx->isInstallment() ? 'true' : 'false' }}, '{{ route('transactions.destroy', $tx) }}')"
                                class="p-1.5 text-zinc-400 hover:text-rose-600 transition shrink-0 ml-2"
                                title="Excluir lançamento"
                            >
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 px-4 text-center text-zinc-400">
                        <div class="w-12 h-12 mx-auto rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mb-2">
                            <x-lucide-receipt class="w-6 h-6" />
                        </div>
                        <p class="text-sm font-bold text-zinc-700">Nenhuma transação encontrada</p>
                        <p class="text-xs text-zinc-400 mt-1">Ajuste os filtros ou adicione uma nova transação.</p>
                    </div>
                @endforelse
            </div>

            <!-- 2. DESKTOP DATA TABLE (Shown on lg: and larger, with scroll protection) -->
            <div class="hidden lg:block overflow-x-auto w-full max-w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200/80 bg-zinc-50/60 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">
                            <th class="py-4 px-5">Vencimento</th>
                            <th class="py-4 px-5">Descrição</th>
                            <th class="py-4 px-5">Categoria</th>
                            <th class="py-4 px-5">Conta</th>
                            <th class="py-4 px-5">Cadastrado por</th>
                            <th class="py-4 px-5">Status</th>
                            <th class="py-4 px-5 text-right">Valor</th>
                            <th class="py-4 px-5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-xs">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-zinc-50/80 transition group">
                                <!-- Vencimento -->
                                <td class="py-4 px-5 whitespace-nowrap text-zinc-600">
                                    <div class="font-bold text-zinc-900 text-xs">{{ $tx->due_date->format('d/m/Y') }}</div>
                                    @if ($tx->paid_at)
                                        <div class="text-[10px] text-zinc-400">Pago: {{ $tx->paid_at->format('d/m/Y') }}</div>
                                    @endif
                                </td>

                                <!-- Descrição + Parcelamento -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-zinc-900 text-sm">{{ $tx->description }}</span>
                                        @if ($tx->isInstallment())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 font-mono">
                                                {{ $tx->installment_number }}/{{ $tx->total_installments }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-zinc-400 mt-0.5 uppercase tracking-wider font-mono">
                                        {{ $tx->payment_method }}
                                    </div>
                                </td>

                                <!-- Categoria -->
                                <td class="py-4 px-5 whitespace-nowrap">
                                    @if ($tx->category)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold" style="background-color: {{ $tx->category->color }}15; color: {{ $tx->category->color }}">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $tx->category->color }}"></span>
                                            <span>{{ $tx->category->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-400">---</span>
                                    @endif
                                </td>

                                <!-- Conta -->
                                <td class="py-4 px-5 whitespace-nowrap text-zinc-700 font-medium">
                                    {{ $tx->account?->name ?? '---' }}
                                </td>

                                <!-- Cadastrado por -->
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-zinc-800 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                            {{ $tx->user?->initials ?? '?' }}
                                        </div>
                                        <span class="text-zinc-700 font-medium">{{ $tx->user?->name }}</span>
                                    </div>
                                </td>

                                <!-- Status Badge & Toggle -->
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <form method="POST" action="{{ route('transactions.update-status', $tx) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            title="Clique para alternar entre pago e pendente"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold transition shadow-xs {{ $tx->isPaid() ? 'bg-emerald-100/80 text-emerald-900 hover:bg-emerald-200' : 'bg-amber-100/80 text-amber-900 hover:bg-amber-200' }}"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full {{ $tx->isPaid() ? 'bg-emerald-600' : 'bg-amber-600' }}"></span>
                                            <span>{{ $tx->isPaid() ? 'Pago' : 'Pendente' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Valor -->
                                <td class="py-4 px-5 whitespace-nowrap text-right font-extrabold text-sm {{ $tx->isIncome() ? 'text-emerald-600' : 'text-zinc-900' }}">
                                    {{ $tx->isIncome() ? '+' : '-' }} {{ $tx->formatted_amount }}
                                </td>

                                <!-- Ações -->
                                <td class="py-4 px-5 whitespace-nowrap text-right">
                                    <button 
                                        type="button" 
                                        @click="confirmDelete('{{ $tx->id }}', '{{ addslashes($tx->display_description) }}', {{ $tx->isInstallment() ? 'true' : 'false' }}, '{{ route('transactions.destroy', $tx) }}')"
                                        class="p-2 text-zinc-300 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition"
                                        title="Excluir lançamento"
                                    >
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center text-zinc-400">
                                    <div class="w-14 h-14 mx-auto rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mb-3">
                                        <x-lucide-receipt class="w-7 h-7" />
                                    </div>
                                    <p class="text-sm font-bold text-zinc-700">Nenhuma transação encontrada</p>
                                    <p class="text-xs text-zinc-400 mt-1">Ajuste os filtros de busca ou adicione um novo lançamento.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="p-4 border-t border-zinc-200/80 bg-zinc-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div 
            x-show="deleteModalOpen" 
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-cloak
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div 
                    x-show="deleteModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
                    @click="deleteModalOpen = false"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div 
                    x-show="deleteModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-zinc-100 sm:my-8"
                >
                    <div class="flex items-center gap-3 pb-4 border-b border-zinc-100">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <x-lucide-alert-triangle class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900">Confirmar Exclusão</h3>
                            <p class="text-xs text-zinc-500">Selecione o escopo da exclusão</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs text-zinc-600">
                            Deseja remover <strong class="text-zinc-900" x-text="deleteTxDescription"></strong>?
                        </p>
                    </div>

                    <!-- Options for Installment vs Single -->
                    <div class="mt-6 space-y-2">
                        <!-- Option 1: Delete only this -->
                        <form method="POST" :action="deleteActionUrl">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="delete_scope" value="only_this">
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition flex items-center justify-center gap-1.5 shadow-sm"
                            >
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                <span x-text="deleteTxIsInstallment ? 'Excluir apenas esta parcela' : 'Excluir lançamento'"></span>
                            </button>
                        </form>

                        <!-- Option 2: Delete this and all future installments (Only if installment) -->
                        <template x-if="deleteTxIsInstallment">
                            <form method="POST" :action="deleteActionUrl">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="delete_scope" value="all_future">
                                <button 
                                    type="submit" 
                                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-zinc-900 hover:bg-zinc-800 text-white transition flex items-center justify-center gap-1.5"
                                >
                                    <x-lucide-calendar-x class="w-3.5 h-3.5 text-amber-400" />
                                    <span>Excluir esta e todas as parcelas futuras</span>
                                </button>
                            </form>
                        </template>

                        <button 
                            type="button" 
                            @click="deleteModalOpen = false" 
                            class="w-full py-2 px-4 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-zinc-100 transition"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
