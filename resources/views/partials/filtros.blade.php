{{-- partials/filtros.blade.php - Componente de filtros para trabajos --}}

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
    {{-- Filtro de categorías --}}
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
    
    {{-- Filtro de precio --}}
    <div class="filtro-dropdown">
      <button class="filtro-dropdown-btn">
        <i class="fas fa-euro-sign"></i> Precio
        <i class="fas fa-chevron-down arrow-icon"></i>
      </button>
      <div class="filtro-dropdown-content">
        @foreach($datosFiltro['rangosPrecios'] as $rango)
        <div class="filtro-option">
          <input type="radio" id="precio-{{ $rango['id'] }}" name="precio" value="{{ $rango['id'] }}" {{ $rango['id'] == 'todos' ? 'checked' : '' }}>
          <label for="precio-{{ $rango['id'] }}">{{ $rango['nombre'] }}</label>
        </div>
        @endforeach
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
    
    {{-- Filtro de ubicación --}}
    <div class="filtro-dropdown">
      <button class="filtro-dropdown-btn">
        <i class="fas fa-map-marker-alt"></i> Cercanía
        <i class="fas fa-chevron-down arrow-icon"></i>
      </button>
      <div class="filtro-dropdown-content">
        @foreach($datosFiltro['ubicaciones'] as $ubicacion)
        <div class="filtro-option">
          <input type="radio" id="ubicacion-{{ $ubicacion['id'] }}" name="ubicacion" value="{{ $ubicacion['id'] }}" {{ $ubicacion['id'] == 'todos' ? 'checked' : '' }}>
          <label for="ubicacion-{{ $ubicacion['id'] }}">{{ $ubicacion['nombre'] }}</label>
        </div>
        @endforeach
      </div>
    </div>
    
    {{-- Filtro de ordenamiento --}}
    <div class="filtro-dropdown">
      <button class="filtro-dropdown-btn">
        <i class="fas fa-sort-amount-down"></i> Ordenar por
        <i class="fas fa-chevron-down arrow-icon"></i>
      </button>
      <div class="filtro-dropdown-content">
        @foreach($datosFiltro['ordenamiento'] as $orden)
        <div class="filtro-option">
          <input type="radio" id="orden-{{ $orden['id'] }}" name="orden" value="{{ $orden['id'] }}" {{ $orden['id'] == 'reciente' ? 'checked' : '' }}>
          <label for="orden-{{ $orden['id'] }}">{{ $orden['nombre'] }}</label>
        </div>
        @endforeach
      </div>
    </div>
    
    {{-- Botones de acción --}}
    <button id="aplicar-filtros" class="aplicar-todos-filtros">
      <i class="fas fa-filter"></i> Aplicar filtros
    </button>
    
    <button id="limpiar-filtros" class="limpiar-filtros">
      <i class="fas fa-times"></i> Limpiar
    </button>
  </div>
</div>
