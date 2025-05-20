// public/js/admin-categorias.js

// Variable para guardar la página actual
let currentPage = 1;

// Validación onblur
function validateNonEmpty(fieldId, message) {
  const f = document.getElementById(fieldId),
        e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
  if (!f.value.trim()) { e.textContent = message; return false; }
  e.textContent = ''; return true;
}

// Limpia errores de creación
function clearCreateCategoriaErrors() {
  const d = document.getElementById('createCategoriaErrors');
  d.classList.add('d-none');
  d.innerHTML = '<ul></ul>';
}

// Limpia errores de edición
function clearEditCategoriaErrors() {
  const d = document.getElementById('editCategoriaErrors');
  d.classList.add('d-none');
  d.innerHTML = '<ul></ul>';
}

// Renderiza las filas de la tabla
function renderCategorias(data) {
  const container = document.getElementById('categorias-container');
  container.innerHTML = '';
  if (!data.length) {
    container.innerHTML = '<tr><td colspan="2" class="text-center">No hay categorías.</td></tr>';
    return;
  }
  data.forEach(cat => {
    const isVisible = cat.visible === 'Sí';
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${cat.nombre}</td>
      <td>
        <button class="btn btn-info btn-sm" onclick="toggleVisibleCategoria(${cat.id}, this)" title="${isVisible?'Ocultar':'Mostrar'}">
          <i class="fas ${isVisible?'fa-eye':'fa-eye-slash'}"></i>
        </button>
        <button class="btn btn-primary btn-sm" onclick="openEditModalCategoria(${cat.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="confirmDeleteCategoria(${cat.id})">
          <i class="fa fa-trash"></i>
        </button>
      </td>`;
    container.appendChild(tr);
  });
}

// Renderiza la paginación
function renderPagination(meta) {
  const ul = document.getElementById('categorias-pagination');
  ul.innerHTML = '';
  meta.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item' + (link.active?' active':'') + (!link.url?' disabled':'');
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
        filterCategorias(currentPage);
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// Llama al API con filtros + paginación
function filterCategorias(page = 1) {
  currentPage = page;
  const filterNombre = document.getElementById('filterNombre').value.trim();
  const sortOrder    = document.getElementById('sortOrder').value;
  const params = new URLSearchParams();
  if (filterNombre) params.append('nombre', filterNombre);
  params.append('sort', sortOrder);
  params.append('page', page);

  fetch(`/admin/categorias/json?${params}`, {
    headers:{ 'Accept':'application/json' }
  })
    .then(r => r.json())
    .then(json => {
      renderCategorias(json.data);
      renderPagination(json);
    })
    .catch(e => console.error('Error cargando categorías:', e));
}

// Crear nueva categoría
function openCreateModalCategoria() {
  clearCreateCategoriaErrors();
  document.getElementById('createNombre').value = '';
  new bootstrap.Modal(document.getElementById('createModal')).show();
}
function submitCreateCategoria() {
  if (!validateNonEmpty('createNombre','El nombre es obligatorio')) return;
  const fd = new FormData(document.getElementById('createCategoriaForm'));
  fetch('/admin/categorias', {
    method:'POST',
    headers:{ 
      'X-CSRF-TOKEN': csrfToken,
      'Accept':'application/json'
    },
    body: fd
  })
  .then(r => r.ok ? r.json() : r.json().then(e=>{throw e;}))
  .then(data => {
    clearCreateCategoriaErrors();
    bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
    filterCategorias(1);
    Swal.fire('¡Éxito!',data.message,'success');
  })
  .catch(err => {
    const d = document.getElementById('createCategoriaErrors');
    d.classList.remove('d-none');
    d.innerHTML = '<ul>' + Object.values(err.errors||{}).flat()
      .map(m=>`<li>${m}</li>`).join('') + '</ul>';
  });
}

// Editar categoría
function openEditModalCategoria(id) {
  clearEditCategoriaErrors();
  fetch(`/admin/categorias/${id}`,{ headers:{Accept:'application/json'} })
    .then(r=>r.json())
    .then(cat=>{
      document.getElementById('editCategoriaId').value = cat.id;
      document.getElementById('editNombre').value      = cat.nombre;
      document.getElementById('editVisible').value     = cat.visible;
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}
function submitEditCategoria() {
  if (!validateNonEmpty('editNombre','El nombre es obligatorio')) return;
  const id = document.getElementById('editCategoriaId').value;
  const fd = new FormData(document.getElementById('editCategoriaForm'));
  fetch(`/admin/categorias/${id}`, {
    method:'POST',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'X-HTTP-Method-Override':'PUT',
      'Accept':'application/json'
    },
    body: fd
  })
  .then(r=>r.ok?r.json():r.json().then(e=>{throw e;}))
  .then(data=>{
    clearEditCategoriaErrors();
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    filterCategorias(currentPage);
    Swal.fire('¡Éxito!',data.message,'success');
  })
  .catch(err=>{
    const d = document.getElementById('editCategoriaErrors');
    d.classList.remove('d-none');
    d.innerHTML = '<ul>' + Object.values(err.errors||{}).flat()
      .map(m=>`<li>${m}</li>`).join('') + '</ul>';
  });
}

// Toggle visible
function toggleVisibleCategoria(id, btn) {
  fetch(`/admin/categorias/${id}/toggle-visible`, {
    method:'PATCH',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'Accept':'application/json'
    }
  })
  .then(r=>r.json())
  .then(data=>{
    const icon = btn.querySelector('i');
    if (data.visible==='Sí') {
      icon.classList.replace('fa-eye-slash','fa-eye');
      btn.title = 'Ocultar';
    } else {
      icon.classList.replace('fa-eye','fa-eye-slash');
      btn.title = 'Mostrar';
    }
    Swal.fire('¡Actualizado!','Visible: '+data.visible,'success');
  });
}

// Eliminar categoría
function confirmDeleteCategoria(id) {
  Swal.fire({
    title:'¿Eliminar?',text:'No podrás revertirlo.',icon:'warning',
    showCancelButton:true,confirmButtonText:'Sí, eliminar'
  }).then(r=> r.isConfirmed && deleteCategoria(id));
}
function deleteCategoria(id) {
  fetch(`/admin/categorias/${id}`,{
    method:'DELETE',
    headers:{
      'X-CSRF-TOKEN': csrfToken,
      'Accept':'application/json'
    }
  })
  .then(r=>r.json().then(data=>({ok:r.ok,data})))
  .then(({ok,data})=>{
    if (ok) {
      filterCategorias(currentPage);
      Swal.fire('¡Eliminado!',data.message,'success');
    } else {
      Swal.fire('Aviso', data.message,'warning');
    }
  });
}

// Inicia todo al cargar la página
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('filterNombre').addEventListener('input',()=>filterCategorias(1));
  document.getElementById('sortOrder').addEventListener('change',()=>filterCategorias(1));
  // arrancamos en página 1
  filterCategorias(1);
  // recarga cada 10s
  setInterval(() => filterCategorias(currentPage), 10000);
});
