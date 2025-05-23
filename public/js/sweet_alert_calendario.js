document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-date-btn").forEach(button => {
        button.addEventListener("click", async () => {
            const trabajoId = button.getAttribute("data-trabajo-id");

            let fechaExistente = null;
            let horaExistente = null;

            try {
                const response = await fetch(`/calendario/fecha/${trabajoId}`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.fecha) {
                        // data.fecha aquí es fecha sola (2025-05-25) o datetime?
                        // Si es datetime, separamos, si no, se asigna directamente
                        if (data.hora) {
                            fechaExistente = data.fecha;
                            horaExistente = data.hora.slice(0, 5);
                        } else {
                            // Por si acaso sólo fecha
                            fechaExistente = data.fecha;
                            horaExistente = '';
                        }
                    }
                }
            } catch (error) {
                console.warn('No se pudo recuperar la fecha existente:', error);
            }

            Swal.fire({
                title: 'Selecciona una fecha y hora de encuentro',
                html: `
                    <input type="date" id="fechaInput" class="swal2-input" value="${fechaExistente || ''}" min="${new Date().toISOString().split("T")[0]}">
                    <input type="time" id="horaInput" class="swal2-input" value="${horaExistente || ''}">
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const fecha = document.getElementById('fechaInput').value;
                    const hora = document.getElementById('horaInput').value;
                    if (!fecha || !hora) {
                        Swal.showValidationMessage('Debes seleccionar una fecha y una hora');
                        return false;
                    }
                    return { fecha, hora };
                }
            }).then(result => {
                if (result.isConfirmed && result.value) {
                    const { fecha, hora } = result.value;
                    const endpoint = fechaExistente ? '/calendario/actualizar' : '/calendario/insertar';

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            trabajo_id: trabajoId,
                            fecha: fecha,
                            hora: hora
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
                        Swal.fire('Error', 'Hubo un error en la petición.', 'error');
                    });
                }
            });
        });
    });
});
