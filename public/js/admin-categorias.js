// public/js/admin-categorias.js

// Función para validar que un campo no esté vacío.
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

// Funciones para limpiar contenedores de errores.
function clearCreateCategoriaErrors() {
    let errorsDiv = document.getElementById('createCategoriaErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

function clearEditCategoriaErrors() {
    let errorsDiv = document.getElementById('editCategoriaErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

// Función para renderizar la tabla de categorías.
function renderCategorias(data) {
    const container = document.getElementById('categorias-container');
    container.innerHTML = '';
    data.forEach(categoria => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${categoria.nombre}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="openEditModalCategoria(${categoria.id})">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteCategoria(${categoria.id})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    });
}

// Función para obtener categorías aplicando el filtro por nombre y orden.
function filterCategorias() {
    const filterNombre = document.getElementById('filterNombre').value.trim();
    const sortOrder = document.getElementById('sortOrder').value;
    
    let params = new URLSearchParams();
    if (filterNombre !== "") {
        params.append('nombre', filterNombre);
    }
    // Agregamos el parámetro de ordenamiento.
    params.append('sort', sortOrder);
    
    const queryString = params.toString() ? '?' + params.toString() : '';
    
    fetch('/api/categorias' + queryString)
        .then(response => response.json())
        .then(data => {
            renderCategorias(data);
        })
        .catch(error => console.error('Error al filtrar categorías:', error));
}

// Función para abrir el modal de creación.
function openCreateModalCategoria() {
    clearCreateCategoriaErrors();
    document.getElementById('createNombre').value = '';
    var createModal = new bootstrap.Modal(document.getElementById('createModal'));
    createModal.show();
}

// Función para enviar el formulario de creación.
function submitCreateCategoria() {
    if (!validateNonEmpty('createNombre', 'El campo Nombre es obligatorio')) {
        return;
    }
    let form = document.getElementById('createCategoriaForm');
    let formData = new FormData(form);
    fetch('/admin/categorias', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
        clearCreateCategoriaErrors();
        var createModal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
        createModal.hide();
        filterCategorias();
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(error => {
        let errorsDiv = document.getElementById('createCategoriaErrors');
        errorsDiv.classList.remove('d-none');
        errorsDiv.innerHTML = '<ul>' + Object.values(error.errors || {}).map(err => `<li>${err}</li>`).join('') + '</ul>';
    });
}

// Función para abrir el modal de edición y cargar datos de la categoría.
function openEditModalCategoria(categoriaId) {
    clearEditCategoriaErrors();
    fetch('/api/categorias/' + categoriaId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editCategoriaId').value = data.id;
            document.getElementById('editNombre').value = data.nombre;
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => console.error('Error al cargar datos de la categoría:', error));
}

// Función para enviar el formulario de edición.
function submitEditCategoria() {
    if (!validateNonEmpty('editNombre', 'El campo Nombre es obligatorio')) {
        return;
    }
    let form = document.getElementById('editCategoriaForm');
    let categoriaId = document.getElementById('editCategoriaId').value;
    let formData = new FormData(form);
    fetch('/admin/categorias/' + categoriaId, {
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
        clearEditCategoriaErrors();
        var editModalEl = document.getElementById('editModal');
        var modal = bootstrap.Modal.getInstance(editModalEl);
        modal.hide();
        filterCategorias();
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(error => {
        let errorsDiv = document.getElementById('editCategoriaErrors');
        errorsDiv.classList.remove('d-none');
        errorsDiv.innerHTML = '<ul>' + Object.values(error.errors || {}).map(err => `<li>${err}</li>`).join('') + '</ul>';
    });
}

// Función para confirmar eliminación con SweetAlert.
function confirmDeleteCategoria(categoriaId) {
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
            deleteCategoria(categoriaId);
        }
    });
}

// Función para eliminar una categoría mediante fetch.
function deleteCategoria(categoriaId) {
    fetch('/admin/categorias/' + categoriaId, {
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
            throw new Error('Error al eliminar la categoría.');
        }
    })
    .then(data => {
        filterCategorias();
        Swal.fire('Eliminado', data.message, 'success');
    })
    .catch(error => console.error('Error en la petición DELETE:', error));
}

// Al cargar la página, obtener la lista de categorías.
document.addEventListener('DOMContentLoaded', function() {
    filterCategorias();
});

// Actualizar la lista cada 1 segundo.
setInterval(filterCategorias, 1000);
