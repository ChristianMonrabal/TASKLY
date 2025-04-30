@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <style>
        /* Estilos específicos para el header en esta página */
        .main-header {
            width: calc(100% - 20px);
            left: 10px;
            right: 10px;
            position: fixed;
        }
        
        /* Ajustes para el contenido principal */
        .main-content {
            margin-top: var(--header-height);
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
                        @if($trabajo->imagenes->isNotEmpty())
                            <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen">
                        @endif
                        <br>
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