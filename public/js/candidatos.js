/**
 * Funciones para la gestión de candidatos en TASKLY
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Módulo de candidatos cargado correctamente');
    
    // Asignar eventos a los botones si existen
    const botonesAceptar = document.querySelectorAll('.action-icon.accept');
    const botonesRechazar = document.querySelectorAll('.action-icon.reject');
    
    botonesAceptar.forEach(boton => {
        boton.addEventListener('click', function(event) {
            const postulacionId = this.getAttribute('data-id');
            // Obtenemos el nombre del trabajador para personalizar el mensaje
            const nombreCandidato = this.closest('.candidate-card').querySelector('.card-title').textContent;
            aceptarCandidato(postulacionId, nombreCandidato, event);
        });
    });
    
    botonesRechazar.forEach(boton => {
        boton.addEventListener('click', function(event) {
            const postulacionId = this.getAttribute('data-id');
            // Obtenemos el nombre del trabajador para personalizar el mensaje
            const nombreCandidato = this.closest('.candidate-card').querySelector('.card-title').textContent;
            rechazarCandidato(postulacionId, nombreCandidato, event);
        });
    });
});

/**
 * Acepta un candidato para un trabajo
 * @param {number} postulacionId - ID de la postulación a aceptar
 * @param {string} nombreCandidato - Nombre del candidato
 * @param {Event} event - Evento del DOM
 */
function aceptarCandidato(postulacionId, nombreCandidato, event) {
    event.preventDefault();
    
    // Usar SweetAlert2 para la confirmación
    Swal.fire({
        title: 'Aceptar candidato',
        html: `¿Estás seguro de que deseas aceptar a <strong>${nombreCandidato}</strong>?<br><small>Al aceptar este candidato, todos los demás serán rechazados automáticamente.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            const boton = event.target.closest('.action-icon');
            const iconoOriginal = boton.innerHTML;
            boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            boton.style.pointerEvents = 'none';
            
            // Mostrar carga global
            Swal.fire({
                title: 'Procesando...',
                text: 'Aceptando candidato, por favor espera.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Obtener el token CSRF
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Crear el objeto FormData
            const formData = new FormData();
            formData.append('_token', token);
            
            // Realizar la petición AJAX
            fetch(`/postulaciones/${postulacionId}/aceptar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                
                // Notificación de éxito con SweetAlert2
                Swal.fire({
                    title: '¡Candidato aceptado!',
                    html: `<strong>${nombreCandidato}</strong> ha sido aceptado correctamente.<br><small>La página se actualizará automáticamente.</small>`,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Notificación de error con SweetAlert2
                Swal.fire({
                    title: 'Error',
                    text: 'Error al aceptar el candidato. Por favor, inténtalo de nuevo.',
                    icon: 'error'
                });
                
                // Restaurar el botón
                boton.innerHTML = iconoOriginal;
                boton.style.pointerEvents = 'auto';
            });
        }
    });
}

/**
 * Rechaza un candidato para un trabajo
 * @param {number} postulacionId - ID de la postulación a rechazar
 * @param {string} nombreCandidato - Nombre del candidato
 * @param {Event} event - Evento del DOM
 */
function rechazarCandidato(postulacionId, nombreCandidato, event) {
    event.preventDefault();
    
    // Usar SweetAlert2 para la confirmación
    Swal.fire({
        title: 'Rechazar candidato',
        html: `¿Estás seguro de que deseas rechazar a <strong>${nombreCandidato}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            const boton = event.target.closest('.action-icon');
            const iconoOriginal = boton.innerHTML;
            boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            boton.style.pointerEvents = 'none';
            
            // Mostrar carga global
            Swal.fire({
                title: 'Procesando...',
                text: 'Rechazando candidato, por favor espera.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Obtener el token CSRF
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Crear el objeto FormData
            const formData = new FormData();
            formData.append('_token', token);
            
            // Realizar la petición AJAX
            fetch(`/postulaciones/${postulacionId}/rechazar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                
                // Notificación de éxito con SweetAlert2
                Swal.fire({
                    title: 'Candidato rechazado',
                    html: `<strong>${nombreCandidato}</strong> ha sido rechazado correctamente.<br><small>La página se actualizará automáticamente.</small>`,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Notificación de error con SweetAlert2
                Swal.fire({
                    title: 'Error',
                    text: 'Error al rechazar el candidato. Por favor, inténtalo de nuevo.',
                    icon: 'error'
                });
                
                // Restaurar el botón
                boton.innerHTML = iconoOriginal;
                boton.style.pointerEvents = 'auto';
            });
        }
    });
}
