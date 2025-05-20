/**
 * Módulo específico para el manejo de botones de scroll del carrusel
 * Este archivo contiene la lógica para el funcionamiento de los botones de scroll
 */

document.addEventListener('DOMContentLoaded', function () {
  console.log('Inicializando botones de scroll...');

  // Seleccionar elementos del DOM
  const cardScrollNuevos = document.getElementById('cardScrollNuevos');
  const btnLeftNuevos = document.getElementById('btn-left-nuevos');
  const btnRightNuevos = document.getElementById('btn-right-nuevos');

  // Verificar que los elementos existen
  if (!cardScrollNuevos || !btnLeftNuevos || !btnRightNuevos) {
    console.error('No se encontraron todos los elementos necesarios para los botones de scroll');
    return;
  }

  // Función para manejar el scroll hacia la izquierda
  function scrollIzquierda() {
    console.log('Botón izquierdo clickeado');
    const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
    cardScrollNuevos.scrollBy({
      left: -scrollDistance,
      behavior: 'smooth'
    });
  }

  // Función para manejar el scroll hacia la derecha
  function scrollDerecha() {
    console.log('Botón derecho clickeado');
    const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
    cardScrollNuevos.scrollBy({
      left: scrollDistance,
      behavior: 'smooth'
    });
  }

  // Función para actualizar el estado de los botones de scroll
  function actualizarBotonesScroll() {
    // Siempre mostrar ambos botones
    btnLeftNuevos.style.display = 'block';
    btnRightNuevos.style.display = 'block';
  }

  // Agregar eventos a los botones
  btnLeftNuevos.addEventListener('click', scrollIzquierda);
  btnRightNuevos.addEventListener('click', scrollDerecha);

  // Actualizar el estado de los botones al cargar y al hacer scroll
  cardScrollNuevos.addEventListener('scroll', actualizarBotonesScroll);
  window.addEventListener('resize', actualizarBotonesScroll);

  // Inicializar el estado de los botones después de un pequeño retraso
  setTimeout(actualizarBotonesScroll, 100); // Asegura que el contenido esté completamente cargado
});
