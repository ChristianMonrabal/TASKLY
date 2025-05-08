@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/sweet_alert_calendario.js') }}"></script>
    <script src="{{ asset('js/eliminar_trabajo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
    <div class="container-fluid py-5">
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
                                    <button title="Añadir fecha de encuentro" class="icon-button add-date-btn" data-trabajo-id="{{ $trabajo->id }}">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>

                                    <a href="{{ route('trabajos.editar', $trabajo->id) }}" title="Editar" class="edit-btn icon-button">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="#" onclick="event.preventDefault(); confirmDeleteTrabajo({{ $trabajo->id }});" title="Eliminar" class="icon-button">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
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

