/**
 * Simple filtering script for TASKLY
 * Only handles filtering by name and category
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get references to filter elements
    const inputBusqueda = document.getElementById('inputBusqueda');
    const selectCategoria = document.getElementById('selectCategoria');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const gridTrabajos = document.getElementById('gridTrabajos');
    
    // Initialize filters
    let filtros = {
        busqueda: '',
        categoria: 'todas'
    };
    
    // Add event listeners
    inputBusqueda.addEventListener('input', debounce(function() {
        filtros.busqueda = this.value.trim();
        aplicarFiltros();
    }, 500));
    
    selectCategoria.addEventListener('change', function() {
        filtros.categoria = this.value;
        aplicarFiltros();
    });
    
    // Clear filters button functionality
    clearFiltersBtn.addEventListener('click', function() {
        // Reset the input fields
        inputBusqueda.value = '';
        selectCategoria.value = 'todas';
        
        // Reset the filters object
        filtros = {
            busqueda: '',
            categoria: 'todas'
        };
        
        // Apply the cleared filters to refresh the results
        aplicarFiltros();
    });
    
    // Function to apply filters
    function aplicarFiltros() {
        gridTrabajos.innerHTML = '<div class="loading">Buscando trabajos...</div>';
        
        // Build URL with query params
        let url = '/trabajos/filtrar-simple?';
        let params = [];
        
        if (filtros.busqueda) {
            params.push('busqueda=' + encodeURIComponent(filtros.busqueda));
        }
        
        if (filtros.categoria && filtros.categoria !== 'todas') {
            params.push('categoria=' + encodeURIComponent(filtros.categoria));
        }
        
        url += params.join('&');
        
        // Fetch filtered results
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
    
    // Load all jobs initially
    aplicarFiltros();
    
    // Debounce function to prevent excessive API calls
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
});
