@extends('layouts.app')

@section('title', 'Candidatos para el Trabajo: ' . $trabajo->titulo) <!-- Título de la página -->

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/candidatos_trabajo.css') }}"/>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Candidatos para el Trabajo: {{ $trabajo->titulo }}</h1>

        @if($trabajo->candidatos->count() > 0)
            <div class="row">
                @foreach ($trabajo->candidatos as $candidato)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Información del candidato -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $candidato->nombre }}</h5>
                                <p class="card-text">{{ Str::limit($candidato->descripcion, 100) }}</p>
                                <p class="card-text"><strong>Experiencia:</strong> {{ $candidato->experiencia }} años</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <!-- Opcionalmente, podrías agregar un botón para ver más detalles del candidato -->
                                <a href="{{ route('candidatos.show', $candidato->id) }}" class="btn btn-primary w-100">Ver Detalles</a>
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
