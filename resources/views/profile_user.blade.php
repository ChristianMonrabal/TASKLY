@extends('layouts.app')

@section('title', 'Perfil de ' . $usuario->nombre)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile_user.css') }}">
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
                    <div class="mb-3">
                        <img src="{{ $usuario->foto_perfil 
                            ? asset('img/profile_images/' . $usuario->foto_perfil) 
                            : asset('img/perfil_default.png') }}" 
                            alt="Foto de perfil" 
                            class="rounded-circle" 
                            width="150" height="150">
                    </div>

                    {{-- Nombre completo --}}
                    <h3 class="mb-0">{{ $usuario->nombre }} {{ $usuario->apellidos }}</h3>

                    {{-- Rol --}}
                    @if ($usuario->rol)
                        <span class="badge bg-primary mt-2">{{ $usuario->rol->nombre }}</span>
                    @endif

                    {{-- Información personal --}}
                    <div class="mt-4 text-start">
                        <p><i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $usuario->email }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Código postal:</strong> {{ $usuario->codigo_postal ?? 'No disponible' }}</p>
                        <p><i class="fas fa-birthday-cake"></i> <strong>Fecha de nacimiento:</strong> 
                            {{ $usuario->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') : 'No disponible' }}
                        </p>
                        <p><i class="fas fa-calendar-alt"></i> <strong>Miembro desde:</strong> {{ $usuario->created_at->format('d/m/Y') }}</p>
                    </div>

                    {{-- Descripción (opcional) --}}
                    @if (!empty($usuario->descripcion))
                        <p class="mt-3">{{ $usuario->descripcion }}</p>
                    @endif

                    {{-- Botón de chat --}}
                    <a href="{{ route('vista.chat', ['user_id' => $usuario->id]) }}" class="btn btn-outline-primary mt-3">
                        <i class="fas fa-comments"></i> Enviar mensaje
                    </a>
                </div>

                {{-- Línea de separación entre la información del usuario y las valoraciones --}}
                <hr class="my-4">   

                {{-- Valoraciones recibidas --}}
                <div class="card-body">
                    <h4 class="mb-4 ms-2">Valoraciones recibidas</h4>

                    @if($usuario->valoracionesRecibidas->count())
                        @foreach($usuario->valoracionesRecibidas as $valoracion)
                            <div class="mb-3">
                                {{-- Contenedor para cada valoración individual --}}
                                <div class="border border-danger rounded p-3 mb-3">

                                    {{-- Imagen de la valoración --}}
                                    @if ($valoracion->img_valoracion)
                                        <div class="mb-3">
                                            <img src="{{ asset('img/valoraciones/' . $valoracion->img_valoracion) }}" alt="Imagen de valoración" class="img-fluid rounded" width="120">
                                        </div>
                                    @endif

                                    {{-- Título y estrellas --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0"><strong>{{ $valoracion->trabajo->titulo ?? 'Trabajo desconocido' }}</strong></h5>
                                        <span class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $valoracion->puntuacion ? '' : '-o' }}"></i>
                                            @endfor
                                        </span>
                                    </div>

                                    {{-- Comentario --}}
                                    @if ($valoracion->comentario)
                                        <p class="mb-2"><strong>Comentario:</strong> {{ $valoracion->comentario }}</p>
                                    @endif

                                    <small class="text-muted">
                                        Valorado el {{ \Carbon\Carbon::parse($valoracion->fecha_valoracion)->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Este usuario aún no ha recibido valoraciones.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
