@extends('Admin.layouts.app')

@section('title', 'Trabajos Completados')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')
<div class="outer-border">
  <h3>TRABAJOS COMPLETADOS</h3>

  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" id="filterCliente" class="form-control" placeholder="Filtrar por Cliente">
    </div>
    <div class="col-md-4">
      <input type="date" id="filterFecha" class="form-control">
    </div>
  </div>

  <table class="table table-bordered table-completados">
    <thead>
      <tr>
        <th>Título</th>
        <th>Cliente</th>
        <th>Estado</th>
        <th>Última actualización</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="completados-container">
        <!-- Se llenará dinámicamente mediante JS -->
    </tbody>
  </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-trabajos-completados.js') }}"></script>
@endsection
