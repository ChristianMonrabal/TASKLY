<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrarse - TASKLY</title>
    <link rel="shortcut icon" href="{{ asset('img/icon.png')}}" type="image/x-icon">
    <link href="{{ asset('css/signup-modern.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Contenedor para las burbujas animadas -->
    <div class="bubbles"></div>
    
    <div class="login-container">
        <div class="login-left">
            <h1>Únete a TASKLY</h1>
            <p>Crea tu cuenta en la plataforma que conecta profesionales con proyectos. Ofrece tus servicios o encuentra el profesional perfecto para tus necesidades.</p>
            
            <ul class="feature-list">
                <li class="feature-item">
                    <i class="fas fa-tasks feature-icon"></i>
                    <span>Publica y gestiona tus proyectos</span>
                </li>
                <li class="feature-item">
                    <i class="fas fa-briefcase feature-icon"></i>
                    <span>Encuentra trabajo como freelancer</span>
                </li>
                <li class="feature-item">
                    <i class="fas fa-star feature-icon"></i>
                    <span>Sistema de valoraciones y reputación</span>
                </li>
            </ul>
        </div>
        
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/icon.png') }}" class="login-icon" alt="TASKLY">
                <h2>Crea tu cuenta en TASKLY</h2>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('signup.store') }}" method="POST" id="signup-form" class="login-form">
                @csrf
                <div class="input-row">
                    <div class="input-group">
                        <label for="name">Nombre</label>
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="input-field" 
                            value="{{ old('name') }}" 
                            
                        >
                    </div>
                    <div class="input-group">
                        <label for="surname">Apellidos</label>
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            type="text" 
                            name="surname" 
                            id="surname" 
                            class="input-field" 
                            value="{{ old('surname') }}" 
                            
                        >
                    </div>
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="phone">Teléfono</label>
                        <i class="fas fa-phone input-icon"></i>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            class="input-field" 
                            value="{{ old('phone') }}" 
                            
                        >
                    </div>
                    <div class="input-group">
                        <label for="postcode">Código postal</label>
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <input 
                            type="text" 
                            name="postcode" 
                            id="postcode" 
                            class="input-field" 
                            value="{{ old('postcode') }}" 
                            
                        >
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="doc_type">Tipo de documento</label>
                        <i class="fas fa-id-card input-icon"></i>
                        <select 
                            name="doc_type" 
                            id="doc_type" 
                            class="input-field" 
                            
                        >
                            <option value="">Seleccionar...</option>
                            <option value="nif" {{ old('doc_type') == 'nif' ? 'selected' : '' }}>NIF</option>
                            <option value="nie" {{ old('doc_type') == 'nie' ? 'selected' : '' }}>NIE</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="dni">Número de documento</label>
                        <i class="fas fa-id-card input-icon"></i>
                        <input 
                            type="text" 
                            name="dni" 
                            id="dni" 
                            class="input-field" 
                            value="{{ old('dni') }}" 
                            disabled
                        >
                    </div>
                    <div class="input-group">
                        <label for="signup-email">Correo</label>
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="signup-email" 
                            class="input-field" 
                            value="{{ old('email') }}" 
                            
                        >
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="signup-password">Contraseña</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="signup-password" 
                            class="input-field" 
                            
                        >
                        <i class="fas fa-eye password-toggle" data-target="signup-password"></i>
                    </div>

                    <div class="input-group">
                        <label for="signup-password-confirmation">Confirmar contraseña</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="signup-password-confirmation" 
                            class="input-field" 
                            
                        >
                        <i class="fas fa-eye password-toggle" data-target="signup-password-confirmation"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn">Crear cuenta</button>
            </form>
            
            <div class="separator">o regístrate con</div>
            
            <div class="social-login">
                <a href="{{ route('login.google') }}" class="social-btn btn-google">
                    <i class="fab fa-google"></i>
                    Registrarse con Google
                </a>
            </div>

            <p class="signup-text">
                ¿Ya tienes cuenta? 
                <a href="{{ route('signin.auth') }}" class="signup-link">Iniciar sesión</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/toogle_password.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/bubbles-animation.js') }}"></script>
</body>
</html>
