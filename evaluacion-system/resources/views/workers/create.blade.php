<x-app-layout>
    <x-slot name="header">
        <div>
            <nav class="flex mb-4 text-slate-400 text-xs font-semibold uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('workers.index') }}" class="hover:text-blue-600 transition duration-200">Trabajadores</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2">Nuevo Trabajador</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Registrar Nuevo Trabajador</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('workers.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre Completo')" class="text-slate-700 font-semibold mb-2" />
                    <x-text-input id="name" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="name" :value="old('name')" required autofocus placeholder="Ej. Juan Pérez" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- Document ID -->
                    <div>
                        <x-input-label for="document_id" :value="__('Documento de Identidad')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="document_id" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="document_id" :value="old('document_id')" required placeholder="CC 12345678" />
                        <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="email" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="email" name="email" :value="old('email')" placeholder="ejemplo@correo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Type -->
                    <div x-data="{ customType: false }">
                        <div class="flex items-center justify-between mb-2">
                            <x-input-label for="type" :value="__('Tipo de Trabajador')" class="text-slate-700 font-semibold" />
                            <button type="button" @click="customType = !customType" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hover:text-blue-700 transition duration-200 flex items-center">
                                <span x-text="customType ? '← Seleccionar' : '+ Añadir Nuevo'"></span>
                            </button>
                        </div>

                        <div x-show="!customType">
                            <select id="type_select" name="type" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm bg-slate-50 text-slate-600 px-4 py-2" x-bind:disabled="customType">
                                <option value="">Selecciona un tipo...</option>
                                <option value="Directivo" {{ old('type') == 'Directivo' ? 'selected' : '' }}>Directivo</option>
                                <option value="Docente" {{ old('type') == 'Docente' ? 'selected' : '' }}>Docente</option>
                                <option value="Administrativo" {{ old('type') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                <option value="Servicios Generales" {{ old('type') == 'Servicios Generales' ? 'selected' : '' }}>Servicios Generales</option>
                                <option value="Psicorientación" {{ old('type') == 'Psicorientación' ? 'selected' : '' }}>Psicorientación</option>
                                <option value="Biblioteca" {{ old('type') == 'Biblioteca' ? 'selected' : '' }}>Biblioteca</option>
                            </select>
                        </div>

                        <div x-show="customType" x-cloak>
                            <x-text-input id="type_custom" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl bg-blue-50/50" type="text" name="type" placeholder="Escribe el nuevo tipo aquí..." x-bind:disabled="!customType" />
                        </div>
                        
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>

                <!-- Position -->
                <div class="mt-4">
                    <x-input-label for="position" :value="__('Cargo / Función')" class="text-slate-700 font-semibold mb-2" />
                    <x-text-input id="position" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="position" :value="old('position')" required placeholder="Ej. Docente de Matemáticas, Secretaria, etc." />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>
            </div>

            <!-- Create User Option -->
            <div class="px-8 pb-6 border-t border-slate-100 pt-6" x-data="{ createUser: {{ old('create_user') ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Acceso al Sistema</h3>
                        <p class="text-sm text-slate-500">¿Deseas crear una cuenta de usuario para este trabajador?</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="create_user" value="1" x-model="createUser" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div x-show="createUser" x-transition.opacity class="mt-4">
                    <div class="p-5 bg-blue-50/50 rounded-xl border border-blue-100">
                        <x-input-label for="role" :value="__('Rol del Usuario')" class="text-slate-700 font-semibold mb-2" />
                        <select id="role" name="role" class="block w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm bg-white text-slate-600 px-4 py-2" :required="createUser">
                            <option value="">Selecciona el rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        <p class="text-xs text-blue-600 mt-3 font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            La contraseña inicial será el documento de identidad.
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('workers.index') }}" class="text-slate-600 hover:text-slate-800 font-medium transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg shadow-blue-200 uppercase tracking-widest">
                    Registrar Trabajador
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
