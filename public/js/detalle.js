/**
 * Script para la página de detalles de trabajo
 * Maneja la galería de imágenes y la interacción con el usuario
 */

// Función para cambiar la imagen principal al hacer clic en una miniatura
function cambiarImagen(src) {
  const imagenPrincipal = document.getElementById('imagenPrincipal');
  if (imagenPrincipal) {
    // Animación suave de transición
    imagenPrincipal.style.opacity = '0.5';
    setTimeout(() => {
      imagenPrincipal.src = src;
      imagenPrincipal.style.opacity = '1';
    }, 200);
    
    // Marcar la miniatura activa
    const miniaturas = document.querySelectorAll('.imagen-miniatura');
    miniaturas.forEach(miniatura => {
      if (miniatura.getAttribute('src') === src) {
        miniatura.classList.add('active');
      } else {
        miniatura.classList.remove('active');
      }
    });
  }
}

// Eventos para la página
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar galería de imágenes
  const miniaturas = document.querySelectorAll('.imagen-miniatura');
  if (miniaturas.length > 0) {
    // Marcar la primera miniatura como activa
    miniaturas[0].classList.add('active');
    
    // Añadir controles de teclado para navegar por las imágenes
    document.addEventListener('keydown', function(e) {
      if (miniaturas.length <= 1) return;
      
      const activeIndex = Array.from(miniaturas).findIndex(m => m.classList.contains('active'));
      if (activeIndex === -1) return;
      
      // Flecha derecha: siguiente imagen
      if (e.key === 'ArrowRight' && activeIndex < miniaturas.length - 1) {
        cambiarImagen(miniaturas[activeIndex + 1].getAttribute('src'));
      }
      // Flecha izquierda: imagen anterior
      else if (e.key === 'ArrowLeft' && activeIndex > 0) {
        cambiarImagen(miniaturas[activeIndex - 1].getAttribute('src'));
      }
    });
  }
  
  // Botón para ver más valoraciones
  const verMasValoraciones = document.getElementById('verMasValoraciones');
  if (verMasValoraciones) {
    verMasValoraciones.addEventListener('click', function(e) {
      e.preventDefault();
      // Aquí iría la lógica para cargar más valoraciones
      alert('Funcionalidad de cargar más valoraciones pendiente de implementación');
    });
  }
  
  // Postulación para el trabajo
  const postularForm = document.querySelector('.postular-form');
  if (postularForm) {
    postularForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const trabajoId = window.location.pathname.split('/').pop();
      if (confirm('¿Estás seguro de que quieres postularte para este trabajo?')) {
        this.submit();
      }
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
