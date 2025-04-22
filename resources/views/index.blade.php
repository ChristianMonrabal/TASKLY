@extends('layouts.app')

@section('title', 'TASKLY - Trabajos Disponibles')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"/>
@endsection

@section('content')
  <h1 class="text-center mb-4">🎯 Trabajos Disponibles</h1>
  
  <section class="nuevos-trabajos">
    <h2 class="seccion-titulo">🆕 Nuevos Trabajos</h2>
    <div class="scroll-wrapper">
      <button class="scroll-btn" id="btn-left-nuevos"><i class="fas fa-chevron-left"></i></button>
      <div class="scroll-container" id="cardScrollNuevos">
        <div class="loading">Cargando nuevos trabajos...</div>
      </div>
      <button class="scroll-btn" id="btn-right-nuevos"><i class="fas fa-chevron-right"></i></button>
    </div>
  </section>

  <div class="buscador-container">
    <div class="simple-search">
      <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="inputBusqueda" placeholder="Buscar por título o descripción..." value="{{ $busqueda ?? '' }}">
      </div>
      
      <div class="category-box">
        <select id="selectCategoria" class="category-select">
          <option value="todas">Todas las categorías</option>
          @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
          @endforeach
        </select>
      </div>
      
      <button id="clearFilters" class="clear-btn">
        <i class="fas fa-times"></i> Borrar filtros
      </button>
    </div>
  </div>

  <section class="todos-trabajos">
    <h2 class="seccion-titulo">📋 Todos los Trabajos</h2>
    <div class="grid-trabajos" id="gridTrabajos">
      <div class="loading">Cargando trabajos...</div>
    </div>
  </section>
@endsection

@section('scripts')
  <!-- Cargar primero el módulo principal que inicializa las variables globales -->
  <script src="{{ asset('js/index-load/main.js') }}"></script>
  <script src="{{ asset('js/index-load/utilidades.js') }}"></script>
  
  <!-- Cargar el nuevo módulo de botones de scroll -->
  <script src="{{ asset('js/index-load/scroll-buttons.js') }}"></script>

  <script src="{{ asset('js/index-load/nuevos-trabajos.js') }}"></script>
  <script src="{{ asset('js/index-load/todos-trabajos.js') }}"></script>
  
  <!-- Script para el filtrado simple (nombre y categoría) -->
  <script src="{{ asset('js/simple-filters.js') }}"></script>
  
  <!-- Script para iniciar las funciones de carga -->
  <script>
    // Ejecutar cuando todo esté cargado
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM completamente cargado, iniciando funciones...');
      
      // Iniciar la carga de nuevos trabajos
      if (typeof cargarNuevosTrabajos === 'function') {
        cargarNuevosTrabajos();
      }
      
      // Inicializar los botones de scroll si existen
      if (typeof initScrollButtons === 'function') {
        initScrollButtons();
      }
    });
  </script>
@endsection
