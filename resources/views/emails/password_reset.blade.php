<h2>Hola {{ $user->nombre }}</h2>
<p>Has solicitado restablecer tu contraseña. Haz clic en el botón a continuación para hacerlo:</p>

<p>
    <a href="{{ $resetUrl }}" style="background-color: #EC6A6A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Restablecer contraseña
    </a>
</p>

<p>Si no solicitaste esto, puedes ignorar este mensaje.</p>
