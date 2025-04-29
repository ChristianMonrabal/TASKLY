// public/js/admin-categorias.js

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

function renderCategorias(data) {
    const container = document.getElementById('categorias-container');
    container.innerHTML = '';
    data.forEach(categoria => {
        const isVisible = categoria.visible === 'Sí';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${categoria.nombre}</td>
            <td>
                <!-- Toggle visibility -->
                <button
                  class="btn btn-info btn-sm"
                  onclick="toggleVisibleCategoria(${categoria.id}, this)"
                  title="${isVisible ? 'Ocultar' : 'Hacer visible'}">
                    <i class="fas ${isVisible ? 'fa-eye' : 'fa-eye-slash'}"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-primary btn-sm" onclick="openEditModalCategoria(${categoria.id})">
                    <i class="fa fa-edit"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteCategoria(${categoria.id})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    });
}

function toggleVisibleCategoria(categoriaId, btn) {
    fetch(`/admin/categorias/${categoriaId}/toggle-visible`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            let icon = btn.querySelector('i');
            if (data.visible === 'Sí') {
                icon.classList.replace('fa-eye-slash','fa-eye');
                btn.title = 'Ocultar';
            } else {
                icon.classList.replace('fa-eye','fa-eye-slash');
                btn.title = 'Hacer visible';
            }
            Swal.fire('Actualizado', `Visible: ${data.visible}`, 'success');
        }
    })
    .catch(() => Swal.fire('Error','No se pudo cambiar la visibilidad.','error'));
}

function filterCategorias() {
    const filterNombre = document.getElementById('filterNombre').value.trim();
    const sortOrder    = document.getElementById('sortOrder').value;
    let params = new URLSearchParams();
    if (filterNombre) params.append('nombre', filterNombre);
    params.append('sort', sortOrder);
    fetch('/api/categorias?' + params.toString())
      .then(r => r.json())
      .then(renderCategorias)
      .catch(e => console.error(e));
}

function openCreateModalCategoria() {
    clearCreateCategoriaErrors();
    document.getElementById('createNombre').value = '';
    var m = new bootstrap.Modal(document.getElementById('createModal'));
    m.show();
}

function submitCreateCategoria() {
    if (!validateNonEmpty('createNombre','El campo Nombre es obligatorio')) return;
    let formData = new FormData(document.getElementById('createCategoriaForm'));
    fetch('/admin/categorias', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept':'application/json'
        },
        body: formData
    })
    .then(r => r.ok ? r.json() : r.json().then(e=>{throw e;}))
    .then(data => {
        clearCreateCategoriaErrors();
        bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
        filterCategorias();
        Swal.fire('Éxito',data.message,'success');
    })
    .catch(err => {
        let div = document.getElementById('createCategoriaErrors');
        div.classList.remove('d-none');
        div.innerHTML = '<ul>'+Object.values(err.errors||{}).map(e=>`<li>${e}</li>`).join('')+'</ul>';
    });
}

function openEditModalCategoria(id) {
    clearEditCategoriaErrors();
    fetch(`/api/categorias/${id}`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('editCategoriaId').value = data.id;
        document.getElementById('editNombre').value      = data.nombre;
        document.getElementById('editVisible').value     = data.visible;
        new bootstrap.Modal(document.getElementById('editModal')).show();
      });
}

function submitEditCategoria() {
    if (!validateNonEmpty('editNombre','El campo Nombre es obligatorio')) return;
    let id = document.getElementById('editCategoriaId').value;
    let fd = new FormData(document.getElementById('editCategoriaForm'));
    fetch(`/admin/categorias/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-HTTP-Method-Override':'PUT',
          'Accept':'application/json'
        },
        body: fd
    })
    .then(r => r.ok ? r.json() : r.json().then(e=>{throw e;}))
    .then(data => {
        clearEditCategoriaErrors();
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        filterCategorias();
        Swal.fire('Éxito',data.message,'success');
    })
    .catch(err => {
        let div = document.getElementById('editCategoriaErrors');
        div.classList.remove('d-none');
        div.innerHTML = '<ul>'+Object.values(err.errors||{}).map(e=>`<li>${e}</li>`).join('')+'</ul>';
    });
}

function confirmDeleteCategoria(id) {
    Swal.fire({
        title: '¿Seguro?',
        text: "No podrás revertirlo",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#dc3545',
        cancelButtonColor:'#6c757d',
        confirmButtonText:'Sí, eliminar'
    }).then(res => res.isConfirmed && deleteCategoria(id));
}

function deleteCategoria(categoriaId) {
    fetch(`/admin/categorias/${categoriaId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    // Leemos siempre el JSON de respuesta, con éxito o error
    .then(response =>
        response.json().then(data => ({ ok: response.ok, data }))
    )
    .then(({ ok, data }) => {
        if (ok) {
            // Borrado OK
            filterCategorias();
            Swal.fire('Eliminado', data.message, 'success');
        } else {
            // Borrado DENEGADO (e.g. trabajos asociados)
            Swal.fire('No se puede eliminar', data.message, 'warning');
        }
    })
    .catch(() => {
        // Error de red u otro no manejado
        Swal.fire('Error', 'Ha ocurrido un error inesperado.', 'error');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    filterCategorias();
    setInterval(filterCategorias,1000);
});
