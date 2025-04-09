<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar perfil de Taskly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

    @extends('layouts.app')
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        <br><br>
        <h1 class="text-center mb-4">Mi perfil</h1>
        
        <div class="profile-photo-wrapper">
            @if($user->foto_perfil)
                <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}" class="current-photo">
            @else
                <div class="no-photo-placeholder">
                    <i class="fas fa-user" style="font-size: 60px;"></i>
                </div>
            @endif
            
            <button type="button" class="camera-icon-btn" data-bs-toggle="modal" data-bs-target="#cameraModal">
                <i class="fas fa-camera"></i>
            </button>
        </div>

        @csrf
        @method('PUT')
    
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}">
            </div>
        </div>
    
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}">
            </div>
        </div>
    
        <div class="form-row">
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $user->codigo_postal) }}">
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->fecha_nacimiento) }}">
            </div>
        </div>
    
        <div class="form-row">
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" readonly>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion">{{ old('descripcion', $user->descripcion) }}</textarea>
            </div>
        </div>
    
        <div id="photo-preview-container" style="display: none; margin: 20px auto; text-align: center;">
            <p>Vista previa de la nueva foto:</p>
            <img id="photo-preview" src="" alt="Foto capturada" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;">
        </div>
        <input type="hidden" name="foto_perfil_camera" id="foto_perfil_camera">
    
        @if(session('success'))
            <div class="green">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li class="error-message">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-center mt-4">
            <button type="submit" class="btn color">Guardar cambios</button>
        </div>
    </form>

    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Hacer foto de perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="camera-container">
                        <video id="camera-preview" autoplay playsinline style="max-width: 100%;"></video>
                    </div>
                    <canvas id="photo-canvas" style="display: none;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn red" id="capture-button">
                        <i class="fas fa-camera me-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>