/**
 * TASKLY - Funcionalidad de mapas y ubicaciones
 * Maneja la interacción con mapas Leaflet para direcciones
 */

// Variables globales para los mapas
let mapaSelector, marcadorSelector;
let mapaPequeno, marcadorPequeno;
let mapaCompleto, marcadorCompleto;

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el selector de mapa en el perfil si existe
    if (document.getElementById('mapa-selector')) {
        inicializarMapaSelector();
        
        // Escuchar cambios en los campos de dirección
        document.getElementById('direccion')?.addEventListener('change', geocodificarDireccion);
        document.getElementById('codigo_postal')?.addEventListener('change', geocodificarDireccion);
        document.getElementById('ciudad')?.addEventListener('change', geocodificarDireccion);
    }
    
    // Inicializar mapa pequeño en la vista de detalles si existe
    if (document.getElementById('mapa-pequeno')) {
        inicializarMapaPequeno();
    }
    
    // Inicializar mapa completo en la vista de navegación si existe
    if (document.getElementById('mapa-completo')) {
        inicializarMapaCompleto();
    }
});

/**
 * Inicializa el mapa selector para elegir ubicación en el perfil
 */
function inicializarMapaSelector() {
    const latitudInput = document.getElementById('latitud');
    const longitudInput = document.getElementById('longitud');
    
    // Obtener coordenadas iniciales (valores por defecto o existentes)
    const latInicial = latitudInput.value || 40.4167;
    const lngInicial = longitudInput.value || -3.7033;
    
    // Crear mapa
    mapaSelector = L.map('mapa-selector').setView([latInicial, lngInicial], 13);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapaSelector);
    
    // Añadir marcador arrastrable
    marcadorSelector = L.marker([latInicial, lngInicial], {
        draggable: true
    }).addTo(mapaSelector);
    
    // Actualizar campos cuando se mueve el marcador
    marcadorSelector.on('dragend', function(e) {
        const position = marcadorSelector.getLatLng();
        
        // Actualizar campos ocultos
        latitudInput.value = position.lat;
        longitudInput.value = position.lng;
        
        // Obtener dirección desde coordenadas (geocodificación inversa)
        obtenerDireccionDesdeCoords(position.lat, position.lng);
    });
    
    // Permitir hacer clic en el mapa para mover el marcador
    mapaSelector.on('click', function(e) {
        const position = e.latlng;
        
        // Mover marcador
        marcadorSelector.setLatLng(position);
        
        // Actualizar campos ocultos
        latitudInput.value = position.lat;
        longitudInput.value = position.lng;
        
        // Obtener dirección desde coordenadas
        obtenerDireccionDesdeCoords(position.lat, position.lng);
    });
    
    // Añadir botón de geolocalization (Mi ubicación)
    añadirBotonGeolocalizacion();
}

/**
 * Inicializa el mapa pequeño en la vista de detalles
 */
function inicializarMapaPequeno() {
    const mapaElement = document.getElementById('mapa-pequeno');
    const lat = mapaElement.dataset.lat;
    const lng = mapaElement.dataset.lng;
    
    if (!lat || !lng) return;
    
    // Crear mapa
    mapaPequeno = L.map('mapa-pequeno').setView([lat, lng], 15);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapaPequeno);
    
    // Añadir marcador
    marcadorPequeno = L.marker([lat, lng]).addTo(mapaPequeno)
        .bindPopup('Ubicación del trabajo')
        .openPopup();
        
    // Deshabilitar zoom con rueda para evitar conflictos con scroll
    mapaPequeno.scrollWheelZoom.disable();
}

/**
 * Inicializa el mapa completo en la vista detallada
 */
function inicializarMapaCompleto() {
    const mapaElement = document.getElementById('mapa-completo');
    const lat = mapaElement.dataset.lat;
    const lng = mapaElement.dataset.lng;
    
    if (!lat || !lng) return;
    
    // Crear mapa
    mapaCompleto = L.map('mapa-completo').setView([lat, lng], 15);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapaCompleto);
    
    // Añadir marcador con estilo personalizado
    const customIcon = L.divIcon({
        className: 'custom-map-marker',
        html: '<i class="fas fa-map-marker-alt"></i>',
        iconSize: [30, 42],
        iconAnchor: [15, 42]
    });
    
    marcadorCompleto = L.marker([lat, lng], {icon: customIcon}).addTo(mapaCompleto)
        .bindPopup('<strong>Tu dirección</strong>')
        .openPopup();
    
    // Añadir control de zoom con animación
    mapaCompleto.on('zoomend', function() {
        marcadorCompleto._icon.classList.add('marker-bounce');
        setTimeout(() => {
            marcadorCompleto._icon.classList.remove('marker-bounce');
        }, 500);
    });
}

/**
 * Añade un botón de control para obtener la geolocalización del usuario
 */
function añadirBotonGeolocalizacion() {
    // Crear un elemento para el botón de control personalizado
    const customControl = L.Control.extend({
        options: {
            position: 'topleft'
        },
        
        onAdd: function(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            
            // Crear el botón con icono y estilo
            const button = L.DomUtil.create('a', 'geolocation-button', container);
            button.innerHTML = '<i class="fas fa-location-arrow"></i>';
            button.title = 'Mi ubicación actual';
            button.role = 'button';
            button.href = '#';
            button.style.width = '30px';
            button.style.height = '30px';
            button.style.lineHeight = '30px';
            button.style.textAlign = 'center';
            button.style.backgroundColor = 'white';
            button.style.display = 'block';
            button.style.color = '#EC6A6A';
            
            // Manejar el evento de clic
            L.DomEvent.on(button, 'click', function(e) {
                L.DomEvent.stopPropagation(e);
                L.DomEvent.preventDefault(e);
                obtenerUbicacionActual();
            });
            
            return container;
        }
    });
    
    // Añadir el control al mapa
    mapaSelector.addControl(new customControl());
}

/**
 * Obtiene la ubicación actual del usuario usando la API de geolocalización
 */
function obtenerUbicacionActual() {
    // Comprobar si el navegador soporta geolocalización
    if (!navigator.geolocation) {
        Swal.fire({
            icon: 'error',
            title: 'Geolocalización no disponible',
            text: 'Tu navegador no soporta la geolocalización.',
            confirmButtonColor: '#EC6A6A'
        });
        return;
    }
    
    // Mostrar un mensaje de carga mientras obtenemos la ubicación
    const toast = Swal.fire({
        title: 'Localizando...',
        text: 'Detectando tu ubicación actual',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Opciones de geolocalización
    const options = {
        enableHighAccuracy: true,  // Alta precisión
        timeout: 10000,           // Tiempo de espera (10 segundos)
        maximumAge: 0             // No usar cache
    };
    
    // Solicitar geolocalización
    navigator.geolocation.getCurrentPosition(
        // Función de éxito
        (position) => {
            toast.close();
            
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Actualizar mapa y marcador
            mapaSelector.setView([lat, lng], 16);
            marcadorSelector.setLatLng([lat, lng]);
            
            // Actualizar campos ocultos
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            
            // Intentar obtener la dirección para esas coordenadas (geocodificación inversa)
            obtenerDireccionDesdeCoords(lat, lng);
            
            // Mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Ubicación detectada',
                text: 'Se ha detectado tu ubicación actual.',
                confirmButtonColor: '#EC6A6A',
                timer: 2000,
                showConfirmButton: false
            });
        },
        // Función de error
        (error) => {
            toast.close();
            
            let mensaje = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    mensaje = 'No has dado permiso para acceder a tu ubicación.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    mensaje = 'La información de ubicación no está disponible.';
                    break;
                case error.TIMEOUT:
                    mensaje = 'Se ha agotado el tiempo de espera para obtener la ubicación.';
                    break;
                case error.UNKNOWN_ERROR:
                    mensaje = 'Ha ocurrido un error desconocido.';
                    break;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error de geolocalización',
                text: mensaje,
                confirmButtonColor: '#EC6A6A'
            });
        },
        options
    );
}

/**
 * Geocodifica una dirección utilizando la API de Nominatim
 */
function geocodificarDireccion() {
    const direccion = document.getElementById('direccion')?.value || '';
    const codigoPostal = document.getElementById('codigo_postal')?.value || '';
    const ciudad = document.getElementById('ciudad')?.value || '';
    
    if (!direccion && !codigoPostal && !ciudad) return;
    
    // Construir consulta de búsqueda
    const query = encodeURIComponent(`${direccion}, ${codigoPostal} ${ciudad}`);
    
    // Hacer petición a Nominatim (respetando límite de uso)
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                // Actualizar inputs ocultos
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
                
                // Actualizar mapa
                mapaSelector.setView([lat, lng], 15);
                marcadorSelector.setLatLng([lat, lng]);
            }
        })
        .catch(error => {
            console.error('Error al geocodificar dirección:', error);
        });
}

/**
 * Obtiene la dirección a partir de coordenadas (geocodificación inversa)
 */
function obtenerDireccionDesdeCoords(lat, lng) {
    // Hacer petición a Nominatim (respetando límite de uso)
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                // Actualizar campos de dirección si existen
                if (document.getElementById('direccion')) {
                    let direccion = '';
                    
                    if (data.address.road) {
                        direccion += data.address.road;
                        if (data.address.house_number) {
                            direccion += ', ' + data.address.house_number;
                        }
                    }
                    
                    document.getElementById('direccion').value = direccion;
                }
                
                if (document.getElementById('codigo_postal')) {
                    document.getElementById('codigo_postal').value = data.address.postcode || '';
                }
                
                if (document.getElementById('ciudad')) {
                    document.getElementById('ciudad').value = data.address.city || data.address.town || data.address.village || '';
                }
            }
        })
        .catch(error => {
            console.error('Error al obtener dirección desde coordenadas:', error);
        });
}

/**
 * Inicia la navegación utilizando la API de navegación del navegador
 */
function iniciarNavegacion(lat, lng) {
    if (!lat || !lng) return;
    
    // Comprobar si el dispositivo es móvil
    const esMobil = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (esMobil) {
        // En móviles abrir app de mapas nativa
        window.open(`geo:${lat},${lng}?q=${lat},${lng}`);
        
        // Fallback para iOS
        setTimeout(function() {
            window.open(`maps://?q=${lat},${lng}`);
        }, 500);
        
        // Fallback general a Google Maps
        setTimeout(function() {
            window.open(`https://maps.google.com/maps?q=${lat},${lng}`);
        }, 1000);
    } else {
        // En desktop abrir Google Maps
        window.open(`https://maps.google.com/maps?q=${lat},${lng}`);
    }
}
