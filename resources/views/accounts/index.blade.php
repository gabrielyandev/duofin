<x-app-layout>
    <div x-data="{ createAccModalOpen: false, selectedColor: '#820ad1' }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Contas Bancárias & Carteiras</h1>
                <p class="text-sm text-zinc-500">Gerencie onde o dinheiro do casal está guardado e seus saldos calculados</p>
            </div>
            <button 
                @click="createAccModalOpen = true"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition self-start sm:self-auto"
            >
                <x-lucide-plus class="w-4 h-4 text-emerald-400" />
                <span>Nova Conta</span>
            </button>
        </div>

        <!-- Accounts Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @forelse ($accounts as $acc)
                <div class="p-6 bg-white rounded-3xl border border-zinc-200/80 shadow-xs group hover:border-zinc-300 hover:shadow-md transition flex flex-col justify-between relative overflow-hidden">
                    <!-- Top color accent bar -->
                    <div class="absolute top-0 inset-x-0 h-1.5" style="background-color: {{ $acc->color }}"></div>

                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-white shadow-xs shrink-0" style="background-color: {{ $acc->color }}">
                                    <x-lucide-credit-card class="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-zinc-900">{{ $acc->name }}</h3>
                                    <div class="text-[11px] text-zinc-400 mt-0.5 font-medium">Saldo inicial: {{ $acc->formatted_initial_balance }}</div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('accounts.destroy', $acc) }}" onsubmit="return confirm('Deseja excluir esta conta? Todas as transações associadas serão removidas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-zinc-300 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition" title="Excluir Conta">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </form>
                        </div>

                        <div class="mt-8 pt-5 border-t border-zinc-100">
                            <div class="flex items-center justify-between text-[11px] font-bold text-zinc-400 uppercase tracking-wider mb-1">
                                <span>Saldo Atual Realizado</span>
                                <span class="text-emerald-700 font-mono text-[10px]">Calculado</span>
                            </div>
                            <div class="text-3xl font-extrabold text-zinc-900 tracking-tight">
                                {{ $acc->formatted_current_balance }}
                            </div>
                            <div class="text-[11px] text-zinc-400 mt-1 flex items-center gap-1 font-medium">
                                <x-lucide-arrow-left-right class="w-3 h-3 text-zinc-400" />
                                <span>{{ $acc->transactions()->count() }} movimentação(ões) registradas</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-zinc-400">
                    <div class="w-14 h-14 mx-auto rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mb-3">
                        <x-lucide-wallet class="w-7 h-7" />
                    </div>
                    <p class="text-base font-bold text-zinc-700">Nenhuma conta cadastrada</p>
                    <p class="text-xs text-zinc-400 mt-1">Cadastre as contas dos bancos do casal (ex: Nubank, Itaú, Inter).</p>
                </div>
            @endforelse
        </div>

        <!-- Create Account Modal -->
        <div 
            x-show="createAccModalOpen" 
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-cloak
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div 
                    x-show="createAccModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
                    @click="createAccModalOpen = false"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div 
                    x-show="createAccModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-zinc-100 sm:my-8"
                >
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-zinc-100 text-zinc-900 flex items-center justify-center">
                                <x-lucide-landmark class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900">Nova Conta Bancária</h3>
                                <p class="text-xs text-zinc-500">Adicione uma carteira ou conta corrente</p>
                            </div>
                        </div>
                        <button @click="createAccModalOpen = false" class="text-zinc-400 hover:text-zinc-600 transition">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>

                    <form method="POST" action="{{ route('accounts.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Nome da Conta / Banco</label>
                            <input type="text" name="name" required placeholder="Ex: Nubank, Itaú Corrente, Carteira" class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Saldo Inicial (R$)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs text-zinc-400 font-bold">R$</span>
                                <input type="number" step="0.01" name="initial_balance" value="0.00" required class="w-full pl-9 text-sm font-bold rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Cor do Banco</label>
                            <div class="flex items-center gap-2 mb-2">
                                @php
                                    $bankColors = ['#820ad1', '#ec7000', '#002f6c', '#ff0000', '#00d287', '#0f172a'];
                                @endphp
                                @foreach ($bankColors as $bColor)
                                    <button 
                                        type="button" 
                                        @click="selectedColor = '{{ $bColor }}'" 
                                        class="w-7 h-7 rounded-full border-2 transition transform hover:scale-110"
                                        :class="selectedColor === '{{ $bColor }}' ? 'border-zinc-900 ring-2 ring-zinc-300' : 'border-white'"
                                        style="background-color: {{ $bColor }}"
                                    ></button>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" :value="selectedColor">
                        </div>

                        <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                            <button type="button" @click="createAccModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-zinc-100">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10">
                                Salvar Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
