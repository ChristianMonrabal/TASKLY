// Función para cambiar la imagen principal
function cambiarImagen(src) {
  document.getElementById('imagenPrincipal').src = src;
}

// Eventos para los botones
document.addEventListener('DOMContentLoaded', function() {
  // Botón de postulación
  const btnPostular = document.getElementById('btnPostular');
  if (btnPostular) {
    btnPostular.addEventListener('click', function() {
      const trabajoId = this.getAttribute('data-id');
      postularTrabajo(trabajoId);
    });
  }
  
  // Botón de chat
  const btnChat = document.getElementById('btnChat');
  if (btnChat) {
    btnChat.addEventListener('click', function() {
      const trabajoId = this.getAttribute('data-id');
      abrirChat(trabajoId);
    });
  }
});

// Función para postularse a un trabajo
function postularTrabajo(trabajoId) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  fetch('/trabajos/' + trabajoId + '/postular', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token
    }
  })
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    if (data.success) {
      alert('¡Te has postulado correctamente!');
      // Actualizar el botón o interfaz según sea necesario
    } else {
      alert(data.message || 'Hubo un error al postularse');
    }
  })
  .catch(function(error) {
    console.error('Error:', error);
    alert('Hubo un error al procesar tu solicitud');
  });
}

// Función para abrir chat
function abrirChat(trabajoId) {
  // Aquí puedes implementar la lógica para abrir un chat
  // Por ahora solo mostraremos un mensaje
  alert('Funcionalidad de chat en desarrollo');
}
