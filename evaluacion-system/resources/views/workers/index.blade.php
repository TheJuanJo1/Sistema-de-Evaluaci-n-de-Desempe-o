<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Trabajadores</h2>
                <p class="text-slate-500 mt-1">Administra el personal institucional (Docentes y Administrativos).</p>
            </div>
            <div class="flex items-center space-x-3">
                @role('Administrador|Talento Humano')
                <a href="{{ route('workers.create') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Trabajador
                </a>
                @endrole
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        @endif

        <livewire:worker-table />
    </div>
</x-app-layout>
