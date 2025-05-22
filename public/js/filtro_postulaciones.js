document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const filtroEstado = document.getElementById('filtroPostulaciones');
    const inputBusqueda = document.getElementById('inputBusqueda');
    const categoriasCheckboxes = document.querySelectorAll('.postulacion-categoria-checkbox'); // Ajustada para ser más específica
    const dropdownHeader = document.getElementById('dropdownHeaderPostulaciones');
    const dropdownOptions = document.getElementById('dropdownOptionsPostulaciones');
    const clearFiltersBtn = document.getElementById('clearFiltersPostulaciones');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const trabajosContainer = document.querySelector('.row.postulaciones-grid');
    const paginadorContainer = document.getElementById('paginadorContainer');
    const items = document.querySelectorAll('.trabajo-item');

    // Objeto para almacenar los filtros activos
    let filtrosActivos = {
        estado: 'todos',
        busqueda: '',
        categorias: [],
        pagina: 1, // Añadimos la paginación
        itemsPorPagina: 8 // Valor por defecto
    };

    // Inicializar valores - no establecemos valor por defecto para usar el 'Elegir estado'
    if (inputBusqueda) inputBusqueda.value = '';

    // Escuchar cambios en el filtro de estado
    if (filtroEstado) {
        filtroEstado.addEventListener('change', function() {
            filtrosActivos.estado = this.value;
            aplicarFiltros();
        });
    }

    // Escuchar cambios en la búsqueda
    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', debounce(function() {
            filtrosActivos.busqueda = this.value.trim().toLowerCase();
            aplicarFiltros();
        }, 300));
    }

    // Escuchar cambios en las categorías
    if (categoriasCheckboxes.length) {
        categoriasCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    filtrosActivos.categorias.push(this.value);
                } else {
                    filtrosActivos.categorias = filtrosActivos.categorias.filter(cat => cat !== this.value);
                }
                aplicarFiltros();
            });
        });
    }
    
    // Escuchar cambios en elementos por página
    if (itemsPerPageSelect) {
        itemsPerPageSelect.addEventListener('change', function() {
            if (this.value) {
                filtrosActivos.itemsPorPagina = parseInt(this.value);
                filtrosActivos.pagina = 1; // Resetear a página 1 cuando se cambia los items por página
                aplicarFiltros();
            }
        });
    }

    // Mostrar/ocultar opciones de categorías al hacer clic en el encabezado
    if (dropdownHeader && dropdownOptions) {
        dropdownHeader.addEventListener('click', function() {
            dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
        });

        // Cerrar el dropdown si se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!dropdownHeader.contains(e.target) && !dropdownOptions.contains(e.target)) {
                dropdownOptions.style.display = 'none';
            }
        });
    }

    // Botón para limpiar filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Restablecer filtros
            filtrosActivos.estado = 'todos';
            filtrosActivos.busqueda = '';
            filtrosActivos.categorias = [];
            filtrosActivos.pagina = 1;
            filtrosActivos.itemsPorPagina = 8; // Volver al valor por defecto
            
            // Restablecer controles visuales
            if (filtroEstado) filtroEstado.selectedIndex = 0;
            if (inputBusqueda) inputBusqueda.value = '';
            if (itemsPerPageSelect) itemsPerPageSelect.selectedIndex = 0;
            categoriasCheckboxes.forEach(checkbox => checkbox.checked = false);
            
            // Aplicar filtros (mostrar todos)
            aplicarFiltros();
        });
    }

    // Función para aplicar los filtros combinados
    function aplicarFiltros() {
        // Para la versión con paginador, usamos los datos filtrados y luego calculamos la página
        let itemsFiltrados = Array.from(items).filter(item => {
            // Por defecto, visible
            let visible = true;
            
            // Filtrar por estado
            if (filtrosActivos.estado !== 'todos' && item.dataset.estado !== filtrosActivos.estado) {
                visible = false;
            }
            
            // Filtrar por texto de búsqueda
            if (filtrosActivos.busqueda && visible) {
                const titulo = item.querySelector('.card-title')?.textContent.toLowerCase() || '';
                const descripcion = item.querySelector('.card-text')?.textContent.toLowerCase() || '';
                if (!titulo.includes(filtrosActivos.busqueda) && !descripcion.includes(filtrosActivos.busqueda)) {
                    visible = false;
                }
            }
            
            // Filtrar por categoría
            if (filtrosActivos.categorias.length > 0 && visible) {
                const categoriasItem = item.dataset.categorias?.split(',') || [];
                // Convertir ambos a string para comparar correctamente
                const coincide = filtrosActivos.categorias.some(cat => 
                    categoriasItem.some(itemCat => String(itemCat) === String(cat))
                );
                if (!coincide) {
                    visible = false;
                }
            }
            
            return visible;
        });
        
        // Configuración de paginación
        const itemsPorPagina = filtrosActivos.itemsPorPagina;
        const totalPaginas = Math.ceil(itemsFiltrados.length / itemsPorPagina);
        
        // Ajustar página actual si es necesario
        if (filtrosActivos.pagina > totalPaginas && totalPaginas > 0) {
            filtrosActivos.pagina = 1;
        }
        
        // Ocultar todos los items primero
        items.forEach(item => {
            item.style.display = 'none';
        });
        
        // Calcular índices para la paginación
        const inicio = (filtrosActivos.pagina - 1) * itemsPorPagina;
        const fin = Math.min(inicio + itemsPorPagina, itemsFiltrados.length);
        
        // Mostrar solo los items de la página actual
        for (let i = inicio; i < fin; i++) {
            itemsFiltrados[i].style.display = '';
        }
        
        // Renderizar paginador
        renderizarPaginador({
            total: itemsFiltrados.length,
            per_page: itemsPorPagina,
            current_page: filtrosActivos.pagina,
            last_page: totalPaginas
        });
        
        // Actualizar AOS para las animaciones
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }
    
    // Función para renderizar el paginador
    function renderizarPaginador(data) {
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
        
        // Actualizar el paginador
        paginadorContainer.innerHTML = html;
        
        // Añadir eventos a los botones de paginación
        document.querySelectorAll('.paginador-btn:not(.disabled)').forEach(btn => {
            btn.addEventListener('click', function() {
                const pagina = parseInt(this.getAttribute('data-pagina'));
                if (pagina && pagina !== filtrosActivos.pagina) {
                    filtrosActivos.pagina = pagina;
                    aplicarFiltros();
                    // Hacer scroll hacia arriba cuando se cambia de página
                    window.scrollTo({ top: trabajosContainer.offsetTop - 100, behavior: 'smooth' });
                }
            });
        });
    }
    
    // Función debounce para evitar exceso de llamadas
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
    
    // Inicialmente, mostrar todos
    aplicarFiltros();
});

