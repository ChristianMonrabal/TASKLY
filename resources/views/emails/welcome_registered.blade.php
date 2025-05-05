<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Bienvenido a Taskly!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset y estilos básicos */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse !important;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
        }
        a {
            color: #EC6A6A;
            text-decoration: none;
        }
        /* Botón */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background-color: #EC6A6A;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
        }
        /* Responsive */
        @media screen and (max-width:600px) {
            .email-container {
                width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Contenedor principal -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" bgcolor="#EC6A6A">
                <table class="email-container" width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <img src="{{ $message->embed(public_path('img/icon.png')) }}" width="120" height="120"alt="Taskly Logo" style="display: block; margin: 0 auto;">
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 40px; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; font-size: 24px; color: #333333;">¡Bienvenido a Taskly!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="background-color: #ffffff; padding: 30px; color: #555555; font-size: 16px; line-height: 24px;">
                            <p>Hola <strong>{{ $user->nombre }}</strong>,</p>
                            <p>¡Gracias por unirte a Taskly! Estamos encantados de tenerte con nosotros.</p>
                            <p>Con tu nueva cuenta podrás:</p>
                            <ul>
                                <li>Publicar y gestionar tus proyectos.</li>
                                <li>Postularte a trabajos que se ajusten a tus habilidades.</li>
                                <li>Chatear directamente con clientes.</li>
                                <li>Recibir valoraciones y mejorar tu perfil.</li>
                            </ul>
                            <p>Para empezar, accede a tu panel:</p>
                            <p style="text-align: center;">
                                <a href="{{ url('/signin') }}" class="btn">Iniciar Sesión</a>
                            </p>
                            <p>Si tienes cualquier duda, no dudes en contactarnos en <a href="mailto:soporte.taskly@outlook.es">soporte.taskly@outlook.es</a>.</p>
                            <p>¡Te deseamos mucho éxito!</p>
                            <p>— El equipo de Taskly</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #f4f4f4; padding: 20px; color: #999999; font-size: 12px;">
                            © {{ date('Y') }} Taskly. Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
