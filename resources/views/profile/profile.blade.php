<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar perfil de Taskly</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
</html>
<body>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        <h1>Editar mi perfil</h1>
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}">
        </div>

        <div class="form-group">
            <label for="codigo_postal">Código Postal:</label>
            <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $user->codigo_postal) }}">
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->fecha_nacimiento) }}">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion">{{ old('descripcion', $user->descripcion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" required>
        </div>

        <div class="form-group">
            <label>Foto de perfil:</label>
            @if($user->foto_perfil)
                <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}" alt="Foto actual" class="current-photo">
            @endif

            <div id="camera-option">
                <div id="camera-container">
                    <video id="camera-preview" autoplay playsinline style="width: 300px;"></video>
                    <br>
                    <button type="button" id="capture-button">Hacer foto</button>
                </div>
                <canvas id="photo-canvas" style="display: none;"></canvas>
                <div id="photo-preview-container" style="display: none;">
                    <p>Vista previa:</p>
                    <img id="photo-preview" src="" alt="Foto capturada" style="width: 300px;">
                </div>
                <input type="hidden" name="foto_perfil_camera" id="foto_perfil_camera">
            </div>
        </div>

        <button type="submit">Guardar cambios</button>
    </form>

    <script src="{{ asset('js/camera.js') }}"></script>
</body>
</html>