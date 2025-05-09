import axios from 'axios';

const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
const btn = document.getElementById('notificationBtn');
const dropdown = document.getElementById('notificationDropdown');
const list = document.getElementById('notificationList');
const badge = btn.querySelector('.notification-badge');

// Marcar todas como leídas
document.querySelector('.mark-all-read').addEventListener('click', async (e) => {
    e.preventDefault();  // Prevenir el comportamiento predeterminado del enlace

    try {
        // Realiza la llamada AJAX para marcar todas las notificaciones como leídas
        const response = await axios.post('/notificaciones/mark-all-read');

        // Si la respuesta es exitosa, actualizamos la UI
        if (response.data.status === 'success') {
            // Cambiar el estado visual de las notificaciones a "leídas"
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => item.classList.remove('unread'));

            // Actualizar el contador de notificaciones
            const notificationBadge = document.querySelector('.notification-badge');
            if (notificationBadge) {
                notificationBadge.textContent = '0';
                notificationBadge.style.display = 'none';  // Ocultar el contador si no hay notificaciones
            }

            // Mostrar una confirmación si es necesario
            alert('Todas las notificaciones se han marcado como leídas');
        }
    } catch (error) {
        console.error('Error al marcar las notificaciones como leídas:', error);
    }
});
// Escuchar evento
window.Echo.private(`App.Models.User.${userId}`)
    .listen('NewNotificacion', e => {
        console.log('Notificación recibida:', e);  // Verifica si el mensaje contiene el nombre del emisor y el tipo

        const formattedTime = moment(e.notificacion.fecha_creacion).fromNow();  // Formato: "Hace 1 hora"

        // Verificar si 'tipo' existe y tiene un valor válido
        console.log('Tipo de notificación:', e.notificacion.tipo);  // Verifica que el tipo se esté pasando correctamente
        let icon = '';

        if (e.notificacion.tipo === 'mensaje') {
            icon = 'fas fa-comment';  // Icono para mensaje
        } else if (e.notificacion.tipo === 'valoracion') {
            icon = 'fas fa-star';  // Icono para valoración
        } else if (e.notificacion.tipo === 'postulacion') {
            icon = 'fas fa-clipboard-check';  // Icono para postulaciones
        } else {
            icon = 'fas fa-info-circle';  // Icono predeterminado
        }

        const html = `
        <div class="notification-item unread" data-id="${e.notificacion.id}">
            <div class="notification-icon"><i class="${icon}"></i></div>
            <div class="notification-content">
                <p>${e.notificacion.mensaje}</p>
                <span class="notification-time">${formattedTime}</span>
            </div>
        </div>`;

        list.insertAdjacentHTML('afterbegin', html);  // Insertamos la nueva notificación
    });





