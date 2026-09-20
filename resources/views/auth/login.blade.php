<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white tracking-tight">Bem-vindo(a) de volta</h2>
        <p class="text-xs text-zinc-400 mt-1">Acesse o workspace financeiro do seu casal</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-1.5">
                E-mail
            </label>
            <div class="relative">
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="seu.email@exemplo.com"
                    class="w-full text-sm rounded-xl bg-zinc-950/80 border-zinc-800 text-white placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500 pl-10"
                />
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <x-lucide-mail class="w-4 h-4" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider">
                    Senha
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-emerald-400 hover:text-emerald-300 font-medium" href="{{ route('password.request') }}">
                        Esqueceu?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••"
                    class="w-full text-sm rounded-xl bg-zinc-950/80 border-zinc-800 text-white placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500 pl-10"
                />
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <x-lucide-lock class="w-4 h-4" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-zinc-800 bg-zinc-950 text-emerald-500 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-xs text-zinc-400">Lembrar de mim</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-sm shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2"
            >
                <span>Acessar Workspace</span>
                <x-lucide-arrow-right class="w-4 h-4" />
            </button>
        </div>

        <!-- Register Link -->
        <div class="pt-4 text-center border-t border-zinc-800">
            <p class="text-xs text-zinc-400">
                Ainda não tem conta conjunta? 
                <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-bold ml-1">
                    Cadastre-se
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
