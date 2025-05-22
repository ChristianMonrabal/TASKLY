@extends('layouts.app')

@section('title', 'Perfil de ' . $usuario->nombre)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil_resenas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logros.css') }}">
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
                    <div data-aos="fade-up" data-aos-duration="1000" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; border: 3px solid #dc3545; box-shadow: 0 0 10px rgba(0,0,0,0.2); margin: 0 auto; position: relative;">
                        <img src="{{ $usuario->foto_perfil 
                            ? asset('img/profile_images/' . $usuario->foto_perfil) 
                            : asset('img/profile_images/perfil_default.png') }}" 
                            alt="Foto de perfil"
                            style="width: 100%; height: 100%; object-fit: cover;">
                        @auth
                            @if(Auth::id() === $usuario->id)
                                <a href="{{ route('profile') }}" class="camera-icon-btn" title="Editar perfil">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif
                        @endauth
                    </div>
                    <br>
                    
                    {{-- Nombre completo --}}
                    <h3 class="mb-0" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">{{ $usuario->nombre }} {{ $usuario->apellidos }}</h3>
                    {{-- Descripción (opcional) --}}
                    @if (!empty($usuario->descripcion))
                        <p class="mt-3">{{ $usuario->descripcion }}</p>
                    @endif
                    {{-- Información personal --}}
                    <div class="mt-4 text-start" data-aos="fade-up" data-aos-delay="400" data-aos-duration="800">
                        <p><i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $usuario->email }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Código postal:</strong> {{ $usuario->codigo_postal ?? 'No disponible' }}</p>
                        <p><i class="fas fa-calendar-alt"></i> <strong>Miembro desde:</strong> {{ $usuario->created_at->format('d/m/Y') }}</p>
                    </div>
                    <br>
                    

                    {{-- Botón de chat & reporte de usuario --}}
                    @if(Auth::id() !== $usuario->id)
                        <a href="{{ route('vista.chat', ['user_id' => $usuario->id]) }}" class="btn btn-outline-primary mt-3" data-aos="fade-right" data-aos-delay="500" data-aos-duration="800">
                            <i class="fas fa-comments"></i> Enviar mensaje
                        </a>
                        <a href="{{ route('reportes.index', ['user_id' => $usuario->id]) }}" class="btn btn-outline-primary mt-3" data-aos="fade-left" data-aos-delay="500" data-aos-duration="800">
                            <i class="fas fa-exclamation-triangle"></i> Reportar usuario
                        </a>
                    @endif
                </div>
                
                {{-- Línea de separación entre la información del usuario y los logros --}}
                <hr class="my-4">   

                {{-- Insignias / Logros --}}
                <div class="logros-seccion" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-center text-danger mb-4" data-aos="zoom-in" data-aos-delay="200" data-aos-duration="800"><i class="fas fa-award me-2"></i><strong>  Logros conseguidos</strong></h2>
                    
                    @if($usuario->logrosCompletados->count())
                        <div class="logros-grid">
                            @foreach($usuario->logrosCompletados as $index => $logroCompleto)
                                @php
                                    $logro = $logroCompleto->logro;
                                @endphp
                                @if($logro)
                                    <div class="logro-card" style="--index: {{ $index }}" data-aos="flip-left" data-aos-delay="{{ 200 + ($index * 100) }}" data-aos-duration="800">
                                        <img src="{{ asset('img/insignias/' . ($logro->foto_logro)) }}" 
                                            alt="{{ $logro->nombre }}" 
                                            class="logro-imagen">
                                        <h3 class="logro-titulo">{{ $logro->nombre }}</h3>
                                        {{-- <p class="logro-descripcion">{{ $logro->descripcion }}</p> --}}
                                        <div class="logro-meta">
                                            <span class="logro-tiempo">{{ \Carbon\Carbon::parse($logroCompleto->created_at)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="sin-logros">
                            <i class="fas fa-medal fa-2x mb-3 text-muted"></i>
                            <p>{{ $usuario->nombre }} aún no ha conseguido logros.</p>
                            <p class="small">Los logros se obtienen al completar tareas especiales en TASKLY.</p>
                        </div>
                    @endif
                </div>
                <br>

                {{-- Línea de separación entre los logros y las valoraciones --}}
                <hr class="my-4">
                
                {{-- Valoraciones recibidas --}}
                <div class="card-body" data-aos="fade-up" data-aos-duration="800">
                    {{-- Título "Valoraciones recibidas" centrado y en color rojo --}}
                    <br>
                    <h2 class="mb-4 ms-2 text-center text-danger" data-aos="zoom-in" data-aos-delay="200" data-aos-duration="800"><strong>Valoraciones recibidas</strong></h2>

                    @if($usuario->valoracionesRecibidas->count())
                        <div class="text-center mb-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
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
                                <div class="resena-card" data-aos="fade-up" data-aos-delay="{{ 400 + ($loop->index * 100) }}" data-aos-duration="800">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
