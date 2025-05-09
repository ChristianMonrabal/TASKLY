<!-- Verifica que $notiCount y $notificaciones estén disponibles -->
<div class="notification-bell">
    <button id="notificationBtn" class="notification-btn">
        <i class="fas fa-bell"></i>
        @if($notiCount > 0)
            <span class="notification-badge">{{ $notiCount }}</span>
        @endif
    </button>
    <div id="notificationDropdown" class="notification-dropdown">
        <div class="notification-header">
            <h3>Notificaciones</h3>
            <a href="#" class="mark-all-read">Marcar todas como leídas</a>
        </div>
        <div id="notificationList" class="notification-list">
            @foreach($notificaciones as $nota)
                <div class="notification-item {{ $nota->leido ? '' : 'unread' }}" data-id="{{ $nota->id }}">
                    <div class="notification-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notification-content">
                        <p>{{ $nota->mensaje }}</p>
                        <span class="notification-time">
                            {{ $nota->fecha_creacion->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="notification-footer">
            <a href="{{ route('notificaciones.index') }}">Ver todas</a>
        </div>
    </div>
</div>
