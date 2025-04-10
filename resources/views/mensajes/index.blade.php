@extends('layouts.app')

@section('title', 'Mensajes - TASKLY')

@section('styles')
    <style>
        .mensajes-container {
            display: flex;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            height: calc(100vh - 150px);
            min-height: 500px;
        }
        
        /* Columna de contactos */
        .contactos-column {
            width: 320px;
            background-color: #f8f9fa;
            border-right: 1px solid var(--border);
            overflow-y: auto;
            flex-shrink: 0;
        }
        
        .contactos-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }
        
        .contactos-header h2 {
            font-size: 20px;
            margin: 0;
            color: var(--text);
        }
        
        .search-box {
            margin-top: 12px;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border);
            border-radius: 30px;
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
            border-bottom: 1px solid rgba(0,0,0,0.03);
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
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }
        
        /* Columna de chat */
        .chat-column {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            background-color: white;
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
        }
        
        .chat-user-info p {
            margin: 0;
            font-size: 13px;
            color: var(--primary);
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
            max-width: 70%;
        }
        
        .message-bubble {
            background-color: white;
            padding: 12px 15px;
            border-radius: 15px;
            border-top-left-radius: 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 5px;
        }
        
        .message.outgoing .message-bubble {
            background-color: var(--primary);
            color: white;
            border-radius: 15px;
            border-top-right-radius: 0;
        }
        
        .message-text {
            margin: 0;
            line-height: 1.4;
            font-size: 14px;
        }
        
        .message-time {
            font-size: 12px;
            color: var(--text-muted);
            text-align: left;
        }
        
        .message.outgoing .message-time {
            text-align: right;
            color: var(--text-muted);
        }
        
        .chat-input {
            padding: 15px;
            border-top: 1px solid var(--border);
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
            border: 1px solid var(--border);
            border-radius: 30px;
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
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            cursor: pointer;
            transition: all var(--transition);
        }
        
        .send-btn:hover {
            background-color: var(--primary-dark);
            transform: scale(1.05);
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
                border-bottom: 1px solid var(--border);
            }
            
            .chat-column {
                flex: 1;
            }
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="mensajes-container">
        <!-- Columna de contactos -->
        <div class="contactos-column">
            <div class="contactos-header">
                <h2>Mensajes</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar conversaciones...">
                </div>
            </div>
            <div class="contactos-list">
                <!-- Contacto activo -->
                <div class="contacto-item active">
                    <div class="contacto-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="contacto-info">
                        <h4 class="contacto-nombre">Ana García</h4>
                        <p class="contacto-trabajo">Diseñadora gráfica • Proyecto de logo</p>
                    </div>
                    <div class="contacto-meta">
                        <span class="contacto-tiempo">12:45</span>
                        <span class="contacto-badge">3</span>
                    </div>
                </div>
                
                <!-- Otros contactos -->
                <div class="contacto-item">
                    <div class="contacto-avatar">
                        <img src="{{ asset('img/profile_images/profile_3_1744135395.jpeg') }}" alt="Carlos Martínez">
                    </div>
                    <div class="contacto-info">
                        <h4 class="contacto-nombre">Carlos Martínez</h4>
                        <p class="contacto-trabajo">Desarrollador web • Sitio e-commerce</p>
                    </div>
                    <div class="contacto-meta">
                        <span class="contacto-tiempo">Ayer</span>
                    </div>
                </div>
                
                <div class="contacto-item">
                    <div class="contacto-avatar">
                        <img src="{{ asset('img/profile_images/profile_11_1744135112.jpeg') }}" alt="Lucía Fernández">
                    </div>
                    <div class="contacto-info">
                        <h4 class="contacto-nombre">Lucía Fernández</h4>
                        <p class="contacto-trabajo">Fotógrafa • Sesión producto</p>
                    </div>
                    <div class="contacto-meta">
                        <span class="contacto-tiempo">27/03</span>
                    </div>
                </div>
                
                <div class="contacto-item">
                    <div class="contacto-avatar">
                        <img src="{{ asset('img/profile_images/profile_12_1744136104.jpeg') }}" alt="Mario Rodríguez">
                    </div>
                    <div class="contacto-info">
                        <h4 class="contacto-nombre">Mario Rodríguez</h4>
                        <p class="contacto-trabajo">SEO • Optimización web</p>
                    </div>
                    <div class="contacto-meta">
                        <span class="contacto-tiempo">23/03</span>
                        <span class="contacto-badge">1</span>
                    </div>
                </div>
                
                <div class="contacto-item">
                    <div class="contacto-avatar">
                        <img src="{{ asset('img/profile_images/profile_2_1744137099.jpeg') }}" alt="Elena Torres">
                    </div>
                    <div class="contacto-info">
                        <h4 class="contacto-nombre">Elena Torres</h4>
                        <p class="contacto-trabajo">Redactora • Contenido blog</p>
                    </div>
                    <div class="contacto-meta">
                        <span class="contacto-tiempo">18/03</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna de chat -->
        <div class="chat-column">
            <div class="chat-header">
                <div class="chat-user">
                    <div class="chat-user-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="chat-user-info">
                        <h3>Ana García</h3>
                        <p>Diseñadora gráfica</p>
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
                </div>
            </div>
            
            <div class="chat-messages">
                <!-- Mensajes recibidos -->
                <div class="message">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">Hola! Quería consultarte sobre el diseño del logo que te pedí. ¿Has tenido tiempo de revisar los requerimientos?</p>
                        </div>
                        <div class="message-time">12:30</div>
                    </div>
                </div>
                
                <!-- Mensajes enviados -->
                <div class="message outgoing">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/default.jpg') }}" alt="Tú">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">¡Hola Ana! Sí, acabo de revisar todas las especificaciones y ya tengo algunas ideas. ¿Te parece si hacemos una videollamada mañana para mostrarte algunos bocetos iniciales?</p>
                        </div>
                        <div class="message-time">12:34</div>
                    </div>
                </div>
                
                <div class="message">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">¡Perfecto! Me vendría muy bien. ¿Qué tal a las 10:00 AM?</p>
                        </div>
                        <div class="message-time">12:37</div>
                    </div>
                </div>
                
                <div class="message">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">Por cierto, te envié unos ejemplos de logos que me gustan al correo. ¿Los has recibido?</p>
                        </div>
                        <div class="message-time">12:38</div>
                    </div>
                </div>
                
                <div class="message outgoing">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/default.jpg') }}" alt="Tú">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">Sí, 10:00 AM me parece perfecto. Y acabo de revisar el correo, tengo los ejemplos que enviaste. Me ayudarán mucho a entender mejor tu estilo visual preferido.</p>
                        </div>
                        <div class="message-time">12:42</div>
                    </div>
                </div>
                
                <div class="message">
                    <div class="message-avatar">
                        <img src="{{ asset('img/profile_images/profile_7_1744137153.jpeg') }}" alt="Ana García">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="message-text">¡Genial! Entonces hablamos mañana. Estoy emocionada por ver tus ideas.</p>
                        </div>
                        <div class="message-time">12:45</div>
                    </div>
                </div>
            </div>
            
            <div class="chat-input">
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
