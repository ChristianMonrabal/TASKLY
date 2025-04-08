@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Detalles')

@section('styles')
  <!-- Fontawesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <style>
    .trabajo-simple {
      max-width: 1000px;
      margin: 20px auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .imagen-principal {
      width: 100%;
      height: 400px;
      overflow: hidden;
    }
    
    .imagen-principal img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .info-container {
      padding: 20px;
    }
    
    .volver-btn {
      margin-bottom: 20px;
      display: inline-block;
      padding: 8px 15px;
      background: #f1f1f1;
      color: #333;
      text-decoration: none;
      border-radius: 4px;
    }
    
    .trabajo-titulo {
      font-size: 28px;
      margin: 0 0 15px 0;
      color: #333;
    }
    
    .precio {
      font-size: 24px;
      font-weight: bold;
      color: #e74c3c;
      margin-bottom: 15px;
    }
    
    .usuario-info {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 6px;
      margin-bottom: 20px;
    }
    
    .usuario-nombre {
      font-weight: bold;
    }
    
    .usuario-valoraciones {
      font-size: 14px;
      color: #666;
    }
    
    .meta-info {
      margin-bottom: 20px;
    }
    
    .meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
      color: #666;
    }
    
    .descripcion-titulo {
      font-size: 18px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .descripcion {
      margin-bottom: 30px;
      line-height: 1.6;
      white-space: pre-line;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 6px;
    }
    
    .botones-accion {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-top: 30px;
    }
    
    .btn {
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
    }
    
    .btn-postular {
      background: #3498db;
      color: white;
    }
    
    .btn-chat {
      background: #2ecc71;
      color: white;
    }
    
    @media (max-width: 768px) {
      .imagen-principal {
        height: 250px;
      }
      
      .trabajo-titulo {
        font-size: 22px;
      }
      
      .botones-accion {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('content')
<div class="container">
  <div class="trabajo-simple">
    <!-- Imagen principal arriba -->
    <div class="imagen-principal">
      @if($trabajo->imagenes && $trabajo->imagenes->count() > 0)
        <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" alt="{{ $trabajo->titulo }}">
      @else
        <img src="{{ asset('img/trabajos/trabajo-default.jpg') }}" alt="Imagen por defecto">
      @endif
    </div>
    
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
    });
  </script>
@endsection
