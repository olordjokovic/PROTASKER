<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fb; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px;">
        <h2 style="color: #224abe; margin-top: 0;">Bienvenido a ProTasker</h2>

        <p>Hola {{ $userName }},</p>

        <p>Tu código de verificación es:</p>

        <div style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4e73df; margin: 20px 0;">
            {{ $code }}
        </div>

        <p>Este código caduca en 10 minutos.</p>

        <p>Si tú no has solicitado este registro, puedes ignorar este correo.</p>
    </div>
</body>
</html>