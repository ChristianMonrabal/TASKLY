<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar sesión - TASKLY</title>
    <link rel="shortcut icon" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    <link href="{{ asset('css/signin-modern.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Contenedor para las burbujas animadas -->
    <div class="bubbles"></div>
    
   
    
    <div class="login-container">
        <div class="login-left">
            <h1>Bienvenido a TASKLY</h1>
            <p>La plataforma que conecta profesionales con proyectos. Encuentra las mejores oportunidades y simplifica la gestión de tus trabajos.</p>
            
            <ul class="feature-list">
                <li class="feature-item">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <span>Conecta con profesionales cualificados</span>
                </li>
                <li class="feature-item">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <span>Gestiona tus proyectos en un solo lugar</span>
                </li>
                <li class="feature-item">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <span>Pagos seguros a través de Stripe</span>
                </li>
            </ul>
        </div>
        
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/icon.png') }}" class="login-icon" alt="TASKLY">
                <h2>Inicia sesión en TASKLY</h2>
                <p>Introduce tus credenciales para continuar</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="signin-form" class="login-form">
                @csrf
                <div class="input-group">
                    <label for="signin-email">Correo electrónico</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input 
                        type="email" 
                        name="email" 
                        id="signin-email" 
                        class="input-field" 
                        value="{{ old('email') }}" 
                        
                    >
                </div>

                <div class="input-group">
                    <label for="signin-password">Contraseña</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        type="password" 
                        name="password" 
                        id="signin-password" 
                        class="input-field" 
                        
                    >
                    <i class="fas fa-eye password-toggle" id="toggle-password"></i>
                </div>

                <div class="forgot-password">
                    <a href="{{ route('forgot-password.auth') }}">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>
            
            <div class="separator">o continúa con</div>
            
            <div class="social-login">
                <a href="{{ route('login.google') }}" class="social-btn btn-google">
                    <i class="fab fa-google"></i>
                    Iniciar sesión con Google
                </a>
            </div>

            <p class="signup-text">
                ¿Aún no tienes cuenta? 
                <a href="{{ route('signup.auth') }}" class="signup-link">Regístrate</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/toogle_password.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/bubbles-animation.js') }}"></script>
</body>
</html>
