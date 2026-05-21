<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'period_id',
        'status',
        'final_score',
        'completed_at',
        'worker_signature',
        'worker_signed_at',
        'satisfaction_score',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'worker_signed_at' => 'datetime',
        'satisfaction_score' => 'decimal:2',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function period()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'period_id');
    }

    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }

    public function observations()
    {
        return $this->hasMany(EvaluationObservation::class);
    }

    public function isFullyEvaluated()
    {
        // Si hay una observación por parte de un Administrador, se considera totalmente completada
        $hasAdminObservation = $this->observations()
            ->whereHas('user', function($query) {
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'Administrador');
                });
            })->exists();

        if ($hasAdminObservation) {
            return true;
        }

        $workerType = $this->worker->type;
        $requiredRoles = Competency::where('type', $workerType)
            ->pluck('evaluator_role')
            ->unique()
            ->toArray();
        
        // Obtener los roles de los usuarios que han dejado observaciones
        $evaluatedRoles = $this->observations()
            ->with('user.roles')
            ->get()
            ->pluck('user.roles.*.name')
            ->flatten()
            ->unique()
            ->toArray();

        $missingRoles = array_diff($requiredRoles, $evaluatedRoles);

        return count($missingRoles) === 0;
    }

    public function calculateFinalScore()
    {
        $performanceAvg = $this->answers()->avg('score') ?: 0;
        
        if ($this->worker->type === 'Docente') {
            $satisfaction = $this->satisfaction_score ?: 0;
            $final = ($performanceAvg * 0.85) + ($satisfaction * 0.15);
        } else {
            $final = $performanceAvg;
        }

        $this->update(['final_score' => $final]);
        return $final;
    }
}
