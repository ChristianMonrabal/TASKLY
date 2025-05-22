// public/js/admin-logros.js

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentPage = 1;

// Validación de texto
function validateNonEmpty(id, msg) {
  const f = document.getElementById(id),
        e = document.getElementById('error' + id.charAt(0).toUpperCase() + id.slice(1));
  if (!f.value.trim()) { e.textContent = msg; return false; }
  e.textContent = ''; return true;
}
function clearErrors(containerId) {
  const d = document.getElementById(containerId);
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}

// Render tabla
function renderLogros(rows) {
  const tb = document.getElementById('logros-container');
  tb.innerHTML = '';
  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="4" class="text-center">No hay logros.</td></tr>';
    return;
  }
  rows.forEach(l => {
    // Ahora usamos directamente foto_url (URL completa) si existe
    const foto = l.foto_url
      ? `<img src="${l.foto_url}" style="height:40px;border-radius:4px">`
      : '';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${l.nombre}</td>
      <td>${l.descripcion||''}</td>
      <td>${foto}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="openEdit(${l.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="confirmDelete(${l.id})">
          <i class="fa fa-trash"></i>
        </button>
      </td>`;
    tb.appendChild(tr);
  });
}

// Render paginación
function renderPagination(meta) {
  const ul = document.getElementById('logros-pagination');
  ul.innerHTML = '';
  meta.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item' +
      (link.active ? ' active' : '') +
      (!link.url ? ' disabled' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.innerHTML = link.label;
    if (link.url) {
      a.dataset.page = new URL(link.url).searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        currentPage = +a.dataset.page;
        filterLogros(currentPage);
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// Fetch + filtros
function filterLogros(page = 1) {
  const params = new URLSearchParams();
  const q = document.getElementById('filterNombre').value.trim();
  if (q) params.append('nombre', q);
  params.append('page', page);

  fetch(`/admin/logros/json?${params}`, {
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
function openCreateModal() {
  clearErrors('createLogroErrors');
  document.getElementById('createLogroForm').reset();
  document.getElementById('previewCreate').style.display = 'none';
  new bootstrap.Modal(document.getElementById('createModal')).show();
}
function submitCreate() {
  if (!validateNonEmpty('createNombre', 'Nombre obligatorio')) return;

  const fd = new FormData(document.getElementById('createLogroForm'));
  fetch('/admin/logros', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
  .then(() => {
    bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
    filterLogros(currentPage);
    Swal.fire('Éxito','Logro creado.','success');
  })
  .catch(err => {
    const c = document.getElementById('createLogroErrors');
    c.classList.remove('d-none');
    c.querySelector('ul').innerHTML =
      Object.values(err.errors||{msg:[err.message]}).flat()
        .map(m=>`<li>${m}</li>`).join('');
  });
}

// Editar
function openEdit(id) {
  clearErrors('editLogroErrors');
  fetch(`/admin/logros/${id}`)
    .then(r => r.json())
    .then(l => {
      document.getElementById('editLogroId').value      = l.id;
      document.getElementById('editNombre').value       = l.nombre;
      document.getElementById('editDescripcion').value  = l.descripcion || '';

      // PREVIEW de la foto actual usando foto_url
      const img = document.getElementById('previewEdit');
      if (l.foto_url) {
        img.src = l.foto_url;
        img.style.display = 'block';
      } else {
        img.style.display = 'none';
      }

      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}
function submitEdit() {
  if (!validateNonEmpty('editNombre','Nombre obligatorio')) return;

  const id = document.getElementById('editLogroId').value;
  const fd = new FormData(document.getElementById('editLogroForm'));
  fd.append('_method','PUT');   // ← así Laravel reconoce el PUT
  fetch(`/admin/logros/${id}`, {
    method:'POST',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'X-HTTP-Method-Override':'PUT',
      'Accept': 'application/json'
    },
    body: fd
  })
  .then(r=>r.ok? r.json() : r.json().then(e=>Promise.reject(e)))
  .then(()=> {
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    filterLogros(currentPage);
    Swal.fire('Éxito','Logro actualizado.','success');
  })
  .catch(err=> {
    const c = document.getElementById('editLogroErrors');
    c.classList.remove('d-none');
    c.querySelector('ul').innerHTML =
      Object.values(err.errors||{msg:[err.message]}).flat()
        .map(m=>`<li>${m}</li>`).join('');
  });
}

// Borrar
function confirmDelete(id) {
  Swal.fire({
    title:'¿Eliminar?',
    text:'No se puede deshacer',
    icon:'warning',
    showCancelButton:true,
    confirmButtonText:'Sí, borrar'
  }).then(r=> r.isConfirmed && deleteLogro(id));
}
function deleteLogro(id) {
  fetch(`/admin/logros/${id}`, {
    method:'DELETE',
    headers:{ 'X-CSRF-TOKEN': csrfToken }
  })
  .then(r=>r.ok? r.json() : r.json().then(e=>Promise.reject(e)))
  .then(() => {
    filterLogros(currentPage);
    Swal.fire('Eliminado','Logro borrado.','success');
  })
  .catch(err => {
    Swal.fire('Error', err.message||'No se pudo eliminar.','error');
  });
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('filterNombre')
    .addEventListener('input', ()=> filterLogros(1));
  filterLogros(currentPage);
  setInterval(()=> filterLogros(currentPage), 10000);
});
