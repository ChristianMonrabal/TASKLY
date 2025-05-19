document.addEventListener('DOMContentLoaded', function() {
    const filtro = document.getElementById('filtroTrabajos');
    const trabajos = document.querySelectorAll('.trabajo-item');

    // Mostrar solo activos por defecto
    if (filtro && trabajos.length) {
        filtro.value = 'activos';
        trabajos.forEach(function(item) {
            if (item.dataset.estado === 'activos') {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });

        filtro.addEventListener('change', function() {
            const valor = this.value;
            trabajos.forEach(function(item) {
                if (valor === 'todos') {
                    item.style.display = '';
                } else if (item.dataset.estado === valor) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});