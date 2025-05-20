document.addEventListener('DOMContentLoaded', function () {
    const feedback = window.profileFeedback || {};

    if (feedback.success) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: feedback.success,
            confirmButtonColor: '#EC6A6A'
        });
    }

    if (feedback.error) {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: feedback.error,
            confirmButtonColor: '#EC6A6A'
        });
    }
});
