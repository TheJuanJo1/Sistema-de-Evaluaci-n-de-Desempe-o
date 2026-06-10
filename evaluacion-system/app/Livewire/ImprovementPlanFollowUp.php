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
    public $plans = [];
    public $selectedPlanId = null;
    public $plan;
    public $newComment = '';
    public $status = '';

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = ImprovementPlan::where('evaluation_id', $this->evaluation->id)
            ->with('user')
            ->get();

        if ($this->plans->isEmpty()) {
            $defaultPlan = ImprovementPlan::create([
                'evaluation_id' => $this->evaluation->id,
                'user_id' => Auth::id(),
                'status' => 'Pendiente',
            ]);
            $this->plans = collect([$defaultPlan]);
        }

        if (!$this->selectedPlanId) {
            $this->selectedPlanId = $this->plans->first()->id;
        }

        $this->loadActivePlan();
    }

    public function loadActivePlan()
    {
        $this->plan = ImprovementPlan::find($this->selectedPlanId);
        if ($this->plan) {
            $this->status = $this->plan->status;
        }
    }

    public function updatedSelectedPlanId($value)
    {
        $this->selectedPlanId = $value;
        $this->loadActivePlan();
    }

    public function addFollowUp()
    {
        $this->validate([
            'newComment' => 'required|string|min:10',
        ]);

        if ($this->plan) {
            FollowUp::create([
                'improvement_plan_id' => $this->plan->id,
                'user_id' => Auth::id(),
                'comments' => $this->newComment,
                'follow_up_date' => now(),
            ]);

            $this->plan->update(['status' => $this->status]);
        }

        $this->newComment = '';
        $this->loadPlans();
        
        session()->flash('status', 'Seguimiento registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.improvement-plan-follow-up', [
            'followUps' => $this->plan ? $this->plan->followUps()->latest()->get() : collect([])
        ]);
    }
}
