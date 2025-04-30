@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <style>
        .main-header {
            width: calc(100% - 20px);
            left: 10px;
            right: 10px;
            position: fixed;
        }
        
        /* Ajustes para el contenido principal */
        .main-content {
<<<<<<< Updated upstream
            margin-top: var(--header-height);
=======
            margin-top: 0;
            margin-top: 0;
>>>>>>> Stashed changes
            padding-top: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-5">
        <div class="mb-4">
            <h1 class="text-center">Mis Trabajos Publicados</h1>
        </div>
        
        @if ($trabajos->count() > 0)
            <div class="trabajos-grid">
                @foreach ($trabajos as $trabajo)
                <div class="trabajo-item">
                    <div class="card">
<<<<<<< Updated upstream
                        @if($trabajo->imagenes->isNotEmpty())
                            <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen">
                        @endif
                        <br>
=======
                        <div class="card-img-container">
                            <div class="image-wrapper">
                                @if($trabajo->imagenes->isNotEmpty())
                                    <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}"/>
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen"/>
                                @endif

                                <div class="icon-buttons">
                                    <a href="{{ route('trabajos.editar', $trabajo->id) }}" title="Editar" class="edit-btn icon-button">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="#" onclick="event.preventDefault(); confirmDeleteTrabajo({{ $trabajo->id }});" title="Eliminar" class="icon-button">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>

                                    <button title="Añadir fecha de encuentro" class="icon-button add-date-btn" data-trabajo-id="{{ $trabajo->id }}">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <br/>
>>>>>>> Stashed changes
                        <div class="card-body">
                            <h2 class="card-title">{{ $trabajo->titulo }}</h2>
                            <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                        </div>
                        <div class="card-actions">
                            <div class="action-buttons">
                                <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="action-btn">Ver detalles</a>
                                <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="action-btn">Ver candidatos</a>
                                <br>
                                <button class="action-btn add-date-btn" data-trabajo-id="{{ $trabajo->id }}">Añadir fecha de encuentro</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No has publicado ningún trabajo todavía.
            </div>
        @endif
    </div>
@endsection

<script src="{{ asset('js/sweet_alert_calendario.js') }}"></script>