<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DuoFin') }} - Autenticação</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased bg-zinc-950 text-zinc-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Ambient Background Glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[350px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center text-zinc-950 font-bold shadow-lg shadow-emerald-500/20">
                    <x-lucide-wallet class="w-6 h-6 text-zinc-950" />
                </div>
                <div class="text-left">
                    <span class="text-2xl font-extrabold tracking-tight text-white block">DuoFin</span>
                    <span class="text-[10px] font-semibold tracking-wider uppercase text-emerald-400 block">Finanças do Casal</span>
                </div>
            </a>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
            <div class="bg-zinc-900/90 backdrop-blur border border-zinc-800 py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
                {{ $slot }}
            </div>
            <div class="mt-6 text-center text-xs text-zinc-500">
                Desenvolvido por <a href="https://gabrielyandev.com.br" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 font-bold transition">gabrielyandev</a>
            </div>
        </div>
    </body>
</html>
