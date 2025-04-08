@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Detalles')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/detalle-trabajo.css') }}"/>
@endsection

@section('content')
  <div class="detalle-trabajo">
    <a href="{{ route('trabajos.index') }}" class="btn-volver">← Volver a la lista de trabajos</a>
    
    <div class="detalle-header">
      <h1 class="detalle-titulo">{{ $trabajo->titulo }}</h1>
      
      <div class="detalle-meta">
        <div class="detalle-meta-item">
          <span>📅 Publicado: {{ $trabajo->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="detalle-meta-item">
          <span>⏱️ Estado: {{ $trabajo->estado ? $trabajo->estado->nombre : 'No definido' }}</span>
        </div>
      </div>
    </div>
    
    <div class="galeria-imagenes">
      @if($trabajo->imgTrabajos && $trabajo->imgTrabajos->count() > 0)
        <img src="{{ asset('img/trabajos/' . $trabajo->imgTrabajos->first()->ruta_img) }}" alt="{{ $trabajo->titulo }}" class="imagen-principal" id="imagenPrincipal">
        
        <div class="miniaturas">
          @foreach($trabajo->imgTrabajos as $img)
            <img src="{{ asset('img/trabajos/' . $img->ruta_img) }}" alt="{{ $trabajo->titulo }}" class="imagen-miniatura" onclick="cambiarImagen('{{ asset('img/trabajos/' . $img->ruta_img) }}')">
          @endforeach
        </div>
      @else
        <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="{{ $trabajo->titulo }}" class="imagen-principal">
      @endif
    </div>
    
    <div class="info-trabajo">
      <div class="descripcion-trabajo">
        <h2>Descripción</h2>
        <p>{{ $trabajo->descripcion }}</p>
        
        <div class="detalles-seccion">
          <h2>Detalles</h2>
          <div class="detalle-item">
            <span class="detalle-item-icon">📅</span>
            <span>Publicado: {{ $trabajo->created_at->format('d/m/Y') }}</span>
          </div>
          
          <div class="detalle-item">
            <span class="detalle-item-icon">⏱️</span>
            <span>Estado: {{ $trabajo->estado ? $trabajo->estado->nombre : 'No definido' }}</span>
          </div>
        </div>
        
        @if($trabajo->user)
        <div class="detalles-seccion">
          <h2>Publicado por</h2>
          <div class="detalle-item">
            <span class="detalle-item-icon">👤</span>
            <span>{{ $trabajo->user->name }}</span>
          </div>
        </div>
        @endif
      </div>
      
      <div class="acciones-trabajo">
        <p class="precio-detalle">${{ $trabajo->precio }}</p>
        <span class="categoria-detalle">{{ $trabajo->categoria ? $trabajo->categoria->nombre : 'Sin categoría' }}</span>
        
        <button class="btn-postular" id="btnPostular" data-id="{{ $trabajo->id }}">Postularme para este trabajo</button>
        <button class="btn-chat" id="btnChat" data-id="{{ $trabajo->id }}">Abrir chat con el empleador</button>
        
        @if($trabajo->user)
        <div class="info-empleador">
          <h3>Sobre el empleador</h3>
          <div class="empleador-datos">
            <img src="{{ asset('img/avatar-placeholder.jpg') }}" alt="{{ $trabajo->user->name }}" class="empleador-avatar">
            <div class="empleador-info">
              <div class="empleador-nombre">{{ $trabajo->user->name }}</div>
              <div class="empleador-meta">Miembro desde {{ $trabajo->user->created_at->format('M Y') }}</div>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/detalle.js') }}"></script>
@endsection
