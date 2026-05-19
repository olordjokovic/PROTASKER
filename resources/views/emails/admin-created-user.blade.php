<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta creada en ProTasker</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f7fb; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

                <tr>
                    <td style="background:linear-gradient(135deg,#224abe,#4e73df); padding:30px; text-align:center; color:white;">
                        <h2 style="margin:0;">ProTasker</h2>
                        <p style="margin:8px 0 0; font-size:14px;">
                            Gestión inteligente de proyectos
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:35px 40px; color:#333;">
                        <p>Hola <strong>{{ $user->name }}</strong>,</p>

                        <p>
                            El administrador de ProTasker ha creado una cuenta para ti.
                        </p>

                        <p>Tus datos de acceso son:</p>

                        <div style="background:#f1f5ff; border:1px solid #dbe4ff; border-radius:12px; padding:18px; margin:24px 0;">
                            <p style="margin:0 0 10px;">
                                <strong>Email:</strong> {{ $user->email }}
                            </p>

                            <p style="margin:0;">
                                <strong>Contraseña temporal:</strong> {{ $plainPassword }}
                            </p>
                        </div>

                        <p>
                            Puedes iniciar sesión desde la página de acceso de ProTasker.
                        </p>

                        <p style="margin-top:25px; font-size:14px; color:#666;">
                            Te recomendamos cambiar tu contraseña después de iniciar sesión.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 40px; text-align:center; background:#f8fafc; font-size:13px; color:#888;">
                        © {{ date('Y') }} ProTasker
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>