@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/sweet_alert_calendario.js') }}"></script>
    <script src="{{ asset('js/eliminar_trabajo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .main-header {
            width: calc(100% - 20px);
            left: 10px;
            right: 10px;
            position: fixed;
        }

        /* Ajustes para el contenido principal */
        .main-content {
            margin-top: 0;
            margin-top: 0;
            padding-top: 30px;
        }

        /* Estilos para mantener el diseño original */
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .card-img-container {
            width: 100%;
            overflow: hidden;
        }

        .card-img-container .image-wrapper {
            position: relative;
        }

        .icon-buttons {
            position: absolute;
            bottom: 8px;
            right: 8px;
            display: flex;
            gap: 6px;
        }

        .icon-buttons button {
            background-color: #EC6A6A(0, 0, 0, 0.6);
            border: none;
            color: #EC6A6A;
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .icon-buttons button:hover {
            background-color: #EC6A6A(0, 0, 0, 0.8);
        }

        .icon-buttons i {
            font-size: 16px;
        }

        .icon-button {
            background-color: rgba(221, 221, 221, 0.6);
            border: none;
            color: #EC6A6A;
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .icon-buttons button,
        .icon-buttons a {
            background-color: rgba(221, 221, 221, 0.6);
            border: none;
            color: #EC6A6A;
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .icon-buttons i {
            font-size: 16px;
        }

        .main-nav {
            color: #ffffff !important;
        }

        .nav-link {
            position: relative;
            color: white;
            font-weight: normal;
            padding: 10px 5px;
            text-decoration: none;
            transition: all var(--transition);
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
                        <div class="card-body">
                            <h2 class="card-title">{{ $trabajo->titulo }}</h2>
                            <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                        </div>
                        <div class="card-actions">
                            <div class="action-buttons">
                                <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="action-btn">Ver detalles</a>
                                <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="action-btn">Ver candidatos</a>
                                {{-- <a href="{{ route('pago.show', $trabajo->id) }}" class="action-btn">Pagar y finalizar</a> --}}
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

