// JavaScript para la funcionalidad de descarga de facturas como PDF
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad para el botón de descargar PDF
    const btnDescargar = document.querySelector('.btn-descargar');
    if (btnDescargar) {
        btnDescargar.addEventListener('click', function() {
            // Primero ocultamos los botones para que no aparezcan en el PDF
            const botonesContainer = document.querySelector('.factura-footer .text-center');
            const displayOriginal = botonesContainer.style.display;
            botonesContainer.style.display = 'none';
            
            // Indicador de proceso
            const indicador = document.createElement('div');
            indicador.innerHTML = '<div style="position: fixed; top: 20px; right: 20px; background: #EC6A6A; color: white; padding: 10px 20px; border-radius: 4px; z-index: 9999;"><i class="fas fa-spinner fa-spin"></i> Generando PDF...</div>';
            document.body.appendChild(indicador);
            
            // Dar tiempo a que se actualice el DOM
            setTimeout(function() {
                // Abrimos una nueva ventana para imprimir solo la factura
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Factura TASKLY</title>');
                
                // Copiamos todos los estilos de la página actual
                document.querySelectorAll('link[rel="stylesheet"]').forEach(function(link) {
                    printWindow.document.write('<link rel="stylesheet" href="' + link.href + '">');
                });
                
                // Estilos adicionales específicos para impresión
                printWindow.document.write(`
                <style>
                    body { font-family: Arial, sans-serif; background-color: white; }
                    .factura-container { max-width: 800px; margin: 0 auto; padding: 20px; }
                    .factura-table { width: 100%; border-collapse: collapse; }
                    .factura-table th { background-color: #f8f8f8; padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                    .factura-table td { padding: 10px; border-bottom: 1px solid #eee; }
                    .factura-table .price { text-align: right; }
                    .factura-totals { margin-left: auto; width: 300px; }
                    .factura-totals .row { display: flex; justify-content: space-between; padding: 5px 0; }
                    .factura-totals .total { font-weight: bold; color: #EC6A6A; font-size: 18px; }
                    .factura-footer .text-center { display: none; }
                </style>
                `);
                
                printWindow.document.write('</head><body>');
                
                // Copiamos solo el contenido de la factura
                const facturaHTML = document.querySelector('.factura-container').outerHTML;
                printWindow.document.write(facturaHTML);
                
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                
                // Esperamos a que cargue el contenido
                printWindow.onload = function() {
                    try {
                        printWindow.focus();
                        printWindow.print();
                        printWindow.close();
                    } catch (e) {
                        console.error('Error al imprimir:', e);
                        printWindow.close();
                    }
                    
                    // Restauramos los botones y quitamos el indicador
                    botonesContainer.style.display = displayOriginal;
                    document.body.removeChild(indicador);
                };
            }, 500);
        });
    }
});
