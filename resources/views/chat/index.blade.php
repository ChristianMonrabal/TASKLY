@extends('layouts.app')

@section('title', 'Mensajes - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection
@section('content')
    <div class="container-fluid py-3"> <!-- Añadido padding vertical extra -->
        <div class="mensajes-container mx-auto"> <!-- Centrado horizontalmente -->
            <!-- Columna de contactos -->
            <div class="contactos-column">
                <div class="contactos-header">
                    <h2>Mensajes</h2>
                    <div class="user-status">
                        <div class="current-user-avatar">
                            <img src="{{ asset('img/profile_images/' . $user->foto_perfil) }}"> 
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
                                                : $postulacion->trabajo->cliente->foto_perfil ?? 'perfil_default.png'),
                                    ) }}">
                                </div>
                                <div class="contacto-info">
                                    <h4 class="contacto-nombre">
                                        {{ $postulacion->tipo == 'recibida'
                                            ? $postulacion->trabajador->nombre
                                            : $postulacion->trabajo->cliente->nombre ?? 'Cliente' }}
                                    </h4>
                                    <p class="contacto-trabajo">
                                        <span style="color: var(--primary);">
                                            {{ $postulacion->tipo == 'recibida' ? 'para:' : 'aplicaste a:' }}
                                            <span id="infotrabajo{{ $postulacion->id }}">
                                                {{ $postulacion->trabajo->titulo }} </span>
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

                </div>

                <div class="messages-area" id="mensajes">

                </div>

                <div class="message-input-area" id="frmenviomensaje">
                    <div class="chat-input-actions">
                        <button class="chat-input-btn" title="Adjuntar archivo">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="chat-input-btn" title="Emoji">
                            <i class="far fa-smile"></i>
                        </button>
                    </div>
                    <textarea class="message-input" placeholder="Escribe un mensaje..."></textarea>
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
