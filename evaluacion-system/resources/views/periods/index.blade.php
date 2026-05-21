<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Periodos de Evaluación</h2>
                <p class="text-slate-500 mt-1">Define y gestiona las ventanas de tiempo para realizar las evaluaciones.</p>
            </div>
            <div>
                <a href="{{ route('periods.create') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Periodo
                </a>
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

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nombre del Periodo</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($periods as $period)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $period->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600">
                                        <p><span class="font-semibold">Inicio:</span> {{ $period->start_date->format('d/m/Y') }}</p>
                                        <p><span class="font-semibold">Fin:</span> {{ $period->end_date->format('d/m/Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $period->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                        {{ $period->is_active ? 'Activo' : 'Cerrado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('periods.edit', $period) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('periods.toggle', $period) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 rounded-lg font-bold text-xs uppercase tracking-wider border transition-colors duration-200 {{ $period->is_active ? 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' : 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100' }}">
                                                {{ $period->is_active ? 'Cerrar Periodo' : 'Activar Periodo' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic">
                                    No hay periodos de evaluación registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
