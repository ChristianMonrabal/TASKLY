<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Work</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/crear_trabajo.css') }}">
    <script src="{{ asset('js/crear_trabajo.js') }}"></script>
</head>
<body>
    <div class="container mt-2">
        <h1 class="text-center">Crear Trabajo</h1>
        <form action="{{ route('trabajos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título del trabajo">
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>

            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="2" placeholder="Descripción del trabajo"></textarea>
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>

            <div class="form-group mb-3">
                <label for="precio">Precio</label>
                <div class="input-group">
                    <input type="number" name="precio" id="precio" class="form-control no-red-border" placeholder="Precio">
                    <span class="input-group-text">€</span>
                </div>
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>
            
            <div class="form-group mb-3">
                <label for="tags">Tags</label>
                <input type="text" name="tags" id="tags" class="form-control" placeholder="Ejemplo: diseño, desarrollo, marketing">
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>

            <div class="form-group mb-3">
                <label for="direccion">Dirección del trabajo</label>
                <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ubicación del trabajo">
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>

            <div class="form-group mb-3">
                <label>Añadir imágenes</label>
                <div class="d-flex justify-content-between">
                    <!-- Caja 1 -->
                    <div class="image-upload">
                        <label for="imagen1">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen1" accept="image/*" onchange="previewImage(event, 'imagen1-preview')" onblur="validateImages()">
                        <img id="imagen1-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                    <!-- Caja 2 -->
                    <div class="image-upload">
                        <label for="imagen2">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen2" accept="image/*" onchange="previewImage(event, 'imagen2-preview')" onblur="validateImages()">
                        <img id="imagen2-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                    <!-- Caja 3 -->
                    <div class="image-upload">
                        <label for="imagen3">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen3" accept="image/*" onchange="previewImage(event, 'imagen3-preview')" onblur="validateImages()">
                        <img id="imagen3-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                    <!-- Caja 4 -->
                    <div class="image-upload">
                        <label for="imagen4">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen4" accept="image/*" onchange="previewImage(event, 'imagen4-preview')" onblur="validateImages()">
                        <img id="imagen4-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                    <!-- Caja 5 -->
                    <div class="image-upload">
                        <label for="imagen5">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes[]" id="imagen5" accept="image/*" onchange="previewImage(event, 'imagen5-preview')" onblur="validateImages()">
                        <img id="imagen5-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                    </div>
                </div>
                <span id="image-error" class="error-message" style="color: red; display: none;">Debes añadir al menos una imagen.</span>
            </div>
            
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Crear</button>
            </div>
        </form>
    </div>

    <!-- Modal para mostrar la imagen en grande -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Cambiado a modal-lg -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Vista previa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="#" alt="Imagen en grande" class="img-fluid">
                    <!-- Botón para modificar la imagen -->
                    <div class="mt-2 d-flex justify-content-center">
                        <button type="button" class="btn btn-warning" id="modifyImageButton" style="margin-right: 5px;" onclick="modifyImage()">Modificar</button>
                        <button type="button" class="btn btn-danger" id="deleteImageButton" style="margin-left: 5px;" onclick="deleteImage()">Borrar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
