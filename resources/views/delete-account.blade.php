<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-950 text-zinc-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Exclusão de Conta e Dados - DuoFin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- PWA Meta Tags -->
        <x-pwa-meta />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans antialiased bg-zinc-950 text-zinc-200 selection:bg-emerald-500 selection:text-zinc-950">
        <!-- Header -->
        <header class="border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
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
                    <a href="/" class="text-sm font-semibold text-zinc-400 hover:text-white transition">
                        Voltar ao Início
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="mb-10 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-500/10 border border-red-500/20 text-xs font-semibold text-red-400 mb-4">
                    <x-lucide-user-x class="w-3.5 h-3.5" />
                    <span>Gerenciamento de Dados do Usuário</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Solicitação de Exclusão de Conta e Dados</h1>
                <p class="text-sm text-zinc-400 mt-2">Aplicativo: <strong>DuoFin</strong> | Desenvolvedor: <strong>gabrielyandev</strong></p>
            </div>

            <div class="space-y-8 text-sm leading-relaxed text-zinc-300 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 sm:p-10 backdrop-blur">
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        1. Como excluir sua conta diretamente pelo aplicativo
                    </h2>
                    <p>
                        Você pode realizar a exclusão total da sua conta e de todos os seus dados a qualquer momento de forma instantânea:
                    </p>
                    <ol class="list-decimal list-inside space-y-2 pl-2 text-zinc-300">
                        <li>Abra o aplicativo <strong>DuoFin</strong> ou acesse pelo navegador em <a href="https://duofin.gabrielyandev.com.br" class="text-emerald-400 hover:underline">https://duofin.gabrielyandev.com.br</a>.</li>
                        <li>Faça login com seu e-mail e senha cadastrados.</li>
                        <li>Acesse o menu <strong>Perfil</strong> (clicando no seu nome ou ícone de usuário no canto superior).</li>
                        <li>Role até a seção <strong>Excluir Conta</strong>.</li>
                        <li>Clique no botão <strong>Excluir Conta</strong>, digite sua senha atual para confirmação de segurança e confirme a operação.</li>
                    </ol>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        2. Dados que são excluídos permanentemente
                    </h2>
                    <p>Ao solicitar a exclusão da sua conta, os seguintes dados são apagados de forma irreversível de nossos servidores:</p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-zinc-300">
                        <li><strong>Dados cadastrais:</strong> Nome, endereço de e-mail e credenciais de login.</li>
                        <li><strong>Registros financeiros individuais:</strong> Transações, notas e lançamentos criados por você.</li>
                        <li><strong>Vínculo de Workspace:</strong> Suas permissões e acessos ao espaço compartilhado com o parceiro(a).</li>
                        <li><strong>Sessões ativas e cache:</strong> Encerramento imediato de todas as sessões conectadas.</li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        3. Período de Retenção
                    </h2>
                    <p>
                        A exclusão no DuoFin é <strong>imediata</strong>. Não mantemos cópias ou backups dos dados pessoais após a confirmação da exclusão da conta pelo usuário.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        4. Solicitação alternativa de exclusão por e-mail
                    </h2>
                    <p>
                        Caso você não consiga acessar o aplicativo para excluir a conta manualmente, você pode solicitar a exclusão enviando uma mensagem diretamente para o desenvolvedor através do site oficial:
                    </p>
                    <p class="pt-1">
                        <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-bold underline underline-offset-2">
                            <span>gabrielyandev.com.br</span>
                            <x-lucide-external-link class="w-3.5 h-3.5" />
                        </a>
                    </p>
                    <p class="text-xs text-zinc-400">
                        *Informe o endereço de e-mail da conta que deseja excluir para que possamos processar a remoção em até 48 horas úteis.
                    </p>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-zinc-900 py-8 bg-zinc-950 text-xs text-zinc-500 text-center">
            <div class="max-w-5xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
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
                    <a href="/privacy" class="text-zinc-400 hover:text-emerald-400 transition">Privacidade</a>
                    <span>&bull;</span>
                    <a href="/" class="text-zinc-400 hover:text-emerald-400 transition">Início</a>
                    <span>&bull;</span>
                    <span>Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="font-bold text-zinc-300 hover:text-emerald-400 transition underline underline-offset-2">gabrielyandev</a></span>
                </div>
            </div>
        </footer>
    </body>
</html>
