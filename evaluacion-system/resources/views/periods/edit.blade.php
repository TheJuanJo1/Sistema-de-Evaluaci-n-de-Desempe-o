<x-app-layout>
    <x-slot name="header">
        <div>
            <nav class="flex mb-4 text-slate-400 text-xs font-semibold uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('periods.index') }}" class="hover:text-blue-600 transition duration-200">Periodos</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2">Editar Periodo</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Editar Periodo: {{ $period->name }}</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('periods.update', $period) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre del Periodo')" class="text-slate-700 font-semibold mb-2" />
                    <x-text-input id="name" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" name="name" :value="old('name', $period->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- Start Date -->
                    <div>
                        <x-input-label for="start_date" :value="__('Fecha de Inicio')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="start_date" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="date" name="start_date" :value="old('start_date', $period->start_date->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <!-- End Date -->
                    <div>
                        <x-input-label for="end_date" :value="__('Fecha de Finalización')" class="text-slate-700 font-semibold mb-2" />
                        <x-text-input id="end_date" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="date" name="end_date" :value="old('end_date', $period->end_date->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <!-- Is Active -->
                <div class="block mt-4">
                    <label for="is_active" class="inline-flex items-center cursor-pointer">
                        <div class="relative">
                            <input id="is_active" name="is_active" type="checkbox" class="sr-only peer" {{ old('is_active', $period->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="ml-3 text-sm font-semibold text-slate-700">Mantener este periodo como activo</span>
                    </label>
                </div>
            </div>

            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('periods.index') }}" class="text-slate-600 hover:text-slate-800 font-medium transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg shadow-blue-200 uppercase tracking-widest">
                    Actualizar Periodo
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
