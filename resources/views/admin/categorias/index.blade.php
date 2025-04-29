@extends('Admin.layouts.app')

@section('title', 'Gestión de Categorías')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')
    <div class="outer-border">
        <h3>GESTIÓN DE CATEGORÍAS</h3>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filtros: búsqueda por nombre y ordenamiento -->
        <div class="mb-3">
            <input type="text" id="filterNombre" placeholder="Buscar por nombre" class="form-control d-inline-block" style="width: 70%;" oninput="filterCategorias()">
            <select id="sortOrder" class="form-control d-inline-block" style="width: 28%;" onchange="filterCategorias()">
                <option value="asc">Ascendente</option>
                <option value="desc">Descendente</option>
            </select>
        </div>

        <!-- Botón para abrir el modal de CREAR Categoría -->
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Crear Categoría
        </button>

        <!-- Tabla para mostrar categorías -->
        <table class="table table-bordered table-categorias">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="categorias-container">
                <!-- Se llenará dinámicamente mediante JS -->
            </tbody>
        </table>
    </div>

    <!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
      <form id="createCategoriaForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nueva Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- nombre -->
            <div class="mb-3">
              <label for="createNombre" class="form-label">Nombre</label>
              <input type="text" id="createNombre" name="nombre" class="form-control">
              <div id="errorCreateNombre" class="error-message"></div>
            </div>
            <!-- visible -->
            <div class="mb-3">
              <label for="createVisible" class="form-label">Visible</label>
              <select id="createVisible" name="visible" class="form-select">
                <option value="Sí">Sí</option>
                <option value="No">No</option>
              </select>
            </div>
            <div id="createCategoriaErrors" class="alert alert-danger d-none"><ul></ul></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="submitCreateCategoria()">Crear</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  <!-- EDIT MODAL -->
  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
      <form id="editCategoriaForm">
        <input type="hidden" id="editCategoriaId" name="id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- nombre -->
            <div class="mb-3">
              <label for="editNombre" class="form-label">Nombre</label>
              <input type="text" id="editNombre" name="nombre" class="form-control">
              <div id="errorEditNombre" class="error-message"></div>
            </div>
            <!-- visible -->
            <div class="mb-3">
              <label for="editVisible" class="form-label">Visible</label>
              <select id="editVisible" name="visible" class="form-select">
                <option value="Sí">Sí</option>
                <option value="No">No</option>
              </select>
            </div>
            <div id="editCategoriaErrors" class="alert alert-danger d-none"><ul></ul></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="submitEditCategoria()">Guardar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-categorias.js') }}"></script>
@endsection
