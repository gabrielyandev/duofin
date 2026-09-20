<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50 overflow-x-hidden max-w-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DuoFin') }} - Finanças Compartilhadas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased text-zinc-800 bg-zinc-50 overflow-x-hidden max-w-full" x-data="{ mobileSidebarOpen: false, inviteModalOpen: false, createTxModalOpen: false }">
        <div class="min-h-full flex flex-col lg:flex-row w-full max-w-full overflow-x-hidden">
            <!-- Mobile Sidebar Backdrop -->
            <div 
                x-show="mobileSidebarOpen" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-zinc-900/60 backdrop-blur-sm lg:hidden"
                @click="mobileSidebarOpen = false"
                style="display: none;"
            ></div>

            <!-- Sidebar Navigation -->
            <aside 
                :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="fixed inset-y-0 left-0 z-50 w-72 bg-zinc-900 text-zinc-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:h-screen lg:shrink-0"
            >
                <!-- Brand Header -->
                <div class="h-18 px-6 flex items-center justify-between border-b border-zinc-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-zinc-950 font-bold shadow-md shadow-emerald-500/20">
                            <x-lucide-wallet class="w-5 h-5 text-zinc-950" />
                        </div>
                        <div>
                            <span class="text-lg font-bold tracking-tight text-white">DuoFin</span>
                            <span class="block text-[11px] font-medium tracking-wide uppercase text-zinc-400">Finanças do Casal</span>
                        </div>
                    </a>
                    <button @click="mobileSidebarOpen = false" class="lg:hidden p-1 text-zinc-400 hover:text-white">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <!-- Active Workspace Widget -->
                @php
                    $currentWorkspace = Auth::user()->currentWorkspace;
                    $workspaceMembers = $currentWorkspace ? $currentWorkspace->users()->get() : collect();
                    $isOwner = Auth::user()->isOwnerOfCurrentWorkspace();
                @endphp
                <div class="p-4 mx-3 my-3 rounded-xl bg-zinc-800/80 border border-zinc-700/60">
                    <div class="flex items-center justify-between text-xs text-zinc-400 mb-1">
                        <span class="font-medium uppercase tracking-wider text-[10px]">Workspace Ativo</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $isOwner ? 'bg-emerald-500/20 text-emerald-300' : 'bg-blue-500/20 text-blue-300' }}">
                            {{ $isOwner ? 'Proprietário' : 'Parceiro' }}
                        </span>
                    </div>
                    <div class="font-semibold text-sm text-white truncate">
                        {{ $currentWorkspace->name ?? 'Sem Workspace' }}
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-zinc-700/50 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-mono">
                            Código: <strong class="text-zinc-200 tracking-wider">{{ $currentWorkspace->invite_code ?? '---' }}</strong>
                        </span>
                        <button 
                            @click="inviteModalOpen = true"
                            class="text-xs text-emerald-400 hover:text-emerald-300 font-medium inline-flex items-center gap-1 transition"
                            title="Gerenciar Parceiro"
                        >
                            <x-lucide-users class="w-3.5 h-3.5" />
                            <span>Parceiro</span>
                        </button>
                    </div>
                </div>

                <!-- Main Nav Links -->
                <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}"
                    >
                        <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
                        <span>Dashboard</span>
                    </a>

                    <a 
                        href="{{ route('transactions.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('transactions.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}"
                    >
                        <x-lucide-arrow-left-right class="w-5 h-5 shrink-0" />
                        <span>Despesas & Receitas</span>
                    </a>

                    <a 
                        href="{{ route('categories.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}"
                    >
                        <x-lucide-tag class="w-5 h-5 shrink-0" />
                        <span>Categorias</span>
                    </a>

                    <a 
                        href="{{ route('accounts.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}"
                    >
                        <x-lucide-credit-card class="w-5 h-5 shrink-0" />
                        <span>Contas Bancárias</span>
                    </a>

                    <a 
                        href="{{ route('workspaces.settings') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('workspaces.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}"
                    >
                        <x-lucide-settings class="w-5 h-5 shrink-0" />
                        <span>Workspace & Casal</span>
                    </a>
                </nav>

                <!-- User Footer -->
                <div class="p-3 border-t border-zinc-800">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-zinc-800/40">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-zinc-700 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                {{ Auth::user()->initials }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-zinc-400 truncate">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1.5 text-zinc-400 hover:text-rose-400 rounded-md transition" title="Sair do sistema">
                                <x-lucide-log-out class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                    <div class="mt-2.5 px-2 text-center text-[10px] text-zinc-500">
                        Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 font-bold transition">gabrielyandev</a>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto overflow-x-hidden w-full max-w-full">
                <!-- Top Navbar -->
                <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-zinc-200 w-full max-w-full">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between gap-4">
                        <!-- Mobile Menu Button -->
                        <div class="flex items-center gap-3 lg:hidden">
                            <button @click="mobileSidebarOpen = true" class="p-2 text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100">
                                <x-lucide-menu class="w-6 h-6" />
                            </button>
                            <span class="font-bold text-zinc-900 text-base">DuoFin</span>
                        </div>

                        <!-- Active Couple Members Badge -->
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="flex items-center -space-x-2">
                                @forelse ($workspaceMembers as $member)
                                    <div 
                                        class="relative w-9 h-9 rounded-full ring-2 ring-white font-bold text-xs flex items-center justify-center shadow-sm {{ $loop->first ? 'bg-zinc-900 text-white z-20' : 'bg-emerald-600 text-white z-10' }}"
                                        title="{{ $member->name }} ({{ $member->pivot->role === 'owner' ? 'Proprietário' : 'Parceiro' }})"
                                    >
                                        {{ $member->initials }}
                                    </div>
                                @empty
                                    <div class="w-9 h-9 rounded-full bg-zinc-300 text-zinc-600 flex items-center justify-center text-xs font-bold ring-2 ring-white">
                                        {{ Auth::user()->initials }}
                                    </div>
                                @endforelse

                                @if ($workspaceMembers->count() < 2)
                                    <button 
                                        @click="inviteModalOpen = true"
                                        class="w-9 h-9 rounded-full border-2 border-dashed border-zinc-300 bg-zinc-50 hover:bg-zinc-100 text-zinc-500 hover:text-emerald-600 flex items-center justify-center text-xs transition ring-2 ring-white z-0"
                                        title="Conectar Parceiro"
                                    >
                                        <x-lucide-user-plus class="w-4 h-4" />
                                    </button>
                                @endif
                            </div>

                            <div class="text-xs">
                                <span class="font-medium text-zinc-900 block">
                                    {{ $workspaceMembers->count() === 2 ? 'Casal Conectado' : 'Aguardando Parceiro' }}
                                </span>
                                <span class="text-zinc-500 text-[11px]">
                                    {{ $workspaceMembers->count() === 2 ? $workspaceMembers->pluck('name')->implode(' & ') : 'Vincule seu parceiro(a)' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                            <!-- Quick Partner Invite Button -->
                            <button 
                                @click="inviteModalOpen = true"
                                class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg border border-zinc-200 text-xs font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition shrink-0"
                            >
                                <x-lucide-share-2 class="w-3.5 h-3.5 text-zinc-500" />
                                <span class="hidden md:inline">Conectar Parceiro</span>
                            </button>

                            <!-- Nova Despesa/Receita Button -->
                            <button 
                                @click="createTxModalOpen = true"
                                class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold shadow-sm transition shrink-0"
                            >
                                <x-lucide-plus class="w-4 h-4" />
                                <span>Nova Transação</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Flash Notifications -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
                    @if (session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <x-lucide-check class="w-4 h-4" />
                                </div>
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (isset($errors) && $errors->any())
                        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 shadow-sm">
                            <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                                <x-lucide-alert-circle class="w-4 h-4 text-rose-600 shrink-0" />
                                <span>Por favor, verifique os erros abaixo:</span>
                            </div>
                            <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full min-w-0 max-w-full overflow-x-hidden">
                    {{ $slot }}
                </main>

                <!-- App Footer with Credits -->
                <footer class="mt-auto py-5 border-t border-zinc-200/80 bg-white/70 text-xs text-zinc-500 w-full max-w-full overflow-x-hidden">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-center sm:text-left">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-zinc-800">DuoFin</span>
                            <span>&bull;</span>
                            <span class="text-zinc-500">Finanças Compartilhadas para Casais</span>
                        </div>
                        <div>
                            Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="font-bold text-zinc-800 hover:text-emerald-600 transition underline underline-offset-2">gabrielyandev</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Global Invite Partner Modal -->
        <div 
            x-show="inviteModalOpen" 
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-cloak
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div 
                    x-show="inviteModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-zinc-900/60 backdrop-blur-sm"
                    @click="inviteModalOpen = false"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div 
                    x-show="inviteModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-zinc-100 sm:my-8"
                >
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <x-lucide-heart-handshake class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900">Conectar com seu Parceiro(a)</h3>
                                <p class="text-xs text-zinc-500">Gerencie a colaboração a dois no workspace</p>
                            </div>
                        </div>
                        <button @click="inviteModalOpen = false" class="text-zinc-400 hover:text-zinc-600 transition">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Member Status -->
                    <div class="mt-4 p-4 rounded-xl bg-zinc-50 border border-zinc-200/80">
                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Membros do Workspace (Máx 2)</div>
                        <div class="space-y-2">
                            @forelse ($workspaceMembers as $member)
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-zinc-900 text-white text-xs font-bold flex items-center justify-center">
                                            {{ $member->initials }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-zinc-800">{{ $member->name }}</div>
                                            <div class="text-[11px] text-zinc-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded {{ $member->pivot->role === 'owner' ? 'bg-zinc-200 text-zinc-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $member->pivot->role === 'owner' ? 'Proprietário' : 'Parceiro(a)' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-xs text-zinc-500">Nenhum membro listado.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Invite Code Section -->
                    <div class="mt-5" x-data="{ copied: false }">
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-2">
                            Seu Código de Convite
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 px-4 py-2.5 rounded-xl bg-zinc-100 border border-zinc-200 font-mono text-base font-bold tracking-widest text-center text-zinc-900 select-all">
                                {{ $currentWorkspace->invite_code ?? '--------' }}
                            </div>
                            <button 
                                type="button"
                                @click="navigator.clipboard.writeText('{{ $currentWorkspace->invite_code ?? '' }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-4 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold flex items-center gap-1.5 transition shrink-0"
                            >
                                <span x-show="!copied" class="flex items-center gap-1">
                                    <x-lucide-copy class="w-3.5 h-3.5" />
                                    Copiar
                                </span>
                                <span x-show="copied" class="flex items-center gap-1 text-emerald-400" style="display: none;">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                    Copiado!
                                </span>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500">
                            Envie este código para seu parceiro(a). Ao inseri-lo, vocês compartilharão as contas, projeções e transações.
                        </p>

                        @if ($isOwner)
                            <form method="POST" action="{{ route('workspaces.regenerate-code') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="text-xs text-zinc-500 hover:text-zinc-700 underline font-medium">
                                    Gerar novo código de convite
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-zinc-200"></div></div>
                        <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-zinc-400 font-semibold">Ou</span></div>
                    </div>

                    <!-- Join Another Workspace Section -->
                    <form method="POST" action="{{ route('workspaces.join') }}">
                        @csrf
                        <label for="invite_code" class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-2">
                            Entrar no Workspace de Alguém
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="text" 
                                name="invite_code" 
                                id="invite_code"
                                placeholder="Digite o código (ex: DUOFIN01)" 
                                maxlength="8"
                                class="flex-1 uppercase font-mono tracking-widest text-sm rounded-xl border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500" 
                                required
                            />
                            <button 
                                type="submit"
                                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold flex items-center gap-1.5 transition shrink-0 shadow-sm"
                            >
                                <x-lucide-arrow-right class="w-3.5 h-3.5" />
                                Entrar
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500">
                            Atenção: cada workspace aceita estritamente no máximo 2 membros vinculados.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Global Create Transaction Modal Component -->
        @include('transactions.create-modal')
    </body>
</html>
