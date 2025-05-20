// public/js/admin-valoraciones.js

// Estado global de paginación
let currentPage = 1;

// Validación onblur
function validateNonEmpty(id, msg) {
  const f = document.getElementById(id),
        e = document.getElementById("error" + id.charAt(0).toUpperCase() + id.slice(1));
  if (!f.value.trim()) { e.textContent = msg; return false; }
  e.textContent = ''; return true;
}

function clearEditValoracionErrors() {
  const d = document.getElementById('editValErrors');
  d.classList.add('d-none');
  d.querySelector('ul').innerHTML = '';
}

function renderValoraciones(rows) {
  const c = document.getElementById('valoraciones-container');
  c.innerHTML = '';
  if (!rows.length) {
    c.innerHTML = '<tr><td colspan="6" class="text-center">No hay valoraciones.</td></tr>';
    return;
  }
  rows.forEach(v => {
    const cliente = v.trabajo?.cliente;
    const clienteNombre = cliente ? `${cliente.nombre} ${cliente.apellidos}` : '';
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${v.trabajo?.titulo || ''}</td>
      <td>${v.trabajador?.nombre || ''}</td>
      <td>${v.puntuacion}</td>
      <td>${v.comentario || ''}</td>
      <td>${clienteNombre}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="openEditModal(${v.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="confirmDelete(${v.id})">
          <i class="fa fa-trash"></i>
        </button>
      </td>`;
    c.appendChild(tr);
  });
}

function renderPagination(meta) {
  const ul = document.getElementById('valoraciones-pagination');
  ul.innerHTML = '';
  meta.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item' + (link.active ? ' active': '') + (!link.url ? ' disabled' :'');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.innerHTML = link.label;
    if (link.url) {
      const url = new URL(link.url);
      a.dataset.page = url.searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        currentPage = Number(a.dataset.page);
        filterValoraciones(currentPage);
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

function filterValoraciones(page = 1) {
  currentPage = page;
  const params = new URLSearchParams();
  ['filterTrabajador','filterCliente'].forEach(id=>{
    const v = document.getElementById(id).value.trim();
    if (v) params.append(id.replace('filter','').toLowerCase(), v);
  });
  params.append('page', page);

  fetch(`/admin/valoraciones/json?${params}`, {
    headers: { 'Accept':'application/json' }
  })
    .then(r => r.json())
    .then(json => {
      renderValoraciones(json.data);
      renderPagination(json);
    })
    .catch(err => {
      console.error('Error cargando valoraciones:', err);
    });
}

function openEditModal(id) {
  clearEditValoracionErrors();
  fetch(`/admin/valoraciones/${id}`, {
    headers: { 'Accept':'application/json' }
  })
    .then(r => r.json())
    .then(v => {
      document.getElementById('editValoracionId').value = v.id;
      document.getElementById('editComentario').value    = v.comentario || '';
      const img = document.getElementById('currentImgValoracion');
      if (v.img_valoracion) {
        img.src = `/storage/${v.img_valoracion}`;
        img.style.display = 'block';
      } else {
        img.style.display = 'none';
      }
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}

function submitEdit() {
  if (!validateNonEmpty('editComentario','Comentario obligatorio')) return;
  const id = document.getElementById('editValoracionId').value;
  const fd = new FormData(document.getElementById('editValoracionForm'));
  fetch(`/admin/valoraciones/${id}`, {
    method:'POST',
    headers:{ 
      'X-CSRF-TOKEN': csrfToken,
      'X-HTTP-Method-Override':'PUT',
      'Accept':'application/json'
    },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e=>{throw e;}))
  .then(_ => {
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    filterValoraciones(currentPage);
    Swal.fire('¡Éxito!','Valoración actualizada.','success');
  })
  .catch(err => {
    const d = document.getElementById('editValErrors');
    d.classList.remove('d-none');
    d.querySelector('ul').innerHTML = Object.values(err.errors||{})
      .flat().map(m=>`<li>${m}</li>`).join('');
  });
}

function confirmDelete(id) {
  Swal.fire({
    title:'¿Eliminar?', text:'No se podrá revertir.', icon:'warning',
    showCancelButton:true, confirmButtonText:'Sí, eliminar'
  }).then(r=>{ if(r.isConfirmed) deleteVal(id); });
}

function deleteVal(id) {
  fetch(`/admin/valoraciones/${id}`, {
    method:'DELETE',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'Accept':'application/json'
    }
  })
  .then(r => r.json())
  .then(_ => {
    filterValoraciones(currentPage);
    Swal.fire('Eliminado','Valoración eliminada.','success');
  });
}

document.addEventListener('DOMContentLoaded', () => {
  ['filterTrabajador','filterCliente'].forEach(id=>
    document.getElementById(id).addEventListener('input',()=>filterValoraciones(1))
  );
  filterValoraciones(1);

  // Recarga automática cada 10 segundos
  setInterval(() => {filterValoraciones(currentPage);}, 10000);
});
