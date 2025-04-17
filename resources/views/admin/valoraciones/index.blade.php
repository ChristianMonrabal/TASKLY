@extends('Admin.layouts.app')

@section('title', 'Gestión de Valoraciones')

@section('styles')
    <!-- Puedes reutilizar el mismo CSS que en usuarios para mantener el mismo diseño -->
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')

    <div class="outer-border">
        <h3>GESTIÓN DE VALORACIONES</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- Sección de Filtros -->
        <div class="mb-3">
            <input type="text" id="filterTrabajador" placeholder="Trabajador" class="form-control d-inline-block" style="width: 45%;" oninput="filterValoraciones()">
            <input type="text" id="filterCliente" placeholder="Cliente" class="form-control d-inline-block" style="width: 45%;" oninput="filterValoraciones()">
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Trabajo</th>
                    <th>Trabajador</th>
                    <th>Puntuación</th>
                    <th>Comentario</th>
                    <th>Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="valoraciones-container">
                <!-- Se llenará dinámicamente mediante JS -->
            </tbody>
        </table>
    </div>

    <!-- Modal: Editar Valoración -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="editModalLabel">Editar Valoración</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
               <form id="editValoracionForm" enctype="multipart/form-data">
                  <input type="hidden" id="editValoracionId">
                  <div class="mb-3">
                      <label for="editComentario" class="form-label">Comentario</label>
                      <textarea name="comentario" id="editComentario" class="form-control" rows="3" onblur="validateNonEmpty('editComentario', 'El campo Comentario es obligatorio')"></textarea>
                      <div id="errorEditComentario" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editImgValoracion" class="form-label">Imagen de Valoración</label>
                      <input type="file" name="img_valoracion" id="editImgValoracion" class="form-control">
                      <div id="errorEditImgValoracion" class="error-message"></div>
                      <!-- Contenedor para mostrar imagen actual -->
                      <div id="currentImgContainer" class="mt-2">
                          <img id="currentImgValoracion" src="" alt="Foto Actual" style="max-width: 100%; height: auto; display: none;">
                      </div>
                      <!-- Checkbox para eliminar la imagen actual -->
                      <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" id="removeImgValoracion" name="remove_img_valoracion">
                          <label class="form-check-label" for="removeImgValoracion">
                              Eliminar foto actual
                          </label>
                      </div>
                  </div>
               </form>
               <div id="editValErrors" class="alert alert-danger d-none">
                   <ul></ul>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
               <button type="button" class="btn btn-primary" onclick="submitEditValoracion()">Actualizar</button>
            </div>
         </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-valoraciones.js') }}"></script>
@endsection
