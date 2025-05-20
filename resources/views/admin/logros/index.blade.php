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
  <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
    <i class="fa fa-plus"></i> Crear Logro
  </button>

  <table class="table table-bordered table-logros">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Descuento (%)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="logros-container"></tbody>
  </table>
  <nav aria-label="Paginación">
    <ul id="logros-pagination" class="pagination justify-content-center"></ul>
  </nav>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear Logro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="createLogroErrors" class="alert alert-danger d-none"><ul></ul></div>
        <form id="createLogroForm">
          @csrf
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
            <label for="createDescuento" class="form-label">Descuento (%)</label>
            <input type="number" id="createDescuento" name="descuento" class="form-control">
            <div id="errorCreateDescuento" class="error-message"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="submitCreateLogro()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Logro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="editLogroErrors" class="alert alert-danger d-none"><ul></ul></div>
        <form id="editLogroForm">
          @csrf
          <input type="hidden" id="editLogroId" name="logro_id">
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
            <label for="editDescuento" class="form-label">Descuento (%)</label>
            <input type="number" id="editDescuento" name="descuento" class="form-control">
            <div id="errorEditDescuento" class="error-message"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="submitEditLogro()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-logros.js') }}"></script>
@endsection
