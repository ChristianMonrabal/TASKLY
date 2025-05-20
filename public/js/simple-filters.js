/**
 * Simple filtering script for TASKLY
 * Only handles filtering by name and category
 * With pagination support
 */

document.addEventListener('DOMContentLoaded', function () {
    const dropdownHeader = document.getElementById('dropdownHeader');
    const dropdownOptions = document.getElementById('dropdownOptions');
    const categoriaCheckboxes = document.querySelectorAll('.categoria-checkbox');
    const gridTrabajos = document.getElementById('gridTrabajos');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const inputBusqueda = document.getElementById('inputBusqueda');
    const inputCodigoPostal = document.getElementById('inputCodigoPostal');
    const paginadorContainer = document.getElementById('paginadorContainer') || document.createElement('div');
    
    // Obtener el contenedor designado para el selector de elementos por página (junto al título "Todos los trabajos")
    let perPageContainer = document.getElementById('perPageContainer');
    
    // Crear selector de elementos por página si no existe
    let perPageSelector = document.getElementById('perPageSelector');
    if (!perPageSelector && perPageContainer) {
        perPageContainer.innerHTML = `
            <span>Mostrar:</span>
            <select id="perPageSelector" class="per-page-selector">
                <option value="6">6</option>
                <option value="12" selected>12</option>
                <option value="24">24</option>
                <option value="48">48</option>
            </select>
        `;
        
        perPageSelector = document.getElementById('perPageSelector');
    }
    
    // Configurar el contenedor del paginador (solo en la parte inferior)
    if (!paginadorContainer.id) {
        paginadorContainer.id = 'paginadorContainer';
        paginadorContainer.className = 'paginador-container';
        
        // Colocar solo después del grid de trabajos
        if (gridTrabajos) {
            gridTrabajos.parentNode.insertBefore(paginadorContainer, gridTrabajos.nextSibling);
        }
    }

    // Inicializar filtros y paginación
    let filtros = {
        busqueda: '',
        codigo_postal: inputCodigoPostal ? inputCodigoPostal.value.trim() : '',
        categorias: [],
        pagina: 1,
        per_page: perPageSelector ? parseInt(perPageSelector.value) : 12
    };

    // Mostrar/ocultar las opciones al hacer clic en el encabezado
    dropdownHeader.addEventListener('click', function () {
        dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
    });

    // Manejar selección de categorías
    categoriaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const value = this.value;

            if (this.checked) {
                filtros.categorias.push(value);
            } else {
                filtros.categorias = filtros.categorias.filter(categoria => categoria !== value);
            }
            
            filtros.pagina = 1; // Volver a la primera página al cambiar filtros
            aplicarFiltros();
        });
    });

    // Manejar búsqueda por nombre o descripción
    inputBusqueda.addEventListener('input', debounce(function () {
        filtros.busqueda = this.value.trim();
        filtros.pagina = 1; // Volver a la primera página al cambiar filtros
        aplicarFiltros();
    }, 300));
    
    // Manejar filtro por código postal
    if (inputCodigoPostal) {
        inputCodigoPostal.addEventListener('input', debounce(function () {
            filtros.codigo_postal = this.value.trim();
            filtros.pagina = 1; // Volver a la primera página al cambiar filtros
            aplicarFiltros();
        }, 300));
    }

    // Botón para borrar filtros
    clearFiltersBtn.addEventListener('click', function () {
        filtros.busqueda = '';
        filtros.codigo_postal = '';
        filtros.categorias = [];
        filtros.pagina = 1; // Volver a la primera página
        inputBusqueda.value = '';
        if (inputCodigoPostal) inputCodigoPostal.value = '';
        categoriaCheckboxes.forEach(checkbox => checkbox.checked = false);
        aplicarFiltros();
    });
    
    // Manejar cambio en selector de elementos por página
    if (perPageSelector) {
        perPageSelector.addEventListener('change', function() {
            filtros.per_page = parseInt(this.value);
            filtros.pagina = 1; // Volver a la primera página al cambiar elementos por página
            aplicarFiltros();
        });
    }

    // Función para aplicar los filtros
    function aplicarFiltros() {
        gridTrabajos.innerHTML = '<div class="loading">Buscando trabajos...</div>';

        // Construir la URL con los parámetros de los filtros
        let url = '/trabajos/filtrar-simple?';
        let params = [];

        if (filtros.busqueda) {
            params.push('busqueda=' + encodeURIComponent(filtros.busqueda));
        }
        
        if (filtros.codigo_postal) {
            params.push('codigo_postal=' + encodeURIComponent(filtros.codigo_postal));
        }

        if (filtros.categorias.length > 0) {
            params.push('categorias=' + encodeURIComponent(filtros.categorias.join(',')));
        }
        
        // Añadir parámetros de paginación
        params.push('page=' + filtros.pagina);
        params.push('per_page=' + filtros.per_page);

        url += params.join('&');

        // Realizar la solicitud para obtener los trabajos filtrados
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                renderizarTodosTrabajos(data);
                renderizarPaginador(data);
            })
            .catch(error => {
                console.error('Error al aplicar filtros:', error);
                gridTrabajos.innerHTML = '<div class="no-trabajos">Error al buscar trabajos. Intenta de nuevo.</div>';
                paginadorContainer.innerHTML = '';
            });
    }

    // Cerrar el desplegable si se hace clic fuera de él
    document.addEventListener('click', function (e) {
        if (!dropdownHeader.contains(e.target) && !dropdownOptions.contains(e.target)) {
            dropdownOptions.style.display = 'none';
        }
    });

    // Cargar todos los trabajos inicialmente
    aplicarFiltros();

    // Función de debounce para evitar múltiples solicitudes
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Función para renderizar el paginador
    function renderizarPaginador(data) {
        // Ya no mostramos la información de resultados
        
        // Si no hay resultados, vaciar el paginador
        if (!data.total || data.total === 0) {
            paginadorContainer.innerHTML = '';
            return;
        }
        
        const totalPaginas = data.last_page;
        const paginaActual = data.current_page;
        let html = '<div class="paginador">';
        
        // Botón anterior
        if (paginaActual > 1) {
            html += `<button class="paginador-btn" data-pagina="${paginaActual - 1}">Anterior</button>`;
        } else {
            html += `<button class="paginador-btn disabled">Anterior</button>`;
        }
        
        // Botones de páginas
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);
        
        // Ajustar para mostrar siempre 5 botones (si hay suficientes páginas)
        if (fin - inicio + 1 < 5 && totalPaginas >= 5) {
            if (inicio === 1) {
                fin = Math.min(5, totalPaginas);
            } else if (fin === totalPaginas) {
                inicio = Math.max(1, totalPaginas - 4);
            }
        }
        
        // Primera página si hay un gap
        if (inicio > 1) {
            html += `<button class="paginador-btn" data-pagina="1">1</button>`;
            if (inicio > 2) {
                html += `<span class="paginador-ellipsis">...</span>`;
            }
        }
        
        // Números de página centrales
        for (let i = inicio; i <= fin; i++) {
            html += `<button class="paginador-btn ${i === paginaActual ? 'active' : ''}" data-pagina="${i}">${i}</button>`;
        }
        
        // Última página si hay un gap
        if (fin < totalPaginas) {
            if (fin < totalPaginas - 1) {
                html += `<span class="paginador-ellipsis">...</span>`;
            }
            html += `<button class="paginador-btn" data-pagina="${totalPaginas}">${totalPaginas}</button>`;
        }
        
        // Botón siguiente
        if (paginaActual < totalPaginas) {
            html += `<button class="paginador-btn" data-pagina="${paginaActual + 1}">Siguiente</button>`;
        } else {
            html += `<button class="paginador-btn disabled">Siguiente</button>`;
        }
        
        html += '</div>';
        
        // Actualizar el paginador inferior
        paginadorContainer.innerHTML = html;
        
        // Añadir eventos a los botones de paginación (tanto arriba como abajo)
        document.querySelectorAll('.paginador-btn:not(.disabled)').forEach(btn => {
            btn.addEventListener('click', function() {
                const pagina = parseInt(this.getAttribute('data-pagina'));
                if (pagina && pagina !== filtros.pagina) {
                    filtros.pagina = pagina;
                    aplicarFiltros();
                    // Si se hace clic en el paginador superior, no hace falta scroll
                    // Si se hace clic en el paginador inferior, hacer scroll hacia arriba
                    if (this.closest('#paginadorContainer')) {
                        window.scrollTo({ top: gridTrabajos.offsetTop - 100, behavior: 'smooth' });
                    }
                }
            });
        });
    }
    
    // Cargar los trabajos inicialmente
    aplicarFiltros();
});
