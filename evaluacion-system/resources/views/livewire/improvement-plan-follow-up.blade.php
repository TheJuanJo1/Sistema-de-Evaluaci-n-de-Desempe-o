<div class="space-y-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-800 px-8 py-5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white tracking-tight">Seguimiento al Plan de Mejora</h3>
            <div class="flex items-center space-x-3">
                <select wire:model="status" class="bg-slate-700 text-white text-xs font-bold rounded-lg border-none focus:ring-2 focus:ring-blue-500">
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Cumplido">Cumplido</option>
                </select>
            </div>
        </div>

        <div class="p-8">
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
