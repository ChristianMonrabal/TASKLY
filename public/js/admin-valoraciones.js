// public/js/admin-valoraciones.js

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

// (Función validateEmail y validateOptionalPassword se mantienen para tener un formato homogéneo,
// aunque en valoraciones probablemente no se usen, pues solo se edita el comentario e imagen)

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
function clearEditValoracionErrors() {
    let errorsDiv = document.getElementById('editValErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

// Función para renderizar la tabla de valoraciones.
function renderValoraciones(data) {
    const container = document.getElementById('valoraciones-container');
    container.innerHTML = '';
    data.forEach(valoracion => {
        const clienteNombre = (valoracion.trabajo && valoracion.trabajo.cliente)
            ? (valoracion.trabajo.cliente.nombre + ' ' + valoracion.trabajo.cliente.apellidos)
            : '';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${valoracion.trabajo.titulo || ''}</td>
            <td>${valoracion.trabajador ? valoracion.trabajador.nombre : ''}</td>
            <td>${valoracion.puntuacion}</td>
            <td>${valoracion.comentario || ''}</td>
            <td>${clienteNombre}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="openEditModalValoracion(${valoracion.id})">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteValoracion(${valoracion.id})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    });
}

// Función para obtener valoraciones filtradas y renderizarlas.
function filterValoraciones() {
    const filterTrabajador = document.getElementById('filterTrabajador').value.trim();
    const filterCliente = document.getElementById('filterCliente').value.trim();

    let params = new URLSearchParams();
    if (filterTrabajador !== "") {
        params.append('trabajador', filterTrabajador);
    }
    if (filterCliente !== "") {
        params.append('cliente', filterCliente);
    }
    const queryString = params.toString() ? '?' + params.toString() : '';

    fetch('/api/valoraciones' + queryString)
        .then(response => response.json())
        .then(data => {
            renderValoraciones(data);
        })
        .catch(error => console.error('Error al filtrar valoraciones:', error));
}

// Función para abrir el modal de edición y cargar datos de una valoración.
function openEditModalValoracion(valoracionId) {
    clearEditValoracionErrors();
    fetch('/api/valoraciones/' + valoracionId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editValoracionId').value = data.id;
            document.getElementById('editComentario').value = data.comentario || '';

            // Mostrar la imagen actual, si existe.
            if (data.img_valoracion) {
                let imgEl = document.getElementById('currentImgValoracion');
                // Asegúrate de que la ruta sea correcta; se asume que la imagen se encuentra en "storage"
                imgEl.src = '/storage/' + data.img_valoracion;
                imgEl.style.display = 'block';
            } else {
                document.getElementById('currentImgValoracion').style.display = 'none';
            }

            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => console.error('Error al cargar datos de la valoración:', error));
}

// Función para enviar el formulario de edición mediante fetch.
function submitEditValoracion() {
    if (!validateNonEmpty('editComentario', 'El campo Comentario es obligatorio')) {
        return;
    }
    let form = document.getElementById('editValoracionForm');
    let valoracionId = document.getElementById('editValoracionId').value;
    let formData = new FormData(form);
    fetch('/admin/valoraciones/' + valoracionId, {
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
        clearEditValoracionErrors();
        var editModalEl = document.getElementById('editModal');
        var modal = bootstrap.Modal.getInstance(editModalEl);
        modal.hide();
        filterValoraciones(); // Actualiza la lista filtrada
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(error => {
        let errorsDiv = document.getElementById('editValErrors');
        errorsDiv.classList.remove('d-none');
        errorsDiv.innerHTML = '<ul>' + Object.values(error.errors || {}).map(err => `<li>${err}</li>`).join('') + '</ul>';
    });
}

// Función para confirmar eliminación con SweetAlert.
function confirmDeleteValoracion(valoracionId) {
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
            deleteValoracion(valoracionId);
        }
    });
}

// Función para eliminar una valoración mediante fetch.
function deleteValoracion(valoracionId) {
    fetch('/admin/valoraciones/' + valoracionId, {
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
            throw new Error('Error al eliminar la valoración.');
        }
    })
    .then(data => {
        filterValoraciones();
        Swal.fire('Eliminado', data.message, 'success');
    })
    .catch(error => console.error('Error en la petición DELETE:', error));
}

// Al cargar la página, obtener la lista filtrada de valoraciones.
document.addEventListener('DOMContentLoaded', function() {
    filterValoraciones();
});

// Actualizar la lista cada 1 segundo.
setInterval(filterValoraciones, 1000);
