/**
 * Módulo para cargar nuevos trabajos
 * Este archivo contiene funciones para cargar y renderizar los trabajos más recientes
 */

/**
 * Función para cargar nuevos trabajos mediante fetch
 */
function cargarNuevosTrabajos() {
  console.log('Intentando cargar nuevos trabajos...');
  
  if (!cardScrollNuevos) {
    console.error('Error: El elemento cardScrollNuevos no existe en el DOM');
    return;
  }
  
  cardScrollNuevos.innerHTML = '<div class="loading">Cargando nuevos trabajos...</div>';
  
  console.log('Haciendo fetch a /trabajos/nuevos');
  fetch('/trabajos/nuevos')
    .then(function(response) {
      console.log('Respuesta recibida:', response.status, response.statusText);
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor: ' + response.status);
      }
      return response.json();
    })
    .then(function(data) {
      console.log('Datos recibidos:', data ? (data.length + ' nuevos trabajos') : 'No hay datos');
      renderizarNuevosTrabajos(data);
    })
    .catch(function(error) {
      console.error('Error al cargar nuevos trabajos:', error);
      cardScrollNuevos.innerHTML = '<div class="no-trabajos">Error al cargar trabajos. Intenta de nuevo.</div>';
    });
}

/**
 * Función para renderizar nuevos trabajos en el carrusel horizontal
 * @param {Array} trabajos - Array de objetos trabajo
 */
function renderizarNuevosTrabajos(trabajos) {
  if (trabajos.length === 0) {
    cardScrollNuevos.innerHTML = '<div class="no-trabajos">No hay trabajos nuevos disponibles.</div>';
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
  
  cardScrollNuevos.innerHTML = html;
}
