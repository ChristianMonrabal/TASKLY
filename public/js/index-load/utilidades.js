/**
 * Módulo de utilidades
 * Este archivo contiene funciones de utilidad compartidas por otros módulos
 */

/**
 * Función para limitar el texto a una longitud máxima
 * @param {string} texto - Texto a limitar
 * @param {number} longitud - Longitud máxima del texto
 * @returns {string} - Texto limitado con puntos suspensivos si excede la longitud
 */
function limitarTexto(texto, longitud) {
  if (!texto) return 'Sin descripción';
  
  if (texto.length <= longitud) {
    return texto;
  }
  
  return texto.substring(0, longitud) + '...';
}

/**
 * Función para actualizar la visibilidad de los botones de scroll
 * basado en la posición de scroll del carrusel
 */
function actualizarBotonesScroll() {
  // Comprobar si los elementos existen
  if (!cardScrollNuevos || !btnLeftNuevos || !btnRightNuevos) {
    return;
  }
  
  const scrollLeft = cardScrollNuevos.scrollLeft;
  const scrollWidth = cardScrollNuevos.scrollWidth;
  const clientWidth = cardScrollNuevos.clientWidth;
  
  // Mostrar/ocultar botón izquierdo basado en la posición de scroll
  if (scrollLeft <= 10) {
    btnLeftNuevos.classList.add('disabled');
  } else {
    btnLeftNuevos.classList.remove('disabled');
  }
  
  // Mostrar/ocultar botón derecho basado en si hay más contenido para scrollear
  if (scrollLeft + clientWidth >= scrollWidth - 10) {
    btnRightNuevos.classList.add('disabled');
  } else {
    btnRightNuevos.classList.remove('disabled');
  }
}
