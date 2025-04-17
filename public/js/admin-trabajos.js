// public/js/admin-trabajos.js

// Validaciones onblur

function validateNonEmpty(fieldId, message) {
    let field = document.getElementById(fieldId);
    let errorDiv = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
    if (field.value.trim() === '') {
        errorDiv.textContent = message;
        return false;
    } else {
        errorDiv.textContent = '';
        return true;
    }
}

function validateEmail(fieldId) {
    let field = document.getElementById(fieldId);
    let errorDiv = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (field.value.trim() === '') {
        errorDiv.textContent = "El campo Correo es obligatorio.";
        return false;
    } else if (!emailRegex.test(field.value.trim())) {
        errorDiv.textContent = "El formato del Correo es inválido.";
        return false;
    } else {
        errorDiv.textContent = "";
        return true;
    }
}

function validateOptionalPassword() {
    let password = document.getElementById('editPassword').value;
    let confirmation = document.getElementById('editPasswordConfirmation').value;
    let errorPass = document.getElementById('errorEditPassword');
    let errorConf = document.getElementById('errorEditPasswordConfirmation');

    if (password === '' && confirmation === '') {
        errorPass.textContent = "";
        errorConf.textContent = "";
        return true;
    }

    if (password.length < 6) {
        errorPass.textContent = "La nueva contraseña debe tener al menos 6 caracteres.";
        return false;
    } else {
        errorPass.textContent = "";
    }

    if (password !== confirmation) {
        errorConf.textContent = "Las contraseñas no coinciden.";
        return false;
    } else {
        errorConf.textContent = "";
    }

    return true;
}

// Función para limpiar el contenedor de errores del modal de trabajos.
function clearEditTrabajoErrors() {
    let errorsDiv = document.getElementById('editErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

// Función para cargar estados para el filtro (para el select de filtro en la vista)
function loadEstadosFiltro() {
    fetch('/api/estados/trabajos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('filterEstado');
            select.innerHTML = '<option value="">Seleccione un Estado</option>';
            data.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.nombre; // Puedes cambiar a estado.id si lo prefieres.
                option.textContent = estado.nombre;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar estados para filtro:', error));
}

// Función para cargar estados para el modal de edición de trabajos (únicamente estados de tipo "trabajos")
function loadEstadosTrabajo(selectedId) {
    fetch('/api/estados/trabajos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('editEstadoId');
            select.innerHTML = '<option value="">Seleccione un estado</option>';
            data.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.id;
                option.textContent = estado.nombre;
                if (estado.id == selectedId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar estados para edición:', error));
}

// Función para renderizar la tabla de trabajos.
function renderTrabajos(data) {
    const container = document.getElementById('trabajos-container');
    container.innerHTML = '';
    data.forEach(trabajo => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${trabajo.titulo}</td>
            <td>${trabajo.descripcion ? trabajo.descripcion.substring(0,50) : ''}</td>
            <td>${trabajo.precio}</td>
            <td>${trabajo.direccion}</td>
            <td>${trabajo.cliente ? (trabajo.cliente.nombre + ' ' + trabajo.cliente.apellidos) : ''}</td>
            <td>${trabajo.estado ? trabajo.estado.nombre : 'N/A'}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="openEditModalTrabajo(${trabajo.id})">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteTrabajo(${trabajo.id})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    });
}

// Función para obtener trabajos filtrados y renderizarlos.
function filterTrabajos() {
    const filterCliente = document.getElementById('filterCliente').value.trim();
    const filterEstado = document.getElementById('filterEstado').value.trim();

    let params = new URLSearchParams();
    if (filterCliente !== "") {
        params.append('cliente', filterCliente);
    }
    if (filterEstado !== "") {
        params.append('estado', filterEstado);
    }
    const queryString = params.toString() ? '?' + params.toString() : '';

    fetch('/api/trabajos' + queryString)
        .then(response => response.json())
        .then(data => {
            renderTrabajos(data);
        })
        .catch(error => console.error('Error al filtrar trabajos:', error));
}

// Función para abrir el modal de edición y cargar datos de un trabajo.
function openEditModalTrabajo(trabajoId) {
    clearEditTrabajoErrors();
    fetch('/api/trabajos/' + trabajoId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editTrabajoId').value = data.id;
            document.getElementById('editTitulo').value = data.titulo;
            document.getElementById('editDescripcion').value = data.descripcion;
            document.getElementById('editPrecio').value = data.precio;
            document.getElementById('editDireccion').value = data.direccion;
            if (data.cliente) {
                document.getElementById('editCliente').value = data.cliente.nombre + ' ' + data.cliente.apellidos;
            } else {
                document.getElementById('editCliente').value = '';
            }
            // Usamos la función loadEstadosTrabajo para cargar solo estados de tipo "trabajos" y marcar el actual.
            loadEstadosTrabajo(data.estado_id);
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => console.error('Error al cargar datos del trabajo:', error));
}

// Función para enviar el formulario de edición mediante fetch.
function submitEditTrabajo() {
    if (!validateNonEmpty('editTitulo', 'El campo Título es obligatorio') ||
        !validateNonEmpty('editDescripcion', 'El campo Descripción es obligatorio') ||
        !validateNonEmpty('editPrecio', 'El campo Precio es obligatorio') ||
        !validateNonEmpty('editDireccion', 'El campo Dirección es obligatorio') ||
        !validateNonEmpty('editEstadoId', 'Seleccione un estado')) {
        return;
    }
    let form = document.getElementById('editTrabajoForm');
    let trabajoId = document.getElementById('editTrabajoId').value;
    let formData = new FormData(form);
    fetch('/admin/trabajos/' + trabajoId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => { throw data; });
        }
    })
    .then(data => {
        document.activeElement.blur();
        clearEditTrabajoErrors();
        var editModalEl = document.getElementById('editModal');
        var modal = bootstrap.Modal.getInstance(editModalEl);
        modal.hide();
        filterTrabajos();
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(error => {
        let errorsDiv = document.getElementById('editErrors');
        errorsDiv.classList.remove('d-none');
        errorsDiv.innerHTML = '<ul>' + Object.values(error.errors || {}).map(err => `<li>${err}</li>`).join('') + '</ul>';
    });
}

// Función para confirmar eliminación con SweetAlert.
function confirmDeleteTrabajo(trabajoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se podrá revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteTrabajo(trabajoId);
        }
    });
}

// Función para eliminar un trabajo mediante fetch.
function deleteTrabajo(trabajoId) {
    fetch('/admin/trabajos/' + trabajoId, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Error al eliminar el trabajo.');
        }
    })
    .then(data => {
        filterTrabajos();
        Swal.fire('Eliminado', data.message, 'success');
    })
    .catch(error => console.error('Error en la petición DELETE:', error));
}

// Al cargar la página, obtener la lista de trabajos y cargar el select del filtro de estados.
document.addEventListener('DOMContentLoaded', function() {
    loadEstadosFiltro();
    filterTrabajos();
});

// Actualizar la lista cada 1 segundo.
setInterval(filterTrabajos, 1000);
