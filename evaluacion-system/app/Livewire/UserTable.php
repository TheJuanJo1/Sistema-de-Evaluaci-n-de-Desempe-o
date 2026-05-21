<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    protected $updatesQueryString = ['search', 'roleFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->with('roles')
            ->where('id', '!=', auth()->id()) // No mostrarse a sí mismo para evitar accidentes
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($q) {
                $q->role($this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('is_active', $this->statusFilter);
            });

        return view('livewire.user-table', [
            'users' => $query->latest()->paginate(10),
            'roles' => \Spatie\Permission\Models\Role::all()
        ]);
    }
}
