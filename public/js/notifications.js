const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
const btn    = document.getElementById('notificationBtn');
const list   = document.getElementById('notificationList');
const badge  = btn.querySelector('.notification-badge') || createBadge();

// Inicial
document.addEventListener('DOMContentLoaded', () => {
    fetchNotifications();
    updateBadgeCount();
});

function createBadge() {
    const span = document.createElement('span');
    span.className = 'notification-badge';
    span.textContent = '0';
    btn.appendChild(span);
    return span;
}

async function fetchNotifications() {
    try {
        const { data } = await axios.get('/notificaciones/new');
        data.forEach(notif => {
            if (!document.querySelector(`.notification-item[data-id="${notif.id}"]`) && !notif.leido) {
                addNotificationToDOM(notif);
            }
        });
        updateBadgeCount();
    } catch (e) {
        console.error('Error al obtener notificaciones:', e);
    }
}

function addNotificationToDOM({ id, mensaje, fecha_creacion, tipo, url, leido }) {
    const link = document.createElement('a');
    link.href = url;
    link.className = 'notification-link';

    const item = document.createElement('div');
    item.className = `notification-item ${leido ? '' : 'unread'}`;
    item.dataset.id   = id;
    item.dataset.tipo = tipo;
    item.innerHTML = `
        <div class="notification-icon">
            <i class="${tipo === 'mensaje' ? 'fas fa-comment' : 'fas fa-info-circle'}"></i>
        </div>
        <div class="notification-content">
            <p>${mensaje}</p>
            <span class="notification-time">${moment(fecha_creacion).fromNow()}</span>
        </div>
    `;
    link.appendChild(item);

    link.addEventListener('click', async () => {
        if (!leido) {
            try {
                await axios.post(`/notificaciones/${id}/mark-read`);
                item.classList.remove('unread');
                updateBadgeCount();
            } catch (err) {
                console.error('Error marcando leída:', err);
            }
        }
    });

    list.insertBefore(link, list.firstChild);
}

function updateBadgeCount() {
    const count = document.querySelectorAll('.notification-item.unread').length;
    badge.textContent = count;
    badge.style.display = count > 0 ? 'block' : 'none';
}

// Marcar todas como leídas
document.querySelector('.mark-all-read')?.addEventListener('click', async e => {
    e.preventDefault();
    try {
        const { data } = await axios.post('/notificaciones/mark-all-read', {}, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        if (data.success) {
            document.querySelectorAll('.notification-item.unread')
                .forEach(el => el.classList.remove('unread'));
            badge.remove();
        }
    } catch (err) {
        console.error(err);
    }
});

// Escuchar evento broadcast
if (window.Echo && userId) {
    Echo.channel(`App.Models.User.${userId}`)
        .listen('NewNotificacion', e => {
            addNotificationToDOM({
                id: e.notificacion.id,
                mensaje: e.notificacion.mensaje,
                fecha_creacion: e.notificacion.fecha_creacion,
                tipo: e.notificacion.tipo,
                url: e.url,
                leido: e.notificacion.leido,
            });
        });
}

// Polling cada 5s
setInterval(fetchNotifications, 5000);