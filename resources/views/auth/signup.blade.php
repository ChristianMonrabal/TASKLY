<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="shortcut icon" href="{{ asset('img/icon.png')}}" type="image/x-icon">
    <link href="{{ asset('css/signup.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Regístrate en</h2>
            <img src="{{ asset('img/icon.png') }}" class="login-icon">

            <form action="{{ route('signup.store') }}" method="POST" id="signup-form">
                @csrf
                <div class="double-input">
                    <div>
                        <input type="text" name="name" id="name" placeholder="Nombre" class="input-field" value="{{ old('name') }}">
                    </div>
                    <div>
                        <input type="text" name="surname" id="surname" placeholder="Apellidos" class="input-field" value="{{ old('surname') }}">
                    </div>
                </div>
            
                <div class="double-input">
                    <div>
                        <input type="text" name="phone" id="phone" placeholder="Número de teléfono" class="input-field" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <input type="text" name="postcode" id="postcode" placeholder="Código postal" class="input-field" value="{{ old('postcode') }}">
                    </div>
                </div>
            
                <div class="triple-input">
                    <div>
                        <select name="doc_type" id="doc_type" class="input-field small-input">
                            <option value="">Tipo de documento</option>
                            <option value="nif" {{ old('doc_type') == 'nif' ? 'selected' : '' }}>NIF</option>
                            <option value="nie" {{ old('doc_type') == 'nie' ? 'selected' : '' }}>NIE</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="dni" id="dni" placeholder="NIF/NIE" class="input-field" disabled value="{{ old('dni') }}">
                    </div>
                </div>
            
                <div>
                    <input type="email" name="email" id="signup-email" placeholder="Correo electrónico" class="input-field" value="{{ old('email') }}">
                </div>
            
                <div class="password-container">
                    <input type="password" name="password" id="signup-password" placeholder="Contraseña" class="input-field">
                    <i class="fa-solid fa-eye password-toggle" data-target="signup-password"></i>
                </div>
            
                <div class="password-container">
                    <input type="password" name="password_confirmation" id="signup-password-confirmation" placeholder="Confirmar contraseña" class="input-field">
                    <i class="fa-solid fa-eye password-toggle" data-target="signup-password-confirmation"></i>
                </div>
            
                @if ($errors->has('general'))
                    <div class="error-box">
                        <p style="color: red;">{{ $errors->first('general') }}</p>
                    </div>
                @endif
            
                <button type="submit" class="login-btn">Registrarse</button>
            </form>
            <br>
            <div class="social-login my-3 text-center">
                <a href="{{ route('login.google') }}"
                   class="btn btn-google btn-lg w-100 d-flex align-items-center justify-content-center no-link">
                  <i class="fab fa-google me-2"></i>
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
</body>
</html>
