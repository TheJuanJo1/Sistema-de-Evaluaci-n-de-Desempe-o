<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-white tracking-tight">Recuperar Contraseña</h1>
        <p class="text-slate-400 text-sm mt-2">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Dinós tu correo y te enviaremos un enlace para restablecerla.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico</label>
            <input id="email" class="block w-full px-4 py-3 rounded-xl input-modern text-sm" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col space-y-4">
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-300 shadow-lg shadow-blue-900/40">
                {{ __('Enviar enlace al correo') }}
            </button>
            
            <a href="{{ route('login') }}" class="text-center text-sm text-slate-500 hover:text-white transition duration-200">
                &larr; Volver al Login
            </a>
        </div>
    </form>
</x-guest-layout>
