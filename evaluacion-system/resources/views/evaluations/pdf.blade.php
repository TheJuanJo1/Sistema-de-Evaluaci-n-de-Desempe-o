<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Evaluaciones {{ $period->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Evaluaciones de Desempeño - {{ $period->name }}</h2>
    <p>Periodo: {{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID Trabajador</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Cargo</th>
                <th>Puntaje Desempeño</th>
                <th>Puntaje Satisfacción</th>
                <th>Puntaje Final</th>
                <th>Estado</th>
                <th>Firmado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluations as $evaluation)
                @php
                    $performanceAvg = $evaluation->answers()->avg('score') ?: 0;
                @endphp
                <tr>
                    <td>{{ $evaluation->worker->document_id }}</td>
                    <td>{{ $evaluation->worker->name }}</td>
                    <td>{{ $evaluation->worker->type }}</td>
                    <td>{{ $evaluation->worker->position }}</td>
                    <td>{{ round($performanceAvg, 2) }}</td>
                    <td>{{ round($evaluation->satisfaction_score ?? 0, 2) }}</td>
                    <td>{{ round($evaluation->final_score ?? 0, 2) }}</td>
                    <td>{{ $evaluation->status }}</td>
                    <td>{{ $evaluation->worker_signed_at ? 'SI' : 'NO' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Evaluator signatures -->
    <div class="signatures" style="margin-top:30px;">
        <h3>Firmas de Evaluadores</h3>
        @php
            $evaluatorUsers = $evaluations->flatMap(function($e) {
                return $e->observations->map->user;
            })->filter()->unique('id');
        @endphp
        @foreach($evaluatorUsers as $user)
            @if($user && $user->signature)
                <div class="signature-box" style="display:inline-block;text-align:center;margin-right:20px;">
                    <img src="{{ public_path('storage/' . $user->signature) }}" alt="Firma {{ $user->name }}" class="signature-img" style="height:80px;" />
                    <p>{{ $user->name }}<br/>{{ $user->roles->pluck('name')->join(', ') }}</p>
                </div>
            @endif
        @endforeach
    </div>

</body>
</html>
