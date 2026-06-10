<div class="space-y-8">
    @if (session('status'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Selector de Plan de Mejora por Evaluador -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h4 class="text-base font-bold text-slate-800">Seleccionar Plan de Mejora</h4>
            <p class="text-xs text-slate-500 mt-0.5">Visualiza y realiza seguimiento al plan de un evaluador específico.</p>
        </div>
        <div class="w-full md:w-80">
            <select wire:model.live="selectedPlanId" class="w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm text-slate-700 font-medium">
                @foreach($plans as $p)
                    <option value="{{ $p->id }}">
                        Plan de: {{ $p->user ? $p->user->name : 'N/A' }} ({{ $p->user ? $p->user->getRoleNames()->first() : 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-800 px-8 py-5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white tracking-tight">
                Plan de: {{ $plan && $plan->user ? $plan->user->name : 'N/A' }} ({{ $plan && $plan->user ? $plan->user->getRoleNames()->first() : 'N/A' }})
            </h3>
            <div class="flex items-center space-x-3">
                <select wire:model="status" class="bg-slate-700 text-white text-xs font-bold rounded-lg border-none focus:ring-2 focus:ring-blue-500">
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Cumplido">Cumplido</option>
                </select>
            </div>
        </div>

        <div class="p-8">
            <!-- Plan Details -->
            @if($plan)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b border-slate-100 pb-8">
                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50">
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-widest mb-2">Aspectos por Mejorar</h4>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $plan->aspects_to_improve ?: 'No especificados' }}</p>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Compromiso de Mejora (Trabajador)</h4>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $plan->worker_commitment ?: 'No especificados' }}</p>
                </div>
            </div>
            @endif

            <!-- Add Follow-up Form -->
            <div class="mb-10 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest">Registrar Nuevo Seguimiento</label>
                <textarea wire:model="newComment" rows="3" class="block w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-slate-700 mb-4" placeholder="Describe los avances o novedades..."></textarea>
                @error('newComment') <p class="text-xs text-red-600 font-bold mb-4">{{ $message }}</p> @enderror
                
                <div class="flex justify-end">
                    <button wire:click="addFollowUp" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition duration-200 shadow-lg shadow-blue-200">
                        Guardar Seguimiento
                    </button>
                </div>
            </div>

            <!-- Follow-up Timeline -->
            <div class="space-y-6">
                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Historial de Seguimientos</h4>
                
                @forelse($followUps as $followUp)
                    <div class="relative pl-8 border-l-2 border-slate-100 pb-6 last:pb-0">
                        <div class="absolute -left-1.5 top-0 w-3 h-3 bg-blue-500 rounded-full border-2 border-white"></div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-slate-400 uppercase">{{ $followUp->follow_up_date->format('d/m/Y H:i') }}</span>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $followUp->user->name }}</span>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">{{ $followUp->comments }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 italic text-sm">No se han registrado seguimientos aún.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
