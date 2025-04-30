@extends('layouts.app')

@section('title', 'Crear Trabajo')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/crear_trabajo.css') }}"/>
@endsection

@section('content')
    <div class="container mt-2">
        <h1 class="text-center">Crear trabajo</h1>
        <form action="{{ route('trabajos.store') }}" id="mi-formulario" method="POST" enctype="multipart/form-data">
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
                    <input type="number" name="precio" id="precio" class="form-control no-red-border" placeholder="Precio (€)">
                </div>
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>

            <div class="form-group mb-3">
                <label for="alta_responsabilidad">¿Trabajo de alta responsabilidad?</label>
                <select name="alta_responsabilidad" id="alta_responsabilidad" class="form-control">
                    <option value="No" selected>No</option>
                    <option value="Sí">Sí</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="categorias">Tags:</label>
                <div class="tag-scroll-box" id="tag-container">
                    @foreach($categorias as $categoria)
                        <div class="tag-option" data-id="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="categorias" id="categorias" value="">
                <small class="form-text text-muted">Haz clic en las categorias para seleccionarlas.</small>
                <span class="error-message" style="color: red; font-size: 0.9rem; display: none;"></span>
            </div>
                                    
            <div class="form-group mb-3">
                <label for="direccion">Codigo postal del trabajo</label>
                <input type="number" name="direccion" id="direccion" class="form-control" placeholder="Ubicación del trabajo">
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
                        <img id="imagen1-preview" src="#" alt="Vista previa" style="display: none;" onclick="handleImageClick(this, 'imagen1')">
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
            <br>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Crear</button>
            </div>
        </form>        
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/crear_trabajo.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
@endsection