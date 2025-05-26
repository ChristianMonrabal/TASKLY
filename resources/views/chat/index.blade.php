@extends('layouts.app')

@section('title', 'Mensajes - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@endsection
@section('content')
    <div class="container-fluid p-0"> <!-- Eliminado padding para vista más completa -->
        <div class="whatsapp-container">
            <!-- Botón de volver a contactos (visible solo en móvil) -->
            <div class="back-to-contacts" id="backToContacts">
                <i class="fas fa-arrow-left"></i>
            </div>
            
            <!-- Columna de contactos -->
            <div class="contactos-column" id="contactosColumn">
                <div class="contactos-header">
                    <div class="user-status">
                        <div class="current-user-avatar">
                            <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}"> 
                        </div>
                        <div class="current-user-info">
                            <h4 class="current-user-name">{{ $user->nombre }}</h4>
                        </div>
                        <div class="header-actions">
                            <button class="header-action-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                    <div class="search-box">
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Buscar o empezar un chat nuevo">
                        </div>
                    </div>
                </div>
                <div class="chats-container">
                    @foreach ($chats as $postulacion)
                        <div class="contacto-item" id="chatactivo{{ $postulacion->id }}" onclick="cargamensaje({{ $postulacion->id }})">
                            <form method="post" id="chat{{ $postulacion->id }}" class="hidden-form">
                                @csrf
                                <input type="hidden" name="trabajo_id" value="{{ $postulacion->trabajo_id }}">
                                <input type="hidden" name="trabajador_id" value="{{ $postulacion->tipo == 'recibida' ? $postulacion->trabajador_id : $postulacion->trabajo->cliente_id }}">
                            </form>
                            <div class="contacto-avatar">
                                <img src="{{ asset(
                                    'img/profile_images/' .
                                        ($postulacion->tipo == 'recibida'
                                            ? $postulacion->trabajador->foto_perfil
                                            : $postulacion->trabajo->cliente->foto_perfil ?? 'perfil_default.png'),
                                ) }}">
                            </div>
                            <div class="contacto-info">
                                <div class="contacto-row">
                                    <h4 class="contacto-nombre">
                                        {{ $postulacion->tipo == 'recibida'
                                            ? $postulacion->trabajador->nombre
                                            : $postulacion->trabajo->cliente->nombre ?? 'Cliente' }}
                                    </h4>
                                    <span class="contacto-tiempo">12:45</span>
                                </div>
                                <div class="contacto-row">
                                    <p class="contacto-last-message">
                                        <span id="infotrabajo{{ $postulacion->id }}" class="truncate-text">
                                            <i class="fas fa-briefcase"></i> {{ $postulacion->trabajo->titulo }}
                                        </span>
                                    </p>
                                    <div class="contacto-meta">
                                        <span class="contacto-badge">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Columna de chat -->
            <div class="chat-column" id="seccionchat">
                <div class="chat-bg"></div>
                <div class="chat-header" id="infouser">
                    <!-- El contenido se carga dinámicamente -->
                </div>

                <div class="messages-area" id="mensajes">
                    <!-- El contenido se carga dinámicamente -->
                </div>

                <div class="message-input-area" id="frmenviomensaje">
                    <!-- El contenido se carga dinámicamente -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- Si hay conversación seleccionada por URL, la abrimos --}}
    @if(! is_null($selectedChatId))
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            // 1) Mostrar la columna de chat
            document.getElementById('seccionchat').style.display = 'flex';
            // 2) Marcar el contacto activo
            const activo = document.getElementById('chatactivo{{ $selectedChatId }}');
            if (activo) activo.classList.add('active');
            // 3) Cargar sus mensajes
            cargamensaje({{ $selectedChatId }});
            // 4) En móvil, mostrar el chat y ocultar contactos
            if (window.innerWidth < 768) {
                document.getElementById('contactosColumn').classList.add('hidden-mobile');
                document.getElementById('seccionchat').classList.add('visible-mobile');
                document.getElementById('backToContacts').style.display = 'flex';
            }
        });
        </script>
    @endif

    <script src="{{ asset('js/chat/cargamensaje.js') }}"></script>
@endsection
