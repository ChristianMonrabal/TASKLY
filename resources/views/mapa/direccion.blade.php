@extends('layouts.app')

@section('title', 'Cómo llegar a ' . $trabajo->titulo)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/maps.css') }}">
    <!-- Leaflet Routing Machine - Necesario para calcular rutas -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
@endsection

@section('content')
<div class="container">
    <h1><i class="fas fa-location-dot text-danger pulse-animation"></i> Cómo llegar a {{ $trabajo->titulo }}</h1>
    <p class="text-muted">{{ $trabajo->direccion }}
        {{ $trabajo->codigo_postal ? ', ' . $trabajo->codigo_postal : '' }}
        {{ $trabajo->ciudad ? ', ' . $trabajo->ciudad : '' }}
    </p>

    <div class="route-options mb-3">
        <button id="btnCaminar" class="btn route-btn" data-modo="walking">
            <i class="fas fa-person-walking"></i> A pie
        </button>
        <button id="btnCoche" class="btn route-btn active" data-modo="driving">
            <i class="fas fa-car-side"></i> En coche
        </button>
        <button id="btnTransporte" class="btn route-btn" data-modo="transit">
            <i class="fas fa-train-subway"></i> Transporte público
        </button>
    </div>

    <div id="mapa-container" style="height: 500px;"></div>
    <div class="route-info mt-2" id="routeInfo">
        <i class="fas fa-circle-info text-primary"></i> Selecciona un modo de transporte para ver la ruta.
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.trabajoLat = {{ $trabajo->latitud }};
        window.trabajoLng = {{ $trabajo->longitud }};
    </script>
    <script src="{{ asset('js/recorrido-direccion.js') }}"></script>
@endsection
