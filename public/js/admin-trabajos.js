// public/js/admin-trabajos.js

let currentPage = 1; // Página actual del paginador

// Validaciones onblur
function validateNonEmpty(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
    if (!field.value.trim()) {
        errorDiv.textContent = message;
        return false;
    }
    errorDiv.textContent = '';
    return true;
}

// Limpia errores del modal de edición
function clearEditTrabajoErrors() {
    const errorsDiv = document.getElementById('editErrors');
    if (errorsDiv) {
        errorsDiv.classList.add('d-none');
        errorsDiv.innerHTML = '<ul></ul>';
    }
}

// Carga las opciones de estado para el filtro principal
function loadEstadosFiltro() {
    fetch('/api/estados/trabajos')
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById('filterEstado');
            select.innerHTML = '<option value="">Seleccione un Estado</option>';
            data.forEach(e => {
                const opt = new Option(e.nombre, e.nombre);
                select.add(opt);
            });
        })
        .catch(err => console.error('Error al cargar estados de filtro:', err));
}

// Carga los estados en el modal de edición, marcando el seleccionado
function loadEstadosTrabajo(selectedId) {
    fetch('/api/estados/trabajos')
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('editEstadoId');
            sel.innerHTML = '<option value="">Seleccione un estado</option>';
            data.forEach(e => {
                const opt = new Option(e.nombre, e.id);
                if (e.id == selectedId) opt.selected = true;
                sel.add(opt);
            });
        })
        .catch(err => console.error('Error al cargar estados para edición:', err));
}

// Renderiza la tabla de trabajos
function renderTrabajos(items) {
    const tbody = document.getElementById('trabajos-container');
    tbody.innerHTML = '';
    items.forEach(t => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${t.titulo}</td>
            <td>${t.descripcion?.substring(0,50) || ''}</td>
            <td>${t.precio}</td>
            <td>${t.direccion}</td>
            <td>${t.cliente ? t.cliente.nombre + ' ' + t.cliente.apellidos : ''}</td>
            <td>${t.estado?.nombre || 'N/A'}</td>
            <td>
              <button class="btn btn-primary btn-sm" onclick="openEditModalTrabajo(${t.id})">
                <i class="fa fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteTrabajo(${t.id})">
                <i class="fa fa-trash"></i>
              </button>
            </td>`;
        tbody.appendChild(tr);
    });
}

// Renderiza la paginación
function renderPagination(meta) {
    const ul = document.getElementById('trabajos-pagination');
    if (!ul) return; // Si no existe en el DOM, salimos
    ul.innerHTML = '';
    meta.links.forEach(link => {
        const li = document.createElement('li');
        li.className = 'page-item' +
            (link.active ? ' active' : '') +
            (!link.url   ? ' disabled' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerHTML = link.label;
        if (link.url) {
            const url = new URL(link.url);
            const page = url.searchParams.get('page') || 1;
            a.addEventListener('click', e => {
                e.preventDefault();
                filterTrabajos(Number(page));
            });
        }
        li.appendChild(a);
        ul.appendChild(li);
    });
}

// Obtiene y refresca la lista (respetando la página actual)
function filterTrabajos(page = currentPage) {
    currentPage = page;
    const params = new URLSearchParams();
    const cliente = document.getElementById('filterCliente').value.trim();
    const estado  = document.getElementById('filterEstado').value.trim();
    if (cliente) params.append('cliente', cliente);
    if (estado)  params.append('estado', estado);
    params.append('page', currentPage);

    fetch(`/api/trabajos?${params}`, { headers: { Accept: 'application/json' } })
        .then(r => r.json())
        .then(json => {
            renderTrabajos(json.data);
            renderPagination(json);
        })
        .catch(err => console.error('Error al filtrar trabajos:', err));
}

// Abre modal y carga datos de un trabajo
function openEditModalTrabajo(id) {
    clearEditTrabajoErrors();
    fetch(`/admin/trabajos/${id}`)
        .then(r => r.json())
        .then(t => {
            document.getElementById('editTrabajoId').value    = t.id;
            document.getElementById('editTitulo').value       = t.titulo;
            document.getElementById('editDescripcion').value = t.descripcion;
            document.getElementById('editPrecio').value      = t.precio;
            document.getElementById('editDireccion').value   = t.direccion;
            document.getElementById('editCliente').value     = t.cliente ? `${t.cliente.nombre} ${t.cliente.apellidos}` : '';
            loadEstadosTrabajo(t.estado_id);
            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(err => console.error('Error al cargar trabajo:', err));
}

// Envía formulario de edición
function submitEditTrabajo() {
    if (!validateNonEmpty('editTitulo', 'Título obligatorio') ||
        !validateNonEmpty('editDescripcion', 'Descripción obligatoria') ||
        !validateNonEmpty('editPrecio', 'Precio obligatorio') ||
        !validateNonEmpty('editDireccion', 'Dirección obligatoria') ||
        !validateNonEmpty('editEstadoId', 'Seleccione un estado')) {
      return;
    }
    const id = document.getElementById('editTrabajoId').value;
    const formData = new FormData(document.getElementById('editTrabajoForm'));
    fetch(`/admin/trabajos/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-HTTP-Method-Override': 'PUT',
          'Accept':'application/json'
        },
        body: formData
    })
    .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))
    .then(_ => {
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        filterTrabajos();
        Swal.fire('¡Éxito!','Trabajo actualizado.','success');
    })
    .catch(err => {
        const d = document.getElementById('editErrors');
        d.classList.remove('d-none');
        d.querySelector('ul').innerHTML =
          Object.values(err.errors||{}).flat().map(m=>`<li>${m}</li>`).join('');
    });
}

// Confirmar y eliminar
function confirmDeleteTrabajo(id) {
    Swal.fire({
      title: '¿Eliminar?',
      text:  'No podrás revertir',
      icon:  'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar'
    }).then(res => { if (res.isConfirmed) deleteTrabajo(id); });
}
function deleteTrabajo(id) {
    fetch(`/admin/trabajos/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept':'application/json'
      }
    })
    .then(async r => {
      const data = await r.json();
      if (!r.ok) throw data;
      return data;
    })
    .then(_ => {
      filterTrabajos(); 
      Swal.fire('Eliminado','Trabajo eliminado.','success');
    })
    .catch(err => Swal.fire('Error', err.message || 'No se pudo eliminar','error'));
}

// Al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    loadEstadosFiltro();
    filterTrabajos();
});

// Refrescar cada 10 segundos sin perder la página seleccionada
setInterval(() => filterTrabajos(currentPage), 10000);
