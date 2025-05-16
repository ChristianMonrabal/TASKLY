document.addEventListener('DOMContentLoaded', function() {
    const filtro = document.getElementById('filtroPostulaciones');
    const items = document.querySelectorAll('.trabajo-item');

    if (filtro && items.length) {
        filtro.value = 'todos';
        items.forEach(function(item) {
            item.style.display = '';
        });

        filtro.addEventListener('change', function() {
            const valor = this.value;
            items.forEach(function(item) {
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