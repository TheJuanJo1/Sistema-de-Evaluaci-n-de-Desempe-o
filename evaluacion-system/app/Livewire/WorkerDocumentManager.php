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
        'file.required' => 'Debe seleccionar un archivo.',
        'file.mimes' => 'Formato no permitido (solo PDF, JPG, JPEG, PNG).',
        'file.max' => 'El archivo no puede superar los 5 MB.',
        'documentName.required' => 'El nombre del documento es obligatorio.',
        'documentName.max' => 'El nombre del documento no puede exceder 255 caracteres.',
        'documentType.max' => 'El tipo del documento no puede exceder 100 caracteres.',
    ];

    /**
     * Reglas de validación en español.
     */
    protected $rules = [
        'file' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        'documentName' => 'required|string|max:255',
        'documentType' => 'nullable|string|max:100',
    ];

    public Worker $worker;
    public $file = null;
    public $documentName;
    public $documentType;

    public function mount(Worker $worker)
    {
        $this->worker = $worker;
    }

    public function updatedFile()
    {
        // Limpiar errores previos del archivo
        $this->resetErrorBag('file');

        try {
            $this->validateOnly('file');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->file = null;
            throw $e;
        }

        if ($this->file) {
            // Autocompletar el nombre del documento si está vacío
            if (empty($this->documentName)) {
                $originalName = $this->file->getClientOriginalName();
                $this->documentName = pathinfo($originalName, PATHINFO_FILENAME);
            }

            // Autocompletar el tipo del documento si está vacío
            if (empty($this->documentType)) {
                $this->documentType = strtoupper($this->file->getClientOriginalExtension());
            }
        }
    }

    public function upload()
    {
        // Validar datos usando las reglas y mensajes definidos arriba
        // Ensure Livewire temporary upload directory exists (Livewire uses local disk by default)
        if (!Storage::disk('local')->exists('livewire-tmp')) {
            Storage::disk('local')->makeDirectory('livewire-tmp');
        }
        // Si no se ha seleccionado ningún archivo, abortar y mostrar error
        if (! $this->file) {
            $this->addError('file', 'Debe seleccionar un archivo antes de subir.');
            return;
        }
        // Validar input (reglas y mensajes definidos en el componente)
        $this->validate();

        // Asegurarse de que el directorio de destino existe
        $directory = 'worker-documents/' . $this->worker->id;
        if (!Storage::disk('private')->exists($directory)) {
            Storage::disk('private')->makeDirectory($directory);
        }

        // Intentar almacenar el archivo
        // Verificar que el archivo sea válido antes de intentar guardarlo
        if (!$this->file->isValid()) {
            $this->addError('file', 'El archivo seleccionado no es válido.');
            return;
        }
        try {
            // Intentar almacenar el archivo en el disco privado
            $path = $this->file->store($directory, 'private');
            if (! $path) {
                $this->addError('file', 'No se pudo subir el archivo.');
                return;
            }
        } catch (\Exception $e) {
            // Registrar el error para depuración y mostrar mensaje amigable
            Log::error('Error al subir archivo: '.$e->getMessage());
            $this->addError('file', 'Ocurrió un error inesperado al subir el archivo.');
            return;
        }


        WorkerDocument::create([
            'worker_id' => $this->worker->id,
            'name' => $this->documentName,
            'path' => $path,
            'type' => $this->documentType,
        ]);

        $this->reset(['file', 'documentName', 'documentType']);
        $this->dispatch('document-uploaded');
        session()->flash('status', 'Documento subido correctamente.');
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
