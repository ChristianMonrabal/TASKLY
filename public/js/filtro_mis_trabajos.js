document.addEventListener('DOMContentLoaded', function() {
    const filtro = document.getElementById('filtroTrabajos');
    const trabajos = document.querySelectorAll('.trabajo-item');
    const mensajeFinalizados = document.getElementById('mensajeFinalizados');

    function actualizarFiltro(valor) {
        let visibles = 0;

        trabajos.forEach(function(item) {
            if (valor === 'todos') {
                item.style.display = '';
                visibles++;
            } else if (item.dataset.estado === valor) {
                item.style.display = '';
                visibles++;
            } else {
                item.style.display = 'none';
            }
        });

        // Mostrar mensaje si filtro es 'finalizados' y no hay visibles
        if (valor === 'finalizados' && visibles === 0) {
            mensajeFinalizados.style.display = 'block';
        } else {
            mensajeFinalizados.style.display = 'none';
        }
    }

    if (filtro && trabajos.length) {
        filtro.value = 'activos';
        actualizarFiltro('activos');

        filtro.addEventListener('change', function() {
            actualizarFiltro(this.value);
        });
    }
});
