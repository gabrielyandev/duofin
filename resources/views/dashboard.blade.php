<x-app-layout>
    @php
        $prevDate = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->subMonth();
        $nextDate = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->addMonth();
    @endphp

    <!-- Dashboard Header with Month Selector -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Visão Geral Financeira</h1>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Ao Vivo</span>
                </span>
            </div>
            <p class="text-sm text-zinc-500 mt-0.5">Acompanhe as contas conjuntas, projeções de parcelas e divisão do casal</p>
        </div>

        <!-- Reference Month Navigator with Dropdown -->
        <div class="flex items-center gap-2 self-start sm:self-auto bg-white p-1.5 rounded-2xl border border-zinc-200/80 shadow-xs" x-data="{ monthPickerOpen: false }">
            <a 
                href="{{ route('dashboard', ['year' => $prevDate->year, 'month' => $prevDate->month]) }}" 
                class="p-2 rounded-xl text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition"
                title="Mês Anterior"
            >
                <x-lucide-chevron-left class="w-4 h-4" />
            </a>

            <!-- Month Dropdown Trigger -->
            <div class="relative">
                <button 
                    type="button"
                    @click="monthPickerOpen = !monthPickerOpen"
                    class="px-3.5 py-1.5 text-xs font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-50 rounded-xl transition"
                >
                    <x-lucide-calendar class="w-3.5 h-3.5 text-emerald-600" />
                    <span>{{ $metrics['month_label'] }}</span>
                    <x-lucide-chevron-down class="w-3 h-3 text-zinc-400" />
                </button>

                <!-- Month Picker Popover -->
                <div 
                    x-show="monthPickerOpen" 
                    @click.away="monthPickerOpen = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-zinc-200 p-3 z-50"
                    style="display: none;"
                >
                    <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider px-2 mb-2">Selecione o Mês</div>
                    <div class="grid grid-cols-3 gap-1">
                        @php
                            $allMonths = [
                                1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                                5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                                9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
                            ];
                        @endphp
                        @foreach ($allMonths as $num => $shortName)
                            <a 
                                href="{{ route('dashboard', ['year' => $currentYear, 'month' => $num]) }}"
                                class="py-1.5 px-2 text-center text-xs font-semibold rounded-lg transition {{ $currentMonth == $num ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}"
                            >
                                {{ $shortName }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a 
                href="{{ route('dashboard', ['year' => $nextDate->year, 'month' => $nextDate->month]) }}" 
                class="p-2 rounded-xl text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition"
                title="Próximo Mês"
            >
                <x-lucide-chevron-right class="w-4 h-4" />
            </a>

            @if ($currentYear !== (int) now()->year || $currentMonth !== (int) now()->month)
                <a 
                    href="{{ route('dashboard') }}" 
                    class="ml-1 px-3 py-1.5 text-[11px] font-bold text-emerald-800 bg-emerald-100/80 hover:bg-emerald-200/80 rounded-xl transition"
                >
                    Mês Atual
                </a>
            @endif
        </div>
    </div>

    <!-- 4 Top Metric Cards (Fintech Style with subtle glow) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <!-- Card 1: Saldo em Contas -->
        <div class="relative overflow-hidden p-6 bg-gradient-to-br from-white via-white to-zinc-50/80 rounded-3xl border border-zinc-200/80 shadow-xs hover:shadow-md hover:border-zinc-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Saldo em Contas</span>
                <div class="w-9 h-9 rounded-xl bg-zinc-100 text-zinc-800 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <x-lucide-wallet class="w-4 h-4" />
                </div>
            </div>
            <div class="mt-4 text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight">
                {{ $metrics['formatted_account_balance'] }}
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-zinc-500">
                <x-lucide-landmark class="w-3.5 h-3.5 text-zinc-400" />
                <span>{{ $accounts->count() }} conta(s) bancária(s) ativas</span>
            </div>
        </div>

        <!-- Card 2: Receitas do Mês -->
        <div class="relative overflow-hidden p-6 bg-gradient-to-br from-white via-white to-emerald-50/40 rounded-3xl border border-zinc-200/80 shadow-xs hover:shadow-md hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Receitas do Mês</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <x-lucide-arrow-up-right class="w-4 h-4" />
                </div>
            </div>
            <div class="mt-4 text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">
                {{ $metrics['formatted_month_income_paid'] }}
            </div>
            <div class="mt-2 text-xs text-zinc-500">
                <span>Total de entradas confirmadas</span>
            </div>
        </div>

        <!-- Card 3: Despesas do Mês -->
        <div class="relative overflow-hidden p-6 bg-gradient-to-br from-white via-white to-rose-50/40 rounded-3xl border border-zinc-200/80 shadow-xs hover:shadow-md hover:border-rose-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Despesas do Mês</span>
                <div class="w-9 h-9 rounded-xl bg-rose-100/80 text-rose-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <x-lucide-arrow-down-left class="w-4 h-4" />
                </div>
            </div>
            <div class="mt-4 text-2xl sm:text-3xl font-extrabold text-rose-600 tracking-tight">
                {{ $metrics['formatted_month_expenses'] }}
            </div>
            <div class="mt-2 flex items-center gap-2 text-xs">
                <span class="text-zinc-600 font-medium">Pagas: <strong class="text-zinc-900">{{ $metrics['formatted_month_expenses_paid'] }}</strong></span>
                <span class="text-zinc-300">•</span>
                <span class="text-amber-700 font-medium">Pend: <strong class="text-amber-900">{{ $metrics['formatted_month_expenses_pending'] }}</strong></span>
            </div>
        </div>

        <!-- Card 4: Balanço Líquido -->
        <div class="relative overflow-hidden p-6 bg-gradient-to-br from-white via-white to-indigo-50/40 rounded-3xl border border-zinc-200/80 shadow-xs hover:shadow-md hover:border-indigo-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Balanço Líquido</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-100/80 text-indigo-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <x-lucide-scale class="w-4 h-4" />
                </div>
            </div>
            <div class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight {{ $metrics['month_net_balance'] >= 0 ? 'text-zinc-900' : 'text-rose-600' }}">
                {{ $metrics['formatted_month_net_balance'] }}
            </div>
            <div class="mt-2 text-xs text-zinc-500">
                <span>Projetado (Receitas - Despesas)</span>
            </div>
        </div>
    </div>

    <!-- Future Expense & Installments Predictability Section (6 Months) -->
    <div class="mt-6 p-6 sm:p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <x-lucide-trending-up class="w-5 h-5 text-emerald-600" />
                    <h2 class="text-base sm:text-lg font-bold text-zinc-900">Previsibilidade de Gastos Futuros</h2>
                </div>
                <p class="text-xs text-zinc-500 mt-1">Total comprometido por mês para os próximos 6 meses com parcelas já contratadas</p>
            </div>
            <div class="flex items-center gap-4 text-xs bg-zinc-50 px-3 py-1.5 rounded-xl border border-zinc-200/60 self-start sm:self-auto">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-zinc-900 inline-block"></span>
                    <span class="text-zinc-700 font-semibold">Total Geral</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-amber-400 inline-block"></span>
                    <span class="text-zinc-700 font-semibold">Parcelas</span>
                </div>
            </div>
        </div>

        <!-- 6-Month Visual Projection Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach ($metrics['projections'] as $proj)
                <a 
                    href="{{ route('dashboard', ['year' => $proj['year'], 'month' => $proj['month']]) }}"
                    class="p-4 rounded-2xl border transition flex flex-col justify-between group {{ $proj['is_selected'] ? 'border-zinc-900 bg-zinc-50/90 ring-2 ring-zinc-900 shadow-sm' : 'border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50/50' }}"
                >
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider {{ $proj['is_selected'] ? 'text-zinc-900' : 'text-zinc-500' }}">
                            {{ $proj['label'] }}
                        </span>
                        @if ($proj['is_current'])
                            <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-md">
                                Atual
                            </span>
                        @endif
                    </div>

                    <!-- Progress Bar Representation -->
                    <div class="space-y-1.5 my-3">
                        <div class="w-full h-3 bg-zinc-100 rounded-full overflow-hidden flex relative">
                            <div 
                                class="bg-zinc-900 h-full rounded-full transition-all duration-500" 
                                style="width: {{ $proj['bar_percentage'] }}%"
                            ></div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 mt-1">
                        <div class="text-sm font-bold text-zinc-900 tracking-tight">
                            {{ $proj['formatted_total'] }}
                        </div>
                        <div class="text-[11px] text-amber-700 font-bold flex items-center gap-1 mt-0.5">
                            <x-lucide-layers class="w-3 h-3 text-amber-500 shrink-0" />
                            <span>{{ $proj['formatted_installments'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 2 Columns Grid: Left (Categories & Partner Spend) | Right (Upcoming Due Bills) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
        <!-- Left Column: 7 Cols -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Expenses by Category -->
            <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Gastos por Categoria</h2>
                        <p class="text-xs text-zinc-500">Distribuição percentual das despesas deste mês</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1 transition">
                        <span>Ver todas</span>
                        <x-lucide-arrow-right class="w-3.5 h-3.5" />
                    </a>
                </div>

                @if (count($metrics['category_expenses']) > 0)
                    <div class="space-y-4">
                        @foreach ($metrics['category_expenses'] as $cat)
                            <div class="p-3 rounded-2xl hover:bg-zinc-50 transition border border-transparent hover:border-zinc-100">
                                <div class="flex items-center justify-between text-xs mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $cat['color'] }}"></span>
                                        <span class="font-bold text-zinc-800 text-sm">{{ $cat['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-zinc-900 text-sm">{{ $cat['formatted_total'] }}</span>
                                        <span class="text-zinc-400 font-semibold text-xs">({{ $cat['percentage'] }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full h-2.5 bg-zinc-100 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500" 
                                        style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }}"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mb-3">
                            <x-lucide-pie-chart class="w-6 h-6" />
                        </div>
                        <p class="text-sm font-semibold text-zinc-700">Nenhuma despesa registrada</p>
                        <p class="text-xs text-zinc-400 mt-1">Clique em "Nova Transação" no topo para começar.</p>
                    </div>
                @endif
            </div>

            <!-- Card: "Quem gastou mais" -->
            <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Divisão do Casal ("Quem gastou mais")</h2>
                        <p class="text-xs text-zinc-500">Proporção das despesas cadastradas por cada parceiro no mês</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-lucide-users-2 class="w-4 h-4" />
                    </div>
                </div>

                @if (count($metrics['spending_by_member']) > 0)
                    <!-- Multi-bar comparison -->
                    <div class="w-full h-3.5 bg-zinc-100 rounded-full overflow-hidden flex mb-5 p-0.5">
                        @foreach ($metrics['spending_by_member'] as $index => $memberData)
                            <div 
                                class="{{ $index === 0 ? 'bg-zinc-900' : 'bg-emerald-500' }} h-full rounded-full transition-all duration-500"
                                style="width: {{ $memberData['percentage'] }}%"
                                title="{{ $memberData['name'] }}: {{ $memberData['percentage'] }}%"
                            ></div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($metrics['spending_by_member'] as $index => $memberData)
                            <div class="p-4 rounded-2xl border border-zinc-200/80 bg-zinc-50/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $index === 0 ? 'bg-zinc-900 text-white' : 'bg-emerald-600 text-white' }} font-bold text-xs flex items-center justify-center shadow-xs">
                                        {{ $memberData['initials'] }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-zinc-900">{{ $memberData['name'] }}</div>
                                        <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">{{ $memberData['role'] === 'owner' ? 'Proprietário' : 'Parceiro(a)' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-extrabold text-zinc-900">{{ $memberData['formatted_total'] }}</div>
                                    <div class="text-xs font-bold {{ $index === 0 ? 'text-zinc-700' : 'text-emerald-700' }}">{{ $memberData['percentage'] }}%</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-xs text-zinc-500">
                        Nenhuma informação de membros disponível.
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: 5 Cols (Upcoming 7 Days Due Bills) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Vencimentos Próximos</h2>
                        <p class="text-xs text-zinc-500">Contas pendentes nos próximos 7 dias</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900">
                        {{ $metrics['upcoming_bills']->count() }} pendente(s)
                    </span>
                </div>

                @if ($metrics['upcoming_bills']->count() > 0)
                    <div class="space-y-3">
                        @foreach ($metrics['upcoming_bills'] as $bill)
                            @php
                                $due = \Carbon\Carbon::parse($bill->due_date);
                                $isToday = $due->isToday();
                                $isTomorrow = $due->isTomorrow();
                            @endphp
                            <div class="p-4 rounded-2xl border border-zinc-200/90 hover:border-zinc-300 transition flex items-center justify-between gap-3 bg-white hover:bg-zinc-50/50">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if ($isToday)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800 animate-pulse">
                                                Vence Hoje
                                            </span>
                                        @elseif ($isTomorrow)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800">
                                                Vence Amanhã
                                            </span>
                                        @else
                                            <span class="text-[11px] font-semibold text-zinc-500 flex items-center gap-1">
                                                <x-lucide-calendar class="w-3.5 h-3.5 text-zinc-400" />
                                                {{ $due->format('d/m/Y') }}
                                            </span>
                                        @endif

                                        @if ($bill->isInstallment())
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-zinc-100 text-zinc-700">
                                                {{ $bill->installment_number }}/{{ $bill->total_installments }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-sm font-bold text-zinc-900 truncate">
                                        {{ $bill->description }}
                                    </div>
                                    <div class="text-xs text-zinc-500 flex items-center gap-1.5 mt-0.5">
                                        <span>{{ $bill->category?->name }}</span>
                                        <span>•</span>
                                        <span>{{ $bill->account?->name }}</span>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-base font-extrabold text-rose-600 mb-1.5">
                                        {{ $bill->formatted_amount }}
                                    </div>
                                    <!-- 1-Click Mark as Paid -->
                                    <form method="POST" action="{{ route('transactions.update-status', $bill) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/60 transition shadow-xs"
                                            title="Marcar como Pago em 1 clique"
                                        >
                                            <x-lucide-check class="w-3.5 h-3.5" />
                                            <span>Pagar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            <x-lucide-check-circle-2 class="w-6 h-6" />
                        </div>
                        <p class="text-sm font-bold text-zinc-800">Tudo em dia!</p>
                        <p class="text-xs text-zinc-400 mt-1">Nenhuma conta pendente para os próximos 7 dias.</p>
                    </div>
                @endif
            </div>

            <!-- Quick Account Balances Summary -->
            <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-zinc-900">Contas Conectadas</h2>
                    <a href="{{ route('accounts.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1 transition">
                        <span>Gerenciar</span>
                        <x-lucide-arrow-right class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($accounts as $acc)
                        <div class="p-3.5 rounded-2xl bg-zinc-50 border border-zinc-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-3.5 h-3.5 rounded-full shrink-0" style="background-color: {{ $acc->color }}"></span>
                                <span class="text-xs font-bold text-zinc-800">{{ $acc->name }}</span>
                            </div>
                            <span class="text-sm font-extrabold text-zinc-900">{{ $acc->formatted_current_balance }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-500">Nenhuma conta cadastrada.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
