document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-date-btn").forEach(button => {
        button.addEventListener("click", async () => {
            const trabajoId = button.getAttribute("data-trabajo-id");

            let fechaExistente = null;

            try {
                const response = await fetch(`/calendario/fecha/${trabajoId}`);
                if (response.ok) {
                    const data = await response.json();
                    fechaExistente = data.fecha || null;
                }
            } catch (error) {
                console.warn('No se pudo recuperar la fecha existente:', error);
            }

            Swal.fire({
                title: 'Selecciona una fecha de encuentro',
                input: 'date',
                inputLabel: 'Fecha',
                inputValue: fechaExistente,
                inputAttributes: {
                    min: new Date().toISOString().split("T")[0]
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: (fecha) => {
                    if (!fecha) {
                        Swal.showValidationMessage('Debes seleccionar una fecha');
                        return false;
                    }
                    return fecha;
                }
            }).then(result => {
                if (result.isConfirmed && result.value) {
                    const endpoint = fechaExistente ? '/calendario/actualizar' : '/calendario/insertar';

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            trabajo_id: trabajoId,
                            fecha: result.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Guardado',
                                text: data.success,
                                icon: 'success'
                            });
                        } else if (data.error) {
                            Swal.fire({
                                title: 'Error',
                                text: data.error,
                                icon: 'error'
                            });
                        } else {
                            Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error);
                        Swal.fire('Error', 'Hubo un problema al guardar la fecha.', 'error');
                    });
                }
            });
        });
    });
});
