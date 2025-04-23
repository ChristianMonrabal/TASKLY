// public/js/admin-usuarios.js

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

// Función para limpiar el contenedor de errores del modal.
function clearEditUsuarioErrors() {
    let errorsDiv = document.getElementById('editErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

// Función para renderizar la tabla de usuarios.
function renderUsuarios(data) {
    const container = document.getElementById('usuarios-container');
    container.innerHTML = '';
    data.forEach(usuario => {
        const rolNombre = usuario.rol ? (usuario.rol.nombre || usuario.rol.name || '') : '';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${usuario.nombre}</td>
            <td>${usuario.apellidos}</td>
            <td>${usuario.email}</td>
            <td>${usuario.telefono || ''}</td>
            <td>${usuario.fecha_nacimiento ? new Date(usuario.fecha_nacimiento).toLocaleDateString() : ''}</td>
            <td>${usuario.dni || ''}</td>
            <td>${rolNombre}</td>
            <td>${usuario.descripcion || ''}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="openEditModal(${usuario.id})">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteUsuario(${usuario.id})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    });
}

// Función para obtener usuarios filtrados y renderizarlos.
function filterUsuarios() {
    const filterNombre = document.getElementById('filterNombre').value.trim();
    const filterApellidos = document.getElementById('filterApellidos').value.trim();
    const filterCorreo = document.getElementById('filterCorreo').value.trim();
    const filterDni = document.getElementById('filterDni').value.trim();
    const filterCodigoPostal = document.getElementById('filterCodigoPostal').value.trim();

    let params = new URLSearchParams();
    if (filterNombre !== "") {
        params.append('nombre', filterNombre);
    }
    if (filterApellidos !== "") {
        params.append('apellidos', filterApellidos);
    }
    if (filterCorreo !== "") {
        params.append('correo', filterCorreo);
    }
    if (filterDni !== "") {
        params.append('dni', filterDni);
    }
    if (filterCodigoPostal !== "") {
        params.append('codigo_postal', filterCodigoPostal);
    }
    const queryString = params.toString() ? '?' + params.toString() : '';

    // Usamos el endpoint de filtrado definido en web.php (/usuarios con filtros)
    fetch('/usuarios' + queryString)
        .then(response => response.json())
        .then(data => {
            renderUsuarios(data);
        })
        .catch(error => console.error('Error al filtrar usuarios:', error));
}

// Función para abrir el modal de edición y cargar datos del usuario.
function openEditModal(usuarioId) {
    clearEditUsuarioErrors();
    fetch('/api/usuarios/' + usuarioId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUsuarioId').value = data.id;
            document.getElementById('editNombre').value = data.nombre;
            document.getElementById('editApellidos').value = data.apellidos;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editTelefono').value = data.telefono || '';
            document.getElementById('editCodigoPostal').value = data.codigo_postal || '';
            document.getElementById('editFechaNacimiento').value = data.fecha_nacimiento || '';
            document.getElementById('editDni').value = data.dni || '';
            document.getElementById('editDescripcion').value = data.descripcion || '';
            // No se puede prellenar el input file por seguridad.
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => console.error('Error al cargar datos del usuario:', error));
}

// Función para enviar el formulario de edición mediante fetch.
function submitEditUsuario() {
    if (!validateNonEmpty('editNombre', 'El campo Nombre es obligatorio') ||
        !validateNonEmpty('editApellidos', 'El campo Apellidos es obligatorio') ||
        !validateEmail('editEmail') ||
        !validateOptionalPassword()) {
        return;
    }
    let form = document.getElementById('editUsuarioForm');
    let usuarioId = document.getElementById('editUsuarioId').value;
    let formData = new FormData(form);
    fetch('/admin/usuarios/' + usuarioId, {
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
        clearEditUsuarioErrors();
        var editModalEl = document.getElementById('editModal');
        var modal = bootstrap.Modal.getInstance(editModalEl);
        modal.hide();
        filterUsuarios(); // Actualiza la lista filtrada según los filtros activos.
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(error => {
        let errorsDiv = document.getElementById('editErrors');
        errorsDiv.classList.remove('d-none');
        errorsDiv.innerHTML = '<ul>' + Object.values(error.errors || {}).map(err => `<li>${err}</li>`).join('') + '</ul>';
    });
}

// Función para confirmar eliminación con SweetAlert.
function confirmDeleteUsuario(usuarioId) {
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
            deleteUsuario(usuarioId);
        }
    });
}

// Función para eliminar un usuario mediante fetch.
function deleteUsuario(usuarioId) {
    fetch('/admin/usuarios/' + usuarioId, {
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
            throw new Error('Error al eliminar el usuario.');
        }
    })
    .then(data => {
        filterUsuarios();
        Swal.fire('Eliminado', data.message, 'success');
    })
    .catch(error => console.error('Error en la petición DELETE:', error));
}

// Al cargar la página, obtener la lista de usuarios filtrados (por defecto, sin filtro)
document.addEventListener('DOMContentLoaded', function() {
    filterUsuarios();
});

setInterval(filterUsuarios, 1000);