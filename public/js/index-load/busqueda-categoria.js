/**
 * Módulo para búsqueda y filtrado por categoría
 * Este archivo contiene funciones para buscar trabajos y filtrar por categoría
 */

/**
 * Función para cargar trabajos por categoría
 * @param {number} categoriaId - ID de la categoría a filtrar
 */
function cargarTrabajosPorCategoria(categoriaId) {
  gridTrabajos.innerHTML = '<div class="loading">Cargando trabajos de esta categoría...</div>';
  
  fetch('/trabajos/categoria-json/' + categoriaId)
    .then(function(response) {
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }
      return response.json();
    })
    .then(function(data) {
      renderizarTodosTrabajos(data);
    })
    .catch(function(error) {
      console.error('Error al cargar trabajos por categoría:', error);
      gridTrabajos.innerHTML = '<div class="no-trabajos">Error al cargar trabajos. Intenta de nuevo.</div>';
    });
}

/**
 * Función para buscar trabajos
 * @param {string} busqueda - Texto de búsqueda
 */
function buscarTrabajos(busqueda) {
  gridTrabajos.innerHTML = '<div class="loading">Buscando trabajos...</div>';
  
  fetch('/trabajos/buscar-json?busqueda=' + encodeURIComponent(busqueda))
    .then(function(response) {
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }
      return response.json();
    })
    .then(function(data) {
      renderizarTodosTrabajos(data);
    })
    .catch(function(error) {
      console.error('Error al buscar trabajos:', error);
      gridTrabajos.innerHTML = '<div class="no-trabajos">Error al buscar trabajos. Intenta de nuevo.</div>';
    });
}
