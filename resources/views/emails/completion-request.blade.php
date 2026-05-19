<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f4f7fb; padding:30px;">
<div style="max-width:650px; margin:auto; background:white; padding:30px; border-radius:18px; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

    <h2 style="color:#14213d;">Nueva solicitud de finalización</h2>

    <p>
        El usuario <strong>{{ $sender->name }} {{ $sender->surname }}</strong>
        ha solicitado marcar como completado un elemento.
    </p>

    <div style="background:#f8fafc; padding:18px; border-radius:12px; margin:20px 0;">
        <p><strong>Usuario:</strong> {{ $sender->name }} {{ $sender->surname }}</p>
        <p><strong>Email:</strong> {{ $sender->email }}</p>
        <p><strong>Tipo:</strong> {{ $completionRequest->item_type === 'project' ? 'Proyecto' : 'Tarea' }}</p>
        <p><strong>Elemento:</strong> {{ $item->name ?? $item->title }}</p>
        <p><strong>Justificación:</strong></p>
        <p>{{ $completionRequest->description }}</p>
    </div>

    @if($completionRequest->evidence_path)
        <p>
            <strong>El usuario adjuntó una evidencia.</strong>
        </p>
    @endif

    <p style="color:#64748b;">
        Revisa esta solicitud desde el panel de ProTasker.
    </p>

</div>
</body>
</html>