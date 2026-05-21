<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #1e293b; color: #fff; padding: 10px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Evaluación de Desempeño</h1>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $evaluation->worker->name }}</strong>,</p>
            <p>Te informamos que tu evaluación de desempeño para el periodo <strong>{{ $evaluation->period->name }}</strong> ha sido completada por todos los evaluadores.</p>
            <p>Ya puedes revisar tus resultados y registrar tu firma de aceptación en el sistema.</p>
            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="button">Ir al Sistema</a>
            </p>
            <p>Si tienes alguna duda, por favor contacta al área de Talento Humano.</p>
            <p>Atentamente,<br>Sistema de Gestión de Desempeño</p>
        </div>
    </div>
</body>
</html>
