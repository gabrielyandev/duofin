<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-950 text-zinc-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Política de Privacidade - DuoFin</title>

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
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-semibold text-emerald-400 mb-4">
                    <x-lucide-shield-check class="w-3.5 h-3.5" />
                    <span>Transparência e Segurança</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Política de Privacidade</h1>
                <p class="text-sm text-zinc-400 mt-2">Última atualização: 5 de outubro de 2026</p>
            </div>

            <div class="space-y-8 text-sm leading-relaxed text-zinc-300 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 sm:p-10 backdrop-blur">
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        1. Visão Geral
                    </h2>
                    <p>
                        O aplicativo <strong>DuoFin</strong> (disponível via web e aplicativo Android em <a href="https://duofin.gabrielyandev.com.br" class="text-emerald-400 hover:underline">https://duofin.gabrielyandev.com.br</a>), desenvolvido por <strong>gabrielyandev</strong>, tem como objetivo auxiliar casais e parceiros a organizarem suas finanças compartilhadas de forma colaborativa, transparente e segura.
                    </p>
                    <p>
                        Esta Política de Privacidade descreve como coletamos, usamos, armazenamos e protegemos as suas informações, em total conformidade com a <strong>Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018)</strong> e com as diretrizes do <strong>Google Play</strong>.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        2. Dados que Coletamos
                    </h2>
                    <p>Para o funcionamento da plataforma, coletamos as seguintes categorias de dados:</p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-zinc-300">
                        <li><strong>Dados de Cadastro e Autenticação:</strong> Nome, endereço de e-mail e senha de acesso (armazenada exclusivamente com hash criptográfico irreversível).</li>
                        <li><strong>Dados Financeiros Inseridos Manualmente pelo Usuário:</strong> Descrições de transações, valores, datas, categorias de despesas/receitas, apelidos ou nomes de contas bancárias e cartões de crédito.</li>
                        <li><strong>Dados do Espaço Compartilhado (Workspace):</strong> Vínculo de convite entre membros do casal para sincronização dos registros financeiros dentro do mesmo workspace.</li>
                    </ul>
                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 text-xs text-zinc-400">
                        <strong>Nota importante de segurança:</strong> O DuoFin <strong>NÃO</strong> solicita, não coleta e não tem acesso a senhas de bancos, dados sensíveis de cartões (CVV/número completo), tokens bancários ou chaves de transferência bancária.
                    </div>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        3. Finalidade do Tratamento dos Dados
                    </h2>
                    <p>Utilizamos os dados coletados estritamente para:</p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-zinc-300">
                        <li>Criar e gerenciar sua conta de usuário;</li>
                        <li>Permitir o lançamento, visualização e cálculo automático dos saldos, receitas e despesas compartilhadas entre você e seu(sua) parceiro(a);</li>
                        <li>Oferecer funcionamento rápido e suporte offline através de tecnologia PWA (Progressive Web App);</li>
                        <li>Garantir a segurança da plataforma e prevenir acessos não autorizados.</li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        4. Compartilhamento de Dados
                    </h2>
                    <p>
                        <strong>Compartilhamento com seu(sua) parceiro(a):</strong> Ao ingressar ou convidar seu parceiro para o mesmo Workspace financeiro, todas as transações, contas e categorias criadas dentro desse espaço passam a ser visíveis entre os membros desse Workspace.
                    </p>
                    <p>
                        <strong>Terceiros e Anunciantes:</strong> O DuoFin <strong>NÃO</strong> comercializa, não aluga e não compartilha seus dados pessoais ou financeiros com terceiros, agências de publicidade ou anunciantes para fins de monetização ou marketing.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        5. Armazenamento, Segurança e Retenção
                    </h2>
                    <p>
                        Adotamos medidas técnicas de segurança rigorosas para proteger seus dados, incluindo tráfego integralmente criptografado sob protocolo HTTPS/TLS, bancos de dados protegidos e senhas criptografadas com algoritmos modernos de hash.
                    </p>
                    <p>
                        Os dados permanecem armazenados enquanto a sua conta estiver ativa ou até que você solicite a exclusão da sua conta.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        6. Direitos do Usuário e Exclusão de Conta (LGPD)
                    </h2>
                    <p>Você tem total controle sobre seus dados pessoais. A qualquer momento, você pode:</p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-zinc-300">
                        <li>Acessar, corrigir e atualizar seus dados pessoais na seção de Perfil do aplicativo;</li>
                        <li><strong>Excluir sua conta e dados:</strong> Você pode excluir sua conta diretamente dentro do aplicativo em <em>Perfil &gt; Excluir Conta</em>. Ao confirmar, todos os seus dados pessoais, transações e acessos serão excluídos permanentemente de nossos servidores;</li>
                        <li>Revogar consentimento ou solicitar esclarecimentos enviando mensagem ao desenvolvedor.</li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        7. Armazenamento Local e Cache (PWA)
                    </h2>
                    <p>
                        O aplicativo utiliza armazenamento local (LocalStorage e Service Worker Cache) no dispositivo do usuário com a única finalidade de melhorar o desempenho, manter a sessão ativa de forma segura e permitir que a aplicação carregue rapidamente mesmo com oscilações de sinal de rede.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        8. Contato do Desenvolvedor
                    </h2>
                    <p>
                        Para quaisquer dúvidas, solicitações ou esclarecimentos sobre esta Política de Privacidade ou sobre o tratamento de seus dados pessoais, entre em contato através do site oficial do desenvolvedor:
                    </p>
                    <p class="pt-1">
                        <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-bold underline underline-offset-2">
                            <span>gabrielyandev.com.br</span>
                            <x-lucide-external-link class="w-3.5 h-3.5" />
                        </a>
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
                    <a href="/" class="text-zinc-400 hover:text-emerald-400 transition">Início</a>
                    <span>&bull;</span>
                    <span>Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="font-bold text-zinc-300 hover:text-emerald-400 transition underline underline-offset-2">gabrielyandev</a></span>
                </div>
            </div>
        </footer>
    </body>
</html>
