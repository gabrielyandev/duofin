<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-950 text-zinc-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DuoFin - Finanças Compartilhadas para Casais Modernos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans antialiased bg-zinc-950 text-zinc-200 selection:bg-emerald-500 selection:text-zinc-950">
        <!-- Navigation -->
        <header class="border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-zinc-950 font-bold shadow-lg shadow-emerald-500/20">
                        <x-lucide-wallet class="w-5 h-5 text-zinc-950" />
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white">DuoFin</span>
                        <span class="block text-[10px] font-semibold tracking-wider uppercase text-emerald-400">Finanças do Casal</span>
                    </div>
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-zinc-300 hover:text-white transition">
                        Entrar
                    </a>
                    <a 
                        href="{{ route('register') }}" 
                        class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 text-sm font-bold shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5"
                    >
                        <span>Começar Grátis</span>
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-20 pb-24 overflow-hidden">
            <!-- Ambient Glow -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-emerald-500/10 blur-[130px] rounded-full pointer-events-none"></div>
            <div class="absolute top-1/3 left-1/3 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[300px] bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900 border border-zinc-800 text-xs font-semibold text-zinc-300 shadow-sm mb-6">
                    <x-lucide-sparkles class="w-3.5 h-3.5 text-emerald-400" />
                    <span>Feito exclusivamente para a vida financeira a dois</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-[1.1]">
                    Finanças do casal sem atritos, planilhas ou segredos.
                </h1>

                <!-- Subtitle -->
                <p class="mt-6 text-base sm:text-lg text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                    Gerencie despesas conjuntas, divida compras parceladas com precisão exata de centavos e acompanhe a previsão dos próximos 6 meses com clareza.
                </p>

                <!-- Actions -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a 
                        href="{{ route('register') }}" 
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-sm shadow-xl shadow-emerald-500/20 transition flex items-center justify-center gap-2"
                    >
                        <span>Criar Workspace do Casal</span>
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                    <a 
                        href="{{ route('login') }}" 
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-white font-semibold text-sm transition flex items-center justify-center gap-2"
                    >
                        <x-lucide-log-in class="w-4 h-4 text-zinc-400" />
                        <span>Acessar Minha Conta</span>
                    </a>
                </div>

                <!-- Live Interface Preview Mockup -->
                <div class="mt-16 max-w-5xl mx-auto rounded-2xl bg-zinc-900/90 border border-zinc-800 p-4 sm:p-6 shadow-2xl shadow-zinc-950/80 text-left">
                    <!-- Window Controls -->
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-800/80 mb-6">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-zinc-700"></span>
                            <span class="w-3 h-3 rounded-full bg-zinc-700"></span>
                            <span class="w-3 h-3 rounded-full bg-zinc-700"></span>
                            <span class="ml-2 text-xs font-mono text-zinc-500">duofin.app/dashboard</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-zinc-400 font-medium">Sincronizado em tempo real</span>
                        </div>
                    </div>

                    <!-- Mockup Metric Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800">
                            <div class="text-[11px] text-zinc-500 uppercase tracking-wider font-semibold">Saldo em Contas</div>
                            <div class="text-xl font-bold text-white mt-1">R$ 7.700,00</div>
                            <div class="text-[10px] text-emerald-400 mt-1 flex items-center gap-1">
                                <x-lucide-check class="w-3 h-3" />
                                <span>2 contas bancárias</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800">
                            <div class="text-[11px] text-zinc-500 uppercase tracking-wider font-semibold">Receitas do Mês</div>
                            <div class="text-xl font-bold text-emerald-400 mt-1">R$ 14.300,00</div>
                            <div class="text-[10px] text-zinc-400 mt-1">Salários combinados</div>
                        </div>

                        <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800">
                            <div class="text-[11px] text-zinc-500 uppercase tracking-wider font-semibold">Despesas do Mês</div>
                            <div class="text-xl font-bold text-rose-400 mt-1">R$ 4.885,30</div>
                            <div class="text-[10px] text-zinc-400 mt-1">Pagas + Pendentes</div>
                        </div>

                        <div class="p-4 rounded-xl bg-zinc-950 border border-zinc-800">
                            <div class="text-[11px] text-zinc-500 uppercase tracking-wider font-semibold">Balanço Líquido</div>
                            <div class="text-xl font-bold text-indigo-400 mt-1">+ R$ 9.414,70</div>
                            <div class="text-[10px] text-zinc-400 mt-1">Superávit projetado</div>
                        </div>
                    </div>

                    <!-- 6-Month Timeline Preview -->
                    <div class="mt-6 p-4 rounded-xl bg-zinc-950/80 border border-zinc-800">
                        <div class="flex items-center justify-between mb-3 text-xs">
                            <div class="flex items-center gap-2">
                                <x-lucide-trending-up class="w-4 h-4 text-emerald-400" />
                                <span class="font-bold text-white">Previsão dos Próximos 6 Meses</span>
                            </div>
                            <span class="text-zinc-500">Parcelas contratadas no radar</span>
                        </div>
                        <div class="grid grid-cols-6 gap-2 text-center">
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-700/60">
                                <div class="text-[10px] font-bold text-zinc-400">SET</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 4.885</div>
                                <div class="text-[9px] text-amber-400">R$ 950 parc.</div>
                            </div>
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-800">
                                <div class="text-[10px] font-bold text-zinc-400">OUT</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 950</div>
                                <div class="text-[9px] text-amber-400">R$ 950 parc.</div>
                            </div>
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-800">
                                <div class="text-[10px] font-bold text-zinc-400">NOV</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 950</div>
                                <div class="text-[9px] text-amber-400">R$ 950 parc.</div>
                            </div>
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-800">
                                <div class="text-[10px] font-bold text-zinc-400">DEZ</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 950</div>
                                <div class="text-[9px] text-amber-400">R$ 950 parc.</div>
                            </div>
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-800">
                                <div class="text-[10px] font-bold text-zinc-400">JAN</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 950</div>
                                <div class="text-[9px] text-amber-400">R$ 950 parc.</div>
                            </div>
                            <div class="p-2 rounded-lg bg-zinc-900 border border-zinc-800">
                                <div class="text-[10px] font-bold text-zinc-400">FEV</div>
                                <div class="text-xs font-bold text-white mt-1">R$ 350</div>
                                <div class="text-[9px] text-amber-400">R$ 350 parc.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Pillars -->
        <section class="py-20 border-t border-zinc-900 bg-zinc-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Projetado para a realidade a dois</h2>
                    <p class="text-sm text-zinc-400 mt-3">Construído do zero para evitar cobranças chatas e surpresas no fim do mês.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-4">
                            <x-lucide-calendar-days class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-white">Parcelamento Inteligente</h3>
                        <p class="text-xs text-zinc-400 mt-2 leading-relaxed">
                            Ao comprar um item parcelado em 12x, o DuoFin calcula a divisão exata de centavos e distribui as parcelas pelos meses futuros automaticamente.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4">
                            <x-lucide-users class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-white">Limite Estrito de 2 Membros</h3>
                        <p class="text-xs text-zinc-400 mt-2 leading-relaxed">
                            Cada workspace é um espaço privado e blindado exclusivamente para você e seu parceiro(a). Nenhum terceiro pode se infiltrar.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-4">
                            <x-lucide-scale class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-white">Visão Equilibrada</h3>
                        <p class="text-xs text-zinc-400 mt-2 leading-relaxed">
                            Saiba em tempo real a proporção de despesas assumidas por cada um sem constrangimentos, com métricas transparentes.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 border-t border-zinc-900">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Pronto para colocar as contas em harmonia?</h2>
                <p class="text-sm text-zinc-400 mt-3 max-w-lg mx-auto">Cadastre-se em segundos, gere seu código de convite e conecte-se com seu parceiro(a) hoje mesmo.</p>
                <div class="mt-8">
                    <a 
                        href="{{ route('register') }}" 
                        class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-sm shadow-xl shadow-emerald-500/25 transition"
                    >
                        <span>Criar Conta Gratuita</span>
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-zinc-900 py-8 bg-zinc-950 text-xs text-zinc-500 text-center">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-emerald-500 flex items-center justify-center text-zinc-950 font-bold">
                        <x-lucide-wallet class="w-3.5 h-3.5" />
                    </div>
                    <span class="font-bold text-zinc-300">DuoFin</span>
                    <span>&bull; Gestão Financeira para Casais</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>&copy; {{ date('Y') }} DuoFin</span>
                    <span>&bull;</span>
                    <span>Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="font-bold text-zinc-300 hover:text-emerald-400 transition underline underline-offset-2">gabrielyandev</a></span>
                </div>
            </div>
        </footer>
    </body>
</html>
