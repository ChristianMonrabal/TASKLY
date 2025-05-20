/**
 * Archivo principal del sistema de carga de trabajos
 * Inicializa las variables globales y configuración de eventos
 */

// Variables para almacenar referencias a elementos DOM
let cardScrollNuevos;
let cardScrollCercanos;
let gridTrabajos;
let btnLeftNuevos;
let btnRightNuevos;
let btnLeftCercanos;
let btnRightCercanos;
let inputBusqueda;
let formularioBusqueda;
let aplicarFiltrosBtn;
let limpiarFiltrosBtn;
let filtroDropdowns;

// Variables para almacenar filtros activos
let filtrosActivos = {
  categorias: [], // Array para selección múltiple
  precio: 'todos',
  ubicacion: 'todos',
  orden: 'reciente'
};

// Función para actualizar botones de scroll
function actualizarBotonesScroll(container, btnLeft, btnRight) {
  if (!container || !btnLeft || !btnRight) return;

  // Mostrar/ocultar botón izquierdo
  if (container.scrollLeft <= 10) {
    btnLeft.style.opacity = '0.5';
    btnLeft.style.cursor = 'default';
    btnLeft.disabled = true;
  } else {
    btnLeft.style.opacity = '1';
    btnLeft.style.cursor = 'pointer';
    btnLeft.disabled = false;
  }

  // Mostrar/ocultar botón derecho
  if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
    btnRight.style.opacity = '0.5';
    btnRight.style.cursor = 'default';
    btnRight.disabled = true;
  } else {
    btnRight.style.opacity = '1';
    btnRight.style.cursor = 'pointer';
    btnRight.disabled = false;
  }
}

// Función que se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  console.log('Inicializando carga de trabajos...');
  
  // Obtener referencias a elementos DOM
  cardScrollNuevos = document.getElementById('cardScrollNuevos');
  cardScrollCercanos = document.getElementById('cardScrollCercanos');
  gridTrabajos = document.getElementById('gridTrabajos');
  btnLeftNuevos = document.getElementById('btn-left-nuevos');
  btnRightNuevos = document.getElementById('btn-right-nuevos');
  btnLeftCercanos = document.getElementById('btn-left-cercanos');
  btnRightCercanos = document.getElementById('btn-right-cercanos');
  inputBusqueda = document.getElementById('inputBusqueda');
  formularioBusqueda = document.getElementById('formularioBusqueda');
  aplicarFiltrosBtn = document.getElementById('aplicarFiltrosBtn');
  limpiarFiltrosBtn = document.getElementById('limpiarFiltrosBtn');
  filtroDropdowns = document.querySelectorAll('.filtro-dropdown');
  
  // Configurar eventos para el carrusel de nuevos trabajos
  if (cardScrollNuevos && btnLeftNuevos && btnRightNuevos) {
    console.log('Elementos para el carrusel de nuevos trabajos encontrados');
    
    btnLeftNuevos.addEventListener('click', function() {
      const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
      cardScrollNuevos.scrollBy({
        left: -scrollDistance,
        behavior: 'smooth'
      });
    });
    
    btnRightNuevos.addEventListener('click', function() {
      const scrollDistance = Math.min(cardScrollNuevos.clientWidth * 0.8, 800);
      cardScrollNuevos.scrollBy({
        left: scrollDistance,
        behavior: 'smooth'
      });
    });
    
    // Actualizar botones al cargar y al hacer scroll
    actualizarBotonesScroll(cardScrollNuevos, btnLeftNuevos, btnRightNuevos);
    cardScrollNuevos.addEventListener('scroll', () => {
      actualizarBotonesScroll(cardScrollNuevos, btnLeftNuevos, btnRightNuevos);
    });
  }
  
  // Configurar eventos para el carrusel de trabajos cercanos (solo si el usuario está autenticado)
  if (cardScrollCercanos && btnLeftCercanos && btnRightCercanos) {
    console.log('Elementos para el carrusel de trabajos cercanos encontrados');
    
    btnLeftCercanos.addEventListener('click', function() {
      const scrollDistance = Math.min(cardScrollCercanos.clientWidth * 0.8, 800);
      cardScrollCercanos.scrollBy({
        left: -scrollDistance,
        behavior: 'smooth'
      });
    });
    
    btnRightCercanos.addEventListener('click', function() {
      const scrollDistance = Math.min(cardScrollCercanos.clientWidth * 0.8, 800);
      cardScrollCercanos.scrollBy({
        left: scrollDistance,
        behavior: 'smooth'
      });
    });
    
    // Actualizar botones al cargar y al hacer scroll
    actualizarBotonesScroll(cardScrollCercanos, btnLeftCercanos, btnRightCercanos);
    cardScrollCercanos.addEventListener('scroll', () => {
      actualizarBotonesScroll(cardScrollCercanos, btnLeftCercanos, btnRightCercanos);
    });
  }
  
  // Actualizar botones al cambiar el tamaño de la ventana
  window.addEventListener('resize', function() {
    if (cardScrollNuevos && btnLeftNuevos && btnRightNuevos) {
      actualizarBotonesScroll(cardScrollNuevos, btnLeftNuevos, btnRightNuevos);
    }
    if (cardScrollCercanos && btnLeftCercanos && btnRightCercanos) {
      actualizarBotonesScroll(cardScrollCercanos, btnLeftCercanos, btnRightCercanos);
    }
  });
  
  // Configurar eventos para la búsqueda si los elementos existen
  if (formularioBusqueda && inputBusqueda) {
    console.log('Elementos para el buscador encontrados');
    
    formularioBusqueda.addEventListener('submit', function(e) {
      e.preventDefault();
      const busqueda = inputBusqueda.value.trim();
      if (busqueda) {
        buscarTrabajos(busqueda);
      }
    });
  }
  
  // Configurar eventos para los filtros si los elementos existen
  if (filtroDropdowns.length > 0) {
    console.log('Elementos para filtros encontrados:', filtroDropdowns.length);
    
    // Evento para mostrar/ocultar los dropdowns de filtros
    filtroDropdowns.forEach(function(dropdown) {
      const btn = dropdown.querySelector('.filtro-dropdown-btn');
      const content = dropdown.querySelector('.filtro-dropdown-content');
      
      if (btn && content) {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          
          // Cerrar otros dropdowns antes de abrir este
          filtroDropdowns.forEach(function(otherDropdown) {
            if (otherDropdown !== dropdown && otherDropdown.classList.contains('active')) {
              otherDropdown.classList.remove('active');
            }
          });
          
          dropdown.classList.toggle('active');
        });
      }
    });
    
    // Cerrar dropdowns cuando se hace clic fuera de ellos
    document.addEventListener('click', function(e) {
      filtroDropdowns.forEach(function(dropdown) {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove('active');
        }
      });
    });
    
    // Configurar filtrado por categorías
    const categoriasCheckboxes = document.querySelectorAll('input[name="categoria"]');
    categoriasCheckboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        // Si se selecciona "Todas", deseleccionar las demás
        if (this.id === 'cat-todos' && this.checked) {
          document.querySelectorAll('input[name="categoria"]:not(#cat-todos)').forEach(function(cb) {
            cb.checked = false;
          });
          filtrosActivos.categorias = [];
        } else {
          // Si se selecciona otra categoría, deseleccionar "Todas"
          document.getElementById('cat-todos').checked = false;
          
          // Actualizar categorías seleccionadas
          const categoriaId = parseInt(this.value);
          
          if (this.checked) {
            // Añadir a las categorías seleccionadas si no está ya
            if (!filtrosActivos.categorias.includes(categoriaId)) {
              filtrosActivos.categorias.push(categoriaId);
            }
          } else {
            // Quitar de las categorías seleccionadas
            const index = filtrosActivos.categorias.indexOf(categoriaId);
            if (index > -1) {
              filtrosActivos.categorias.splice(index, 1);
            }
          }
        }
      });
    });
    
    // Configurar filtrado por precio
    const precioRadios = document.querySelectorAll('input[name="precio"]');
    precioRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        if (this.checked) {
          filtrosActivos.precio = this.value;
        }
      });
    });
    
    // Configurar filtrado por ubicación
    const ubicacionRadios = document.querySelectorAll('input[name="ubicacion"]');
    ubicacionRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        if (this.checked) {
          filtrosActivos.ubicacion = this.value;
        }
      });
    });
    
    // Configurar ordenamiento
    const ordenRadios = document.querySelectorAll('input[name="orden"]');
    ordenRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        if (this.checked) {
          filtrosActivos.orden = this.value;
        }
      });
    });
    
    // Configurar filtro de precio personalizado
    const aplicarRangoBtn = document.getElementById('aplicarRangoPrecio');
    const minPrecioInput = document.getElementById('minPrecio');
    const maxPrecioInput = document.getElementById('maxPrecio');
    
    if (aplicarRangoBtn && minPrecioInput && maxPrecioInput) {
      aplicarRangoBtn.addEventListener('click', function() {
        const min = minPrecioInput.value.trim();
        const max = maxPrecioInput.value.trim();
        
        if (min || max) {
          // Deseleccionar otras opciones de precio
          document.querySelectorAll('input[name="precio"]').forEach(function(radio) {
            radio.checked = false;
          });
          
          // Establecer filtro de precio personalizado
          filtrosActivos.precio = `${min || 0}-${max || ''}`;
        }
      });
    }
    
    // Configurar evento para aplicar todos los filtros
    if (aplicarFiltrosBtn) {
      aplicarFiltrosBtn.addEventListener('click', function() {
        aplicarFiltros();
      });
    }
    
    // Configurar evento para limpiar todos los filtros
    if (limpiarFiltrosBtn) {
      limpiarFiltrosBtn.addEventListener('click', function() {
        // Restablecer filtros activos
        filtrosActivos = {
          categorias: [],
          precio: 'todos',
          ubicacion: 'todos',
          orden: 'reciente'
        };
        
        // Restablecer controles de UI
        document.getElementById('cat-todos').checked = true;
        document.querySelectorAll('input[name="categoria"]:not(#cat-todos)').forEach(function(checkbox) {
          checkbox.checked = false;
        });
        
        document.querySelector('input[name="precio"][value="todos"]').checked = true;
        document.querySelector('input[name="ubicacion"][value="todos"]').checked = true;
        document.querySelector('input[name="orden"][value="reciente"]').checked = true;
        
        // Limpiar inputs de rango personalizado
        if (minPrecioInput && maxPrecioInput) {
          minPrecioInput.value = '';
          maxPrecioInput.value = '';
        }
        
        // Cargar todos los trabajos sin filtros
        cargarTodosTrabajos();
      });
    }
  }
  
  // Cargar todos los trabajos al iniciar
  if (gridTrabajos) {
    cargarTodosTrabajos();
  }
});

// Función para generar estrellas de valoración
export function generarEstrellas(valoracion) {
    if (!valoracion) return 'N/A';
    
    const estrellasLlenas = Math.floor(valoracion);
    const mediaEstrella = valoracion % 1 >= 0.5 ? 1 : 0;
    const estrellasVacias = 5 - estrellasLlenas - mediaEstrella;
    
    return '★'.repeat(estrellasLlenas) + 
           (mediaEstrella ? '½' : '') + 
           '☆'.repeat(estrellasVacias);
}