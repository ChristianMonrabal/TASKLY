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
            <span class="error-message"></span>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="2" placeholder="Descripción del trabajo"></textarea>
            <span class="error-message"></span>
        </div>

        <div class="form-group mb-3">
            <label for="precio">Precio</label>
            <div class="input-group">
                <input type="number" name="precio" id="precio" class="form-control no-red-border" placeholder="Precio (€)">
            </div>
            <span class="error-message"></span>
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
            <span class="error-message"></span>
        </div>

        <div class="form-group mb-3">
            <label for="direccion">Codigo postal del trabajo</label>
            <input type="number" name="direccion" id="direccion" class="form-control" placeholder="Ubicación del trabajo">
            <span class="error-message"></span>
        </div>

        <div class="form-group mb-3">
            <label>Añadir imágenes</label>
            <div class="d-flex justify-content-between">
                @for ($i = 1; $i <= 5; $i++)
                <div class="image-upload">
                    <label for="imagen{{ $i }}">
                        <span>+</span>
                    </label>
                    <input type="file" name="imagenes[]" id="imagen{{ $i }}" accept="image/*" onchange="previewImage(event, 'imagen{{ $i }}-preview')">
                    <img id="imagen{{ $i }}-preview" src="#" alt="Vista previa" style="display: none;" onclick="showImageInModal(this)">
                </div>
                @endfor
            </div>
            <span id="image-error" class="error-message" style="color: red; display: none;">Debes añadir al menos una imagen.</span>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <br>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-success">Crear</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/crear_trabajo.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
@endsection
