<x-app-layout>
    <x-slot name="header">
        <div>
            <nav class="flex mb-4 text-slate-400 text-xs font-semibold uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('evaluations.index') }}" class="hover:text-blue-600 transition duration-200">Evaluaciones</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2">Evaluando a {{ $evaluation->worker->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Formulario de Evaluación</h2>
            <p class="text-slate-500 mt-1">Trabajador: <span class="font-semibold text-slate-700">{{ $evaluation->worker->name }}</span> | Cargo: <span class="font-semibold text-slate-700">{{ $evaluation->worker->position }}</span></p>
        </div>
    </x-slot>

    <div class="py-2">
        <livewire:evaluation-form :evaluation="$evaluation" />
    </div>
</x-app-layout>
