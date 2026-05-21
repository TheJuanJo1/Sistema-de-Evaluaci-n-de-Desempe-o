<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluación - {{ $evaluation->worker->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            color: #1e293b;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #64748b;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            border: 1px solid #e2e8f0;
        }
        .label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 30%;
        }
        .competency-header {
            background-color: #1e293b;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            margin-top: 30px;
            border-radius: 4px;
        }
        .question-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .question-table th {
            text-align: left;
            padding: 8px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .question-table td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10pt;
        }
        .score {
            text-align: center;
            font-weight: bold;
            width: 60px;
        }
        .observations-section {
            margin-top: 40px;
        }
        .obs-card {
            border: 1px solid #e2e8f0;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .obs-card h4 {
            margin: 0 0 5px;
            font-size: 10pt;
            color: #1e293b;
        }
        .obs-card p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .footer {
            margin-top: 50px;
        }
        .final-score {
            text-align: right;
            font-size: 14pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 40px;
        }
        .signatures {
            width: 100%;
            margin-top: 60px;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
            display: inline-block;
        }
        .signature-img {
            max-width: 200px;
            max-height: 80px;
            margin-bottom: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEMA DE EVALUACIÓN DE DESEMPEÑO</h1>
        <p>REPORTE INSTITUCIONAL CONSOLIDADO</p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Trabajador:</td>
                <td>{{ $evaluation->worker->name }}</td>
                <td class="label">Documento:</td>
                <td>{{ $evaluation->worker->document_id }}</td>
            </tr>
            <tr>
                <td class="label">Cargo:</td>
                <td>{{ $evaluation->worker->position }}</td>
                <td class="label">Tipo:</td>
                <td>{{ $evaluation->worker->type }}</td>
            </tr>
            <tr>
                <td class="label">Periodo:</td>
                <td>{{ $evaluation->period->name }}</td>
                <td class="label">Estado:</td>
                <td>{{ $evaluation->status }}</td>
            </tr>
            <tr>
                <td class="label">Fecha Emisión:</td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td class="label">Puntaje Final:</td>
                <td><strong>{{ number_format($evaluation->final_score, 2) }} / 5.00</strong></td>
            </tr>
        </table>
    </div>

    @if($evaluation->worker->type === 'Docente')
    <div style="margin-bottom: 25px;">
        <h3 style="border-bottom: 2px solid #e2e8f0; padding-bottom: 5px;">RESUMEN DE EVALUACIÓN INTEGRAL</h3>
        <table class="info-table">
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td>Componente</td>
                <td>Peso</td>
                <td>Puntaje</td>
                <td>Puntaje Ponderado</td>
            </tr>
            <tr>
                <td>Desempeño, Actitudes y Capacidades</td>
                <td>85%</td>
                <td>{{ number_format($evaluation->answers()->avg('score') ?: 0, 2) }}</td>
                <td>{{ number_format(($evaluation->answers()->avg('score') ?: 0) * 0.85, 2) }}</td>
            </tr>
            <tr>
                <td>Satisfacción del Servicio</td>
                <td>15%</td>
                <td>{{ number_format($evaluation->satisfaction_score ?: 0, 2) }}</td>
                <td>{{ number_format(($evaluation->satisfaction_score ?: 0) * 0.15, 2) }}</td>
            </tr>
            <tr style="background-color: #f1f5f9; font-weight: bold; font-size: 12pt;">
                <td colspan="3">PROMEDIO TOTAL</td>
                <td>{{ number_format($evaluation->final_score, 2) }}</td>
            </tr>
        </table>
    </div>
    @endif

    @foreach($competencies as $competencyName => $answers)
        <div class="competency-header">{{ $competencyName }}</div>
        <table class="question-table">
            <thead>
                <tr>
                    <th>Pregunta / Criterio</th>
                    <th class="score">Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $answer)
                    <tr>
                        <td>{{ $answer->question->text }}</td>
                        <td class="score">{{ $answer->score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="page-break"></div>

    <div class="observations-section">
        <h3 style="border-bottom: 2px solid #e2e8f0; padding-bottom: 5px;">OBSERVACIONES DE LOS EVALUADORES</h3>
        @foreach($evaluation->observations as $obs)
            <div class="obs-card">
                <h4>{{ $obs->user->name }} - {{ $obs->user->getRoleNames()->first() }}</h4>
                <p>"{{ $obs->observation }}"</p>
            </div>
        @endforeach
    </div>

    @php
        $plan = \App\Models\ImprovementPlan::where('evaluation_id', $evaluation->id)->first();
    @endphp

    @if($plan)
    <div style="margin-top: 30px;">
        <h3 style="border-bottom: 2px solid #3b82f6; padding-bottom: 5px; color: #1d4ed8;">PLAN DE MEJORA</h3>
        <table class="info-table">
            <tr>
                <td class="label">Aspectos a Mejorar:</td>
                <td style="padding: 10px;">{{ $plan->aspects_to_improve }}</td>
            </tr>
            <tr>
                <td class="label">Compromiso del Trabajador:</td>
                <td style="padding: 10px;">{{ $plan->worker_commitment }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <div class="final-score">
            PROMEDIO FINAL: {{ number_format($evaluation->final_score, 2) }}
        </div>

        <table class="signatures">
            <tr>
                <td style="width: 50%; vertical-align: bottom;">
                    <div style="width: 90%; border-top: 1px solid #333; text-align: center; padding-top: 10px;">
                        @if($evaluation->worker_signature)
                            <img src="{{ $evaluation->worker_signature }}" class="signature-img" alt="Firma Trabajador"><br>
                        @else
                            <div style="height: 50px;"></div>
                        @endif
                        <strong>{{ $evaluation->worker->name }}</strong><br>
                        Firma del Trabajador<br>
                        <span style="font-size: 8pt;">{{ $evaluation->worker_signed_at ? $evaluation->worker_signed_at->format('d/m/Y H:i') : '' }}</span>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: bottom;">
                    <div style="width: 90%; border-top: 1px solid #333; text-align: center; padding-top: 10px; float: right;">
                        <div style="height: 50px;"></div>
                        <strong>INSTITUCIÓN EDUCATIVA</strong><br>
                        Firma Autorizada
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
