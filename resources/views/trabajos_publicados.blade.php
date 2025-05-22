@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/sweet_alert_calendario.js') }}"></script>
    <script src="{{ asset('js/eliminar_trabajo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/filtro_mis_trabajos.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid pt-3 pb-5" style="position: relative;">
        <div class="buscador-container" data-aos="fade-up" data-aos-duration="1000">
            <div class="simple-search">
                <div class="search-box" data-aos="fade-right" data-aos-delay="300">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="inputBusqueda" placeholder="Buscar por título o descripción..." value="{{ $busqueda ?? '' }}">
                </div>
                
                <div class="state-filter-box" data-aos="fade-right" data-aos-delay="400">
                    <i class="fas fa-filter filter-icon"></i>
                    <select id="filtroTrabajos" class="filter-select">
                        <option value="" disabled selected>Elegir estado</option>
                        <option value="todos">Todos los trabajos</option>
                        <option value="activos">Trabajos activos</option>
                        <option value="finalizados">Trabajos finalizados</option>
                        <option value="cancelados">Trabajos cancelados</option>
                    </select>
                </div>
                
                <div class="category-box" data-aos="fade-right" data-aos-delay="500">
                    <div class="category-dropdown">
                        <div class="dropdown-header" id="dropdownHeader"><i class="fas fa-tags"></i> Categorías</div>
                        <div class="dropdown-options" id="dropdownOptions">
                            @if(isset($categorias) && count($categorias) > 0)
                                @foreach($categorias as $categoria)
                                    <label class="dropdown-option">
                                        <input type="checkbox" class="categoria-checkbox" value="{{ $categoria->id }}">
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
                
                <button id="clearFilters" class="clear-btn" data-aos="fade-left" data-aos-delay="600">
                    <i class="fas fa-times"></i> Borrar filtros
                </button>
            </div>
        </div>
        @if ($trabajos->count() > 0)
            <div class="trabajos-grid">
                @foreach ($trabajos as $trabajo)
                    @php
                        $estado = $trabajo->estado_id;
                    @endphp
                    <div class="trabajo-item" 
                        data-estado="{{ $estado == 3 ? 'finalizados' : (in_array($estado, [1,2]) ? 'activos' : 'otros') }}"
                        data-categorias="{{ $trabajo->categoriastipotrabajo->pluck('id')->implode(',') }}"
                        data-aos="fade-up"
                        style="height: 100%;">
                        <div class="card" style="height: 100%; position: relative; cursor: pointer;" onclick="window.location='{{ route('trabajos.detalle', $trabajo->id) }}'">
                            <div class="card-img-container">
                                <div class="image-wrapper">
                                    @if($trabajo->imagenes->isNotEmpty())
                                        <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}"/>
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen"/>
                                    @endif

                                    <div class="icon-buttons" onclick="event.stopPropagation();">
                                        <button title="Añadir fecha de encuentro" class="icon-button add-date-btn" data-trabajo-id="{{ $trabajo->id }}">
                                            <i class="fas fa-calendar-plus"></i>
                                        </button>

                                    @php
                                        $trabajoCompletado = App\Models\Pago::whereHas('postulacion', function($query) use ($trabajo) {
                                            $query->where('trabajo_id', $trabajo->id);
                                        })->exists();
                                    @endphp
                                    
                                    @if(!$trabajoCompletado)
                                        <a href="{{ route('trabajos.editar', $trabajo->id) }}" title="Editar" class="edit-btn icon-button">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <button class="edit-btn icon-button" style="background-color: #ccc; cursor: not-allowed;" title="No se puede editar un trabajo completado" disabled>
                                            <i class="fas fa-edit" style="color: #888;"></i>
                                        </button>
                                    @endif
                                    
                                    @php
                                        $tienePago = App\Models\Pago::whereHas('postulacion', function($query) use ($trabajo) {
                                            $query->where('trabajo_id', $trabajo->id);
                                        })->exists();
                                    @endphp
                                    
                                    @if($tienePago)
                                        <a href="{{ route('payment.factura', $trabajo->id) }}" title="Descargar factura" class="icon-button" style="background-color: #4CAF50;">
                                            <i class="fas fa-file-invoice-dollar" style="color: white;"></i>
                                        </a>
                                    @else
                                        <a href="#" onclick="event.preventDefault(); confirmDeleteTrabajo({{ $trabajo->id }});" title="Eliminar" class="icon-button">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    
                                    @php
                                        $postulacion = App\Models\Postulacion::where('trabajo_id', $trabajo->id)
                                            ->where('estado_id', 10) // Estado aceptado
                                            ->first();
                                    @endphp
                                    
                                    @if($postulacion)
                                        <a href="{{ route('payment.show', $trabajo->id) }}" title="Realizar pago" class="icon-button payment-btn">
                                            <i class="fas fa-credit-card" style="color:#EC6A6A;"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <br/>
                            <div class="card-body">
                                <h2 class="card-title">{{ $trabajo->titulo }}</h2>
                                <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                                <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                            </div>
                            <div class="card-actions">
                                <div class="action-buttons">
                                    <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="action-btn" onclick="event.stopPropagation();">
                                        Ver candidatos ({{ $trabajo->postulaciones->count() }})
                                    </a>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if(session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-info">
                No has publicado ningún trabajo.
            </div>
        @endif
        
        <!-- Contenedor para el paginador -->
        <div id="paginadorContainer" class="paginador-container my-4" data-aos="fade-up">
            <!-- Aquí se renderizará el paginador dinámicamente -->
        </div>
        
        <div id="noResultados" class="alert alert-warning" style="display:none;">
            No se han encontrado resultados
        </div>
    </div>
</div>
@endsection
