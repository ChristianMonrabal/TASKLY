@extends('layouts.app')

@section('title', 'Editar Trabajo - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/editar_trabajo.css') }}"/>
@endsection

@section('script')
<script src="{{ asset('js/editar_trabajo.js') }}"></script>
@endsection

@section('content')
<div class="container py-5">
    <form action="{{ route('trabajos.actualizar', $trabajo->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <input type="hidden" name="trabajo_id" value="{{ $trabajo->id }}">

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $trabajo->titulo) }}">
            <div class="error-message" id="error-titulo">{{ $errors->first('titulo') }}</div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $trabajo->descripcion) }}</textarea>
            <div class="error-message" id="error-descripcion">{{ $errors->first('descripcion') }}</div>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio', $trabajo->precio) }}">
            <div class="error-message" id="error-precio">{{ $errors->first('precio') }}</div>
        </div>

        <div class="mb-3">
            <label for="alta_responsabilidad" class="form-label">¿Trabajo de alta responsabilidad?</label>
            <select name="alta_responsabilidad" id="alta_responsabilidad" class="form-control">
                <option value="No" {{ old('alta_responsabilidad', $trabajo->alta_responsabilidad) == 'No' ? 'selected' : '' }}>No</option>
                <option value="Sí" {{ old('alta_responsabilidad', $trabajo->alta_responsabilidad) == 'Sí' ? 'selected' : '' }}>Sí</option>
            </select>
            <div class="error-message" id="error-alta_responsabilidad">{{ $errors->first('alta_responsabilidad') }}</div>
        </div>

        <div class="mb-3">
            <label for="categorias" class="form-label">Tags</label>
            <small class="form-text text-muted">Haz clic en las categorías para seleccionarlas.</small>
            <div class="tag-scroll-box" id="tag-container">
                @foreach($categorias as $categoria)
                <div class="tag-option {{ in_array($categoria->id, is_array(old('categorias', $trabajo->categorias->pluck('id')->toArray())) ? old('categorias', $trabajo->categorias->pluck('id')->toArray()) : []) ? 'selected' : '' }}" 
                    data-id="{{ $categoria->id }}">
                    {{ $categoria->nombre }}
                </div>
                @endforeach
            </div>
            
            @php
                $categoriasSeleccionadas = old('categorias', $trabajo->categorias->pluck('id')->toArray());
                $categoriasString = is_array($categoriasSeleccionadas) ? implode(',', $categoriasSeleccionadas) : $categoriasSeleccionadas;
            @endphp
            <input type="hidden" name="categorias" id="categorias" value="{{ $categoriasString }}">

            <div class="error-message">{{ $errors->first('categorias') }}</div>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Código Postal del trabajo</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $trabajo->direccion) }}">
            <div class="error-message" id="error-direccion">{{ $errors->first('direccion') }}</div>
        </div>

        <div class="mb-3">
            @php $imagenesActuales = $trabajo->imagenes; @endphp
            <label for="imagenes" class="form-label">Imágenes</label>
            <div class="d-flex justify-content-between flex-wrap gap-2">
                @for ($i = 0; $i < 5; $i++)
                    @php
                        $imagen = $imagenesActuales[$i] ?? null;
                        $previewId = 'imagen' . ($i + 1) . '-preview';
                        $inputId = 'imagen' . ($i + 1);
                        $previewSrc = $imagen ? asset('img/trabajos/' . $imagen->ruta_imagen) : '#';
                        $display = $imagen ? 'block' : 'none';
                    @endphp

                    <div class="image-upload">
                        <label for="{{ $inputId }}">
                            <span>+</span>
                        </label>
                        <input type="file" name="imagenes_nuevas[]" id="{{ $inputId }}" data-index="{{ $i }}" accept="image/*">
                        <img id="{{ $previewId }}" src="{{ $previewSrc }}" alt="Vista previa"
                            style="display: {{ $display }};" onclick="showImageInModal(this)">
                        <input type="hidden" name="imagenes_anteriores[]" value="{{ $imagen->ruta_imagen ?? '' }}" class="imagen-anterior" data-index="{{ $i }}">
                    </div>
                @endfor
            </div>
        </div>

        <br>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary-guardar">Guardar Cambios</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary-cancelar">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/editar_trabajo.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
@endsection
