<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f7fb; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">

                <!-- CONTAINER -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background: linear-gradient(135deg,#224abe,#4e73df); padding:30px; text-align:center; color:white;">
                            <h2 style="margin:0; font-weight:700; letter-spacing:0.5px;">
                                ProTasker
                            </h2>
                            <p style="margin:8px 0 0; font-size:14px; opacity:0.9;">
                                Gestión inteligente de proyectos
                            </p>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:35px 40px; color:#333;">

                            <p style="margin-top:0; font-size:15px;">
                                Hola <strong>{{ $userName }}</strong>,
                            </p>

                            <p style="font-size:15px; line-height:1.6;">
                                Tu código de verificación es:
                            </p>

                            <!-- CODE BOX -->
                            <div style="text-align:center; margin:30px 0;">
                                <span style="
                                    display:inline-block;
                                    font-size:34px;
                                    font-weight:700;
                                    letter-spacing:10px;
                                    color:#224abe;
                                    background:#f1f5ff;
                                    padding:16px 24px;
                                    border-radius:12px;
                                    border:1px solid #dbe4ff;
                                ">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="font-size:14px; color:#555;">
                                Este código caduca en <strong>10 minutos</strong>.
                            </p>

                            <p style="font-size:14px; color:#777; margin-top:25px;">
                                Si tú no has solicitado este registro, puedes ignorar este correo.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding:20px 40px; text-align:center; background:#f8fafc; font-size:13px; color:#888;">
                            © {{ date('Y') }} ProTasker · Todos los derechos reservados
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>