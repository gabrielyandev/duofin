<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white tracking-tight">Criar Conta Compartilhada</h2>
        <p class="text-xs text-zinc-400 mt-1">Seu workspace financeiro será criado automaticamente</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-1.5">
                Seu Nome Completo
            </label>
            <div class="relative">
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="Ex: Gabriel Silva"
                    class="w-full text-sm rounded-xl bg-zinc-950/80 border-zinc-800 text-white placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500 pl-10"
                />
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <x-lucide-user class="w-4 h-4" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-400" />
        </div>

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
            <label for="password" class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-1.5">
                Senha
            </label>
            <div class="relative">
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Mínimo de 8 caracteres"
                    class="w-full text-sm rounded-xl bg-zinc-950/80 border-zinc-800 text-white placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500 pl-10"
                />
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <x-lucide-lock class="w-4 h-4" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-1.5">
                Confirmar Senha
            </label>
            <div class="relative">
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Repita sua senha"
                    class="w-full text-sm rounded-xl bg-zinc-950/80 border-zinc-800 text-white placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500 pl-10"
                />
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <x-lucide-shield-check class="w-4 h-4" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-sm shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2"
            >
                <span>Criar Conta & Começar</span>
                <x-lucide-arrow-right class="w-4 h-4" />
            </button>
        </div>

        <!-- Login Link -->
        <div class="pt-4 text-center border-t border-zinc-800">
            <p class="text-xs text-zinc-400">
                Já possui uma conta? 
                <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-bold ml-1">
                    Fazer Login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
