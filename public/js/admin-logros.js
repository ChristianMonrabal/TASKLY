// public/js/admin-logros.js

// Validaciones
function validateNonEmpty(fieldId, message) {
    const f = document.getElementById(fieldId);
    const e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
    if (f.value.trim() === "") {
        e.textContent = message;
        return false;
    }
    e.textContent = "";
    return true;
}

function validateNumeric(fieldId, message) {
    const f = document.getElementById(fieldId);
    const e = document.getElementById("error" + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
    if (f.value === "" || isNaN(f.value) || Number(f.value) < 0) {
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
    data.forEach(l => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${l.nombre}</td>
          <td>${l.descripcion||''}</td>
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

// Filtrado en vivo
function filterLogros() {
    const q = document.getElementById('filterNombre').value.trim();
    const url = q ? `/api/logros?nombre=${encodeURIComponent(q)}` : '/api/logros';
    fetch(url)
      .then(r => r.json())
      .then(renderLogros)
      .catch(console.error);
}

// Modales y envíos
function openCreateModalLogro() {
    clearCreateLogroErrors();
    document.getElementById('createLogroForm').reset();
    new bootstrap.Modal(document.getElementById('createModal')).show();
}

function submitCreateLogro() {
    if (!validateNonEmpty('createNombre', 'Nombre obligatorio') ||
        !validateNumeric('createDescuento', 'Descuento ≥ 0')) return;

    const formData = new FormData(document.getElementById('createLogroForm'));
    fetch('/admin/logros', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: formData
    })
    .then(r => r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
    .then(data => {
        new bootstrap.Modal(document.getElementById('createModal')).hide();
        filterLogros();
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(err => {
        const c = document.getElementById('createLogroErrors');
        c.classList.remove('d-none');
        c.querySelector('ul').innerHTML = Object.values(err.errors||{msg:[err.message]}).flat().map(msg=>`<li>${msg}</li>`).join('');
    });
}

function openEditModalLogro(id) {
    clearEditLogroErrors();
    fetch(`/api/logros/${id}`)
      .then(r=>r.json())
      .then(l => {
        document.getElementById('editLogroId').value = l.id;
        document.getElementById('editNombre').value   = l.nombre;
        document.getElementById('editDescripcion').value = l.descripcion||'';
        document.getElementById('editDescuento').value = l.descuento;
        new bootstrap.Modal(document.getElementById('editModal')).show();
      });
}

function submitEditLogro() {
    if (!validateNonEmpty('editNombre', 'Nombre obligatorio') ||
        !validateNumeric('editDescuento', 'Descuento ≥ 0')) return;

    const id = document.getElementById('editLogroId').value;
    const fd = new FormData(document.getElementById('editLogroForm'));

    fetch(`/admin/logros/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-HTTP-Method-Override': 'PUT'
        },
        body: fd
    })
    .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
    .then(data => {
        // cerrar modal correctamente
        const editModalEl = document.getElementById('editModal');
        const editModal   = bootstrap.Modal.getInstance(editModalEl);
        editModal.hide();

        filterLogros();
        Swal.fire('Éxito', data.message, 'success');
    })
    .catch(err => {
        const c = document.getElementById('editLogroErrors');
        c.classList.remove('d-none');
        c.querySelector('ul').innerHTML = Object.values(err.errors || {msg:[err.message]})
          .flat()
          .map(msg => `<li>${msg}</li>`)
          .join('');
    });
}

function confirmDeleteLogro(id) {
    Swal.fire({
      title: '¿Eliminar?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton:true,
      confirmButtonColor:'#dc3545',
      confirmButtonText:'Sí, borrar'
    }).then(res=>{
      if(res.isConfirmed) deleteLogro(id);
    });
}

function deleteLogro(id) {
    fetch(`/admin/logros/${id}`, {
        method:'DELETE',
        headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r=>r.ok? r.json() : r.json().then(e=>Promise.reject(e)))
    .then(data=>{
        filterLogros();
        Swal.fire('Eliminado', data.message, 'success');
    })
    .catch(err=>{
        Swal.fire('Error', err.message || 'No se pudo eliminar.', 'error');
    });
}

// Inicialización
document.addEventListener('DOMContentLoaded', ()=> {
  filterLogros();
  document.getElementById('filterNombre').addEventListener('input', filterLogros);
});
