@extends('layouts.app')

@section('title', 'TASKLY - Trabajos Disponibles')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"/>
@endsection

@section('content')
  @if(Auth::check() && count($trabajosCercanos) > 0)
    <section class="trabajos-cercanos" data-aos="fade-up">
        <h2 class="seccion-titulo" data-aos="fade-right" data-aos-delay="200"><i class="fas fa-globe" style="color: #4A90E2;"></i> Trabajos cercanos a ti ({{ Auth::user()->codigo_postal }})</h2>
        <div class="scroll-wrapper">
            <button class="scroll-btn" id="btn-left-cercanos"><i class="fas fa-chevron-left"></i></button>
            <div class="scroll-container" id="cardScrollCercanos">
                @foreach($trabajosCercanos as $trabajo)
                    <div class="card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" onclick="window.location.href='{{ route('trabajos.detalle', $trabajo->id) }}'">
                        <div class="card-img">
                            <img src="{{ $trabajo->imagenes->isNotEmpty() ? asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) : asset('img/trabajos/default.png') }}" alt="{{ $trabajo->titulo }}">
                        </div>
                        <div class="card-content">
                            <h2>{{ $trabajo->titulo }}</h2>
                            <p>{{ Str::limit($trabajo->descripcion, 100) }}</p>
                            <div class="precio">€{{ $trabajo->precio }}</div>
                            <div class="categorias">
                                @if ($trabajo->categoriastipotrabajo->isNotEmpty())
                                    <button class="categoria-btn">
                                        {{ $trabajo->categoriastipotrabajo->take(2)->pluck('nombre')->join(', ') }}
                                    </button>
                                @else
                                    <button class="categoria-btn">Sin categoría</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="scroll-btn" id="btn-right-cercanos"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
  @endif

  <section class="nuevos-trabajos" data-aos="fade-up" data-aos-anchor-placement="top-center">
    <h2 class="seccion-titulo" data-aos="fade-right" data-aos-delay="100">🆕 Nuevos trabajos</h2>
    <div class="scroll-wrapper">
      <button class="scroll-btn" id="btn-left-nuevos"><i class="fas fa-chevron-left"></i></button>
      <div class="scroll-container" id="cardScrollNuevos">
        <div class="loading">Cargando nuevos trabajos...</div>
      </div>
      <button class="scroll-btn" id="btn-right-nuevos"><i class="fas fa-chevron-right"></i></button>
    </div>
  </section>

  <div class="buscador-container" data-aos="fade-up" data-aos-duration="1000">
    <div class="simple-search">
      <div class="search-box" data-aos="fade-right" data-aos-delay="300">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="inputBusqueda" placeholder="Buscar por título o descripción..." value="{{ $busqueda ?? '' }}">
      </div>
      
      <div class="postal-box" data-aos="fade-right" data-aos-delay="400">
        <i class="fas fa-map-marker-alt postal-icon"></i>
        <input type="text" id="inputCodigoPostal" placeholder="Código postal">
      </div>
      
      <div class="category-box" data-aos="fade-right" data-aos-delay="500">
        <div class="category-dropdown">
            <div class="dropdown-header" id="dropdownHeader">Selecciona categorías</div>
            <div class="dropdown-options" id="dropdownOptions">
                @foreach($categorias as $categoria)
                    <label class="dropdown-option">
                        <input type="checkbox" class="categoria-checkbox" value="{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </label>
                @endforeach
            </div>
        </div>
      </div>
      
      <button id="clearFilters" class="clear-btn" data-aos="fade-left" data-aos-delay="600">
        <i class="fas fa-times"></i> Borrar filtros
      </button>
    </div>
  </div>

  <section class="todos-trabajos">
    <div class="seccion-header">
      <h2 class="seccion-titulo">📋 Todos los trabajos</h2>
      <div id="perPageContainer" class="per-page-container"></div>
    </div>
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
