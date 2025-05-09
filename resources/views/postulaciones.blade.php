@extends('layouts.app')

@section('title', 'Mis Postulaciones')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/postulaciones.css') }}"/>
@endsection

@section('content')
    <div class="container-fluid py-5 postulaciones-page">
        @if ($postulaciones->count() > 0)
            <div class="trabajos-grid">
                @foreach ($postulaciones as $postulacion)
                <div class="trabajo-item">
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