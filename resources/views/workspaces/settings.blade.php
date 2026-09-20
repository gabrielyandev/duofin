<x-app-layout>
    <div class="max-w-4xl space-y-6">
        <!-- Header -->
        <div class="pb-6 border-b border-zinc-200">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Configurações do Workspace</h1>
            <p class="text-sm text-zinc-500">Gerenciamento do ambiente compartilhado e membros vinculados</p>
        </div>

        <!-- Couple Connection Status Banner -->
        <div class="p-6 bg-gradient-to-r from-zinc-900 to-zinc-800 text-white rounded-3xl shadow-md relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="flex items-center -space-x-3">
                        @foreach ($members as $member)
                            <div class="w-12 h-12 rounded-full border-2 border-zinc-900 bg-emerald-500 text-zinc-950 font-bold flex items-center justify-center text-sm shadow-md">
                                {{ $member->initials }}
                            </div>
                        @endforeach
                        @if ($members->count() < 2)
                            <div class="w-12 h-12 rounded-full border-2 border-dashed border-zinc-500 bg-zinc-800/80 text-zinc-400 flex items-center justify-center text-xs">
                                <x-lucide-user-plus class="w-5 h-5" />
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">
                            {{ $members->count() === 2 ? 'Casal Conectado com Sucesso' : 'Aguardando Conexão do Parceiro(a)' }}
                        </h2>
                        <p class="text-xs text-zinc-300 mt-0.5">
                            {{ $members->count() === 2 ? 'Vocês dois compartilham a visualização em tempo real de todas as movimentações.' : 'Compartilhe seu código de convite para que seu parceiro(a) acesse este workspace.' }}
                        </p>
                    </div>
                </div>

                @if ($members->count() < 2)
                    <button 
                        @click="inviteModalOpen = true"
                        class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-xs shadow-md transition shrink-0"
                    >
                        Convidar Parceiro
                    </button>
                @endif
            </div>
        </div>

        <!-- Workspace Info Card -->
        <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
            <h2 class="text-base font-bold text-zinc-900 mb-1">Dados do Workspace</h2>
            <p class="text-xs text-zinc-500 mb-4">Atualize o título de exibição do ambiente financeiro</p>

            <form method="POST" action="{{ route('workspaces.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Nome do Workspace</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $workspace->name) }}" 
                        required 
                        class="w-full max-w-md text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>
                <button type="submit" class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Salvar Alterações
                </button>
            </form>
        </div>

        <!-- Members Card -->
        <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-zinc-900">Membros Ativos ({{ $members->count() }}/2)</h2>
                    <p class="text-xs text-zinc-500">Capacidade máxima restrita a 2 membros</p>
                </div>
            </div>

            <div class="divide-y divide-zinc-100">
                @foreach ($members as $member)
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-full bg-zinc-900 text-white font-bold text-xs flex items-center justify-center">
                                {{ $member->initials }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                                    <span>{{ $member->name }}</span>
                                    @if ($member->id === Auth::id())
                                        <span class="text-[10px] text-zinc-400 font-normal">(Você)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-zinc-500">{{ $member->email }}</div>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $member->pivot->role === 'owner' ? 'bg-zinc-100 text-zinc-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $member->pivot->role === 'owner' ? 'Proprietário' : 'Parceiro(a)' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Invite Code Section -->
        <div class="p-6 sm:p-7 bg-white rounded-3xl border border-zinc-200/80 shadow-xs" x-data="{ copied: false }">
            <h2 class="text-base font-bold text-zinc-900 mb-1">Código de Compartilhamento</h2>
            <p class="text-xs text-zinc-500 mb-4">Compartilhe este código de 8 dígitos para seu parceiro(a) vincular a conta dele(a) à sua.</p>

            <div class="flex items-center gap-3 max-w-md">
                <div class="flex-1 px-4 py-3 rounded-2xl bg-zinc-100/80 border border-zinc-200 font-mono text-lg font-bold tracking-widest text-center text-zinc-900 select-all">
                    {{ $workspace->invite_code }}
                </div>
                <button 
                    type="button"
                    @click="navigator.clipboard.writeText('{{ $workspace->invite_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-5 py-3 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold flex items-center gap-2 transition shrink-0 shadow-sm"
                >
                    <span x-show="!copied" class="flex items-center gap-1.5">
                        <x-lucide-copy class="w-4 h-4" />
                        Copiar
                    </span>
                    <span x-show="copied" class="flex items-center gap-1.5 text-emerald-400" style="display: none;">
                        <x-lucide-check class="w-4 h-4" />
                        Copiado!
                    </span>
                </button>
            </div>

            @if (Auth::user()->isOwnerOfCurrentWorkspace())
                <form method="POST" action="{{ route('workspaces.regenerate-code') }}" class="mt-4" onsubmit="return confirm('Deseja invalidar o código atual e gerar um novo?');">
                    @csrf
                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-semibold underline">
                        Invalidar e gerar novo código de convite
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
