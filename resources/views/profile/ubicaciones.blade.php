@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/ubicaciones.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('profile') }}" class="btn btn-outline-secondary" data-aos="fade-right" data-aos-duration="800">
            <i class="fas fa-arrow-left me-1"></i> Volver al Perfil
        </a>
        <h2 class="m-0 text-center" data-aos="fade-up" data-aos-duration="1000">Mis Ubicaciones <i class="fas fa-map-marker-alt ms-2" style="color: #EC6A6A;"></i></h2>
        <div style="width: 120px;"></div> <!-- Espacio para equilibrar el diseño -->
    </div>
    
    <!-- Mensajes de feedback -->
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif
    
    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
    </div>
    @endif
    
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 2px solid rgba(236, 106, 106, 0.2);">
            <h4 class="m-0"><i class="fas fa-home me-2" style="color: #EC6A6A;"></i> Mi Dirección Principal</h4>
            @if($direccionPrincipal)
            <button type="button" class="btn-mapa" data-bs-toggle="modal" data-bs-target="#editarDireccionModal">
                <i class="fas fa-edit"></i> Editar
            </button>
            @endif
        </div>
        <div class="card-body p-0">
            @if($direccionPrincipal)
                <div class="row g-0">
                    <!-- Columna del mapa -->
                    <div class="col-md-7">
                        <div id="mapa-pequeno" class="mapa-pequeno h-100" 
                            data-lat="{{ $direccionPrincipal->latitud }}" 
                            data-lng="{{ $direccionPrincipal->longitud }}">
                        </div>
                    </div>
                    <!-- Columna de información -->
                    <div class="col-md-5">
                        <div class="direccion-info p-4">
                            <div class="mb-4 text-center">
                                <span class="badge rounded-pill" style="background-color: #EC6A6A; font-size: 1rem; padding: 8px 15px;">
                                    <i class="fas fa-map-marker-alt me-2"></i> Tu ubicación
                                </span>
                            </div>
                            
                            <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 10px; border-left: 4px solid #EC6A6A;">
                                <p class="mb-2"><i class="fas fa-road me-2" style="color: #EC6A6A;"></i> <strong>Dirección:</strong></p>
                                <p class="ms-4">{{ $direccionPrincipal->direccion }}</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 10px;">
                                        <p class="mb-1"><i class="fas fa-mail-bulk me-2" style="color: #EC6A6A;"></i> <strong>C.P.:</strong></p>
                                        <p class="ms-4">{{ $direccionPrincipal->codigo_postal }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 10px;">
                                        <p class="mb-1"><i class="fas fa-city me-2" style="color: #EC6A6A;"></i> <strong>Ciudad:</strong></p>
                                        <p class="ms-4">{{ $direccionPrincipal->ciudad }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección de privacidad -->
                            <div class="privacy-info mt-4 p-3" style="background-color: #f8f9fa; border-radius: 10px; border-left: 4px solid #5c6bc0;">
                                <h6 class="mb-2"><i class="fas fa-shield-alt me-2" style="color: #5c6bc0;"></i> Privacidad de tu dirección</h6>
                                <p class="small mb-1">Tu dirección principal es <strong>privada</strong> y solo la puedes ver tú.</p>
                                <p class="small mb-1">En los detalles del trabajo:</p>
                                <ul class="small mb-2">
                                    <li>La dirección solo aparece como texto, <strong>sin mapa</strong> que muestre la ubicación exacta.</li>
                                    <li>Los candidatos <strong>no pueden</strong> ver la dirección del trabajo.</li>
                                    <li>Solo el trabajador que <strong>aceptes</strong> para el trabajo podrá ver la dirección como texto.</li>
                                </ul>
                                <p class="small mb-0"><i class="fas fa-info-circle me-1" style="color: #5c6bc0;"></i> Si quieres proteger tu privacidad, puedes usar una dirección diferente para cada trabajo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marked-alt fa-4x mb-4" style="color: #EC6A6A;"></i>
                    <h5 class="mb-3">No tienes ninguna dirección principal configurada</h5>
                    <p class="text-muted mb-4">Añade tu dirección para mejorar tu experiencia en TASKLY</p>
                    <button type="button" class="btn-mapa mt-3" data-bs-toggle="modal" data-bs-target="#editarDireccionModal">
                        <i class="fas fa-plus-circle me-2"></i> Añadir dirección
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal para añadir/editar dirección -->
    <div class="modal fade" id="editarDireccionModal" tabindex="-1" aria-labelledby="editarDireccionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editarDireccionModalLabel">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $direccionPrincipal ? 'Editar mi dirección' : 'Añadir mi dirección' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.ubicacion.guardar') }}" method="POST" id="form-direccion">
                        @csrf
                        
                        <!-- Buscador de direcciones -->
                        <div class="buscador-container mb-4">
                            <label for="buscador-direcciones">Buscar dirección:</label>
                            <div class="position-relative">
                                <input type="text" id="buscador-direcciones" class="buscador-direcciones" 
                                    placeholder="Escribe tu calle, número, ciudad..." autocomplete="off">
                                <i class="fas fa-search buscador-icon"></i>
                            </div>
                            <!-- Resultados -->
                            <div id="resultados-direcciones" class="resultados-direcciones"></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="direccion" class="form-label">Dirección completa</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                    required value="{{ old('direccion', $direccionPrincipal->direccion ?? '') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                    required value="{{ old('codigo_postal', $direccionPrincipal->codigo_postal ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                    required value="{{ old('ciudad', $direccionPrincipal->ciudad ?? '') }}">
                            </div>
                        </div>
                        
                        <!-- Botón de búsqueda manual -->
                        <button type="button" id="btn-buscar-manual" class="btn-mapa mb-4">
                            <i class="fas fa-search me-2"></i> Buscar esta dirección en el mapa
                        </button>
                        
                        <div class="mb-4">
                            <label class="form-label">Selecciona ubicación en el mapa</label>
                            <div id="mapa-selector" class="mapa-selector"></div>
                            <p class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i> Puedes arrastrar el marcador o hacer clic en el mapa para seleccionar la ubicación exacta.
                            </p>
                        </div>
                        
                        <input type="hidden" id="latitud" name="latitud" 
                            value="{{ old('latitud', $direccionPrincipal->latitud ?? '40.4167') }}">
                        <input type="hidden" id="longitud" name="longitud" 
                            value="{{ old('longitud', $direccionPrincipal->longitud ?? '-3.7033') }}">
                        
                        <div class="form-check form-switch mt-4 mb-4 p-3" style="background-color: #f8f9fa; border-radius: 10px; border-left: 4px solid #EC6A6A;">
                            <input class="form-check-input" type="checkbox" role="switch" id="es_visible_para_trabajador" name="es_visible_para_trabajador" 
                                {{ old('es_visible_para_trabajador', $direccionPrincipal->es_visible_para_trabajador ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="es_visible_para_trabajador">
                                <i class="fas fa-eye me-2" style="color: #EC6A6A;"></i> Hacer visible mi dirección para trabajadores aceptados
                            </label>
                            <div class="form-text small mt-2">
                                Si activas esta opción, los trabajadores que aceptes para tus trabajos podrán ver tu dirección.<br>
                                Si la desactivas, solo tú podrás ver la dirección exacta, incluso después de aceptar un trabajador.
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-mapa">
                                <i class="fas fa-save me-2"></i> Guardar ubicación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- El mapa de navegación ha sido eliminado, ya que solo queremos actualizar la dirección -->
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/ubicaciones.js') }}"></script>
<script src="{{ asset('js/buscador-direcciones.js') }}"></script>
<script>
    // Inicializar mapas cuando se abren los modales
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar mapa pequeño si existe
        if (document.getElementById('mapa-pequeno')) {
            inicializarMapaPequeno();
        }
        
        // Inicializar mapa selector cuando se abre el modal
        const editarDireccionModal = document.getElementById('editarDireccionModal');
        if (editarDireccionModal) {
            editarDireccionModal.addEventListener('shown.bs.modal', function () {
                if (mapaSelector) {
                    mapaSelector.invalidateSize();
                } else {
                    inicializarMapaSelector();
                }
            });
        }
        
        // Inicializar mapa completo cuando se abre el modal
        const mapaModal = document.getElementById('mapaModal');
        if (mapaModal) {
            mapaModal.addEventListener('shown.bs.modal', function () {
                if (mapaCompleto) {
                    mapaCompleto.invalidateSize();
                } else {
                    inicializarMapaCompleto();
                }
            });
        }
    });
</script>
@endsection
