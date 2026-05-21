<?php

namespace App\Livewire;

use App\Models\Worker;
use App\Models\WorkerDocument;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class WorkerDocumentManager extends Component
{
    use WithFileUploads;

    public Worker $worker;
    public $file;
    public $documentName;
    public $documentType;

    public function mount(Worker $worker)
    {
        $this->worker = $worker;
    }

    public function upload()
    {
        $this->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
            'documentName' => 'required|string|max:255',
            'documentType' => 'nullable|string|max:100',
        ]);

        $path = $this->file->store('worker-documents/' . $this->worker->id, 'private');

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
