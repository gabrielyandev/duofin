<!-- Network Status Alert -->
<div 
    x-data="networkStatus" 
    class="relative z-50 pointer-events-none"
>
    <!-- Offline Alert -->
    <div 
        x-show="!isOnline" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-full opacity-0"
        class="fixed top-0 inset-x-0 bg-rose-600/95 backdrop-blur text-white text-xs font-semibold py-2 px-4 text-center shadow-lg pointer-events-auto flex items-center justify-center gap-2"
        style="display: none;"
    >
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <line x1="2" y1="2" x2="22" y2="22"></line>
            <path d="M8.5 16.5a5 5 0 0 1 7 0"></path>
            <path d="M2 8.82a15 15 0 0 1 4.17-2.65"></path>
            <path d="M10.66 5c4.01-.36 8.14.9 11.34 3.82"></path>
            <path d="M5 12.86a10 10 0 0 1 2.87-1.87"></path>
            <path d="M14.7 10.7a10 10 0 0 1 4.3 2.16"></path>
            <line x1="12" y1="20" x2="12.01" y2="20"></line>
        </svg>
        <span>Você está trabalhando offline. Alterações serão sincronizadas quando a rede voltar.</span>
    </div>

    <!-- Back Online Toast -->
    <div 
        x-show="showOnlineAlert" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-full opacity-0"
        class="fixed top-0 inset-x-0 bg-emerald-600/95 backdrop-blur text-white text-xs font-semibold py-2 px-4 text-center shadow-lg pointer-events-auto flex items-center justify-center gap-2"
        style="display: none;"
    >
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span>Conexão restabelecida com sucesso!</span>
    </div>
</div>

<!-- PWA Install Prompt Banner -->
<div 
    x-data="pwaInstallPrompt" 
    x-show="canInstall" 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-md z-50 bg-zinc-900/95 text-zinc-100 border border-zinc-800 rounded-2xl shadow-2xl p-4 backdrop-blur-md"
    style="display: none;"
>
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-zinc-950 font-bold shrink-0 shadow-md shadow-emerald-500/20">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-white">Instalar o DuoFin</p>
            <p class="text-xs text-zinc-400 truncate">Acesse suas finanças direto da sua tela inicial.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button 
                @click="dismiss" 
                class="px-2.5 py-1.5 text-xs font-semibold text-zinc-400 hover:text-zinc-200 transition"
            >
                Depois
            </button>
            <button 
                @click="install" 
                class="px-3.5 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-zinc-950 text-xs font-bold shadow-md shadow-emerald-500/20 transition flex items-center gap-1"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M19 12l-7 7-7-7"/>
                </svg>
                <span>Instalar</span>
            </button>
        </div>
    </div>
</div>
