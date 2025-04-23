document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-date-btn").forEach(button => {
        button.addEventListener("click", () => {
            Swal.fire({
                title: 'Selecciona una fecha de encuentro',
                input: 'date',
                inputLabel: 'Fecha',
                inputAttributes: {
                    min: new Date().toISOString().split("T")[0]
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: (fecha) => {
                    if (!fecha) {
                        Swal.showValidationMessage('Debes seleccionar una fecha');
                    }
                    return fecha;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire('¡Fecha seleccionada!', `Fecha elegida: ${result.value}`, 'info');
                }
            });
        });
    });
});