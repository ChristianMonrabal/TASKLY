@extends('layouts.app')

@section('title', 'Mensajes - TASKLY')

@section('styles')
    <style>
        .mensajes-container {
            display: flex;
            background-color: white;
            border-radius: 0;
            box-shadow: 0 5px 15px rgba(236, 106, 106, 0.15);
            overflow: hidden;
            height: calc(100vh - 120px);
            /* Más alto */
            min-height: 650px;
            /* Más alto */
            border: 2px solid var(--primary);
            margin-top: 10px;
            margin-bottom: 10px;
            width: 100%;
            max-width: 1400px;
            /* Más ancho para pantallas grandes */
        }

        /* Columna de contactos */
        .contactos-column {
            width: 300px;
            /* Un poco más estrecha */
            background-color: #f8f9fa;
            border-right: 1px solid var(--primary-light);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .contactos-header {
            padding: 20px;
            border-bottom: 1px solid var(--primary-light);
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }

        .contactos-header h2 {
            font-size: 20px;
            margin: 0 0 5px;
            color: var(--text);
        }

        .user-status {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--primary-light);
        }

        .current-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 12px;
            border: 2px solid var(--primary);
        }

        .current-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .current-user-info {
            flex: 1;
        }

        .current-user-name {
            font-weight: 500;
            color: var(--text);
            margin: 0 0 2px;
            font-size: 14px;
        }

        .current-user-job {
            color: var(--primary);
            font-size: 12px;
            margin: 0;
        }

        .search-box {
            margin-top: 12px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--primary);
            border-radius: 0;
            font-size: 14px;
            transition: all var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(236, 106, 106, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 14px;
        }

        .contactos-list {
            padding: 10px 0;
        }

        .contacto-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            cursor: pointer;
            transition: background-color var(--transition);
        }

        .contacto-item:hover {
            background-color: rgba(236, 106, 106, 0.03);
        }

        .contacto-item.active {
            background-color: rgba(236, 106, 106, 0.08);
            border-left: 3px solid var(--primary);
        }

        .contacto-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .contacto-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .contacto-info {
            flex: 1;
            overflow: hidden;
        }

        .contacto-nombre {
            font-weight: 500;
            color: var(--text);
            margin: 0 0 5px;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contacto-trabajo {
            color: var(--text-muted);
            font-size: 13px;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contacto-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-left: 10px;
        }

        .contacto-tiempo {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .contacto-badge {
            background-color: var(--primary);
            color: white;
            font-size: 11px;
            min-width: 18px;
            height: 18px;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            box-shadow: 0 2px 4px rgba(236, 106, 106, 0.25);
        }

        /* Columna de chat */
        º .chat-column {
            flex: 1;
            /* display: none; */
            flex-direction: column;
            min-width: 0;
            /* Evita que el contenido desborde en pantallas pequeñas */
        }

        .chat-header {
            padding: 20px;
            /* Más espacio en el encabezado */
            border-bottom: 1px solid var(--primary-light);
            display: flex;
            align-items: center;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 5;
        }

        .chat-user {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .chat-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .chat-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .chat-user-info h3 {
            margin: 0 0 3px;
            font-size: 16px;
            color: var(--text);
            display: flex;
            align-items: center;
        }

        .chat-user-info h3 .chat-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #2ecc71;
            border-radius: 0;
            margin-left: 8px;
        }

        .chat-user-info p {
            margin: 0;
            font-size: 13px;
            color: var(--primary);
        }

        .chat-context {
            font-size: 12px;
            color: var(--text-light);
            margin-top: 3px;
        }

        .chat-actions {
            display: flex;
            gap: 15px;
        }

        .chat-action-btn {
            background: transparent;
            border: none;
            color: var(--text-light);
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
            transition: color var(--transition);
        }

        .chat-action-btn:hover {
            color: var(--primary);
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f8f9fa;
            background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDBoNDB2NDBoLTQweiIvPjxwYXRoIGQ9Ik0xMCAxMGgyMHYyMGgtMjB6IiBmaWxsPSIjRUNFQ0VDIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48L2c+PC9zdmc+');
        }

        .message {
            display: flex;
            margin-bottom: 20px;
        }

        .message.outgoing {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
            margin-left: 0;
            flex-shrink: 0;
        }

        .message.outgoing .message-avatar {
            margin-left: 10px;
            margin-right: 0;
        }

        .message-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-content {
            max-width: 85%;
            /* Mayor anchura para los mensajes */
        }

        .message-bubble {
            background-color: white;
            padding: 15px 18px;
            /* Burbujas de chat más grandes */
            border-radius: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            margin-bottom: 5px;
            border: 1px solid var(--primary);
            max-width: 80%;
            /* Limita el ancho para mejor legibilidad */
        }

        .message.outgoing .message-bubble {
            background-color: var(--primary);
            color: white;
            border-radius: 0;
        }

        .message-text {
            margin: 0;
            line-height: 1.5;
            font-size: 15px;
            /* Texto más grande para mejor legibilidad */
        }

        .message-time {
            font-size: 13px;
            /* Texto de hora más grande */
            color: var(--text-muted);
            text-align: left;
            margin-top: 4px;
            /* Más separación del mensaje */
        }

        .message.outgoing .message-time {
            text-align: right;
            color: var(--text-muted);
        }

        .chat-input {
            padding: 20px;
            /* Más espacio para el área de entrada */
            border-top: 1px solid var(--primary-light);
            background-color: white;
            display: flex;
            align-items: center;
        }

        .chat-input-actions {
            display: flex;
            gap: 10px;
            margin-right: 10px;
        }

        .chat-input-btn {
            background: transparent;
            border: none;
            color: var(--text-light);
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
            transition: color var(--transition);
        }

        .chat-input-btn:hover {
            color: var(--primary);
        }

        .chat-input-field {
            flex: 1;
            position: relative;
        }

        .chat-input-field input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--primary);
            border-radius: 0;
            font-size: 14px;
            transition: all var(--transition);
        }

        .chat-input-field input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(236, 106, 106, 0.1);
        }

        .send-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            cursor: pointer;
            transition: all var(--transition);
            box-shadow: 0 2px 8px rgba(236, 106, 106, 0.3);
        }

        .send-btn:hover {
            background-color: var(--primary-dark);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(236, 106, 106, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mensajes-container {
                flex-direction: column;
                height: calc(100vh - 120px);
            }

            .contactos-column {
                width: 100%;
                height: 100%;
                max-height: 40vh;
                border-right: none;
                border-bottom: 1px solid var(--primary-light);
            }

            .chat-column {
                flex: 1;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid"> <!-- Cambiado a container-fluid para más ancho -->
        <div class="mensajes-container mx-auto"> <!-- Centrado horizontalmente -->
            <!-- Columna de contactos -->
            <div class="contactos-column">
                <div class="contactos-header">
                    <h2>Mensajes</h2>

                    <div class="user-status">
                        <div class="current-user-avatar">
                            <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}" alt="Tu avatar">
                        </div>
                        <div class="current-user-info">
                            <h4 class="current-user-name">{{ $user->nombre }} {{ $user->apellidos }}</h4>
                        </div>
                    </div>

                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar conversaciones...">
                    </div>
                </div>
                {{-- {{ $chats }} --}}
                @foreach ($chats as $postulacion)
                    <div class="contactos-list" onclick="cargamensaje({{ $postulacion->id }})">
                        <form method="post" id="chat{{ $postulacion->id }}">
                            @csrf
                            <input type="hidden" name="trabajo_id" value="{{ $postulacion->trabajo_id }}">
                            <input type="hidden" name="trabajador_id"
                                value="{{ $postulacion->tipo == 'recibida' ? $postulacion->trabajador_id : $postulacion->trabajo->cliente_id }}">

                            <div class="contacto-item" id="chatactivo{{ $postulacion->id }}">
                                <div class="contacto-avatar">
                                    <img src="{{ asset(
                                        'img/profile_images/' .
                                            ($postulacion->tipo == 'recibida'
                                                ? $postulacion->trabajador->foto_perfil
                                                : $postulacion->trabajo->cliente->foto_perfil ?? 'default.png'),
                                    ) }}"
                                        alt="Foto de perfil">
                                </div>
                                <div class="contacto-info">
                                    <h4 class="contacto-nombre">
                                        {{ $postulacion->tipo == 'recibida'
                                            ? $postulacion->trabajador->nombre
                                            : $postulacion->trabajo->cliente->nombre ?? 'Cliente' }}
                                    </h4>
                                    <p class="contacto-trabajo">
                                        <span style="color: var(--primary);">
                                            {{ $postulacion->tipo == 'recibida' ? 'Postulación recibida:' : 'Postulación realizada:' }}
                                            {{ $postulacion->trabajo->titulo }}
                                        </span>
                                    </p>
                                </div>
                                <div class="contacto-meta">
                                    <span class="contacto-tiempo">12:45</span>
                                    <span class="contacto-badge">3</span>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Columna de chat -->
            <div class="chat-column" style="display: none" id="seccionchat">
                <div class="chat-header" id="infouser">
                    {{-- <div class="chat-user">
                        <div class="chat-user-avatar">
                            <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                        </div>
                        <div class="chat-user-info">
                            <h3>Ana García <span class="chat-indicator"></span></h3>
                            <p>Diseñadora gráfica</p>
                            <div class="chat-context">Proyecto: Diseño de logo para empresa</div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="chat-action-btn" title="Llamar">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="chat-action-btn" title="Videollamada">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="chat-action-btn" title="Más opciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div> --}}
                </div>

                <div class="chat-messages" id="mensajes"
                    style="padding: 25px; display: flex; flex-direction: column; gap: 18px;">
                    <!-- Mensajes recibidos -->
                    {{-- <div class="message">
                        <div class="message-avatar">
                            <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p class="message-text">Hola! Quería consultarte sobre el diseño del logo que te pedí. ¿Has
                                    tenido tiempo de revisar los requerimientos?</p>
                            </div>
                            <div class="message-time">12:30 - Ana García</div>
                        </div>
                    </div>
                    <!-- Mensajes enviados -->
                    <div class="message outgoing">
                        <div class="message-avatar">
                            <img src="{{ asset('img/profile_images/default.jpg') }}" alt="Tú">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p class="message-text">¡Hola Ana! Sí, acabo de revisar todas las especificaciones y ya
                                    tengo algunas ideas. ¿Te parece si hacemos una videollamada mañana para mostrarte
                                    algunos bocetos iniciales?</p>
                            </div>
                            <div class="message-time">12:34 - {{ Auth::user()->name }}</div>
                        </div>
                    </div> --}}
                </div>

                <div class="chat-input" id="frmenviomensaje">
                    <div class="chat-input-actions">
                        <button class="chat-input-btn" title="Adjuntar archivo">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="chat-input-btn" title="Emoji">
                            <i class="far fa-smile"></i>
                        </button>
                    </div>
                    <div class="chat-input-field">
                        <input type="text" placeholder="Escribe un mensaje...">
                    </div>
                    <button class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/chat/cargamensaje.js') }}"></script>
@endsection
