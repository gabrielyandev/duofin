<x-app-layout>
    <div x-data="{ createCatModalOpen: false, selectedColor: '#0ea5e9' }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Categorias Financeiras</h1>
                <p class="text-sm text-zinc-500">Organize os gastos e fontes de renda do casal por categorias personalizadas</p>
            </div>
            <button 
                @click="createCatModalOpen = true"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10 transition self-start sm:self-auto"
            >
                <x-lucide-plus class="w-4 h-4 text-emerald-400" />
                <span>Nova Categoria</span>
            </button>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6">
            @forelse ($categories as $cat)
                <div class="p-6 bg-white rounded-3xl border border-zinc-200/80 shadow-xs flex items-center justify-between group hover:border-zinc-300 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold shadow-xs shrink-0" style="background-color: {{ $cat->color }}15; color: {{ $cat->color }}">
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $cat->color }}"></span>
                        </div>
                        <div>
                            <div class="text-base font-bold text-zinc-900">{{ $cat->name }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $cat->type === 'expense' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $cat->type === 'expense' ? 'Despesa' : 'Receita' }}
                                </span>
                                <span class="text-[11px] text-zinc-400 font-medium">
                                    {{ $cat->transactions()->count() }} lançamento(s)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Deseja excluir esta categoria?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-zinc-300 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition" title="Excluir Categoria">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-zinc-400">
                    <div class="w-14 h-14 mx-auto rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mb-3">
                        <x-lucide-tag class="w-7 h-7" />
                    </div>
                    <p class="text-base font-bold text-zinc-700">Nenhuma categoria cadastrada</p>
                    <p class="text-xs text-zinc-400 mt-1">Crie categorias para classificar os lançamentos do casal.</p>
                </div>
            @endforelse
        </div>

        <!-- Create Category Modal -->
        <div 
            x-show="createCatModalOpen" 
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-cloak
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div 
                    x-show="createCatModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-zinc-950/70 backdrop-blur-sm"
                    @click="createCatModalOpen = false"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div 
                    x-show="createCatModalOpen"
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
                                <x-lucide-tag class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900">Nova Categoria</h3>
                                <p class="text-xs text-zinc-500">Classificação para despesas ou receitas</p>
                            </div>
                        </div>
                        <button @click="createCatModalOpen = false" class="text-zinc-400 hover:text-zinc-600 transition">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>

                    <form method="POST" action="{{ route('categories.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Nome da Categoria</label>
                            <input type="text" name="name" required placeholder="Ex: Farmácia, Pet, Educação" class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Tipo de Movimentação</label>
                            <select name="type" required class="w-full text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="expense">Despesa</option>
                                <option value="income">Receita</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Cor de Identificação</label>
                            <div class="flex items-center gap-2 mb-2">
                                @php
                                    $presets = ['#6366f1', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#0ea5e9'];
                                @endphp
                                @foreach ($presets as $pColor)
                                    <button 
                                        type="button" 
                                        @click="selectedColor = '{{ $pColor }}'" 
                                        class="w-7 h-7 rounded-full border-2 transition transform hover:scale-110"
                                        :class="selectedColor === '{{ $pColor }}' ? 'border-zinc-900 ring-2 ring-zinc-300' : 'border-white'"
                                        style="background-color: {{ $pColor }}"
                                    ></button>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" :value="selectedColor">
                        </div>

                        <input type="hidden" name="icon" value="tag">

                        <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                            <button type="button" @click="createCatModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-zinc-100">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md shadow-zinc-900/10">
                                Salvar Categoria
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
