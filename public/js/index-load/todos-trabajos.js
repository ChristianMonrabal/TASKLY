/**
 * Módulo para cargar todos los trabajos
 * Este archivo contiene funciones para cargar y renderizar todos los trabajos disponibles
 */

/**
 * Función para cargar todos los trabajos mediante fetch
 */
function cargarTodosTrabajos() {
  console.log('Intentando cargar todos los trabajos...');
  
  if (!gridTrabajos) {
    console.error('Error: El elemento gridTrabajos no existe en el DOM');
    return;
  }
  
  gridTrabajos.innerHTML = '<div class="loading">Cargando trabajos...</div>';
  
  console.log('Haciendo fetch a /trabajos/todos');
  fetch('/trabajos/todos')
    .then(function(response) {
      console.log('Respuesta recibida:', response.status, response.statusText);
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor: ' + response.status);
      }
      return response.json();
    })
    .then(function(data) {
      console.log('Datos recibidos:', data ? (data.length + ' trabajos') : 'No hay datos');
      renderizarTodosTrabajos(data);
    })
    .catch(function(error) {
      console.error('Error al cargar todos los trabajos:', error);
      gridTrabajos.innerHTML = '<div class="no-trabajos">Error al cargar trabajos. Intenta de nuevo.</div>';
    });
}

/**
 * Función para renderizar todos los trabajos en el grid
 * @param {Object} data - Objeto paginado con trabajos y metadata
 */
function renderizarTodosTrabajos(data) {
  // Verificar si los datos vienen en formato paginado
  const trabajos = data.data || data;
  
  if (!trabajos || trabajos.length === 0) {
    gridTrabajos.innerHTML = '<div class="no-trabajos">No se encontraron trabajos que coincidan con tus filtros.</div>';
    return;
  }
  
  let html = '';
  
  trabajos.forEach(function(trabajo) {
    // Ruta de imagen predeterminada si no hay imágenes
    let imagenUrl = trabajo.imagenes && trabajo.imagenes.length > 0 
      ? '/img/trabajos/' + trabajo.imagenes[0].ruta_imagen 
      : '/img/trabajos/trabajo-default.jpg';
    
    // Calcular la valoración promedio
    let valoracionPromedio = 0;
    let numValoraciones = 0;
    
    if (trabajo.valoraciones && trabajo.valoraciones.length > 0) {
      numValoraciones = trabajo.valoraciones.length;
      valoracionPromedio = trabajo.valoraciones.reduce((sum, val) => sum + val.puntuacion, 0) / numValoraciones;
      valoracionPromedio = valoracionPromedio.toFixed(1); // Redondear a 1 decimal
    }
    
    // Crear el HTML de la tarjeta
    html += `
      <div class="card" onclick="window.location.href='/trabajos/${trabajo.id}'">
        <div class="card-img">
          <img src="${imagenUrl}" alt="${trabajo.titulo}">
        </div>
        <div class="card-content">
          <h2>${trabajo.titulo}</h2>
          <p>${limitarTexto(trabajo.descripcion, 100)}</p>
          <div class="precio">€${trabajo.precio}</div>
          <div class="categorias">
            ${
              trabajo.categoriastipotrabajo && trabajo.categoriastipotrabajo.length > 0
                ? `<button class="categoria-btn">${trabajo.categoriastipotrabajo.slice(0, 2).map(categoria => categoria.nombre).join(', ')}</button>`
                : '<button class="categoria-btn">Sin categoría</button>'
            }
          </div>
        </div>
      </div>
    `;
  });
  
  gridTrabajos.innerHTML = html;
}
