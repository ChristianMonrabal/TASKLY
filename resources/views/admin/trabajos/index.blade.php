@extends('Admin.layouts.app')

@section('title', 'Gestión de Trabajos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')

    <div class="outer-border">
        <h3>GESTIÓN DE TRABAJOS</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- Sección de Filtros -->
        <div class="mb-3">
            <input type="text" id="filterCliente" placeholder="Cliente" class="form-control d-inline-block" style="width: 45%;" oninput="filterTrabajos()">
            <select id="filterEstado" class="form-control d-inline-block" style="width: 45%;" onchange="filterTrabajos()">
                <option value="">Seleccione un Estado</option>
            </select>
        </div>
        <a href="{{ route('admin.trabajos.completados') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-history"></i> Historial de trabajos completados
        </a>
        <table class="table table-bordered table-trabajos">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Dirección</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="trabajos-container">
                <!-- Se llenará dinámicamente mediante JS -->
            </tbody>
        </table>
    </div>

    <!-- Modal: Editar Trabajo -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="editModalLabel">Editar Trabajo</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
               <form id="editTrabajoForm" enctype="multipart/form-data">
                  <input type="hidden" id="editTrabajoId">
                  <div class="mb-3">
                      <label for="editTitulo" class="form-label">Título</label>
                      <input type="text" name="titulo" id="editTitulo" class="form-control" onblur="validateNonEmpty('editTitulo','El campo Título es obligatorio')">
                      <div id="errorEditTitulo" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editDescripcion" class="form-label">Descripción</label>
                      <textarea name="descripcion" id="editDescripcion" class="form-control" rows="3" onblur="validateNonEmpty('editDescripcion','El campo Descripción es obligatorio')"></textarea>
                      <div id="errorEditDescripcion" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editPrecio" class="form-label">Precio</label>
                      <input type="text" name="precio" id="editPrecio" class="form-control" onblur="validateNonEmpty('editPrecio','El campo Precio es obligatorio')">
                      <div id="errorEditPrecio" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editDireccion" class="form-label">Dirección</label>
                      <input type="text" name="direccion" id="editDireccion" class="form-control" onblur="validateNonEmpty('editDireccion','El campo Dirección es obligatorio')">
                      <div id="errorEditDireccion" class="error-message"></div>
                  </div>
                  <!-- Campo para mostrar el Cliente; este campo NO se puede editar -->
                  <div class="mb-3">
                      <label for="editCliente" class="form-label">Cliente</label>
                      <input type="text" name="cliente" id="editCliente" class="form-control" disabled>
                  </div>
                  <div class="mb-3">
                      <label for="editEstadoId" class="form-label">Estado</label>
                      <select name="estado_id" id="editEstadoId" class="form-control" onblur="validateNonEmpty('editEstadoId','Seleccione un estado')">
                          <option value="">Seleccione un estado</option>
                          <!-- Se llenará mediante JS con loadEstados() -->
                      </select>
                      <div id="errorEditEstadoId" class="error-message"></div>
                  </div>
               </form>
               <div id="editErrors" class="alert alert-danger d-none">
                   <ul></ul>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
               <button type="button" class="btn btn-primary" onclick="submitEditTrabajo()">Actualizar</button>
            </div>
         </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-trabajos.js') }}"></script>
@endsection
