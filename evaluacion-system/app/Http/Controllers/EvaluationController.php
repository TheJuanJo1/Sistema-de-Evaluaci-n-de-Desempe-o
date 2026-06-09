<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluationController extends Controller
{
    public function index()
    {
        $activePeriod = EvaluationPeriod::active()->first();
        
        if (!$activePeriod) {
            return view('evaluations.no-period');
        }

        $workers = Worker::where('is_active', true)->get();
        
        // Cargar o crear evaluaciones para cada trabajador en el periodo activo
        foreach ($workers as $worker) {
            Evaluation::firstOrCreate([
                'worker_id' => $worker->id,
                'period_id' => $activePeriod->id,
            ]);
        }

        $evaluations = Evaluation::where('period_id', $activePeriod->id)
            ->with(['worker', 'observations'])
            ->get();

        return view('evaluations.index', compact('evaluations', 'activePeriod'));
    }

    public function edit(Evaluation $evaluation)
    {
        // El formulario será un componente Livewire
        return view('evaluations.edit', compact('evaluation'));
    }

    public function sign(Evaluation $evaluation)
    {
        if ($evaluation->status !== 'Completada') {
            return back()->with('error', 'La evaluación debe estar completada por todos los evaluadores antes de firmar.');
        }

        return view('evaluations.sign', compact('evaluation'));
    }

    public function followUp(Evaluation $evaluation)
    {
        return view('evaluations.follow-up', compact('evaluation'));
    }

    public function storeSignature(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        $evaluation->update([
            'worker_signature' => $request->signature,
            'worker_signed_at' => now(),
        ]);

        return redirect()->route('evaluations.index')->with('status', 'Evaluación firmada correctamente.');
    }

    public function exportPdf(EvaluationPeriod $period)
    {
        $evaluations = Evaluation::where('period_id', $period->id)
            ->with(['worker', 'answers'])
            ->get();

        $pdf = Pdf::loadView('evaluations.pdf', compact('evaluations', 'period'));
        $filename = "Consolidado_" . str_replace(' ', '_', $period->name) . ".pdf";
        return $pdf->download($filename);
    }

}
