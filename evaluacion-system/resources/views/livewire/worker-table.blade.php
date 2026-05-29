<div class="space-y-6">
    <!-- Filters & Action Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input wire:model.live="search" type="text" placeholder="Buscar por nombre, documento o cargo..." class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                </div>

                <!-- Type Filter -->
                <div>
                    <select wire:model.live="typeFilter" class="block w-full px-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 text-slate-600 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 transition duration-200 text-sm">
                        <option value="">Todos los Tipos</option>
                        <option value="Directivo">Directivos</option>
                        <option value="Docente">Docentes</option>
                        <option value="Administrativo">Administrativos</option>
                        <option value="Servicios Generales">Servicios Generales</option>
                        <option value="Psicorientación">Psicorientación</option>
                        <option value="Biblioteca">Biblioteca</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="statusFilter" class="block w-full px-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 text-slate-600 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 transition duration-200 text-sm">
                        <option value="">Todos los Estados</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Trabajador</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Cargo / Tipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Documento</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workers as $worker)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200">
                                        {{ substr($worker->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-semibold text-slate-800">{{ $worker->name }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $worker->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $worker->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-800 font-medium">{{ $worker->position }}</p>
                                <p class="text-xs text-slate-500">{{ $worker->type }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $worker->document_id }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    @role('Administrador|Talento Humano|Rector')
                                    @if(isset($worker->model_type) && ($worker->model_type === 'worker' || $worker->model_type === 'user'))
<!-- Conditional edit link -->
@php
    $editRoute = ($worker->model_type === 'worker') ? route('workers.edit', $worker->id) : route('users.edit', $worker->id);
@endphp
<a href="{{ $editRoute }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200" title="Editar">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</a>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('workers.toggle', $worker->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 {{ $worker->is_active ? 'text-slate-400 hover:text-red-600 hover:bg-red-50' : 'text-slate-400 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition duration-200" title="{{ $worker->is_active ? 'Desactivar' : 'Activar' }}">
                                                @if($worker->is_active)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic">
                                No se encontraron trabajadores que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($workers->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $workers->links() }}
            </div>
        @endif
    </div>
</div>
