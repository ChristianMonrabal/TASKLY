/**
 * Sistema de mapas para TASKLY utilizando Leaflet
 * Incluye funcionalidades para:
 * - Mostrar mapas interactivos
 * - Detección de ubicación actual
 * - Búsqueda y autocompletado de direcciones
 * - Actualización automática de campos de formulario
 */

class TasklyMap {
    constructor(mapId, options = {}) {
        this.mapId = mapId;
        this.options = {
            zoom: options.zoom || 13,
            center: options.center || [40.4167, -3.7033], // Madrid por defecto
            readonly: options.readonly || false,
            miniMap: options.miniMap || false,
            formFields: options.formFields || null,
            marker: options.marker || null
        };

        this.map = null;
        this.marker = null;
        this.geocoder = null;
        this.init();
    }

    init() {
        // Inicializar el mapa
        this.map = L.map(this.mapId).setView(this.options.center, this.options.zoom);
        
        // Añadir capa de mapa
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(this.map);

        // Inicializar geocoder para búsqueda de direcciones
        this.geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            position: 'topleft',
            placeholder: 'Buscar dirección...',
            errorMessage: 'No se encontró la dirección',
            showResultIcons: true,
            suggestMinLength: 3,
            suggestTimeout: 250,
            queryMinLength: 3
        }).on('markgeocode', (e) => {
            this.handleGeocodeResult(e);
        }).addTo(this.map);

        // Si hay un marcador inicial, mostrarlo
        if (this.options.marker) {
            this.setMarker(this.options.marker.lat, this.options.marker.lng);
        }

        // Si el mapa no es de solo lectura, habilitar la interacción
        if (!this.options.readonly) {
            this.enableMapInteraction();
        }

        // Ajustar el tamaño del mapa después de cargarlo
        setTimeout(() => {
            this.map.invalidateSize();
        }, 100);
    }

    enableMapInteraction() {
        // Permitir clic en el mapa para establecer ubicación
        this.map.on('click', (e) => {
            this.setMarker(e.latlng.lat, e.latlng.lng);
            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        // Añadir botón para obtener ubicación actual
        const locationButton = L.control({ position: 'bottomright' });
        locationButton.onAdd = () => {
            const btn = L.DomUtil.create('button', 'location-button');
            btn.innerHTML = '<i class="fas fa-location-arrow"></i> Mi ubicación';
            btn.onclick = (e) => {
                L.DomEvent.stopPropagation(e);
                this.getCurrentLocation();
                return false;
            };
            return btn;
        };
        locationButton.addTo(this.map);
    }

    setMarker(lat, lng) {
        const pos = [lat, lng];
        
        // Si ya existe un marcador, actualizar su posición
        if (this.marker) {
            this.marker.setLatLng(pos);
        } else {
            // Crear un nuevo marcador
            this.marker = L.marker(pos, {
                draggable: !this.options.readonly
            }).addTo(this.map);

            // Si es interactivo, permitir arrastrar el marcador
            if (!this.options.readonly) {
                this.marker.on('dragend', (e) => {
                    const position = e.target.getLatLng();
                    this.reverseGeocode(position.lat, position.lng);
                });
            }
        }

        // Actualizar los campos del formulario con las coordenadas
        if (this.options.formFields && !this.options.readonly) {
            if (this.options.formFields.latitud) {
                document.getElementById(this.options.formFields.latitud).value = lat;
            }
            if (this.options.formFields.longitud) {
                document.getElementById(this.options.formFields.longitud).value = lng;
            }
        }

        // Centrar el mapa en el marcador
        this.map.setView(pos, this.map.getZoom());
    }

    handleGeocodeResult(e) {
        // Limpiar marcadores anteriores si existen
        if (this.marker) {
            this.map.removeLayer(this.marker);
        }

        const latlng = e.geocode.center;
        this.setMarker(latlng.lat, latlng.lng);
        this.map.fitBounds(e.geocode.bbox);

        // Actualizar campos del formulario con la información de la dirección
        if (this.options.formFields && !this.options.readonly) {
            this.updateAddressFields(e.geocode);
        }
    }

    reverseGeocode(lat, lng) {
        if (!this.options.formFields || this.options.readonly) return;

        // Realizar geocodificación inversa para obtener dirección desde coordenadas
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    this.updateAddressFields({
                        properties: {
                            address: data.address
                        },
                        name: data.display_name
                    });
                }
            })
            .catch(error => {
                console.error('Error en geocodificación inversa:', error);
            });
    }

    updateAddressFields(geocodeResult) {
        const fields = this.options.formFields;
        const address = geocodeResult.properties?.address || {};
        
        // Actualizar campos según disponibilidad
        if (fields.direccion && geocodeResult.name) {
            const direccionElement = document.getElementById(fields.direccion);
            if (direccionElement) {
                // Extraer calle y número si están disponibles
                const road = address.road || '';
                const houseNumber = address.house_number || '';
                const streetAddress = road + (houseNumber ? ' ' + houseNumber : '');
                
                // Si hay una dirección de calle, usarla; de lo contrario usar el nombre completo
                direccionElement.value = streetAddress || geocodeResult.name;
            }
        }
        
        if (fields.codigo_postal && address.postcode) {
            const cpElement = document.getElementById(fields.codigo_postal);
            if (cpElement) {
                cpElement.value = address.postcode;
            }
        }
        
        if (fields.ciudad) {
            const ciudadElement = document.getElementById(fields.ciudad);
            if (ciudadElement) {
                // Usar ciudad, pueblo o municipio según disponibilidad
                const ciudad = address.city || address.town || address.village || address.municipality || '';
                ciudadElement.value = ciudad;
            }
        }
    }

    getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    this.setMarker(lat, lng);
                    this.reverseGeocode(lat, lng);
                    
                    // Zoom al área
                    this.map.setView([lat, lng], 16);
                },
                (error) => {
                    console.error('Error al obtener ubicación:', error);
                    alert('No se pudo obtener tu ubicación. Por favor, verifica los permisos de tu navegador.');
                }
            );
        } else {
            alert('Tu navegador no soporta geolocalización.');
        }
    }

    resize() {
        if (this.map) {
            this.map.invalidateSize();
        }
    }
}

// Función de ayuda para crear un mini mapa de solo lectura
function createMiniMap(elementId, lat, lng, zoom = 15) {
    if (!document.getElementById(elementId)) return null;
    
    return new TasklyMap(elementId, {
        center: [lat, lng],
        zoom: zoom,
        readonly: true,
        miniMap: true,
        marker: {
            lat: lat,
            lng: lng
        }
    });
}
