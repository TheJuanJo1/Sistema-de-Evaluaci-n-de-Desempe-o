<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Ticket de Soporte</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Solicitud de Soporte del Sistema Institucional</h2>
    <p>Has recibido un nuevo mensaje de soporte de un usuario del sistema.</p>
    
    <div style="background-color: #f4f4f5; padding: 15px; border-radius: 8px; margin-top: 20px;">
        <p><strong>Remitente:</strong> {{ $senderUser->name }} ({{ $senderUser->email }})</p>
        <p><strong>Asunto:</strong> {{ $ticketSubject }}</p>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">
        <p><strong>Mensaje:</strong></p>
        <p style="white-space: pre-wrap;">{{ $ticketMessage }}</p>
    </div>
    
    <p style="margin-top: 30px; font-size: 12px; color: #777;">Este mensaje fue enviado desde el Sistema de Evaluación de Desempeño.</p>
</body>
</html>
