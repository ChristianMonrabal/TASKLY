@extends('layouts.app')

@section('title', 'Mis Postulaciones')

@section('content')
    <h1>Mis Postulaciones</h1>

    @if($postulaciones->isEmpty())
        <p>No tienes postulaciones activas.</p>
    @else
        <div class="postulaciones-list">
            @foreach($postulaciones as $postulacion)
                <div class="postulacion-item">
                    <h2>{{ $postulacion->trabajo->titulo }}</h2>
                    <p>{{ $postulacion->trabajo->descripcion }}</p>
                    <p><strong>Estado:</strong> {{ $postulacion->estado->nombre }}</p>
                    <p><strong>Publicado por:</strong> {{ $postulacion->trabajo->cliente->nombre }}</p>
                    <p><strong>Precio:</strong> ${{ number_format($postulacion->trabajo->precio, 2) }}</p>
                    <a href="{{ route('trabajos.show', $postulacion->trabajo->id) }}" class="btn btn-primary">Ver Trabajo</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection