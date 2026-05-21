<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Competency;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationObservation;
use App\Mail\EvaluationCompletedMail;
use App\Notifications\EvaluationCompletedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EvaluationForm extends Component
{
    public Evaluation $evaluation;
    public $answers = [];
    public $observation = '';
    public $aspectsToImprove = '';
    public $workerCommitment = '';
    public $satisfactionScore = null;
    public $userRole = '';
    public $canSeeAll = false;
    public $isAdmin = false;

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $user = Auth::user();
        $this->userRole = $user->getRoleNames()->first();
        $this->isAdmin = $user->hasRole('Administrador');
        $this->canSeeAll = $user->hasAnyRole(['Administrador', 'Talento Humano', 'Rector']);

        // Determinar el tipo de competencia (Docente o Administrativo para otros)
        $competencyType = ($this->evaluation->worker->type === 'Docente') ? 'Docente' : 'Administrativo';

        // Obtener competencias para cargar respuestas existentes
        $query = Competency::where('type', $competencyType);
        if (!$this->canSeeAll) {
            $query->where('evaluator_role', $this->userRole);
        }
        $competencies = $query->with('questions')->get();

        // Cargar respuestas existentes
        foreach ($competencies as $competency) {
            foreach ($competency->questions as $question) {
                $answer = EvaluationAnswer::where('evaluation_id', $this->evaluation->id)
                    ->where('question_id', $question->id)
                    ->first();
                
                $this->answers[$question->id] = $answer ? $answer->score : null;
            }
        }

        // Cargar observación del usuario para esta evaluación
        $obs = EvaluationObservation::where('evaluation_id', $this->evaluation->id)
            ->where('user_id', $user->id)
            ->first();
        
        $this->observation = $obs ? $obs->observation : '';

        // Cargar plan de mejora
        $plan = \App\Models\ImprovementPlan::where('evaluation_id', $this->evaluation->id)->first();
        if ($plan) {
            $this->aspectsToImprove = $plan->aspects_to_improve;
            $this->workerCommitment = $plan->worker_commitment;
        }

        $this->satisfactionScore = $this->evaluation->satisfaction_score;
    }

    public function save()
    {

        // Debug: log entry when save is invoked
        \Log::info('EvaluationForm.save invoked for evaluation ID: ' . $this->evaluation->id);
        
        // Validate inputs (existing validation logic follows)


        // Determinar el tipo de competencia
        $competencyType = ($this->evaluation->worker->type === 'Docente') ? 'Docente' : 'Administrativo';

        // Obtener las competencias actuales para validación
        $query = Competency::where('type', $competencyType);
        if (!$this->canSeeAll) {
            $query->where('evaluator_role', $this->userRole);
        }
        $competencies = $query->with('questions')->get();

        // Validar que se respondieron todas las preguntas visibles y permitidas
        $rules = [];
        foreach ($competencies as $competency) {
            $canEvaluateThis = $this->isAdmin || $competency->evaluator_role === $this->userRole;
            if ($canEvaluateThis) {
                foreach ($competency->questions as $question) {
                    $rules["answers.{$question->id}"] = 'required|integer|min:1|max:5';
                }
            }
        }
        $rules['observation'] = 'required|string|min:10';

        $this->validate($rules, [
            'answers.*.required' => 'Esta pregunta es obligatoria.',
            'observation.required' => 'La observación es obligatoria para finalizar la evaluación.',
            'observation.min' => 'La observación debe ser más detallada (mín. 10 caracteres).',
        ]);

        // Guardar respuestas solo de las competencias permitidas
        $user = Auth::user();
        foreach ($competencies as $competency) {
            $canEvaluateThis = $this->isAdmin || $competency->evaluator_role === $this->userRole;
            if ($canEvaluateThis) {
                foreach ($competency->questions as $question) {
                    if (isset($this->answers[$question->id])) {
                        EvaluationAnswer::updateOrCreate(
                            [
                                'evaluation_id' => $this->evaluation->id,
                                'question_id' => $question->id,
                            ],
                            [
                                'user_id' => $user->id,
                                'score' => $this->answers[$question->id],
                            ]
                        );
                    }
                }
            }
        }

        // Guardar observación
        EvaluationObservation::updateOrCreate(
            [
                'evaluation_id' => $this->evaluation->id,
                'user_id' => $user->id,
            ],
            [
                'observation' => $this->observation,
            ]
        );

        // Guardar Plan de Mejora
        \App\Models\ImprovementPlan::updateOrCreate(
            ['evaluation_id' => $this->evaluation->id],
            [
                'aspects_to_improve' => $this->aspectsToImprove,
                'worker_commitment' => $this->workerCommitment,
            ]
        );

        if ($this->evaluation->worker->type === 'Docente' && $this->satisfactionScore !== null) {
            $this->evaluation->update(['satisfaction_score' => $this->satisfactionScore]);
        }
// Notificar a Talento Humano que este evaluador ha completado su parte
        $thUsers = \App\Models\User::role('Talento Humano')->get();
        if ($thUsers->isNotEmpty()) {
            Notification::send($thUsers, new EvaluationCompletedNotification($user, $this->evaluation->worker));
        }
        // Actualizar estado de la evaluación
        if ($this->evaluation->isFullyEvaluated()) {
            $this->evaluation->update([
                'status' => 'Completada',
                'completed_at' => now()
            ]);
            $this->evaluation->calculateFinalScore();


        } else {
            $this->evaluation->update(['status' => 'En Proceso']);
            session()->flash('status', 'Tu evaluación ha sido guardada correctamente. La evaluación general sigue EN PROCESO (pendiente de otros evaluadores).');
        }

        return redirect()->route('evaluations.index');
    }

    public function selectAnswer($questionId, $value, $competencyEvaluatorRole)
    {
        if (!$this->isAdmin && $competencyEvaluatorRole !== $this->userRole) {
            return; // No puede evaluar
        }
        // Si el valor ya está seleccionado, lo quitamos (null), si no, lo asignamos
        $current = $this->answers[$questionId] ?? null;
        $this->answers[$questionId] = ($current == $value) ? null : $value;
    }

    public function render()
    {
        // Determinar el tipo de competencia
        $competencyType = ($this->evaluation->worker->type === 'Docente') ? 'Docente' : 'Administrativo';

        $query = Competency::where('type', $competencyType);
        
        if (!$this->canSeeAll) {
            $query->where('evaluator_role', $this->userRole);
        }

        $competencies = $query->with('questions')->get();

        return view('livewire.evaluation-form', [
            'competencies' => $competencies
        ]);
    }
}
