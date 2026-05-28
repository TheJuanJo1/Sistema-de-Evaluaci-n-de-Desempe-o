<?php

namespace App\Livewire;

use App\Models\Worker;
use App\Models\WorkerDocument;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WorkerDocumentManager extends Component
{
    use WithFileUploads;

    /**
     * Mensajes de validación en español.
     */
    protected $messages = [
        'files.required' => 'Debe seleccionar al menos un archivo.',
        'files.array' => 'Los archivos seleccionados deben ser una lista.',
        'files.*.file' => 'Uno de los elementos no es un archivo válido.',
        'files.*.mimes' => 'Uno o más archivos no tienen formato permitido (solo PDF, JPG, JPEG, PNG).',
        'files.*.max' => 'Uno o más archivos superan el límite de 5 MB.',
        'documentName.max' => 'El nombre del documento no puede exceder 255 caracteres.',
        'documentType.max' => 'El tipo del documento no puede exceder 100 caracteres.',
    ];

    /**
     * Reglas de validación en español.
     */
    protected $rules = [
        'files' => 'required|array|min:1',
        'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB cada uno
        'documentName' => 'nullable|string|max:255',
        'documentType' => 'nullable|string|max:100',
    ];

    public Worker $worker;
    public $files = []; // Cambiado a arreglo para soporte múltiple
    public $documentName;
    public $documentType;

    public function mount(Worker $worker)
    {
        $this->worker = $worker;
    }

    public function updatedFiles()
    {
        // Limpiar errores previos de archivos
        $this->resetErrorBag('files');

        try {
            $this->validateOnly('files.*');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->files = [];
            throw $e;
        }

        // Si se subió exactamente UN archivo, autocompletamos la interfaz
        if (count($this->files) === 1) {
            $singleFile = $this->files[0];
            if (empty($this->documentName)) {
                $originalName = $singleFile->getClientOriginalName();
                $this->documentName = pathinfo($originalName, PATHINFO_FILENAME);
            }
            if (empty($this->documentType)) {
                $this->documentType = strtoupper($singleFile->getClientOriginalExtension());
            }
        } else {
            // Si son múltiples, limpiamos los campos manuales para evitar duplicaciones
            $this->reset(['documentName', 'documentType']);
        }
    }

    public function upload()
    {
        // Asegurarse de que el directorio temporal existe
        if (!Storage::disk('local')->exists('livewire-tmp')) {
            Storage::disk('local')->makeDirectory('livewire-tmp');
        }

        // Si no se ha seleccionado ningún archivo, abortar
        if (empty($this->files)) {
            $this->addError('files', 'Debe seleccionar al menos un archivo antes de subir.');
            return;
        }

        // Validar input (reglas y mensajes definidos en el componente)
        $this->validate();

        // Asegurarse de que el directorio de destino existe
        $directory = 'worker-documents/' . $this->worker->id;
        if (!Storage::disk('private')->exists($directory)) {
            Storage::disk('private')->makeDirectory($directory);
        }

        $uploadedCount = 0;
        $errorsCount = 0;

        foreach ($this->files as $fileItem) {
            if (!$fileItem->isValid()) {
                $errorsCount++;
                continue;
            }

            try {
                // Almacenar el archivo en el disco privado
                $path = $fileItem->store($directory, 'private');
                if (!$path) {
                    $errorsCount++;
                    continue;
                }

                $originalName = $fileItem->getClientOriginalName();
                
                // Si es exactamente un archivo, y se le especificó nombre, usarlo. Sino usar original.
                $finalName = (count($this->files) === 1 && !empty($this->documentName))
                    ? $this->documentName
                    : pathinfo($originalName, PATHINFO_FILENAME);

                // Si es exactamente un archivo, y se le especificó tipo, usarlo. Sino usar la extensión en mayúsculas.
                $finalType = (count($this->files) === 1 && !empty($this->documentType))
                    ? $this->documentType
                    : strtoupper($fileItem->getClientOriginalExtension());

                WorkerDocument::create([
                    'worker_id' => $this->worker->id,
                    'name' => $finalName,
                    'path' => $path,
                    'type' => $finalType,
                ]);

                $uploadedCount++;

            } catch (\Exception $e) {
                Log::error('Error al subir archivo en lote: '.$e->getMessage());
                $errorsCount++;
            }
        }

        // Limpiar estados
        $this->reset(['files', 'documentName', 'documentType']);
        $this->dispatch('document-uploaded');

        if ($uploadedCount > 0) {
            $msg = $uploadedCount === 1
                ? 'Documento subido correctamente.'
                : "{$uploadedCount} documentos subidos correctamente.";
            
            if ($errorsCount > 0) {
                $msg .= " (Hubo errores en {$errorsCount} archivo(s)).";
            }
            session()->flash('status', $msg);
        } else {
            $this->addError('files', 'No se pudo guardar ningún archivo.');
        }
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            array_splice($this->files, $index, 1);
        }

        // Si no quedan archivos en cola, limpiar campos
        if (empty($this->files)) {
            $this->reset(['files', 'documentName', 'documentType']);
        } elseif (count($this->files) === 1) {
            // Si queda exactamente uno, autocompletar si está vacío
            $singleFile = $this->files[0];
            if (empty($this->documentName)) {
                $originalName = $singleFile->getClientOriginalName();
                $this->documentName = pathinfo($originalName, PATHINFO_FILENAME);
            }
            if (empty($this->documentType)) {
                $this->documentType = strtoupper($singleFile->getClientOriginalExtension());
            }
        }
    }

    public function delete($documentId)
    {
        $document = WorkerDocument::where('worker_id', $this->worker->id)->findOrFail($documentId);
        Storage::disk('private')->delete($document->path);
        $document->delete();

        session()->flash('status', 'Documento eliminado.');
    }

    public function download($documentId)
    {
        $document = WorkerDocument::where('worker_id', $this->worker->id)->findOrFail($documentId);
        return Storage::disk('private')->download($document->path, $document->name . '.' . pathinfo($document->path, PATHINFO_EXTENSION));
    }

    public function render()
    {
        return view('livewire.worker-document-manager', [
            'documents' => $this->worker->documents()->latest()->get()
        ]);
    }
}
