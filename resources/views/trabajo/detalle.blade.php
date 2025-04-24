@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Detalles')

@section('styles')
  <!-- Fontawesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link rel="stylesheet" href="{{ asset('css/detalle.css') }}">
@endsection

@section('content')
<div class="container-fluid">
  <div class="trabajo-detalle-container">
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
        <img src="{{ asset('img/trabajos/trabajo-default.png') }}" alt="Imagen por defecto">
      </div>
    @endif
    
    <!-- Información abajo -->
    <div class="info-container">
      <a href="{{ route('trabajos.index') }}" class="volver-btn"><i class="fas fa-arrow-left"></i> Volver</a>
      
      <h1 class="trabajo-titulo">{{ $trabajo->titulo }}</h1>
      
      <div class="info-grid">
        <div class="info-col content-col">
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

            @if($trabajo->alta_responsabilidad === 'Sí')
            <div class="meta-item">
              <i class="fas fa-exclamation-triangle text-warning"></i>
              <span><strong>Alta responsabilidad</strong></span>
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
          
            <div class="meta-item precio-destacado">
              <i class="fas fa-euro-sign"></i>
              <span>Precio: {{ $trabajo->precio }}€</span>
            </div>
          </div>
                    
          <!-- Descripción -->
          <div class="seccion-detalle">
            <h3 class="seccion-titulo"><i class="fas fa-align-left"></i> Descripción del proyecto</h3>
            <div class="descripcion">
              {{ $trabajo->descripcion }}
            </div>
          </div>
          
          @if($trabajo->habilidades && $trabajo->habilidades->count() > 0)
          <!-- Habilidades requeridas -->
          <div class="seccion-detalle">
            <h3 class="seccion-titulo"><i class="fas fa-tools"></i> Habilidades requeridas</h3>
            <div class="habilidades-lista">
              @foreach($trabajo->habilidades as $habilidad)
                <span class="habilidad-tag">{{ $habilidad->nombre }}</span>
              @endforeach
            </div>
          </div>
          @endif
        </div>
        
        <div class="info-col sidebar-col">
          <!-- Información del usuario -->
          <div class="sidebar-card">
            <h4 class="card-titulo">Publicado por {{ $trabajo->cliente->nombre }}</h4>
            <div class="usuario-info">
              <div class="usuario-avatar">
                <img src="{{ asset('img/profile_images/default-avatar.png') }}" alt="{{ $trabajo->cliente->foto_perfil ?? 'Usuario' }}">
              </div>
              <div>
                <div class="usuario-nombre">{{ $trabajo->cliente->nombre ?? 'Usuario' }}</div>
                @php
                  $totalValoraciones = $trabajo->user ? $trabajo->user->valoraciones()->count() : 0;
                @endphp
                <div class="usuario-valoraciones">
                  <i class="fas fa-star"></i>
                  {{ $totalValoraciones }} reseñas
                </div>
              </div>
            </div>
      
            <!-- Botones de acción -->
            <div class="botones-accion">
              @if(Auth::check())
                @if(Auth::id() != $trabajo->cliente_id)
                  <div class="boton-wrapper">
                    @if(isset($yaPostulado) && $yaPostulado)
                      <button type="button" class="btn btn-postulado" disabled>
                        <i class="fas fa-check"></i> Ya postulado
                      </button>
                    @else
                      <form class="postular-form" action="{{ route('trabajos.postular', $trabajo->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-postular">
                          <i class="fas fa-paper-plane"></i> Postularme
                        </button>
                      </form>
                    @endif
                  </div>
                  
                  <div class="boton-wrapper">
                    <a href="{{ route('vista.chat', $trabajo->id) }}" class="btn btn-chat" title="Chatear">
                      <i class="fas fa-comments fa-lg"></i>
                    </a>
                  </div>
                @else
                  <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Eres el creador de este trabajo
                  </div>
                @endif
              @else
                <div class="boton-wrapper">
                  <button class="btn btn-postular" disabled>
                    <i class="fas fa-sign-in-alt"></i> Necesitas iniciar sesión
                  </button>
                </div>
                
                <div class="boton-wrapper">
                  <button class="btn btn-chat" disabled title="Chatear">
                    <i class="fas fa-comments fa-lg"></i>
                  </button>
                </div>
              @endif
            </div>
          </div>
              
          @if($trabajo->postulantes_count)
          <li class="meta-item">
            <i class="fas fa-users"></i>
            <span>Postulaciones: {{ $trabajo->postulantes_count }}</span>
          </li>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('js/detalle.js') }}"></script>
@endsection
