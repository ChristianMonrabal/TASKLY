<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar sesión</title>
    <link rel="shortcut icon" href="{{ asset('img/icon.png')}}" type="image/x-icon">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Inicia sesión en</h2>
            <img src="{{ asset('img/icon.png') }}" class="login-icon">

            <form action="{{ route('login') }}" method="POST" id="signin-form">
                @csrf
                <input type="email" name="email" id="signin-email" placeholder="Correo electrónico" class="input-field">

                <div class="password-container">
                    <input type="password" name="password" id="signin-password" placeholder="Contraseña" class="input-field">
                    <i class="fas fa-eye password-toggle" id="toggle-password"></i>
                </div>

                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>

            <p class="signup-text">
                ¿Aún no tienes cuenta?
                <a href="{{ route('signup.auth') }}" class="signup-link">Regístrate</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/toogle_password.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
