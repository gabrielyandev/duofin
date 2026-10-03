<!-- Edit Account Modal -->
<div 
    x-show="editAccountModalOpen" 
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="editAccountModalOpen" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
            @click="editAccountModalOpen = false; window.haptic('light')"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div 
            x-show="editAccountModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-md p-6 sm:p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-zinc-200"
        >
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <x-lucide-landmark class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900">Editar Saldo da Conta</h3>
                        <p class="text-xs text-zinc-500">Atualize o saldo inicial ou os dados da conta</p>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="editAccountModalOpen = false; window.haptic('light')"
                    class="p-2 text-zinc-400 hover:text-zinc-600 rounded-xl transition"
                >
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <form :action="editAccountAction" method="POST" class="mt-6 space-y-4" onsubmit="window.haptic('success')">
                @csrf
                @method('PUT')

                <!-- Account Name -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Nome da Conta / Banco</label>
                    <input 
                        type="text" 
                        name="name" 
                        x-model="editAccountData.name"
                        required 
                        class="w-full text-sm font-semibold rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500" 
                        placeholder="Ex: Nubank, Itaú, Carteira Física"
                    >
                </div>

                <!-- Initial Balance -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">Saldo Inicial / Base (R$)</label>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Altera o Saldo Total</span>
                    </div>
                    <div class="relative rounded-2xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-zinc-400 font-bold text-sm">
                            R$
                        </div>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="initial_balance" 
                            x-model="editAccountData.initial_balance"
                            required 
                            class="w-full pl-11 text-base font-extrabold text-zinc-900 rounded-2xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono" 
                            placeholder="0,00"
                        >
                    </div>
                    <p class="text-[11px] text-zinc-400 mt-1">O saldo atual da conta é calculado somando este saldo inicial com as receitas e deduzindo as despesas pagas.</p>
                </div>

                <!-- Color Palette -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Cor de Identificação</label>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @php
                            $colors = ['#820ad1', '#ec7000', '#f43f5e', '#0ea5e9', '#10b981', '#f59e0b', '#6366f1', '#18181b'];
                        @endphp
                        @foreach ($colors as $c)
                            <button 
                                type="button" 
                                @click="editAccountData.color = '{{ $c }}'; window.haptic('light')" 
                                class="w-8 h-8 rounded-full transition-transform hover:scale-110 flex items-center justify-center shrink-0 border-2"
                                :class="editAccountData.color === '{{ $c }}' ? 'border-zinc-900 scale-110 shadow-md ring-2 ring-zinc-300' : 'border-transparent'"
                                style="background-color: {{ $c }}"
                            >
                                <span x-show="editAccountData.color === '{{ $c }}'" class="text-white text-xs font-bold">✓</span>
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" :value="editAccountData.color">
                </div>

                <!-- Actions -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-zinc-100">
                    <button 
                        type="button" 
                        @click="editAccountModalOpen = false; window.haptic('light')" 
                        class="px-5 py-2.5 rounded-2xl text-xs font-bold text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition flex items-center gap-2"
                    >
                        <x-lucide-check class="w-4 h-4 text-emerald-400" />
                        <span>Salvar Saldo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
