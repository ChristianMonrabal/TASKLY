// inputs y tabla
const inputCliente = document.getElementById('filterCliente');
const inputFecha   = document.getElementById('filterFecha');
const tbody        = document.getElementById('completados-container');

// Función para renderizar filas
function renderCompletados(data) {
  tbody.innerHTML = '';
  if (data.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No se encontraron trabajos.</td></tr>';
    return;
  }
  data.forEach(t => {
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

// Petición con filtros
function loadCompletados() {
  const params = new URLSearchParams();
  if (inputCliente.value) params.append('cliente', inputCliente.value);
  if (inputFecha.value)   params.append('fecha', inputFecha.value);

  fetch(`/admin/trabajos/completados/json?${params.toString()}`, {
    headers: { 'Accept':'application/json' }
  })
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(renderCompletados)
    .catch(err => {
      console.error('Error al cargar completados:', err);
      tbody.innerHTML = `<tr><td colspan="6" class="text-danger text-center">Error cargando datos.</td></tr>`;
    });
}

// Listeners
inputCliente.addEventListener('input', () => loadCompletados());
inputFecha.addEventListener('change', () => loadCompletados());

// Inicial
document.addEventListener('DOMContentLoaded', loadCompletados);

// Actualizar la lista cada 1 segundo.
setInterval(loadCompletados, 1000);

