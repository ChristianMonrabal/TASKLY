/**
 * TASKLY - Buscador de direcciones
 * Proporciona funcionalidad de autocompletado para búsqueda de direcciones
 */

let timeoutId;
let resultadosVisible = false;
const MIN_CARACTERES = 3;
const DELAY_BUSQUEDA = 500; // ms

document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-direcciones');
    
    if (!buscador) return;
    
    // Crear contenedor para resultados si no existe
    let resultadosContainer = document.getElementById('resultados-direcciones');
    if (!resultadosContainer) {
        resultadosContainer = document.createElement('div');
        resultadosContainer.id = 'resultados-direcciones';
        resultadosContainer.className = 'resultados-direcciones';
        buscador.parentNode.insertBefore(resultadosContainer, buscador.nextSibling);
    }
    
    // Escuchar entrada en el buscador
    buscador.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Limpiar timeout anterior
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        
        // Ocultar resultados si la consulta es muy corta
        if (query.length < MIN_CARACTERES) {
            ocultarResultados();
            return;
        }
        
        // Establecer timeout para evitar demasiadas peticiones
        timeoutId = setTimeout(function() {
            buscarDirecciones(query);
        }, DELAY_BUSQUEDA);
    });
    
    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (resultadosVisible && !buscador.contains(event.target) && !resultadosContainer.contains(event.target)) {
            ocultarResultados();
        }
    });
    
    // Evitar envío de formulario al presionar Enter en el buscador
    buscador.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            // Si hay resultados, seleccionar el primero
            const primerResultado = document.querySelector('#resultados-direcciones .resultado-item');
            if (primerResultado) {
                primerResultado.click();
            }
        }
    });
});

/**
 * Busca direcciones utilizando la API de Nominatim
 */
function buscarDirecciones(query) {
    // Sanitizar consulta
    const queryEncoded = encodeURIComponent(query);
    
    // Añadir parámetros para mejorar búsqueda en España
    // countrycodes=es: Limita la búsqueda a España
    // viewbox: Limita el área geográfica (coordenadas de España aproximadamente)
    // bounded=1: Asegura que los resultados están dentro del viewbox
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${queryEncoded}&countrycodes=es&viewbox=-10,44,5,36&bounded=1&limit=5&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                // Si no hay resultados, intentar con un formato diferente
                // A veces ayuda añadir "España" explícitamente
                const queryWithCountry = `${query}, España`;
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(queryWithCountry)}&limit=5&addressdetails=1`)
                    .then(response => response.json())
                    .then(newData => {
                        mostrarResultados(newData);
                    })
                    .catch(error => {
                        console.error('Error en la segunda búsqueda:', error);
                    });
            } else {
                mostrarResultados(data);
            }
        })
        .catch(error => {
            console.error('Error al buscar direcciones:', error);
        });
}

/**
 * Muestra los resultados de la búsqueda
 */
function mostrarResultados(resultados) {
    const resultadosContainer = document.getElementById('resultados-direcciones');
    
    // Limpiar resultados anteriores
    resultadosContainer.innerHTML = '';
    
    if (resultados.length === 0) {
        const noResultados = document.createElement('div');
        noResultados.className = 'no-resultados';
        noResultados.textContent = 'No se encontraron direcciones';
        resultadosContainer.appendChild(noResultados);
    } else {
        // Crear lista de resultados
        resultados.forEach(resultado => {
            const item = document.createElement('div');
            item.className = 'resultado-item';
            
            // Formatear dirección para dirección española
            const address = resultado.address;
            
            // Componentes principales de una dirección
            let calle = '';
            let numero = '';
            let codigoPostal = '';
            let ciudad = '';
            
            // Extraer datos de la respuesta en formato español
            if (address.road || address.pedestrian || address.street) {
                calle = address.road || address.pedestrian || address.street || '';
                // En España, las calles pueden tener prefijos como "Calle", "Avenida", etc.
                if (!calle.toLowerCase().startsWith('calle') && 
                    !calle.toLowerCase().startsWith('c/') && 
                    !calle.toLowerCase().startsWith('avda') && 
                    !calle.toLowerCase().startsWith('avenida') && 
                    !calle.toLowerCase().startsWith('plaza')) {
                    calle = 'Calle ' + calle;
                }
            }
            
            // Número de portal
            numero = address.house_number || '';
            
            // Código postal - en España suele ser de 5 dígitos
            codigoPostal = address.postcode || '';
            
            // Ciudad o municipio
            ciudad = address.city || address.town || address.village || address.municipality || '';
            
            // Construir dirección completa en formato español
            let direccionCompleta = '';
            
            if (calle) {
                direccionCompleta += calle;
                if (numero) {
                    direccionCompleta += ', ' + numero;
                }
            }
            
            if (codigoPostal || ciudad) {
                if (direccionCompleta) direccionCompleta += ', ';
                if (codigoPostal) direccionCompleta += codigoPostal + ' ';
                if (ciudad) direccionCompleta += ciudad;
            }
            
            // Si la dirección está vacía, usar la dirección completa del resultado
            if (!direccionCompleta.trim()) {
                direccionCompleta = resultado.display_name.split(',').slice(0, 3).join(',');
            }
            
            // Mostrar dirección completa
            item.textContent = direccionCompleta;
            
            // Al hacer clic en un resultado
            item.addEventListener('click', function() {
                // Actualizar buscador con el texto seleccionado
                document.getElementById('buscador-direcciones').value = direccionCompleta;
                
                // Actualizar campos individuales
                if (document.getElementById('direccion')) {
                    let direccionCampo = '';
                    if (calle) {
                        direccionCampo = calle;
                        if (numero) direccionCampo += ', ' + numero;
                    } else {
                        direccionCampo = resultado.display_name.split(',')[0];
                    }
                    document.getElementById('direccion').value = direccionCampo;
                }
                
                if (document.getElementById('codigo_postal')) {
                    document.getElementById('codigo_postal').value = codigoPostal;
                }
                
                if (document.getElementById('ciudad')) {
                    document.getElementById('ciudad').value = ciudad;
                }
                
                // Actualizar mapa
                if (document.getElementById('latitud') && document.getElementById('longitud')) {
                    document.getElementById('latitud').value = resultado.lat;
                    document.getElementById('longitud').value = resultado.lon;
                    
                    // Actualizar marcador en el mapa si existe
                    if (typeof mapaSelector !== 'undefined' && mapaSelector) {
                        mapaSelector.setView([resultado.lat, resultado.lon], 15);
                        marcadorSelector.setLatLng([resultado.lat, resultado.lon]);
                    }
                }
                
                // Ocultar resultados
                ocultarResultados();
            });
            
            resultadosContainer.appendChild(item);
        });
    }
    
    // Mostrar contenedor de resultados
    resultadosContainer.style.display = 'block';
    resultadosVisible = true;
}

/**
 * Oculta los resultados de la búsqueda
 */
function ocultarResultados() {
    const resultadosContainer = document.getElementById('resultados-direcciones');
    
    if (resultadosContainer) {
        resultadosContainer.style.display = 'none';
        resultadosVisible = false;
    }
}
