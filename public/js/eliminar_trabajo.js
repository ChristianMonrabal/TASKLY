function confirmDeleteTrabajo(trabajoId) {
    Swal.fire({
        title: '¿Estás seguro que quieres eliminar este trabajo?',
        text: "Esta acción no se podrá revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear un formulario para la eliminación
            const form = document.createElement('form');
            form.method = 'POST';  // Aseguramos que sea un POST
            form.action = `/trabajos/${trabajoId}`;  // Asegúrate de que esta ruta coincida

            // Crear un campo CSRF (seguridad de Laravel)
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Obtener el CSRF token
            form.appendChild(csrfToken);

            // Crear el campo para el método DELETE
            const deleteMethod = document.createElement('input');
            deleteMethod.type = 'hidden';
            deleteMethod.name = '_method';
            deleteMethod.value = 'DELETE';  // Especificar que el método es DELETE
            form.appendChild(deleteMethod);

            // Agregar el formulario al body
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
        }
    });
}
