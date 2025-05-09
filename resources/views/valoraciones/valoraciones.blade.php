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
                <img src="{{ asset('img/profile_images/perfil_default.png') }}" class="current-photo">
            </div>
        </div>

        <div class="valoracion-container">
            <h6>Juan Pérez</h6>
            <h2>Montaje de muebles</h2>

            <div class="rating-stars" id="stars">
                <i class="fas fa-star" data-value="1"></i>
                <i class="fas fa-star" data-value="2"></i>
                <i class="fas fa-star" data-value="3"></i>
                <i class="fas fa-star" data-value="4"></i>
                <i class="fas fa-star" data-value="5"></i>
            </div>
            <span id="star-error" class="error-message">Por favor, selecciona una calificación.</span>

            <div class="form-group">
                <label for="comentario">Añade un comentario:</label>
                <textarea id="comentario" rows="4" placeholder="Escribe tu valoración..."></textarea>
                <span id="comment-error" class="error-message">El comentario no puede estar vacío.</span>
            </div>

            <div class="form-group mb-3">
                <label>Añadir imagen</label>
                <div class="d-flex justify-content-between">
                    <div class="image-upload">
                        <label for="imagen1" id="image-label">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen1" accept="image/*" onchange="previewImage(event, 'imagen1-preview')" onblur="validateImages()">
                        <img id="imagen1-preview" src="" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                </div>
                <span id="image-error" class="error-message">Debes añadir al menos una imagen.</span>
            </div>
            <button class="btn color w-100">Finalizar trabajo</button>
        </div>
    </div>
    @endsection
    <script src="{{ asset('js/valoraciones.js') }}"></script>
</body>
</html>
