@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
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
                            <!-- Imagen del trabajo -->
                            @if($trabajo->imagenes->isNotEmpty())
                            <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $trabajo->titulo }}</h5>
                                <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                                <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                            </div>
                            <div class="card-actions">
                                <div class="action-buttons">
                                    <a href="/view/trabajo/detalle/{{ $trabajo->id }}" class="action-btn">Ver detalles</a>
                                    <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="action-btn">Ver candidatos</a>
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
