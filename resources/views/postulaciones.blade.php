@extends('layouts.app')

@section('title', 'Mis Postulaciones')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/postulaciones.css') }}"/>
@endsection

@section('content')
    <div class="container-fluid py-5 postulaciones-page">
        <!-- Filtro de estado -->
        <div class="filtro-postulaciones-container" style="margin-bottom: 20px;">
            <select id="filtroPostulaciones" class="filtro-postulaciones-select">
                <option value="todos">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="aceptada">Aceptada</option>
                <option value="rechazada">Rechazada</option>
            </select>
        </div>
        @if ($postulaciones->count() > 0)
            <div class="trabajos-grid">
                @foreach ($postulaciones as $postulacion)
                @php
                    $estado = $postulacion->estado_id;
                    $estadoTexto = $estado == 9 ? 'pendiente' : ($estado == 10 ? 'aceptada' : ($estado == 11 ? 'rechazada' : 'otros'));
                @endphp
                <div class="trabajo-item" data-estado="{{ $estadoTexto }}">
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
    </div>
@endsection

<script src="{{ asset('js/filtro_postulaciones.js') }}"></script>