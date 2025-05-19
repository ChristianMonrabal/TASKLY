@extends('Admin.layouts.app')

@section('content')
<div class="outer-border">
  <h3>Administrar Reportes</h3>

  {{-- Filtros --}}
  <div class="mb-3">
    <input type="text" id="filterMotivo" placeholder="Motivo" class="form-control d-inline-block" style="width:30%;">
    <select id="filterGravedad" class="form-select d-inline-block" style="width:20%;">
      <option value="">Todas Gravedades</option>
      @foreach($niveles as $n)
        <option value="{{ $n->id }}">{{ $n->nombre }}</option>
      @endforeach
    </select>
    <select id="filterEstado" class="form-select d-inline-block" style="width:20%;">
      <option value="">Todos Estados</option>
      @foreach($estados as $e)
        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
      @endforeach
    </select>
  </div>

  <table class="table table-striped table-reportes">
    <thead>
      <tr>
        <th>Reportado</th>
        <th>Reporta</th>
        <th>Motivo</th>
        <th>Gravedad</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="reportes-container"></tbody>
  </table>

  <nav aria-label="Paginación">
    <ul id="reportes-pagination" class="pagination justify-content-center"></ul>
  </nav>
</div>

{{-- Modal para cambiar estado --}}
<div class="modal fade" id="editReporteModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Cambiar Estado</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <div id="editReporteErrors" class="alert alert-danger d-none"><ul></ul></div>
      <input type="hidden" id="editReporteId">
      <div class="mb-3">
        <label for="editEstado" class="form-label">Estado</label>
        <select id="editEstado" class="form-select"></select>
        <div id="errorEditEstado" class="text-danger"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button class="btn btn-primary" onclick="submitEditReporte()">Guardar</button>
    </div>
  </div></div>
</div>
{{-- Modal para toggle activo --}}
<div class="modal fade" id="toggleUserModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Confirmar Acción</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" id="toggleUserModalBody"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button class="btn btn-primary" id="toggleUserModalConfirmBtn">Confirmar</button>
    </div>
  </div></div>
</div>

{{-- Modal para enviar aviso --}}
<div class="modal fade" id="notifyUserModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Enviar Aviso</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <textarea id="notifyMessage" class="form-control" rows="4" placeholder="Tu mensaje…"></textarea>
      <div id="notifyError" class="text-danger mt-2"></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button class="btn btn-primary" id="notifyUserModalConfirmBtn">Enviar</button>
    </div>
  </div></div>
</div>
@endsection

@section('scripts')
<script>
  // Carga de opciones de estado en el modal
  const estadosList = @json($estados->map(fn($e)=>['id'=>$e->id,'nombre'=>$e->nombre]));
  document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('editEstado');
    estadosList.forEach(e => sel.add(new Option(e.nombre, e.id)));
  });
</script>
<script src="{{ asset('js/admin-reportes.js') }}"></script>
@endsection
