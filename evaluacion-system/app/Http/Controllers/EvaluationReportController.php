<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{
    public function generate(Evaluation $evaluation)
    {
        if ($evaluation->status !== 'Completada') {
            return back()->with('error', 'El reporte solo está disponible para evaluaciones completadas.');
        }

        $evaluation->load(['worker', 'period', 'answers.question.competency', 'observations.user']);

        // Agrupar respuestas por competencia para el reporte
        $competencies = $evaluation->answers->groupBy(function($answer) {
            return $answer->question->competency->name;
        });

        $pdf = Pdf::loadView('reports.evaluation', [
            'evaluation' => $evaluation,
            'competencies' => $competencies
        ]);

        return $pdf->download("Evaluacion_{$evaluation->worker->name}_{$evaluation->period->name}.pdf");
    }
}
