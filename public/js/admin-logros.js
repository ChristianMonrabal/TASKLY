
// Mantener página actual
let currentPage = 1;

// Validaciones
function validateNonEmpty(fieldId, message) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
  if (f.value.trim() === "") {
    e.textContent = message;
    return false;
  }
  e.textContent = "";
  return true;
}
function validateNumeric(fieldId, message) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)),
        v = Number(f.value);
  if (f.value === "" || isNaN(v) || v < 0) {
    e.textContent = message;
    return false;
  }
  e.textContent = "";
  return true;
}

// Limpiar errores
function clearCreateLogroErrors() {
  const d = document.getElementById('createLogroErrors');
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}
function clearEditLogroErrors() {
  const d = document.getElementById('editLogroErrors');
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}

// Render tabla
function renderLogros(data) {
  const tbody = document.getElementById('logros-container');
  tbody.innerHTML = '';
  if (data.length === 0) {
    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron logros.</td></tr>';
  } else {
    data.forEach(l => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${l.nombre}</td>
        <td>${l.descripcion || ''}</td>
        <td>${l.descuento}</td>
        <td>
          <button class="btn btn-primary btn-sm" onclick="openEditModalLogro(${l.id})">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-danger btn-sm" onclick="confirmDeleteLogro(${l.id})">
            <i class="fas fa-trash"></i>
          </button>
        </td>`;
      tbody.appendChild(tr);
    });
  }
}

// Render paginación
function renderPagination(meta) {
  const ul = document.getElementById('logros-pagination');
  ul.innerHTML = '';
  meta.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item'
      + (link.active ? ' active' : '')
      + (!link.url ? ' disabled' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.innerHTML = link.label;
    if (link.url) {
      const u = new URL(link.url);
      a.dataset.page = u.searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        filterLogros(a.dataset.page);
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// Fetch + filtros + paginación
function filterLogros(page = 1) {
  const q = document.getElementById('filterNombre').value.trim();
  const params = new URLSearchParams();
  if (q) params.append('nombre', q);
  params.append('page', page);
  fetch(`/api/logros?${params.toString()}`, {
    headers: { 'Accept': 'application/json' }
  })
    .then(r => r.json())
    .then(json => {
      renderLogros(json.data);
      renderPagination(json);
    })
    .catch(() => {
      document.getElementById('logros-container').innerHTML =
        '<tr><td colspan="4" class="text-danger text-center">Error cargando datos.</td></tr>';
    });
}

// Crear
function openCreateModalLogro() {
  clearCreateLogroErrors();
  document.getElementById('createLogroForm').reset();
  new bootstrap.Modal(document.getElementById('createModal')).show();
}
function submitCreateLogro() {
  if (!validateNonEmpty('createNombre', 'Nombre obligatorio') ||
      !validateNumeric('createDescuento', 'Descuento ≥ 0')) return;

  const fd = new FormData(document.getElementById('createLogroForm'));
  fetch('/admin/logros', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
  .then(() => {
    new bootstrap.Modal(document.getElementById('createModal')).hide();
    filterLogros(currentPage);
    Swal.fire('Éxito','Logro creado.','success');
  })
  .catch(err => {
    const c = document.getElementById('createLogroErrors');
    c.classList.remove('d-none');
    c.querySelector('ul').innerHTML =
      Object.values(err.errors||{msg:[err.message]}).flat()
        .map(msg=>`<li>${msg}</li>`).join('');
  });
}

// Editar
function openEditModalLogro(id) {
  clearEditLogroErrors();
  fetch(`/api/logros/${id}`)
    .then(r=>r.json())
    .then(l=>{
      document.getElementById('editLogroId').value        = l.id;
      document.getElementById('editNombre').value         = l.nombre;
      document.getElementById('editDescripcion').value    = l.descripcion||'';
      document.getElementById('editDescuento').value      = l.descuento;
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}
function submitEditLogro() {
  if (!validateNonEmpty('editNombre','Nombre obligatorio') ||
      !validateNumeric('editDescuento','Descuento ≥ 0')) return;

  const id = document.getElementById('editLogroId').value;
  const fd = new FormData(document.getElementById('editLogroForm'));
  fetch(`/admin/logros/${id}`, {
    method:'POST',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'X-HTTP-Method-Override':'PUT'
    },
    body: fd
  })
  .then(r=>r.ok? r.json() : r.json().then(e=>Promise.reject(e)))
  .then(()=>{
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    filterLogros(currentPage);
    Swal.fire('Éxito','Logro actualizado.','success');
  })
  .catch(err=>{
    const c = document.getElementById('editLogroErrors');
    c.classList.remove('d-none');
    c.querySelector('ul').innerHTML =
      Object.values(err.errors||{msg:[err.message]}).flat()
        .map(msg=>`<li>${msg}</li>`).join('');
  });
}

// Borrar
function confirmDeleteLogro(id) {
  Swal.fire({
    title:'¿Eliminar?',
    text:'No se puede deshacer',
    icon:'warning',
    showCancelButton:true,
    confirmButtonColor:'#dc3545',
    confirmButtonText:'Sí, borrar'
  }).then(r=> r.isConfirmed && deleteLogro(id));
}
function deleteLogro(id) {
  fetch(`/admin/logros/${id}`, {
    method:'DELETE',
    headers:{ 'X-CSRF-TOKEN': csrfToken }
  })
  .then(r=>r.ok? r.json() : r.json().then(e=>Promise.reject(e)))
  .then(()=> {
    filterLogros(currentPage);
    Swal.fire('Eliminado','Logro borrado.','success');
  })
  .catch(err=>{
    Swal.fire('Error', err.message||'No se pudo eliminar.','error');
  });
}

// Init
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('filterNombre').addEventListener('input', ()=> filterLogros(1));
  filterLogros(currentPage);
  setInterval(()=> filterLogros(currentPage), 10000);
});
