@extends('layouts.app')

@section('title', isset($trabajo) ? 'Editar ubicación - ' . $trabajo->titulo : 'Añadir ubicación')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="{{ asset('css/ubicaciones.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i> 
                        {{ isset($trabajo) ? 'Editar ubicación del trabajo' : 'Añadir ubicación al trabajo' }}
                    </h4>
                </div>
                
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $trabajo->titulo }}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-tag me-1"></i> {{ $trabajo->categoriastipotrabajo->first()->nombre ?? 'Sin categoría' }}
                            <span class="mx-2">·</span>
                            <i class="fas fa-euro-sign me-1"></i> {{ number_format($trabajo->precio, 2) }}€
                        </p>
                    </div>
                    
                    <!-- Buscador de direcciones -->
                    <div class="buscador-container">
                        <label for="buscador-direcciones">Buscar dirección:</label>
                        <div class="position-relative">
                            <input type="text" id="buscador-direcciones" class="buscador-direcciones" 
                                placeholder="Escribe la calle, número, ciudad..." autocomplete="off">
                            <i class="fas fa-search buscador-icon"></i>
                        </div>
                        <!-- Aquí se mostrarán los resultados dinámicamente -->
                    </div>
                    
                    <form action="{{ route('trabajos.guardar-direccion', $trabajo->id) }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="direccion" class="form-label">Dirección completa</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                    required value="{{ old('direccion', $direccion->direccion ?? '') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                    required value="{{ old('codigo_postal', $direccion->codigo_postal ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                    required value="{{ old('ciudad', $direccion->ciudad ?? '') }}">
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="es_visible_para_trabajador" 
                                name="es_visible_para_trabajador"
                                {{ (isset($direccion) && $direccion->es_visible_para_trabajador) ? 'checked' : '' }}>
                            <label class="form-check-label" for="es_visible_para_trabajador">
                                Mostrar la dirección a los trabajadores antes de aceptar postulación
                            </label>
                            <div class="form-text">
                                Si no marcas esta opción, la dirección solo será visible para ti y para los trabajadores cuya postulación hayas aceptado.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Selecciona ubicación en el mapa</label>
                            <div id="mapa-selector" class="mapa-selector"></div>
                            <p class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i> Puedes arrastrar el marcador o hacer clic en el mapa para seleccionar la ubicación exacta.
                            </p>
                        </div>
                        
                        <input type="hidden" id="latitud" name="latitud" 
                            value="{{ old('latitud', $direccion->latitud ?? '40.4167') }}">
                        <input type="hidden" id="longitud" name="longitud" 
                            value="{{ old('longitud', $direccion->longitud ?? '-3.7033') }}">
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Volver al trabajo
                            </a>
                            <button type="submit" class="btn-mapa">
                                <i class="fas fa-save me-2"></i> Guardar ubicación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/ubicaciones.js') }}"></script>
<script src="{{ asset('js/buscador-direcciones.js') }}"></script>
@endsection
