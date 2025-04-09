/**
 * Módulo para el sistema de filtros
 * Este archivo contiene las funciones relacionadas con la aplicación y manejo de filtros
 */

/**
 * Función para aplicar todos los filtros seleccionados
 * Procesa los filtros activos y realiza una solicitud al servidor
 */
function aplicarFiltros() {
  console.log('Aplicando filtros...');
  
  // URL base para la solicitud de filtrado
  let url = '/trabajos/filtrar?';
  
  // Añadir filtro de categorías si hay alguna seleccionada
  if (filtrosActivos.categorias.length > 0) {
    filtrosActivos.categorias.forEach(function(categoriaId, index) {
      url += `categorias[]=${categoriaId}` + (index < filtrosActivos.categorias.length - 1 ? '&' : '');
    });
  }
  
  // Añadir filtro de precio si es diferente de 'todos'
  if (filtrosActivos.precio !== 'todos') {
    // Si ya hay parámetros en la URL, añadir un &
    if (url.charAt(url.length - 1) !== '?') {
      url += '&';
    }
    
    // Si es un rango personalizado (contiene un guion)
    if (filtrosActivos.precio.includes('-')) {
      const [min, max] = filtrosActivos.precio.split('-');
      url += `precio_min=${min}`;
      if (max) {
        url += `&precio_max=${max}`;
      }
    } else {
      // Si es una opción predefinida
      url += `precio=${filtrosActivos.precio}`;
    }
  }
  
  // Añadir filtro de ubicación si es diferente de 'todos'
  if (filtrosActivos.ubicacion !== 'todos') {
    // Si ya hay parámetros en la URL, añadir un &
    if (url.charAt(url.length - 1) !== '?') {
      url += '&';
    }
    
    url += `ubicacion=${filtrosActivos.ubicacion}`;
  }
  
  // Añadir ordenamiento si es diferente del predeterminado
  if (filtrosActivos.orden !== 'reciente') {
    // Si ya hay parámetros en la URL, añadir un &
    if (url.charAt(url.length - 1) !== '?') {
      url += '&';
    }
    
    url += `orden=${filtrosActivos.orden}`;
  }
  
  // Si no se aplicó ningún filtro, cargar todos los trabajos
  if (url === '/trabajos/filtrar?') {
    cargarTodosTrabajos();
    return;
  }
  
  // Mostrar indicador de carga
  gridTrabajos.innerHTML = '<div class="loading">Aplicando filtros...</div>';
  
  console.log('Filtros activos:', filtrosActivos);
  
  fetch(url)
    .then(function(response) {
      console.log('Respuesta del servidor:', response.status);
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor: ' + response.status);
      }
      return response.json();
    })
    .then(function(data) {
      console.log('Datos filtrados recibidos:', data ? (data.length + ' trabajos') : 'No hay datos');
      // Si hay datos, mostrar el primero para depuración
      if (data && data.length > 0) {
        console.log('Ejemplo de trabajo recibido:', {
          id: data[0].id,
          titulo: data[0].titulo,
          categorias: data[0].categoriastipotrabajo,
          imagenes: data[0].imagenes
        });
      }
      renderizarTodosTrabajos(data);
    })
    .catch(function(error) {
      console.error('Error al aplicar filtros:', error);
      gridTrabajos.innerHTML = '<div class="no-trabajos">Error al aplicar filtros. Intenta de nuevo.</div>';
    });
}
