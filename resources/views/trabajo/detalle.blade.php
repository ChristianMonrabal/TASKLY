@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Detalles')

@section('styles')
  <!-- Fontawesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  {{-- <link rel="stylesheet" href="{{ asset('css/trabajos.css') }}"> --}}
@endsection

@section('content')
<div class="container">
  <div class="trabajo-simple">
    <!-- Galería de imágenes arriba -->
    @if($trabajo->imagenes && $trabajo->imagenes->count() > 0)
      <div class="galeria-imagenes">
        <!-- Imagen principal grande -->
        <div class="imagen-principal">
          <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" alt="{{ $trabajo->titulo }}" id="imagen-grande">
        </div>
        
        <!-- Miniaturas de todas las imágenes -->
        @if($trabajo->imagenes->count() > 1)
          <div class="galeria-miniaturas">
            @foreach($trabajo->imagenes as $imagen)
              <div class="miniatura" onclick="cambiarImagen('{{ asset('img/trabajos/' . $imagen->ruta_imagen) }}')">
                <img src="{{ asset('img/trabajos/' . $imagen->ruta_imagen) }}" alt="{{ $trabajo->titulo }}">
              </div>
            @endforeach
          </div>
        @endif
      </div>
    @else
      <div class="imagen-principal">
        <img src="{{ asset('img/trabajos/trabajo-default.jpg') }}" alt="Imagen por defecto">
      </div>
    @endif
    
    <!-- Información abajo -->
    <div class="info-container">
      <a href="{{ route('trabajos.index') }}" class="volver-btn"><i class="fas fa-arrow-left"></i> Volver</a>
      
      <h1 class="trabajo-titulo">{{ $trabajo->titulo }}</h1>
      
      <div class="precio">€{{ $trabajo->precio }}</div>
      
      <!-- Información del usuario -->
      <div class="usuario-info">
        <i class="fas fa-user-circle fa-2x"></i>
        <div>
          <div class="usuario-nombre">{{ $trabajo->user->name ?? 'Usuario' }}</div>
          @php
            $totalValoraciones = $trabajo->user ? $trabajo->user->valoraciones()->count() : 0;
          @endphp
          <div class="usuario-valoraciones">{{ $totalValoraciones }} reseñas</div>
        </div>
      </div>
      
      <!-- Meta información -->
      <div class="meta-info">
        <div class="meta-item">
          <i class="fas fa-calendar"></i>
          <span>Publicado: {{ $trabajo->created_at->format('d/m/Y') }}</span>
        </div>
        
        @if($trabajo->fecha_expiracion)
          <div class="meta-item">
            <i class="fas fa-hourglass-end"></i>
            <span>Expira: {{ date('d/m/Y', strtotime($trabajo->fecha_expiracion)) }}</span>
          </div>
        @endif
        
        <div class="meta-item">
          <i class="fas fa-tag"></i>
          <span>
            @if($trabajo->categoriastipotrabajo && $trabajo->categoriastipotrabajo->count() > 0)
              {{ $trabajo->categoriastipotrabajo->pluck('nombre')->implode(', ') }}
            @else
              Sin categoría
            @endif
          </span>
        </div>
      </div>
      
      <!-- Descripción -->
      <h3 class="descripcion-titulo"><i class="fas fa-align-left"></i> Descripción</h3>
      <div class="descripcion">
        {{ $trabajo->descripcion }}
      </div>
      
      <!-- Botones de acción -->
      <div class="botones-accion">
        @if(Auth::check() && Auth::id() != $trabajo->user_id)
          <form class="postular-form" action="{{ route('trabajos.postular', $trabajo->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-postular">
              <i class="fas fa-paper-plane"></i> Postularme
            </button>
          </form>
          
          @if($trabajo->user)
            <a href="{{ route('chat.show', $trabajo->user_id) }}" class="btn btn-chat">
              <i class="fas fa-comments"></i> Chatear
            </a>
          @endif
        @elseif(!Auth::check())
          <button class="btn btn-postular" disabled>
            <i class="fas fa-sign-in-alt"></i> Necesitas iniciar sesión para postularte
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializar form de postulación
      const postularForm = document.querySelector('.postular-form');
      if (postularForm) {
        postularForm.addEventListener('submit', function(e) {
          e.preventDefault();
          if (confirm('¿Estás seguro de que deseas postularte para este trabajo?')) {
            this.submit();
          }
        });
      }
      
      // Activar primera miniatura
      const primeraMinatura = document.querySelector('.miniatura');
      if (primeraMinatura) {
        primeraMinatura.classList.add('active');
      }
    });
    
    // Función para cambiar la imagen principal al hacer clic en una miniatura
    function cambiarImagen(rutaImagen) {
      const imagenGrande = document.getElementById('imagen-grande');
      
      // Cambiar la imagen con animación
      imagenGrande.style.opacity = '0.5';
      setTimeout(() => {
        imagenGrande.src = rutaImagen;
        imagenGrande.style.opacity = '1';
      }, 200);
      
      // Actualizar clase activa en miniaturas
      const miniaturas = document.querySelectorAll('.miniatura');
      miniaturas.forEach(miniatura => {
        miniatura.classList.remove('active');
        if (miniatura.querySelector('img').src === rutaImagen) {
          miniatura.classList.add('active');
        }
      });
    }
  </script>
@endsection
