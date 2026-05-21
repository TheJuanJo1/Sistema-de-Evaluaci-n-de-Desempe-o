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
                            <span class="ml-1 md:ml-2">Editar Trabajador</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Editar Trabajador: {{ $worker->name }}</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('workers.update', $worker) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre Completo')" class="text-slate-700 font-semibold mb-2" />
                    <x-text-input id="name" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="name" :value="old('name', $worker->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- Document ID -->
                    <div>
                        <x-input-label for="document_id" :value="__('Documento de Identidad')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="document_id" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="document_id" :value="old('document_id', $worker->document_id)" required />
                        <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="email" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="email" name="email" :value="old('email', $worker->email)" />
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
                                <option value="Directivo" {{ old('type', $worker->type) == 'Directivo' ? 'selected' : '' }}>Directivo</option>
                                <option value="Docente" {{ old('type', $worker->type) == 'Docente' ? 'selected' : '' }}>Docente</option>
                                <option value="Administrativo" {{ old('type', $worker->type) == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                <option value="Servicios Generales" {{ old('type', $worker->type) == 'Servicios Generales' ? 'selected' : '' }}>Servicios Generales</option>
                                <option value="Psicorientación" {{ old('type', $worker->type) == 'Psicorientación' ? 'selected' : '' }}>Psicorientación</option>
                                <option value="Biblioteca" {{ old('type', $worker->type) == 'Biblioteca' ? 'selected' : '' }}>Biblioteca</option>
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
                    <x-text-input id="position" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="position" :value="old('position', $worker->position)" required />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>
            </div>

            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('workers.index') }}" class="text-slate-600 hover:text-slate-800 font-medium transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg shadow-blue-200 uppercase tracking-widest">
                    Actualizar Información
                </button>
            </div>
        </form>

        <!-- Document Management Section -->
        <div class="mt-12 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8">
                <livewire:worker-document-manager :worker="$worker" />
            </div>
        </div>
    </div>
</x-app-layout>
