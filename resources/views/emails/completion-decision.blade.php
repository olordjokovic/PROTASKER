<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f4f7fb; padding:30px;">
<div style="max-width:650px; margin:auto; background:white; padding:30px; border-radius:18px; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

    <h2 style="color:#14213d;">Resultado de tu solicitud</h2>

    <p>
        El administrador ha revisado tu solicitud de finalización.
    </p>

    <div style="background:#f8fafc; padding:18px; border-radius:12px; margin:20px 0;">
        <p><strong>Elemento:</strong> {{ $item->name ?? $item->title }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($completionRequest->status) }}</p>
        <p><strong>Respuesta del administrador:</strong></p>
        <p>{{ $completionRequest->admin_response }}</p>
    </div>

    @if($completionRequest->admin_evidence_path)
        <p>
            <strong>El administrador adjuntó una evidencia.</strong>
        </p>
    @endif

    <p style="color:#64748b;">
        Puedes ver más detalles entrando en tu cuenta de ProTasker.
    </p>

</div>
</body>
</html>