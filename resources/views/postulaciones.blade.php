@extends('layouts.app')

@section('title', 'Mis Postulaciones')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/postulaciones.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/filtro_postulaciones.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid pt-3 pb-5 postulaciones-page">
        <div class="buscador-container" data-aos="fade-up" data-aos-duration="1000">
            <div class="simple-search">
                <div class="search-box" data-aos="fade-right" data-aos-delay="300">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="inputBusqueda" placeholder="Buscar por título o descripción...">
                </div>
                
                <div class="state-filter-box" data-aos="fade-right" data-aos-delay="400">
                    <i class="fas fa-filter filter-icon"></i>
                    <select id="filtroPostulaciones" class="filter-select">
                        <option value="" disabled selected>Elegir estado</option>
                        <option value="todos">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aceptada">Aceptada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>

                <div class="category-box" data-aos="fade-right" data-aos-delay="500">
                    <div class="category-dropdown">
                        <div class="dropdown-header" id="dropdownHeaderPostulaciones"><i class="fas fa-tags"></i> Categorías</div>
                        <div class="dropdown-options" id="dropdownOptionsPostulaciones">
                            @if(isset($categorias) && count($categorias) > 0)
                                @foreach($categorias as $categoria)
                                    <label class="dropdown-option">
                                        <input type="checkbox" class="categoria-checkbox postulacion-categoria-checkbox" value="{{ $categoria->id }}">
                                        {{ $categoria->nombre }}
                                    </label>
                                @endforeach
                            @else
                                <div class="no-categories">No hay categorías disponibles</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="items-per-page-box" data-aos="fade-right" data-aos-delay="600">
                    <i class="fas fa-list-ol filter-icon"></i>
                    <select id="itemsPerPage" class="filter-select">
                        <option value="" disabled selected>Elementos por página</option>
                        <option value="4">4 elementos</option>
                        <option value="8">8 elementos</option>
                        <option value="12">12 elementos</option>
                        <option value="16">16 elementos</option>
                    </select>
                </div>
                
                <button id="clearFiltersPostulaciones" class="clear-btn" data-aos="fade-left" data-aos-delay="600">
                    <i class="fas fa-times"></i> Borrar filtros
                </button>
            </div>
        </div>
        @if ($postulaciones->count() > 0)
            <div class="trabajos-grid">
                @foreach ($postulaciones as $postulacion)
                @php
                    $estado = $postulacion->estado_id;
                    $estadoTexto = $estado == 9 ? 'pendiente' : ($estado == 10 ? 'aceptada' : ($estado == 11 ? 'rechazada' : 'otros'));
                @endphp
                <div class="trabajo-item" 
                    data-estado="{{ $estadoTexto }}"
                    data-categorias="{{ $postulacion->trabajo->categoriastipotrabajo->pluck('id')->implode(',') }}"
                    data-aos="fade-up">
                    <div class="card">
                        <div class="card-img-container">
                            <div class="image-wrapper">
                                @if($postulacion->trabajo->imagenes->isNotEmpty())
                                    <img src="{{ asset('img/trabajos/' . $postulacion->trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $postulacion->trabajo->titulo }}"/>
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen"/>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <h2 class="card-title">{{ $postulacion->trabajo->titulo }}</h2>
                            <p class="card-text">{{ Str::limit($postulacion->trabajo->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {{ $postulacion->trabajo->precio }} €</p>
                            <p class="card-text"><strong>Estado:</strong> {{ $postulacion->estado->nombre }}</p>
                        </div>
                        <div class="card-actions">
                            <div class="action-buttons">
                                <a href="{{ route('trabajos.detalle', $postulacion->trabajo->id) }}" class="action-btn">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No tienes postulaciones activas.
            </div>
        @endif
        
        <!-- Contenedor para el paginador -->
        <div id="paginadorContainer" class="paginador-container my-4" data-aos="fade-up">
            <!-- Aquí se renderizará el paginador dinámicamente -->
        </div>
        
    </div>
@endsection

<script src="{{ asset('js/filtro_postulaciones.js') }}"></script>