<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Evaluaciones de Desempeño</h2>
                <p class="text-slate-500 mt-1">Periodo Actual: <span class="font-bold text-blue-600">{{ $activePeriod->name }}</span> ({{ $activePeriod->start_date->format('d/m/Y') }} - {{ $activePeriod->end_date->format('d/m/Y') }})</p>
            </div>
            @role('Administrador|Talento Humano')
            <div>
                <a href="{{ route('evaluations.export', $activePeriod) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Exportar (.CSV)
                </a>
            </div>
            @endrole
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
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Trabajador</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($evaluations as $evaluation)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100">
                                            {{ substr($evaluation->worker->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-semibold text-slate-800">{{ $evaluation->worker->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $evaluation->worker->position }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $evaluation->worker->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center space-x-1.5">
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">Global:</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $evaluation->status == 'Completada' ? 'bg-green-100 text-green-700' : ($evaluation->status == 'En Proceso' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                                {{ $evaluation->status }}
                                            </span>
                                        </div>
                                        @if($evaluation->worker_signed_at)
                                            <span class="inline-flex items-center text-[10px] text-green-600 font-bold uppercase mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Firmado
                                            </span>
                                        @endif
                                        @php
                                            $workerType = $evaluation->worker->type;
                                            $userRole = auth()->user()->getRoleNames()->first();
                                            $isAdmin = auth()->user()->hasRole('Administrador');
                                            $isRequiredToEvaluate = $isAdmin || \App\Models\Competency::where('type', $workerType)
                                                ->where('evaluator_role', $userRole)
                                                ->exists();
                                            $hasEvaluated = $evaluation->observations->where('user_id', auth()->id())->count() > 0;
                                        @endphp
                                        <div class="flex items-center space-x-1.5 mt-1">
                                            @if($isRequiredToEvaluate)
                                                @if($hasEvaluated)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Tu evaluación: Realizada
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>Tu evaluación: Pendiente
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-50 text-slate-500 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>Tu rol: No aplica
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        @if($evaluation->status == 'Completada' && !$evaluation->worker_signed_at)
                                            <a href="{{ route('evaluations.sign', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition duration-150">
                                                Firmar
                                            </a>
                                        @endif
                                        @if($evaluation->status == 'Completada')
                                            <a href="{{ route('evaluations.report', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 shadow-sm transition duration-150" title="Descargar Reporte PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
                                            </a>
                                            <a href="{{ route('evaluations.follow-up', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 shadow-sm transition duration-150" title="Seguimiento">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                                            </a>
                                        @endif
                                        @if($isRequiredToEvaluate)
                                            <a href="{{ route('evaluations.edit', $evaluation) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring transition ease-in-out duration-150 {{ $hasEvaluated ? 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 ring-indigo-300' : 'bg-blue-600 hover:bg-blue-700 active:bg-blue-900 ring-blue-300' }}">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                {{ $hasEvaluated ? 'Editar' : 'Evaluar' }}
                                            </a>
                                        @else
                                            <a href="{{ route('evaluations.edit', $evaluation) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring transition ease-in-out duration-150 ring-slate-300">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Ver
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic">
                                    No hay trabajadores registrados para evaluar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
