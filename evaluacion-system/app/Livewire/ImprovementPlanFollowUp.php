<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\ImprovementPlan;
use App\Models\FollowUp;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ImprovementPlanFollowUp extends Component
{
    public Evaluation $evaluation;
    public $plan;
    public $newComment = '';
    public $status = '';

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->plan = ImprovementPlan::firstOrCreate(
            ['evaluation_id' => $evaluation->id],
            ['status' => 'Pendiente']
        );
        $this->status = $this->plan->status;
    }

    public function addFollowUp()
    {
        $this->validate([
            'newComment' => 'required|string|min:10',
        ]);

        FollowUp::create([
            'improvement_plan_id' => $this->plan->id,
            'user_id' => Auth::id(),
            'comments' => $this->newComment,
            'follow_up_date' => now(),
        ]);

        $this->plan->update(['status' => $this->status]);

        $this->newComment = '';
        $this->plan->refresh();
        
        session()->flash('status', 'Seguimiento registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.improvement-plan-follow-up', [
            'followUps' => $this->plan->followUps()->latest()->get()
        ]);
    }
}
