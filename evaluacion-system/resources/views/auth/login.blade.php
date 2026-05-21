<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-white tracking-tight">Bienvenido</h1>
        <p class="text-white text-sm mt-2">Ingresa tus credenciales para acceder al sistema.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-white uppercase tracking-widest mb-2">Correo Electrónico</label>
            <input id="email" class="block w-full px-4 py-3 rounded-xl input-modern text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-bold text-white uppercase tracking-widest">Contraseña</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-white hover:text-blue-300 transition duration-200" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <input id="password" class="block w-full px-4 py-3 rounded-xl input-modern text-sm" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-800 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-slate-400">Recordar sesión</label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-300 shadow-lg shadow-blue-900/40 transform hover:-translate-y-0.5 active:translate-y-0" style="border-radius: 10px;">
                Iniciar Sesión
            </button>
        </div>
    </form>
</x-guest-layout>
