<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f4f7fb; padding:30px;">

<div style="
    max-width:600px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
">

    <h2 style="color:#14213d;">
        Nuevo mensaje en ProTasker
    </h2>

    <p>
        Hola {{ $receiver->name }},
    </p>

    <p>
        Has recibido un nuevo mensaje interno.
    </p>

    <hr>

    <p>
        <strong>De:</strong>
        {{ $sender->name }}
    </p>

    <p>
        <strong>Asunto:</strong>
        {{ $messageData->subject }}
    </p>

    

    <br>

    <a href="{{ url('/messages') }}"
       style="
        display:inline-block;
        padding:12px 18px;
        background:#0d6efd;
        color:#fff;
        text-decoration:none;
        border-radius:10px;
        font-weight:bold;
       ">
        Ver mensajes
    </a>

</div>

</body>
</html>