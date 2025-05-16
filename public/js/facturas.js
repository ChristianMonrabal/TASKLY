// JavaScript para la funcionalidad de impresión de facturas
document.addEventListener('DOMContentLoaded', function() {
    // Cuando el botón de imprimir es clickeado
    const btnImprimir = document.querySelector('.btn-imprimir');
    if (btnImprimir) {
        btnImprimir.addEventListener('click', function() {
            window.print();
        });
    }
});
