<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="text-2xl font-black text-slate-800 leading-tight">
                    Editar Usuario
                </h2>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">
                    {{ $user->name }}
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 md:p-10">
                <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Nombre Completo" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                        <x-text-input id="name" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>


                    <!-- Email -->
                    <div>
                        <x-input-label for="email" value="Correo Electrónico" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                        <x-text-input id="email" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Documento de Identidad -->
                    <div>
                        <x-input-label for="document_id" value="Documento de Identidad" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                        <x-text-input id="document_id" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200" type="text" name="document_id" :value="old('document_id', $user->document_id ?? '')" />
                        <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-input-label for="role" value="Rol de Usuario" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                        <select id="role" name="role" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200 text-sm py-2.5">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- Document Management -->
                    <div class="mt-6">
                        <livewire:worker-document-manager :worker="$user" />
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-100">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Cambiar Contraseña</h3>
                        <p class="text-xs text-slate-400 mb-6 font-bold uppercase tracking-tight">Deja en blanco si no deseas cambiarla.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <x-input-label for="password" value="Nueva Contraseña" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                                <x-text-input id="password" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200" type="password" name="password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-input-label for="password_confirmation" value="Confirmar Contraseña" class="font-bold text-slate-700 uppercase text-[10px] tracking-widest mb-2" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 transition duration-200" type="password" name="password_confirmation" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 pt-6 border-t border-slate-100">
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700 py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transition duration-200">
                            {{ __('Actualizar Usuario') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
