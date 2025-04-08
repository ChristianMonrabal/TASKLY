/**
 * Módulo específico para el manejo de botones de scroll del carrusel
 * Este archivo contiene la lógica para el funcionamiento de los botones de scroll
 */

// Función para inicializar los botones de scroll
function inicializarBotonesScroll() {
  console.log('Inicializando botones de scroll...');
  
  // Asegurarse de que los elementos existen en el DOM
  if (!cardScrollNuevos || !btnLeftNuevos || !btnRightNuevos) {
    console.error('No se encontraron todos los elementos necesarios para los botones de scroll');
    return;
  }
  
  // Eliminar eventos existentes para evitar duplicados
  btnLeftNuevos.removeEventListener('click', scrollIzquierda);
  btnRightNuevos.removeEventListener('click', scrollDerecha);
  
  // Agregar nuevos event listeners
  btnLeftNuevos.addEventListener('click', scrollIzquierda);
  btnRightNuevos.addEventListener('click', scrollDerecha);
  
  console.log('Botones de scroll inicializados correctamente');
  
  // Mostrar/ocultar botones según sea necesario
  actualizarBotonesScroll();
  
  // Actualizar estado de botones al cargar y al hacer scroll
  cardScrollNuevos.addEventListener('scroll', actualizarBotonesScroll);
  window.addEventListener('resize', actualizarBotonesScroll);
}

// Función para manejar el scroll hacia la izquierda
function scrollIzquierda() {
  console.log('Botón izquierdo clickeado');
  if (cardScrollNuevos) {
    // Calcular la distancia de scroll basada en el ancho visible
    const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
    cardScrollNuevos.scrollBy({
      left: -scrollDistance,
      behavior: 'smooth'
    });
  }
}

// Función para manejar el scroll hacia la derecha
function scrollDerecha() {
  console.log('Botón derecho clickeado');
  if (cardScrollNuevos) {
    // Calcular la distancia de scroll basada en el ancho visible
    const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
    cardScrollNuevos.scrollBy({
      left: scrollDistance,
      behavior: 'smooth'
    });
  }
}

// La función se llamará desde el script principal después de cargar los elementos necesarios
