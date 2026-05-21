<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPeriod;
use Illuminate\Http\Request;

class EvaluationPeriodController extends Controller
{
    public function index()
    {
        $periods = EvaluationPeriod::latest()->get();
        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        return view('periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        // Si se marca como activo, desactivamos los demás (solo puede haber uno activo)
        if ($request->has('is_active')) {
            EvaluationPeriod::where('is_active', true)->update(['is_active' => false]);
        }

        EvaluationPeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('periods.index')->with('status', 'Periodo de evaluación creado.');
    }

    public function edit(EvaluationPeriod $period)
    {
        return view('periods.edit', compact('period'));
    }

    public function update(Request $request, EvaluationPeriod $period)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        if ($request->has('is_active') && !$period->is_active) {
            EvaluationPeriod::where('is_active', true)->update(['is_active' => false]);
        }

        $period->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('periods.index')->with('status', 'Periodo actualizado.');
    }

    public function toggleStatus(EvaluationPeriod $period)
    {
        if (!$period->is_active) {
            // Si vamos a activar este, desactivamos el resto
            EvaluationPeriod::where('is_active', true)->update(['is_active' => false]);
        }
        
        $period->update(['is_active' => !$period->is_active]);
        $status = $period->is_active ? 'activado' : 'desactivado';

        return back()->with('status', "Periodo {$status} exitosamente.");
    }
}
