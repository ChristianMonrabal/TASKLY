<div class="notification-bell">
    <button id="notificationBtn" class="notification-btn">
        <i class="fas fa-bell"></i>
        @if($notificaciones->count() > 0)
            <span class="notification-badge">{{ $notificaciones->count() }}</span>
        @endif
    </button>

    <div id="notificationDropdown" class="notification-dropdown">
        <div class="notification-header">
            <h3>Notificaciones</h3>
            <a href="#" class="mark-all-read">Marcar todas como leídas</a>
        </div>
        <div id="notificationList" class="notification-list">
            @foreach($notificaciones as $noti)
                <a href="{{ $noti->url }}" class="notification-link">
                    <div class="notification-item {{ $noti->leido ? '' : 'unread' }}"
                         data-id="{{ $noti->id }}"
                         data-tipo="{{ $noti->tipo }}">
                        <div class="notification-icon">
                            <i class="{{ $noti->tipo == 'mensaje' ? 'fas fa-comment' : 'fas fa-info-circle' }}"></i>
                        </div>
                        <div class="notification-content">
                            <p>{{ $noti->mensaje }}</p>
                            <span class="notification-time">{{ $noti->fecha_creacion->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{-- <div class="notification-footer">
            <a href="{{ route('notificaciones.index') }}">Ver todas</a>
        </div> --}}
    </div>
</div>
