<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar perfil de Taskly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datos_bancarios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ubicaciones.css') }}">
    
    <!-- Leaflet para mapas -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>

<body>
    @extends('layouts.app')
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        <br><br><br>
        <div class="profile-photo-wrapper">
            @if($user->foto_perfil && empty(request('foto_perfil_camera')))
                <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}" class="current-photo" id="current-photo">
            @elseif(!empty(request('foto_perfil_camera')))
                <img src="{{ request('foto_perfil_camera') }}" class="current-photo" id="current-photo">
            @else
                <div class="no-photo-placeholder">
                    <img src="{{ asset('img/profile_images/perfil_default.png') }}" class="current-photo" id="current-photo">
                </div>
            @endif

            <button type="button" class="camera-icon-btn" data-bs-toggle="modal" data-bs-target="#cameraModal">
                <i class="fas fa-camera"></i>
            </button>
        </div>

        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $user->codigo_postal) }}">
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->fecha_nacimiento) }}">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group datos-bancarios-container configured {{ $user->direccionPrincipal ? 'configured' : '' }}">
                <div>
                    <h4 class="datos-bancarios-title"><i class="fas fa-map-marker-alt me-2"></i> Mi Ubicación</h4>
                    <p class="datos-bancarios-text">
                        @if($user->direccionPrincipal)
                            Tu ubicación está configurada y visible para potenciales empleadores.
                        @else
                            Aún no has configurado tu ubicación. ¡Hazlo ahora para que puedan encontrarte!
                        @endif
                    </p>
                </div>
                <a href="{{ route('profile.ubicaciones') }}" class="btn btn-configurar {{ $user->direccionPrincipal ? 'configured' : '' }}" style="display: inline-block; padding: 8px 15px; border-radius: 4px; color: #fff; background-color: #EC6A6A; text-decoration: none; font-weight: bold; border: none; margin-left: 10px;">
                    @if($user->direccionPrincipal)
                        <i class="fas fa-circle-check me-1"></i> Configurado
                    @else
                        <i class="fas fa-gear me-1"></i> Configurar
                    @endif
                </a>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="new-password">Contraseña:</label>
                <div class="password-container">
                    <input type="password" id="new-password" name="password" class="input-field">
                    <i class="fas fa-eye password-toggle" id="toggle-password2" data-target="new-password"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion">{{ old('descripcion', $user->descripcion) }}</textarea>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group datos-bancarios-container {{ $user->datosBancarios && $user->datosBancarios->stripe_account_id ? 'configured' : 'not-configured' }}">
                <div>
                    <h4 class="datos-bancarios-title"><i class="fas fa-credit-card me-2"></i> Datos Bancarios</h4>
                    <p class="datos-bancarios-text">
                        @if($user->datosBancarios && $user->datosBancarios->stripe_account_id)
                            Tus datos bancarios están configurados y listos para recibir pagos.
                        @else
                            Configura tus datos bancarios para recibir pagos por tus trabajos.
                        @endif
                    </p>
                </div>
                <a href="{{ route('profile.datos-bancarios') }}" class="btn btn-configurar {{ $user->datosBancarios && $user->datosBancarios->stripe_account_id ? 'configured' : 'not-configured' }}">
                    @if($user->datosBancarios && $user->datosBancarios->stripe_account_id)
                        <i class="fas fa-check-circle me-1"></i> Configurado
                    @else
                        <i class="fas fa-cog me-1"></i> Configurar
                    @endif
                </a>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Habilidades:</label>
            <div class="form-row">
                <div class="form-group">
                    <label for="habilidades-seleccionadas">Mis habilidades actuales:</label>
                    <select id="habilidades-seleccionadas" multiple class="form-select custom-multiselect" disabled>
                        @foreach($user->habilidades as $habilidad)
                            <option>{{ $habilidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="habilidades">Seleccionar habilidades:</label>
                    <select name="habilidades[]" id="habilidades" multiple class="form-select custom-multiselect">
                        @foreach($habilidades as $habilidad)
                            <option value="{{ $habilidad->id }}" 
                                @if(in_array($habilidad->id, $user->habilidades->pluck('id')->toArray())) selected @endif>
                                {{ $habilidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <input type="hidden" name="foto_perfil_camera" id="foto_perfil_camera">

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#EC6A6A'
                    });
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: '{{ $errors->first() }}',
                        confirmButtonColor: '#EC6A6A'
                    });
                });
            </script>
        @endif

        <div class="text-center mt-4">
            <button type="submit" class="btn color">Guardar cambios</button>
        </div>
    </form>

    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Hacer foto de perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="camera-container">
                        <video id="camera-preview" autoplay playsinline style="max-width: 100%;"></video>
                    </div>
                    <canvas id="photo-canvas" style="display: none;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn red" id="capture-button">
                        <i class="fas fa-camera me-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/toogle_password_profile.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
    <script src="{{ asset('js/profile_alerts.js') }}"></script>
    
    <!-- Scripts para mapas -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/ubicaciones.js') }}"></script>
    <script src="{{ asset('js/buscador-direcciones.js') }}"></script>
    <script>
        window.profileFeedback = {
            success: {!! json_encode(session('success')) !!},
            error: {!! json_encode($errors->first()) !!}
        };
        
        // Script para la búsqueda manual de direcciones
        document.addEventListener('DOMContentLoaded', function() {
            const btnBuscarManual = document.getElementById('btn-buscar-manual');
            
            if (btnBuscarManual) {
                btnBuscarManual.addEventListener('click', function() {
                    const direccion = document.getElementById('direccion').value || '';
                    const codigoPostal = document.getElementById('codigo_postal').value || '';
                    const ciudad = document.getElementById('ciudad').value || '';
                    
                    if (!direccion && !codigoPostal && !ciudad) {
                        alert('Por favor, introduce al menos un dato de la dirección.');
                        return;
                    }
                    
                    // Construir consulta con los datos disponibles
                    const query = `${direccion}, ${codigoPostal} ${ciudad}, España`;
                    
                    // Mostrar indicador de carga
                    const iconoOriginal = btnBuscarManual.innerHTML;
                    btnBuscarManual.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Buscando...';
                    btnBuscarManual.disabled = true;
                    
                    // Hacer petición a Nominatim (respetando límite de uso)
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=es&limit=1&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                const resultado = data[0];
                                
                                // Actualizar campos ocultos
                                document.getElementById('latitud').value = resultado.lat;
                                document.getElementById('longitud').value = resultado.lon;
                                
                                // Actualizar mapa
                                if (typeof mapaSelector !== 'undefined' && mapaSelector) {
                                    mapaSelector.setView([resultado.lat, resultado.lon], 15);
                                    marcadorSelector.setLatLng([resultado.lat, resultado.lon]);
                                }
                                
                                // Mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Dirección encontrada!',
                                    text: 'Se ha localizado la dirección en el mapa.',
                                    confirmButtonColor: '#EC6A6A'
                                });
                            } else {
                                // Mensaje de error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Dirección no encontrada',
                                    text: 'No se ha podido localizar la dirección. Intenta ser más específico o utiliza el buscador de direcciones.',
                                    confirmButtonColor: '#EC6A6A'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al buscar dirección:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error al buscar la dirección.',
                                confirmButtonColor: '#EC6A6A'
                            });
                        })
                        .finally(() => {
                            // Restaurar botón
                            btnBuscarManual.innerHTML = iconoOriginal;
                            btnBuscarManual.disabled = false;
                        });
                });
            }
        });
    </script>
</body>
</html>
