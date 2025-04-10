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
        <table class="table table-bordered">
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

    <!-- Modal para CREAR Categoría -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="createModalLabel">Crear Categoría</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
             </div>
             <div class="modal-body">
                 <form id="createCategoriaForm">
                     <div class="mb-3">
                         <label for="createNombre" class="form-label">Nombre</label>
                         <input type="text" name="nombre" id="createNombre" class="form-control" placeholder="Ingrese el nombre de la categoría">
                         <div id="errorCreateNombre" class="error-message"></div>
                     </div>
                 </form>
                 <div id="createCategoriaErrors" class="alert alert-danger d-none">
                     <ul></ul>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-success" onclick="submitCreateCategoria()">Crear</button>
             </div>
         </div>
      </div>
    </div>

    <!-- Modal para EDITAR Categoría -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="editModalLabel">Editar Categoría</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
             </div>
             <div class="modal-body">
                 <form id="editCategoriaForm">
                     <input type="hidden" id="editCategoriaId">
                     <div class="mb-3">
                         <label for="editNombre" class="form-label">Nombre</label>
                         <input type="text" name="nombre" id="editNombre" class="form-control" onblur="validateNonEmpty('editNombre','El campo Nombre es obligatorio')">
                         <div id="errorEditNombre" class="error-message"></div>
                     </div>
                 </form>
                 <div id="editCategoriaErrors" class="alert alert-danger d-none">
                     <ul></ul>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                 <button type="button" class="btn btn-primary" onclick="submitEditCategoria()">Actualizar</button>
             </div>
         </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-categorias.js') }}"></script>
@endsection
