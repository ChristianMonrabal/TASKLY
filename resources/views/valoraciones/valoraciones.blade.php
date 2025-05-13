<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valorar trabajo - Taskly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/valoraciones.css') }}">
</head>

<body>
@extends('layouts.app')
@section('content')

<div class="valoracion-wrapper">
    <div class="profile-photo-wrapper">
        <div class="no-photo-placeholder">
            @if(isset($trabajador) && !empty($trabajador->foto_perfil))
                <img src="{{ asset('img/profile_images/' . $trabajador->foto_perfil) }}" class="current-photo" alt="{{ $trabajador->nombre ?? 'Perfil' }}">
            @else
                <img src="{{ asset('img/profile_images/perfil_default.png') }}" class="current-photo" alt="Perfil">
            @endif
        </div>
    </div>

    <form class="valoracion-container" action="{{ route('valoraciones.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf

        <h6>{{ $trabajador->nombre }}</h6>
        <h2>{{ $trabajo->titulo }}</h2>

        <div class="rating-stars" id="stars">
            <i class="fas fa-star" data-value="1"></i>
            <i class="fas fa-star" data-value="2"></i>
            <i class="fas fa-star" data-value="3"></i>
            <i class="fas fa-star" data-value="4"></i>
            <i class="fas fa-star" data-value="5"></i>
        </div>
        <input type="hidden" name="puntuacion" id="puntuacion">
        <span id="star-error" class="error-message" style="display: none;">Por favor, selecciona una calificación.</span>

        <div class="form-group">
            <label for="comentario">Añade un comentario:</label>
            <textarea id="comentario" name="comentario" rows="4" placeholder="Escribe tu valoración..."></textarea>
            <span id="comment-error" class="error-message" style="display: none;">El comentario no puede estar vacío.</span>
        </div>

        <div class="form-group mb-3">
            <label>Añadir imagen</label>
            <div class="d-flex justify-content-between">
                <div class="image-upload">
                    <label for="imagen" id="image-label">
                        <span>+</span>
                    </label>
                    <input type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImage(event, 'imagen-preview')" onblur="validateImages()">
                    <img id="imagen-preview" src="" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                </div>
            </div>
        </div>
        @if ($errors->any())
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-circle"></i> Se han producido los siguientes errores:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <button type="submit" class="btn color w-100">Finalizar trabajo</button>
    </form>
</div>

@endsection
<script src="{{ asset('js/valoraciones.js') }}"></script>
</body>
</html>
