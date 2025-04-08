@extends('layouts.app')

@section('title', 'TASKLY - Trabajos Disponibles')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"/>
@endsection

@section('content')
  <h1 class="text-center mb-4">🎯 Trabajos Disponibles</h1>
  
  <div class="buscador-container">
    <div class="buscador">
      <form id="formularioBusqueda">
        <div class="search-input-container">
          <i class="fas fa-search search-icon"></i>
          <input type="text" name="busqueda" id="inputBusqueda" placeholder="Buscar por título, categoría o precio..." value="{{ $busqueda ?? '' }}">
          <button type="submit" class="btn-buscar">Buscar</button>
        </div>
      </form>
    </div>
    
    <div class="filtros-avanzados">
      <div class="filtro-dropdown">
        <button class="filtro-dropdown-btn">
          <i class="fas fa-tag"></i> Categoría
          <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="filtro-dropdown-content">
          <div class="filtro-option">
            <input type="checkbox" id="cat-todos" name="categoria" value="todos" checked>
            <label for="cat-todos">Todos</label>
          </div>
          <div class="categoria-grid">
            @foreach($categorias as $categoria)
            <div class="filtro-option">
              <input type="checkbox" id="cat-{{ $categoria->id }}" name="categoria" value="{{ $categoria->id }}">
              <label for="cat-{{ $categoria->id }}">{{ $categoria->nombre }}</label>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      
      <div class="filtro-dropdown">
        <button class="filtro-dropdown-btn">
          <i class="fas fa-euro-sign"></i> Precio
          <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="filtro-dropdown-content">
          <div class="filtro-option">
            <input type="radio" id="precio-todos" name="precio" value="todos" checked>
            <label for="precio-todos">Cualquier precio</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="precio-bajo" name="precio" value="0-50">
            <label for="precio-bajo">Hasta 50€</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="precio-medio" name="precio" value="50-200">
            <label for="precio-medio">50€ - 200€</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="precio-alto" name="precio" value="200-500">
            <label for="precio-alto">200€ - 500€</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="precio-muy-alto" name="precio" value="500-">
            <label for="precio-muy-alto">Más de 500€</label>
          </div>
          <div class="filtro-range">
            <label>Precio personalizado:</label>
            <div class="range-inputs">
              <input type="number" id="precio-min" min="0" placeholder="Min €">
              <span>-</span>
              <input type="number" id="precio-max" min="0" placeholder="Max €">
            </div>
            <button type="button" id="aplicar-precio" class="aplicar-filtro-btn">Aplicar</button>
          </div>
        </div>
      </div>
      
      <div class="filtro-dropdown">
        <button class="filtro-dropdown-btn">
          <i class="fas fa-map-marker-alt"></i> Cercanía
          <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="filtro-dropdown-content">
          <div class="filtro-option">
            <input type="radio" id="ubicacion-todos" name="ubicacion" value="todos" checked>
            <label for="ubicacion-todos">Cualquier ubicación</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="ubicacion-local" name="ubicacion" value="local">
            <label for="ubicacion-local">En mi ciudad</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="ubicacion-10km" name="ubicacion" value="10">
            <label for="ubicacion-10km">Hasta 10 km</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="ubicacion-50km" name="ubicacion" value="50">
            <label for="ubicacion-50km">Hasta 50 km</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="ubicacion-100km" name="ubicacion" value="100">
            <label for="ubicacion-100km">Hasta 100 km</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="ubicacion-remoto" name="ubicacion" value="remoto">
            <label for="ubicacion-remoto">Trabajo remoto</label>
          </div>
        </div>
      </div>
      
      <div class="filtro-dropdown">
        <button class="filtro-dropdown-btn">
          <i class="fas fa-sort-amount-down"></i> Ordenar por
          <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="filtro-dropdown-content">
          <div class="filtro-option">
            <input type="radio" id="orden-reciente" name="orden" value="reciente" checked>
            <label for="orden-reciente">Más recientes</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="orden-precio-asc" name="orden" value="precio-asc">
            <label for="orden-precio-asc">Precio: menor a mayor</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="orden-precio-desc" name="orden" value="precio-desc">
            <label for="orden-precio-desc">Precio: mayor a menor</label>
          </div>
          <div class="filtro-option">
            <input type="radio" id="orden-valoracion" name="orden" value="valoracion">
            <label for="orden-valoracion">Mejor valorados</label>
          </div>
        </div>
      </div>
      
      <button id="aplicar-filtros" class="aplicar-todos-filtros">
        <i class="fas fa-filter"></i> Aplicar filtros
      </button>
      
      <button id="limpiar-filtros" class="limpiar-filtros">
        <i class="fas fa-times"></i> Limpiar
      </button>
    </div>
  </div>

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
  
  <!-- Cargar el resto de módulos funcionales -->
  <script src="{{ asset('js/index-load/nuevos-trabajos.js') }}"></script>
  <script src="{{ asset('js/index-load/todos-trabajos.js') }}"></script>
  <script src="{{ asset('js/index-load/busqueda-categoria.js') }}"></script>
  <script src="{{ asset('js/index-load/filtros.js') }}"></script>
  
  <!-- Script para iniciar las funciones de carga -->
  <script>
    // Ejecutar cuando todo esté cargado
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM completamente cargado, iniciando funciones...');
      
      // Iniciar la carga de nuevos trabajos
      if (typeof cargarNuevosTrabajos === 'function') {
        cargarNuevosTrabajos();
      }
      
      // Iniciar la carga de todos los trabajos
      if (typeof cargarTodosTrabajos === 'function') {
        cargarTodosTrabajos();
      }
      
      // Inicializar los botones de scroll
      if (typeof inicializarBotonesScroll === 'function') {
        // Dar un pequeño retraso para asegurar que los elementos estén cargados
        setTimeout(function() {
          inicializarBotonesScroll();
        }, 500);
      }
    });
  </script>
@endsection
