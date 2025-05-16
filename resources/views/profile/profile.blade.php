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
        <br><br><br>
        <div class="profile-photo-wrapper">
            @if($user->foto_perfil && empty(request('foto_perfil_camera')))
                <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}" class="current-photo" id="current-photo">
            @elseif(!empty(request('foto_perfil_camera')))
                <img src="{{ request('foto_perfil_camera') }}" class="current-photo" id="current-photo">
            @else
                <div class="no-photo-placeholder">
                    <img src="{{ asset('img/profile_images/perfil_default.png') }}" class="current-photo" id="current-photo">
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

        <div class="form-group">
            <label class="form-label">Habilidades:</label>
            <div class="form-row">
                <div class="form-group">
                    <label for="habilidades-seleccionadas">Mis habilidades actuales:</label>
                    <select id="habilidades-seleccionadas" multiple class="form-select custom-multiselect" disabled>
                        @foreach($user->habilidades as $habilidad)
                            <option>{{ $habilidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="habilidades">Seleccionar habilidades:</label>
                    <select name="habilidades[]" id="habilidades" multiple class="form-select custom-multiselect">
                        @foreach($habilidades as $habilidad)
                            <option value="{{ $habilidad->id }}" 
                                @if(in_array($habilidad->id, $user->habilidades->pluck('id')->toArray())) selected @endif>
                                {{ $habilidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <input type="hidden" name="foto_perfil_camera" id="foto_perfil_camera">

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#EC6A6A'
                    });
                });
            </script>
        @endif


        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: '{{ $errors->first() }}',
                        confirmButtonColor: '#EC6A6A'
                    });
                });
            </script>
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
    <script src="{{ asset('js/profile_alerts.js') }}"></script>
    <script>
    window.profileFeedback = {
        success: {!! json_encode(session('success')) !!},
        error: {!! json_encode($errors->first()) !!}
    };
    </script>
</body>
</html>