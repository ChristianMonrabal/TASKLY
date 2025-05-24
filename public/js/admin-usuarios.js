// public/js/admin-usuarios.js

// Mantener la página actual
let currentPage = 1;

// ————————————————————————————————
// Validaciones edición
// ————————————————————————————————
function validateNonEmpty(fieldId, message) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
  if (!f.value.trim()) { e.textContent = message; return false; }
  e.textContent = ''; return true;
}

function validateEmail(fieldId) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)),
        emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!f.value.trim()) {
    e.textContent = "El campo Correo es obligatorio."; return false;
  } else if (!emailRegex.test(f.value.trim())) {
    e.textContent = "El formato del Correo es inválido."; return false;
  }
  e.textContent = ''; return true;
}

function validateOptionalPassword() {
  const pwd = document.getElementById('editPassword').value,
        conf = document.getElementById('editPasswordConfirmation').value,
        e1 = document.getElementById('errorEditPassword'),
        e2 = document.getElementById('errorEditPasswordConfirmation');
  if (!pwd && !conf) { e1.textContent = ''; e2.textContent = ''; return true; }
  if (pwd.length < 6) { e1.textContent = "Mínimo 6 caracteres."; return false; }
  e1.textContent = '';
  if (pwd !== conf) { e2.textContent = "No coinciden."; return false; }
  e2.textContent = ''; return true;
}

function clearEditUsuarioErrors() {
  const d = document.getElementById('editErrors');
  if (d) { d.classList.add('d-none'); d.querySelector('ul').innerHTML = ''; }
}

// ————————————————————————————————
// Validaciones creación
// ————————————————————————————————
function clearCreateUsuarioErrors() {
  const d = document.getElementById('createErrors');
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}

function validateCreateNonEmpty(fieldId, message) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
  if (!f.value.trim()) { e.textContent = message; return false; }
  e.textContent = ''; return true;
}

function validateCreateEmail(fieldId) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)),
        re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!f.value.trim()) {
    e.textContent = "Correo obligatorio."; return false;
  }
  if (!re.test(f.value.trim())) {
    e.textContent = "Formato inválido."; return false;
  }
  e.textContent = ''; return true;
}

function validateCreatePassword() {
  const pwd  = document.getElementById('createPassword').value,
        conf = document.getElementById('createPasswordConfirmation').value,
        e1   = document.getElementById('errorCreatePassword'),
        e2   = document.getElementById('errorCreatePasswordConfirmation');
  if (pwd.length < 6) {
    e1.textContent = "Mínimo 6 caracteres."; return false;
  }
  e1.textContent = '';
  if (pwd !== conf) {
    e2.textContent = "No coinciden."; return false;
  }
  e2.textContent = '';
  return true;
}

// ————————————————————————————————
// Renderiza la tabla de usuarios
// ————————————————————————————————
function renderUsuarios(items) {
  const tbody = document.getElementById('usuarios-container');
  tbody.innerHTML = '';
  items.forEach(u => {
    const rol         = u.rol?.nombre || '',
          activoLabel = u.activo === 'si' ? 'Activo' : 'Inactivo',
          canDelete   = (() => {
            if (u.id === 1) return false;                                  // nunca borrar super-admin
            if (u.rol_id === 1 && window.currentUser.id !== 1) return false; // sólo super-admin puede borrar otros admins
            return true;
          })();
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${u.nombre}</td>
      <td>${u.apellidos}</td>
      <td>${u.email}</td>
      <td>${u.telefono || ''}</td>
      <td>${u.fecha_nacimiento ? new Date(u.fecha_nacimiento).toLocaleDateString() : ''}</td>
      <td>${u.dni || ''}</td>
      <td>${rol}</td>
      <td>${u.descripcion || ''}</td>
      <td>${activoLabel}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="openEditModal(${u.id})">
          <i class="fa fa-edit"></i>
        </button>
        ${canDelete
          ? `<button class="btn btn-danger btn-sm" onclick="confirmDeleteUsuario(${u.id})">
               <i class="fa fa-trash"></i>
             </button>`
          : ''
        }
      </td>`;
    tbody.appendChild(tr);
  });
}

// ————————————————————————————————
// Renderiza la paginación
// ————————————————————————————————
function renderPagination(paginated) {
  const ul = document.getElementById('usuarios-pagination');
  ul.innerHTML = '';
  paginated.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item' + (link.active ? ' active' : '') + (!link.url ? ' disabled' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.innerHTML = link.label;
    if (link.url) {
      const page = new URL(link.url).searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        filterUsuarios(Number(page));
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// ————————————————————————————————
// Obtiene usuarios filtrados y renderiza
// ————————————————————————————————
function filterUsuarios(page = 1) {
  currentPage = page;
  const params = new URLSearchParams();
  ['filterNombre','filterApellidos','filterCorreo','filterDni']
    .forEach(id => {
      const v = document.getElementById(id).value.trim();
      if (v) params.append(id.replace('filter','').toLowerCase(), v);
    });
  params.append('page', page);

  fetch(`/admin/usuarios/json?${params}`, {
    headers: { 'Accept': 'application/json' }
  })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(json => {
      renderUsuarios(json.data);
      renderPagination(json);
    })
    .catch(() => {
      Swal.fire('Error', 'No se pudo cargar usuarios', 'error');
    });
}

// ————————————————————————————————
// Editar Usuario
// ————————————————————————————————
function openEditModal(usuarioId) {
  clearEditUsuarioErrors();
  fetch(`/admin/usuarios/${usuarioId}`, { headers: { Accept: 'application/json' } })
    .then(r => r.json())
    .then(u => {
      document.getElementById('editUsuarioId').value           = u.id;
      document.getElementById('editNombre').value              = u.nombre;
      document.getElementById('editApellidos').value           = u.apellidos;
      document.getElementById('editEmail').value               = u.email;
      document.getElementById('editTelefono').value            = u.telefono || '';
      document.getElementById('editCodigoPostal').value        = u.codigo_postal || '';
      document.getElementById('editFechaNacimiento').value     = u.fecha_nacimiento || '';
      document.getElementById('editDni').value                 = u.dni || '';
      document.getElementById('editDescripcion').value         = u.descripcion || '';
      new bootstrap.Modal(document.getElementById('editModal')).show();
    })
    .catch(console.error);
}

function submitEditUsuario() {
  if (
    !validateNonEmpty('editNombre','Obligatorio') ||
    !validateNonEmpty('editApellidos','Obligatorio') ||
    !validateEmail('editEmail') ||
    !validateOptionalPassword()
  ) return;

  const id = document.getElementById('editUsuarioId').value;
  const fd = new FormData(document.getElementById('editUsuarioForm'));
  fetch(`/admin/usuarios/${id}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'X-HTTP-Method-Override': 'PUT',
      'Accept': 'application/json'
    },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
  .then(() => {
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    filterUsuarios(currentPage);
    Swal.fire('¡Éxito!','Usuario actualizado correctamente.','success');
  })
  .catch(e => {
    const d = document.getElementById('editErrors');
    d.classList.remove('d-none');
    d.querySelector('ul').innerHTML =
      Object.values(e.errors||{}).flat().map(m=>`<li>${m}</li>`).join('');
  });
}

// ————————————————————————————————
// Eliminar Usuario
// ————————————————————————————————
function confirmDeleteUsuario(id) {
  Swal.fire({
    title: '¿Eliminar?',
    text:  'No podrás revertir',
    icon:  'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar'
  }).then(r => { if (r.isConfirmed) deleteUsuario(id); });
}

function deleteUsuario(id) {
  fetch(`/admin/usuarios/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Accept': 'application/json'
    }
  })
  .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
  .then(res => {
    filterUsuarios(currentPage);
    Swal.fire('Eliminado', res.message, 'success');
  })
  .catch(err => {
    Swal.fire('Error', err.message || 'No se pudo eliminar', 'error');
  });
}

// ————————————————————————————————
// Crear Usuario
// ————————————————————————————————
function openCreateModal() {
  document.getElementById('createUsuarioForm').reset();
  clearCreateUsuarioErrors();
  new bootstrap.Modal(document.getElementById('createModal')).show();
}

function submitCreateUsuario() {
  if (
    !validateCreateNonEmpty('createNombre','Nombre obligatorio') ||
    !validateCreateNonEmpty('createApellidos','Apellidos obligatorios') ||
    !validateCreateEmail('createEmail') ||
    !validateCreateNonEmpty('createTelefono','Teléfono obligatorio') ||
    !validateCreateNonEmpty('createCodigoPostal','Código Postal obligatorio') ||
    !validateCreateNonEmpty('createFechaNacimiento','Fecha de Nacimiento obligatoria') ||
    !validateCreateNonEmpty('createDni','DNI obligatorio') ||
    !validateCreateNonEmpty('createDescripcion','Descripción obligatoria') ||
    !validateCreatePassword()
  ) return;

  const fd = new FormData(document.getElementById('createUsuarioForm'));
  fetch('/admin/usuarios', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Accept': 'application/json'
    },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
  .then(() => {
    bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
    filterUsuarios(1);
    Swal.fire('¡Éxito!','Usuario creado correctamente.','success');
  })
  .catch(err => {
    const c = document.getElementById('createErrors');
    c.classList.remove('d-none');
    c.querySelector('ul').innerHTML =
      Object.values(err.errors||{msg:[err.message]}).flat()
        .map(m=>`<li>${m}</li>`).join('');
  });
}

// ————————————————————————————————
// Inicialización y refresco automático
// ————————————————————————————————
document.addEventListener('DOMContentLoaded', () => {
  ['filterNombre','filterApellidos','filterCorreo','filterDni'].forEach(id =>
    document.getElementById(id).addEventListener('input', () => filterUsuarios(1))
  );
  filterUsuarios();
});
setInterval(() => filterUsuarios(currentPage), 10000);
