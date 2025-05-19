// public/js/admin-reportes.js

// Para no perder la página al refrescar
let currentPage = 1;

// Variables para acciones pendientes
let pendingToggleUserId, pendingToggleNewState;
let pendingNotifyUserId;

// ————— Validaciones y helpers —————

function validateNonEmpty(id, msg) {
  const f = document.getElementById(id),
        e = document.getElementById("error" + id.charAt(0).toUpperCase() + id.slice(1));
  if (!f.value.trim()) { e.textContent = msg; return false; }
  e.textContent = ''; return true;
}

function validateSelect(id, msg) {
  const f = document.getElementById(id),
        e = document.getElementById("error" + id.charAt(0).toUpperCase() + id.slice(1));
  if (!f.value) { e.textContent = msg; return false; }
  e.textContent = ''; return true;
}

function clearEditReporteErrors() {
  const d = document.getElementById('editReporteErrors');
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}

// ————— Renderizado de tabla y paginación —————

function renderReportes(data) {
  const c = document.getElementById('reportes-container');
  c.innerHTML = '';

  data.forEach(r => {
    const rr = r.usuario_reportado   || {},
          rp = r.reportado_por       || {},
          ng = r.nivel_gravedad      || {},
          er = r.estado_reporte      || {};

    // Detectar si está activo
    const isActive    = String(rr.activo).toLowerCase() === 'si';
    const labelToggle = isActive ? 'Desactivar cuenta' : 'Reactivar cuenta';
    const newState    = isActive ? 'no'               : 'si';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <div class="dropdown">
          <button class="btn btn-sm p-0 dropdown-toggle text-decoration-none"
                  type="button"
                  id="userMenu${r.id}"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">
            ${rr.nombre || '-'} ${rr.apellidos || ''}
          </button>
          <ul class="dropdown-menu" aria-labelledby="userMenu${r.id}">
            <li>
              <button class="dropdown-item"
                      onclick="accionUsuarioToggle(${rr.id}, '${newState}', '${labelToggle}')">
                ${labelToggle}
              </button>
            </li>
            <li>
              <button class="dropdown-item"
                      onclick="accionUsuarioNotify(${rr.id})">
                Enviar aviso
              </button>
            </li>
          </ul>
        </div>
      </td>
      <td>${rp.nombre || '-'} ${rp.apellidos || ''}</td>
      <td>${r.motivo}</td>
      <td>${ng.nombre || '-'}</td>
      <td>${er.nombre || '-'}</td>
      <td>${new Date(r.created_at).toLocaleString()}</td>
      <td>
        <button class="btn btn-warning btn-sm" onclick="openEditReporteModal(${r.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="confirmDeleteReporte(${r.id})">
          <i class="fa fa-trash"></i>
        </button>
      </td>`;
    c.appendChild(tr);
  });
}

function renderPagination(meta) {
  const ul = document.getElementById('reportes-pagination');
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
      a.dataset.page = url.searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        filterReportes(a.dataset.page);
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// ————— Fetch de datos y filtros —————

function filterReportes(page = 1) {
  currentPage = page;
  const params = new URLSearchParams();
  ['filterMotivo','filterGravedad','filterEstado'].forEach(id => {
    const v = document.getElementById(id).value.trim();
    if (v) params.append(id.replace('filter','').toLowerCase(), v);
  });
  params.append('page', page);

  fetch(`/admin/reportes/json?${params}`, {
    headers: { 'Accept': 'application/json' }
  })
    .then(res => res.json())
    .then(json => {
      renderReportes(json.data);
      renderPagination(json);
    });
}

// ————— Modales: editar estado —————

function openEditReporteModal(id) {
  clearEditReporteErrors();
  fetch(`/admin/reportes/${id}`, {
    headers: { 'Accept': 'application/json' }
  })
    .then(res => res.json())
    .then(data => {
      document.getElementById('editReporteId').value = data.id;
      document.getElementById('editEstado').value     = data.estado;
      new bootstrap.Modal(document.getElementById('editReporteModal')).show();
    });
}

function submitEditReporte() {
  if (!validateSelect('editEstado','Seleccione un estado')) return;

  const id = document.getElementById('editReporteId').value;
  const fd = new FormData();
  fd.append('_method','PUT');
  fd.append('estado', document.getElementById('editEstado').value);

  fetch(`/admin/reportes/${id}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept':      'application/json'
    },
    body: fd
  })
    .then(res => res.ok ? res.json() : res.json().then(e => { throw e; }))
    .then(() => {
      bootstrap.Modal.getInstance(document.getElementById('editReporteModal')).hide();
      filterReportes(currentPage);
      Swal.fire('¡Éxito!','Estado actualizado.','success');
    })
    .catch(err => {
      const d  = document.getElementById('editReporteErrors');
      const lis = Object.values(err.errors || {}).flat().map(m => `<li>${m}</li>`).join('');
      d.classList.remove('d-none');
      d.querySelector('ul').innerHTML = lis;
    });
}

// ————— Modales: eliminar reporte —————

function confirmDeleteReporte(id) {
  Swal.fire({
    title: '¿Eliminar?',
    text:  'No podrás revertir',
    icon:  'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar'
  })
    .then(r => { if (r.isConfirmed) deleteReporte(id); });
}

function deleteReporte(id) {
  fetch(`/admin/reportes/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept':      'application/json'
    }
  })
    .then(res => res.json())
    .then(() => {
      filterReportes(currentPage);
      Swal.fire('Eliminado','Reporte eliminado.','success');
    });
}

// ————— Acciones: toggle usuario —————

function accionUsuarioToggle(userId, newState, label) {
  pendingToggleUserId   = userId;
  pendingToggleNewState = newState;
  document.getElementById('toggleUserModalBody').textContent =
    `¿Seguro que deseas ${label.toLowerCase()}?`;

  const btn = document.getElementById('toggleUserModalConfirmBtn');
  btn.onclick = () => {
    const fd = new FormData();
    fd.append('activo', pendingToggleNewState);

    fetch(`/admin/usuarios/${pendingToggleUserId}/toggle-active`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept':      'application/json'
      },
      body: fd
    })
      .then(r => r.json())
      .then(data => {
        Swal.fire(label, data.message, 'success');
        filterReportes(currentPage);
      })
      .finally(() => {
        bootstrap.Modal.getInstance(
          document.getElementById('toggleUserModal')
        ).hide();
      });
  };

  new bootstrap.Modal(
    document.getElementById('toggleUserModal')
  ).show();
}

// ————— Acciones: enviar aviso —————

function accionUsuarioNotify(userId) {
  pendingNotifyUserId = userId;
  document.getElementById('notifyMessage').value    = '';
  document.getElementById('notifyError').textContent = '';

  const btn = document.getElementById('notifyUserModalConfirmBtn');
  btn.onclick = () => {
    const msg = document.getElementById('notifyMessage').value.trim();
    if (!msg) {
      document.getElementById('notifyError').textContent =
        'El mensaje no puede estar vacío.';
      return;
    }

    fetch(`/admin/usuarios/${pendingNotifyUserId}/notify`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept':      'application/json',
        'Content-Type':'application/json'
      },
      body: JSON.stringify({ mensaje: msg })
    })
      .then(r => r.ok ? r.json() : r.json().then(e=>{throw e;}))
      .then(data => {
        Swal.fire('Aviso enviado', data.message, 'success');
      })
      .catch(err => {
        document.getElementById('notifyError').textContent =
          err.message || 'Error al enviar.';
      })
      .finally(() => {
        bootstrap.Modal.getInstance(
          document.getElementById('notifyUserModal')
        ).hide();
      });
  };

  new bootstrap.Modal(
    document.getElementById('notifyUserModal')
  ).show();
}

// ————— Inicialización —————

document.addEventListener('DOMContentLoaded', () => {
  filterReportes();
  ['filterMotivo','filterGravedad','filterEstado']
    .forEach(id => document.getElementById(id)
      .addEventListener('input', () => filterReportes(currentPage)));
  
  // Refrescar cada 10 segundos sin perder la página actual:
  setInterval(() => filterReportes(currentPage), 10000);
});
