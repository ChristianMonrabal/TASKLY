@extends('layouts.app')

@section('title', 'Candidatos para el Trabajo: ' . $trabajo->titulo) <!-- Título de la página -->

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/candidatos_trabajo.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
@endsection

@section('head_scripts')
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/candidatos.js') }}"></script>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4">Candidatos para el Trabajo: {{ $trabajo->titulo }}</h1>

        @if($trabajo->postulaciones->count() > 0)
            <div class="candidates-grid">
                @foreach ($trabajo->postulaciones as $postulacion)
                    <div class="candidate-item">
                        <div class="card candidate-card text-center">
                            <!-- Información del candidato -->
                            <div class="card-body p-3">
                                <!-- Imagen del candidato centrada -->
                                <a href="{{ route('perfil.usuario', $postulacion->trabajador->id) }}" class="text-decoration-none text-dark">
                                    <div class="text-center mb-2">
                                        <div class="candidate-avatar mx-auto">
                                            @if($postulacion->trabajador->foto_perfil)
                                                <img src="{{ asset('img/profile_images/' . $postulacion->trabajador->foto_perfil) }}" alt="{{ $postulacion->trabajador->nombre }}" class="rounded-circle">
                                            @else
                                                <img src="{{ asset('img/perfil_default.png') }}" alt="Avatar por defecto" class="rounded-circle">
                                            @endif
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $postulacion->trabajador->nombre }} {{ $postulacion->trabajador->apellidos }}</h5>
                                </a>
                                                                
                                <!-- Estado de la postulación -->
                                @if($postulacion->estado_id === 10)
                                    <span class="badge estado-aceptado my-1">Aceptado</span>
                                @elseif($postulacion->estado_id === 11)
                                    <span class="badge estado-rechazado my-1">Rechazado</span>
                                @else
                                    <span class="badge estado-pendiente my-1">Pendiente</span>
                                @endif
                                
                                <p class="card-text mb-1"><small>{{ $postulacion->created_at->format('d/m/Y') }}</small></p>
                                
                                @if(isset($postulacion->mensaje))
                                    <p class="card-text small text-center">{{ Str::limit($postulacion->mensaje, 40) }}</p>
                                @endif
                                
                                <!-- Acciones para el candidato (centradas) -->
                                <div class="action-icons">
                                    <a href="#" class="action-icon accept mx-1" data-id="{{ $postulacion->id }}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="#" class="action-icon reject mx-1" data-id="{{ $postulacion->id }}">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a href="/chat?user_id={{ $postulacion->trabajador->id }}" class="action-icon chat mx-1">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                </div>
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
