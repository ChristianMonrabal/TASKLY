@extends('layouts.app')

@section('title', 'Candidatos para el Trabajo: ' . $trabajo->titulo) <!-- Título de la página -->

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/candidatos_trabajo.css') }}"/>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Candidatos para el Trabajo: {{ $trabajo->titulo }}</h1>

        @if($trabajo->postulaciones->count() > 0)
            <div class="row">
                @foreach ($trabajo->postulaciones as $postulacion)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Información del candidato -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $postulacion->trabajador->user->name }}</h5>
                                @if(isset($postulacion->trabajador->descripcion))
                                    <p class="card-text">{{ Str::limit($postulacion->trabajador->descripcion, 100) }}</p>
                                @endif
                                @if(isset($postulacion->mensaje))
                                    <p class="card-text"><strong>Mensaje:</strong> {{ Str::limit($postulacion->mensaje, 100) }}</p>
                                @endif
                                <p class="card-text"><strong>Fecha de postulación:</strong> {{ $postulacion->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <!-- Botón para contactar al candidato -->
                                <a href="/mensajes?user_id={{ $postulacion->trabajador->user->id }}" class="btn btn-primary w-100">Contactar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No hay candidatos para este trabajo todavía.
            </div>
        @endif
    </div>
@endsection
