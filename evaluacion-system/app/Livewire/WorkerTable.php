<?php

namespace App\Livewire;

use App\Models\Worker;
use App\Models\User;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkerTable extends Component
{


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
        // Build base worker query with filters
        $workerQuery = Worker::query()
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
            })
            ->latest();

        $workers = $workerQuery->get()->map(function ($w) {
            return (object)[
                'id' => $w->id,
                'name' => $w->name,
                'document_id' => $w->document_id,
                'position' => $w->position,
                'type' => $w->type,
                'model_type' => 'worker',
                'is_active' => $w->is_active,
            ];
        });

        $users = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Administrador');
            })
            ->get()
            ->map(function ($user) {
                return (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                    'document_id' => null,
                    'position' => null,
                    'type' => $user->roles->pluck('name')->first() ?? 'Usuario',
                    'model_type' => 'user',
                    'is_active' => $user->is_active,
                ];
            });

        $combined = $workers->concat($users)->sortByDesc('id');

        // Manual pagination of the combined collection
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $combined->forPage($page, $perPage),
            $combined->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.worker-table', [
            'workers' => $paginated,
        ]);
    }
}
