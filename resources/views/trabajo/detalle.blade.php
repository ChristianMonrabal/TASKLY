@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Detalles')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/detalle.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="trabajo-detalle-container">
            <div class="info-container">
                <a href="{{ url()->previous() }}" class="volver-btn"><i class="fas fa-arrow-left"></i> Volver</a>

                <h1 class="trabajo-titulo">{{ $trabajo->titulo }}</h1>

                <div class="info-grid">
                    <div class="info-col content-col">
                        <div class="seccion-detalle">
                            <h3 class="seccion-titulo"><i class="fas fa-align-left"></i> Descripción</h3>
                            <div class="descripcion">
                                {{ $trabajo->descripcion }}
                            </div>
                        </div>

                        <div class="seccion-detalle">
                            <h3 class="seccion-titulo"><i class="fas fa-info-circle"></i> Información</h3>
                            <div class="meta-info">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $trabajo->created_at->format('d/m/Y') }}</span>
                                </div>

                                @if ($trabajo->alta_responsabilidad === 'Sí')
                                    <div class="meta-item">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <span><strong>Alta responsabilidad</strong></span>
                                    </div>
                                @endif

                                <div class="meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span>
                                        @if ($trabajo->categoriastipotrabajo && $trabajo->categoriastipotrabajo->count() > 0)
                                            {{ $trabajo->categoriastipotrabajo->pluck('nombre')->implode(', ') }}
                                        @else
                                            Sin categoría
                                        @endif
                                    </span>
                                </div>

                                <div class="meta-item precio-destacado">
                                    <i class="fas fa-euro-sign"></i>
                                    <span>{{ $trabajo->precio }}</span>
                                </div>

                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $trabajo->direccion}}</span>
                                </div>
                            </div>
                        </div>

                        @if ($trabajo->habilidades && $trabajo->habilidades->count() > 0)
                            <div class="seccion-detalle">
                                <h3 class="seccion-titulo"><i class="fas fa-tools"></i> Habilidades requeridas</h3>
                                <div class="habilidades-lista">
                                    @foreach ($trabajo->habilidades as $habilidad)
                                        <span class="habilidad-tag">{{ $habilidad->nombre }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($trabajo->imagenes && $trabajo->imagenes->count() > 0)
                            <div class="seccion-detalle">
                                <h3 class="seccion-titulo"><i class="fas fa-images"></i> Imágenes</h3>
                                <div class="galeria-miniaturas">
                                    @foreach ($trabajo->imagenes as $index => $imagen)
                                        <div class="miniatura"
                                            onclick="abrirModalImagen('{{ asset('img/trabajos/' . $imagen->ruta_imagen) }}', {{ $index }})"
                                            data-index="{{ $index }}">
                                            <img src="{{ asset('img/trabajos/' . $imagen->ruta_imagen) }}"
                                                alt="{{ $trabajo->titulo }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="info-col sidebar-col">
                        <div class="sidebar-card">
                            <h4 class="card-titulo">Publicado por {{ $trabajo->cliente->nombre }}</h4>
                                <div class="usuario-info">
                                    <div class="usuario-avatar">
                                        <a href="{{ route('perfil.usuario', ['nombre' => Str::slug($trabajo->cliente->nombre . ' ' . $trabajo->cliente->apellidos)]) }}">
                                            <img src="{{ $trabajo->cliente->foto_perfil 
                                                        ? asset('img/profile_images/' . $trabajo->cliente->foto_perfil) 
                                                        : asset('img/profile_images/perfil_default.png') }}"
                                                alt="Foto de {{ $trabajo->cliente->nombre }}">
                                        </a>
                                    </div>
                                </div>
                            <div>
                                <div class="usuario-nombre">{{ $trabajo->cliente->nombre ?? 'Usuario' }}</div>
                                @php
                                    $totalValoraciones = $trabajo->cliente ? $trabajo->valoraciones->count() : 0;
                                @endphp
                                <div class="usuario-valoraciones">
                                    <i class="fas fa-star"></i>
                                    {{ $totalValoraciones }} reseñas
                                </div>
                            </div>
                        </div>

                        <div class="botones-accion">
                            @if (Auth::check())
                                @if (Auth::id() != $trabajo->cliente_id)
                                    <div class="boton-wrapper">
                                        @php
                                            $postulacion = $trabajo->postulaciones->where('trabajador_id', Auth::id())->first();
                                        @endphp

                                        @if ($postulacion)
                                            @if ($postulacion->estado_id == 11) {{-- Estado "rechazado" --}}
                                                <button class="btn btn-danger" disabled>Has sido rechazado</button>
                                            @elseif ($postulacion->estado_id == 10) {{-- Estado "aceptada" --}}
                                                <button class="btn btn-danger" disabled>Has sido seleccionado!</button>
                                            @else
                                                <form id="cancelar-postulacion-form" action="{{ route('trabajos.cancelarPostulacion', $trabajo->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-postular" onclick="confirmarCancelacion()">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                            @endif
                                        @else
                                            <form class="postular-form" action="{{ route('trabajos.postular', $trabajo->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-postular">
                                                    <i class="fas fa-paper-plane"></i> Postularme
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="boton-wrapper">
                                        <a href="{{ route('vista.chat', $trabajo->id) }}" class="btn btn-chat" title="Chatear">
                                            <i class="fas fa-comments fa-lg"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle"></i> Eres el creador de este trabajo
                                    </div>
                                @endif
                            @else
                                <div class="boton-wrapper">
                                    <button class="btn btn-postular" disabled>
                                        <i class="fas fa-sign-in-alt"></i> Necesitas iniciar sesión
                                    </button>
                                </div>

                                <div class="boton-wrapper">
                                    <button class="btn btn-chat" disabled title="Chatear">
                                        <i class="fas fa-comments fa-lg"></i> Mensajes
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if ($trabajo->postulantes_count)
                            <li class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>Postulaciones: {{ $trabajo->postulantes_count }}</span>
                            </li>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="imagen-modal" id="modalImagen">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <button class="modal-nav prev" onclick="navegarImagen(-1)">&#10094;</button>
        <button class="modal-nav next" onclick="navegarImagen(1)">&#10095;</button>
        <img id="modalImagenSrc" src="" alt="Imagen ampliada">
        <div class="contador-imagen" id="contadorImagen"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/detalle.js') }}"></script>
@endsection
