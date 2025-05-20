// public/js/admin-trabajos-completados.js

// Página actual (se conserva en el refresco automático)
let currentPage = 1;

// Referencias a inputs y contenedor de filas
const inputCliente = document.getElementById('filterCliente');
const inputFecha   = document.getElementById('filterFecha');
const tbody        = document.getElementById('completados-container');

// Dibuja las filas de la tabla
function renderCompletados(items) {
  tbody.innerHTML = '';
  if (items.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="5" class="text-center">No se encontraron trabajos.</td>
      </tr>
    `;
    return;
  }
  items.forEach(t => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${t.titulo}</td>
      <td>${t.cliente.nombre} ${t.cliente.apellidos}</td>
      <td>${t.estado.nombre}</td>
      <td>${new Date(t.updated_at).toLocaleString()}</td>
      <td>
        <button class="btn btn-success btn-sm">
          <i class="fas fa-download"></i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

// Dibuja la paginación
function renderPagination(meta) {
  const ul = document.getElementById('completados-pagination');
  if (!ul) return;
  ul.innerHTML = '';
  meta.links.forEach(link => {
    const li = document.createElement('li');
    li.className = 'page-item' +
      (link.active ? ' active' : '') +
      (!link.url   ? ' disabled' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.innerHTML = link.label.replace('&laquo;', '«').replace('&raquo;', '»');
    if (link.url) {
      const p = new URL(link.url).searchParams.get('page');
      a.addEventListener('click', e => {
        e.preventDefault();
        loadCompletados(Number(p));
      });
    }
    li.appendChild(a);
    ul.appendChild(li);
  });
}

// Carga datos (con filtros y página), y refresca tabla + paginación
function loadCompletados(page = currentPage) {
  currentPage = page;
  const params = new URLSearchParams();
  if (inputCliente.value.trim()) params.append('cliente', inputCliente.value.trim());
  if (inputFecha.value)           params.append('fecha',   inputFecha.value);
  params.append('page', currentPage);

  fetch(`/admin/trabajos/completados/json?${params.toString()}`, {
    headers: { 'Accept': 'application/json' }
  })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(json => {
      // Si tu controlador devuelve paginate(), json.data es el array,
      // y json.meta / json.links contienen la info de paginación.
      renderCompletados(json.data || json);
      renderPagination(json);
    })
    .catch(err => {
      console.error('Error al cargar completados:', err);
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-danger text-center">
            Error cargando datos.
          </td>
        </tr>
      `;
    });
}

// Listeners de filtros
inputCliente.addEventListener('input', () => loadCompletados(1));
inputFecha.addEventListener('change',  () => loadCompletados(1));

// Al inicio
document.addEventListener('DOMContentLoaded', () => {
  loadCompletados();
});

// Refresco automático cada 10s, conserva la página actual
setInterval(() => loadCompletados(currentPage), 10000);
