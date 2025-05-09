/**
 * Simple filtering script for TASKLY
 * Only handles filtering by name and category
 */

document.addEventListener('DOMContentLoaded', function () {
    const dropdownHeader = document.getElementById('dropdownHeader');
    const dropdownOptions = document.getElementById('dropdownOptions');
    const categoriaCheckboxes = document.querySelectorAll('.categoria-checkbox');
    const gridTrabajos = document.getElementById('gridTrabajos');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const inputBusqueda = document.getElementById('inputBusqueda');

    // Inicializar filtros
    let filtros = {
        busqueda: '',
        categorias: []
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

            aplicarFiltros();
        });
    });

    // Manejar búsqueda por nombre o descripción
    inputBusqueda.addEventListener('input', debounce(function () {
        filtros.busqueda = this.value.trim();
        aplicarFiltros();
    }, 300));

    // Botón para borrar filtros
    clearFiltersBtn.addEventListener('click', function () {
        filtros.busqueda = '';
        filtros.categorias = [];
        inputBusqueda.value = '';
        categoriaCheckboxes.forEach(checkbox => checkbox.checked = false);
        aplicarFiltros();
    });

    // Función para aplicar los filtros
    function aplicarFiltros() {
        gridTrabajos.innerHTML = '<div class="loading">Buscando trabajos...</div>';

        // Construir la URL con los parámetros de los filtros
        let url = '/trabajos/filtrar-simple?';
        let params = [];

        if (filtros.busqueda) {
            params.push('busqueda=' + encodeURIComponent(filtros.busqueda));
        }

        if (filtros.categorias.length > 0) {
            params.push('categorias=' + encodeURIComponent(filtros.categorias.join(',')));
        }

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
            })
            .catch(error => {
                console.error('Error al aplicar filtros:', error);
                gridTrabajos.innerHTML = '<div class="no-trabajos">Error al buscar trabajos. Intenta de nuevo.</div>';
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
});
