<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Evaluaciones de Desempeño</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-slate-200">
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-50 rounded-full mb-6">
                        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">No hay un periodo de evaluación activo</h3>
                    <p class="text-slate-500 max-w-md mx-auto mb-8">
                        Para poder realizar evaluaciones, el Administrador debe crear y activar un periodo de evaluación vigente (semestre, trimestre o año).
                    </p>
                    @role('Administrador')
                        <a href="{{ route('periods.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 transition duration-200 shadow-lg shadow-blue-200 uppercase tracking-widest">
                            Gestionar Periodos
                        </a>
                    @else
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest">Contacta al Administrador del sistema</p>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
