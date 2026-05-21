<?php

namespace App\Livewire;

use App\Models\Worker;
use Livewire\Component;
use Livewire\WithPagination;

class WorkerTable extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';

    protected $updatesQueryString = ['search', 'typeFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Worker::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('document_id', 'like', '%' . $this->search . '%')
                        ->orWhere('position', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('type', $this->typeFilter);
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('is_active', $this->statusFilter);
            });

        return view('livewire.worker-table', [
            'workers' => $query->latest()->paginate(10)
        ]);
    }
}
