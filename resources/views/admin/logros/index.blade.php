@extends('Admin.layouts.app')

@section('title', 'Gestión de Logros')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')
<div class="outer-border">
  <h3>GESTIÓN DE LOGROS</h3>

  <div class="mb-3">
    <input type="text" id="filterNombre" class="form-control w-50" placeholder="Filtrar por nombre">
  </div>

  <button type="button" class="btn btn-success mb-3" onclick="openCreateModal()">
    <i class="fa fa-plus"></i> Crear Logro
  </button>

  <table class="table table-bordered table-logros">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Foto</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="logros-container"></tbody>
  </table>

  <nav aria-label="Paginación">
    <ul id="logros-pagination" class="pagination justify-content-center"></ul>
  </nav>
</div>

{{-- Crear Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createLogroForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Logro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="createLogroErrors" class="alert alert-danger d-none"><ul></ul></div>

          <div class="mb-3">
            <label for="createNombre" class="form-label">Nombre</label>
            <input type="text" id="createNombre" name="nombre" class="form-control">
            <div id="errorCreateNombre" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createDescripcion" class="form-label">Descripción</label>
            <textarea id="createDescripcion" name="descripcion" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label for="createFotoLogro" class="form-label">Foto del logro</label>
            <input type="file" id="createFotoLogro" name="foto_logro" class="form-control" accept="image/*">
            <div id="errorCreateFotoLogro" class="error-message"></div>
            <img id="previewCreate" style="max-width:100%; margin-top:.5rem; display:none;">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="submitCreate()">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Editar Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editLogroForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="editLogroId" name="logro_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Logro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="editLogroErrors" class="alert alert-danger d-none"><ul></ul></div>

          <div class="mb-3">
            <label for="editNombre" class="form-label">Nombre</label>
            <input type="text" id="editNombre" name="nombre" class="form-control">
            <div id="errorEditNombre" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="editDescripcion" class="form-label">Descripción</label>
            <textarea id="editDescripcion" name="descripcion" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label for="editFotoLogro" class="form-label">Cambiar foto</label>
            <input type="file" id="editFotoLogro" name="foto_logro" class="form-control" accept="image/*">
            <div id="errorEditFotoLogro" class="error-message"></div>
            {{-- Vista previa de la foto actual --}}
            <img id="previewEdit" style="max-width:100%; margin-top:.5rem; display:none;">

            <div class="form-check mt-3">
              <input type="hidden" name="remove_foto_logro" value="0">
              <input class="form-check-input" type="checkbox" id="removeFotoLogro" name="remove_foto_logro" value="1">
              <label class="form-check-label" for="removeFotoLogro">
                Eliminar foto actual
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="submitEdit()">Guardar cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-logros.js') }}"></script>
@endsection
