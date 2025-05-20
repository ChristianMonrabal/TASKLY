@extends('layouts.app')

@section('title', 'Perfil de ' . $usuario->nombre)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil_resenas.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Contenedor con borde rojo para el perfil y valoraciones --}}
            <div class="card shadow rounded-lg border border-danger p-4">

                {{-- Información del perfil --}}
                <div class="card-body text-center">

                    {{-- Foto de perfil --}}
                    <div style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; border: 3px solid #dc3545; box-shadow: 0 0 10px rgba(0,0,0,0.2); margin: 0 auto;">
                        <img src="{{ $usuario->foto_perfil 
                            ? asset('img/profile_images/' . $usuario->foto_perfil) 
                            : asset('img/perfil_default.png') }}" 
                            alt="Foto de perfil"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <br>

                    {{-- Nombre completo --}}
                    <h3 class="mb-0">{{ $usuario->nombre }} {{ $usuario->apellidos }}</h3>

                    {{-- Información personal --}}
                    <div class="mt-4 text-start">
                        <p><i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $usuario->email }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Código postal:</strong> {{ $usuario->codigo_postal ?? 'No disponible' }}</p>
                        <p><i class="fas fa-calendar-alt"></i> <strong>Miembro desde:</strong> {{ $usuario->created_at->format('d/m/Y') }}</p>
                    </div>

                    {{-- Descripción (opcional) --}}
                    @if (!empty($usuario->descripcion))
                        <p class="mt-3">{{ $usuario->descripcion }}</p>
                    @endif

                    {{-- Botón de chat & reporte de usuario --}}
                    @if(Auth::id() !== $usuario->id)
                        <a href="{{ route('vista.chat', ['user_id' => $usuario->id]) }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-comments"></i> Enviar mensaje
                        </a>
                        <a href="{{ route('reportes.index', ['user_id' => $usuario->id]) }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-exclamation-triangle"></i> Reportar usuario
                        </a>
                    @endif
                </div>
                
                {{-- Línea de separación entre la información del usuario y las valoraciones --}}
                <hr class="my-4">   

                {{-- Valoraciones recibidas --}}
                <div class="card-body">
                    {{-- Título "Valoraciones recibidas" centrado y en color rojo --}}
                    <br>
                    <h2 class="mb-4 ms-2 text-center text-danger"><strong>Valoraciones recibidas</strong></h2>

                    @if($usuario->valoracionesRecibidas->count())
                        <div class="text-center mb-4">
                            <h4 class="text-dark">Valoración media de todos sus trabajos:</h4>
                            <div class="estrella-amarilla fs-4">
                                @php
                                    $media = round($mediaValoraciones, 1);
                                    $mediaEntera = floor($media);
                                    $mediaDecimal = $media - $mediaEntera;
                                @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $mediaEntera)
                                        <i class="fas fa-star estrella-amarilla"></i>
                                    @elseif ($i == $mediaEntera + 1 && $mediaDecimal >= 0.5)
                                        <i class="fas fa-star-half-alt estrella-amarilla"></i>
                                    @else
                                        <i class="far fa-star estrella-amarilla"></i>
                                    @endif
                                @endfor

                                <span class="text-muted ms-2">({{ $media }}/5)</span>
                            </div>
                        </div>
                    @endif

                    {{-- Contenedor grid para las valoraciones --}}
                    @if($usuario->valoracionesRecibidas->count())
                        <div class="resenas-grid">
                            @foreach($usuario->valoracionesRecibidas as $valoracion)
                                <div class="resena-card">
                                    <div class="resena-header">
                                        <h5 class="resena-titulo">{{ $valoracion->trabajo->titulo ?? 'Trabajo desconocido' }}</h5>
                                        <span class="resena-estrellas">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $valoracion->puntuacion ? '' : '-o' }} estrella-amarilla"></i>
                                            @endfor
                                        </span>
                                    </div>

                                    {{-- Comentario --}}
                                    @if ($valoracion->comentario)
                                        <div class="resena-comentario">
                                            <p><strong>Comentario:</strong> {{ $valoracion->comentario }}</p>
                                        </div>
                                    @endif

                                    <div class="resena-fecha">
                                        Valorado el {{ \Carbon\Carbon::parse($valoracion->fecha_valoracion)->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Este usuario aún no ha recibido valoraciones.</p>
                    @endif

                    {{-- Insignias / Logros --}}
                    @if($usuario->logrosCompletados->count())
                        <hr class="my-4">
                        <div class="card-body text-center">
                            <h2 class="mb-4 text-danger"><strong>Insignias obtenidas</strong></h2>
                            <div class="d-flex flex-wrap justify-content-center gap-4">
                                @foreach($usuario->logrosCompletados as $logroCompleto)
                                    @php
                                        $logro = $logroCompleto->logro;
                                    @endphp
                                    @if($logro)
                                        <div class="text-center" style="max-width: 120px;">
                                            <div class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $logro->descripcion }}">
                                                <img src="{{ asset('img/insignias/' . $logro->foto_logro) }}" 
                                                    alt="{{ $logro->nombre }}" 
                                                    class="img-thumbnail shadow"
                                                    style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #dc3545; border-radius: 10px;">
                                            </div>
                                            <p class="mt-2 text-dark"><strong>{{ $logro->nombre }}</strong></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
