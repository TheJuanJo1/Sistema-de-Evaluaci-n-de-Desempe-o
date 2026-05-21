<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-800">Documentos del Trabajador</h3>
        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-full uppercase tracking-wider">Módulo Seguro</span>
    </div>

    @if (session('status'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 border-dashed">
        <form wire:submit.prevent="upload" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Documento</label>
                    <input wire:model="documentName" type="text" placeholder="Ej. Contrato 2024" class="block w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                    @error('documentName') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo de Documento</label>
                    <input wire:model="documentType" type="text" placeholder="Ej. Contrato, Identidad, Título" class="block w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                    @error('documentType') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Archivo (PDF, JPG, PNG - Máx 5MB)</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-slate-50 transition duration-200">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-slate-500"><span class="font-semibold text-blue-600">Haz clic para subir</span> o arrastra y suelta</p>
                        </div>
                        <input wire:model="file" type="file" class="hidden" />
                    </label>
                </div>
                <div wire:loading wire:target="file" class="mt-2 text-xs text-blue-600 font-medium">Cargando archivo...</div>
                @error('file') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition duration-200 shadow-lg disabled:opacity-50 uppercase tracking-widest">
                    <span wire:loading.remove wire:target="upload">Subir Documento</span>
                    <span wire:loading wire:target="upload">Subiendo...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($documents as $doc)
            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center overflow-hidden">
                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="ml-4 overflow-hidden">
                        <p class="text-sm font-bold text-slate-800 truncate" title="{{ $doc->name }}">{{ $doc->name }}</p>
                        <p class="text-xs text-slate-500 capitalize">{{ $doc->type ?? 'Sin tipo' }} • {{ $doc->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-1">
                    <button wire:click="download({{ $doc->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200" title="Descargar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </button>
                    <button wire:click="delete({{ $doc->id }})" wire:confirm="¿Estás seguro de eliminar este documento?" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center bg-slate-50 rounded-2xl border border-slate-100 italic text-slate-400">
                No hay documentos registrados para este trabajador.
            </div>
        @endforelse
    </div>
</div>
