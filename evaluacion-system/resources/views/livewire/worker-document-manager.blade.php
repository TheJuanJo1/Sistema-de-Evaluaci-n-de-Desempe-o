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
    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 border-dashed" x-data="{
        clientError: '',
        validateFiles(event) {
            this.clientError = '';
            const input = event.target;
            if (!input.files || input.files.length === 0) return;
            
            let filesWithErrors = [];
            const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                const extension = file.name.split('.').pop().toLowerCase();
                
                // Validar extensión
                if (!allowedExtensions.includes(extension)) {
                    filesWithErrors.push(file.name + ' (formato no permitido)');
                    continue;
                }
                
                // Validar tamaño (5MB = 5242880 bytes) - Usamos <= invertido para evitar '>'
                if (!(file.size <= 5242880)) {
                    filesWithErrors.push(file.name + ' (supera los 5 MB)');
                    continue;
                }
            }
            
            // Usamos la existencia de elementos en el array para evitar '>'
            if (filesWithErrors.length) {
                this.clientError = 'Errores en selección: ' + filesWithErrors.join(', ');
                input.value = ''; // Limpiar el input
                event.stopImmediatePropagation(); // Evita que Livewire reciba el cambio
                event.preventDefault();
                @this.set('files', []); // Limpiar en Livewire
                return;
            }
        }
    }">
        <form wire:submit.prevent="saveDocuments" enctype="multipart/form-data" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="documentName" class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Documento <span class="text-xs text-slate-400 font-normal">(Opcional para lote)</span></label>
                    <input id="documentName" wire:model="documentName" type="text" placeholder="Ej. Contrato 2024" class="block w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                    @error('documentName') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="documentType" class="block text-sm font-semibold text-slate-700 mb-1">Tipo de Documento <span class="text-xs text-slate-400 font-normal">(Opcional para lote)</span></label>
                    <input id="documentType" wire:model="documentType" type="text" placeholder="Ej. Contrato, Identidad, Título" class="block w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                    @error('documentType') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="files" class="block text-sm font-semibold text-slate-700 mb-1">Archivos (PDF, JPG, PNG - Máx 5MB c/u)</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-slate-50 transition duration-200">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-slate-500"><span class="font-semibold text-blue-600">Haz clic para buscar</span> o arrastra y suelta varios archivos</p>
                        </div>
                        <input id="files" wire:model="files" type="file" class="hidden" multiple @change="validateFiles($event)"/>
                    </label>
                </div>
                
                <!-- Client side validation error -->
                <template x-if="clientError">
                    <div class="mt-2 text-xs font-semibold text-red-600 flex items-center bg-red-50 p-2.5 rounded-lg border border-red-100">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span x-text="clientError"></span>
                    </div>
                </template>

                <div wire:loading wire:target="files" class="mt-2 text-xs text-blue-600 font-semibold flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Procesando y cargando archivos en el servidor...
                </div>

                @error('files') 
                    <span class="text-xs text-red-600 mt-2 block font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $message }}
                    </span>
                @enderror
                @error('files.*') 
                    <span class="text-xs text-red-600 mt-2 block font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- File Previews -->
            @if (!empty($files) && !$errors->has('files') && !$errors->has('files.*'))
                <div class="mt-4 space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Archivos listos para subir:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($files as $index => $fileItem)
                            <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center space-x-3 shadow-sm animate-fade-in">
                                <!-- Preview Thumbnail or Icon -->
                                <div class="relative flex-shrink-0 h-12 w-12 rounded-lg border border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center">
                                    @if (in_array(strtolower($fileItem->getClientOriginalExtension()), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $fileItem->temporaryUrl() }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-red-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-[8px] font-black uppercase mt-0.5">{{ $fileItem->getClientOriginalExtension() }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate" title="{{ $fileItem->getClientOriginalName() }}">
                                        {{ $fileItem->getClientOriginalName() }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium">
                                        {{ round($fileItem->getSize() / 1024, 1) }} KB
                                    </p>
                                </div>

                                <!-- Cancel specific file -->
                                <button type="button" wire:click="removeFile({{ $index }})" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition duration-200" title="Quitar este archivo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex justify-end pt-2">
                <button type="submit" 
                        wire:loading.attr="disabled" 
                        {{ empty($files) ? 'disabled' : '' }} 
                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition duration-200 shadow-md disabled:opacity-50 disabled:bg-slate-300 disabled:cursor-not-allowed uppercase tracking-wider">
                    <svg wire:loading wire:target="saveDocuments" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="saveDocuments">Subir Documento(s)</span>
                    <span wire:loading wire:target="saveDocuments">Subiendo...</span>
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
