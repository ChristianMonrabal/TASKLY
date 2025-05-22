@extends('layouts.app')

@section('title', 'Mapa de ubicación - ' . $trabajo->titulo)

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="{{ asset('css/ubicaciones.css') }}">
    <style>
        .info-seccion {
            background-color: var(--bs-light);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .dark-mode .info-seccion {
            background-color: #2d2d2d;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .trabajo-titulo {
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .consejo-box {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary);
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        
        .dark-mode .consejo-box {
            background-color: #383838;
            border-left: 4px solid var(--primary);
        }
    </style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="btn-mapa me-3">
                    <i class="fas fa-arrow-left"></i> Volver al trabajo
                </a>
                <h1 class="trabajo-titulo mb-0">Ubicación del trabajo</h1>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="direccion-card">
                @if(isset($trabajo->direccionPrincipal) && ($trabajo->direccionPrincipal->es_visible_para_trabajador || Auth::id() == $trabajo->cliente_id))
                    <div class="direccion-info">
                        <h4 class="mb-3"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Dirección del trabajo</h4>
                        <p><i class="fas fa-map-pin me-2"></i><strong>Dirección:</strong> {{ $trabajo->direccionPrincipal->direccion }}</p>
                        <p><i class="fas fa-thumbtack me-2"></i><strong>Código postal:</strong> {{ $trabajo->direccionPrincipal->codigo_postal }}</p>
                        <p><i class="fas fa-city me-2"></i><strong>Ciudad:</strong> {{ $trabajo->direccionPrincipal->ciudad }}</p>
                    </div>
                    
                    <div id="mapa-completo" class="mapa-completo"
                        data-lat="{{ $trabajo->direccionPrincipal->latitud }}"
                        data-lng="{{ $trabajo->direccionPrincipal->longitud }}">
                    </div>
                    
                    <div class="d-flex justify-content-center p-3">
                        <button class="btn-mapa" onclick="iniciarNavegacion({{ $trabajo->direccionPrincipal->latitud }}, {{ $trabajo->direccionPrincipal->longitud }})">
                            <i class="fas fa-directions me-2"></i> Obtener indicaciones
                        </button>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="alert alert-warning">
                            <i class="fas fa-lock me-2"></i> La ubicación exacta se revelará cuando el cliente acepte tu postulación.
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="info-seccion">
                <h4 class="mb-3"><i class="fas fa-info-circle" style="color: var(--primary);"></i> Detalles del trabajo</h4>
                <p><strong>Título:</strong> {{ $trabajo->titulo }}</p>
                <p><strong>Categoría:</strong> {{ $trabajo->categoriastipotrabajo->first()->nombre ?? 'No especificada' }}</p>
                <p><strong>Precio:</strong> {{ number_format($trabajo->precio, 2) }}€</p>
                <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($trabajo->fecha_limite)->format('d/m/Y') }}</p>
            </div>
            
            <div class="info-seccion">
                <h4 class="mb-3"><i class="fas fa-user" style="color: var(--primary);"></i> Cliente</h4>
                <p><strong>Nombre:</strong> {{ $trabajo->cliente->nombre }} {{ $trabajo->cliente->apellidos }}</p>
                <p><strong>Miembro desde:</strong> {{ \Carbon\Carbon::parse($trabajo->cliente->created_at)->format('d/m/Y') }}</p>
                
                <div class="consejo-box">
                    <p class="mb-1"><i class="fas fa-shield-alt me-2"></i> <strong>Seguridad:</strong></p>
                    <p class="mb-0 small">Por tu seguridad, siempre avisa a alguien de confianza antes de acudir a un trabajo.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/ubicaciones.js') }}"></script>
@endsection
