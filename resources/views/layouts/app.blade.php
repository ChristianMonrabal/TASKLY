<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TASKLY - Plataforma de Trabajos Freelance')</title>
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png" />

    <!-- Estilos principales -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}" />

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Estilos específicos de la página -->
    @yield('styles')
    
    <!-- Scripts en el head -->
    @yield('head_scripts')
</head>

<body>
    <div class="app-container">
        <!-- Cabecera principal -->
        <header class="main-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="{{ route('trabajos.index') }}">
                            <img src="{{ asset('img/icon.png') }}" alt="TASKLY Logo" class="logo-img">
                        </a>
                    </div>

                    <nav class="main-nav">
                        <ul>
                            <li><a href="{{ route('trabajos.index') }}"
                                    class="nav-link {{ request()->routeIs('trabajos.index') ? 'active' : '' }}"><i
                                        class="fas fa-briefcase"></i> Trabajos</a></li>
                            <li><a href="{{ url('trabajos_publicados') }}" class="nav-link {{ request()->is('trabajos_publicados') ? 'active' : '' }}"><i class="fas fa-project-diagram"></i> Mis
                                    Trabajos</a></li>
                            <li><a href="{{ route('vista.chat') }}"
                                    class="nav-link {{ request()->is('mensajes*') ? 'active' : '' }}"><i
                                        class="fas fa-envelope"></i> Mensajes</a></li>
                        </ul>
                    </nav>

                    <div class="user-actions">
                        <!-- Si el usuario está autenticado, mostrar notificaciones -->
                        @auth
                            <!-- Icono para añadir trabajo -->
                            <div class="trabajo-icon">
                                <a href="/crear_trabajo" class="trabajo-btn" title="Añadir nuevo trabajo">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </div>

                            <!-- Botón de notificaciones -->
                            <div class="notification-bell">
                                <button class="notification-btn" id="notificationBtn">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-badge">3</span>
                                </button>
                                <div class="notification-dropdown" id="notificationDropdown">
                                    <div class="notification-header">
                                        <h3>Notificaciones</h3>
                                        <a href="#" class="mark-all-read">Marcar todas como leídas</a>
                                    </div>
                                    <div class="notification-list">
                                        <div class="notification-item unread">
                                            <div class="notification-icon">
                                                <i class="fas fa-comment"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p>Tienes un nuevo mensaje de <strong>Juan Pérez</strong></p>
                                                <span class="notification-time">Hace 5 minutos</span>
                                            </div>
                                        </div>
                                        <div class="notification-item unread">
                                            <div class="notification-icon">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p>Tu proyecto ha recibido una nueva valoración</p>
                                                <span class="notification-time">Hace 1 hora</span>
                                            </div>
                                        </div>
                                        <div class="notification-item">
                                            <div class="notification-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p>Tu postulación para "Diseño web" ha sido aceptada</p>
                                                <span class="notification-time">Ayer</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notification-footer">
                                        <a href="#" class="view-all">Ver todas</a>
                                    </div>
                                </div>
                            </div>

                            <div class="user-dropdown">
                                <button class="dropdown-btn">
                                    <div class="user-avatar">
                                        <img src="{{ asset('img/profile_images/' . (Auth::user()->foto_perfil ?? 'default.jpg')) }}"
                                            class="current-photo">

                                    </div>
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="icon">▼</span>
                                </button>
                                <div class="dropdown-content">
                                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Mi Perfil</a>
                                    <a href="{{ route('trabajos.index') }}"><i class="fas fa-briefcase"></i> Trabajos</a>
                                    <a href="{{ url('/trabajos_publicados') }}"><i class="fas fa-project-diagram"></i> Mis Trabajos</a>
                                    <a href="{{ route('vista.chat') }}"><i class="fas fa-envelope"></i> Mensajes</a>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                        </a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Si el usuario no está autenticado -->
                            <div class="auth-buttons">
                                <a href="{{ route('signin.auth') }}" class="btn btn-outline">Iniciar Sesión</a>
                                <a href="{{ route('signup.auth') }}" class="btn btn-primary">Registrarse</a>
                            </div>

                            <!-- Menú móvil para no autenticados (sólo visible en móvil) -->
                            <div class="mobile-menu-no-auth">
                                <button class="mobile-dropdown-btn">
                                    <span class="icon">▼</span>
                                </button>
                                <div class="mobile-dropdown-content">
                                    <a href="{{ route('trabajos.index') }}"><i class="fas fa-briefcase"></i> Trabajos</a>
                                    <a href="{{ route('signin.auth') }}"><i class="fas fa-sign-in-alt"></i> Iniciar
                                        Sesión</a>
                                    <a href="{{ route('signup.auth') }}"><i class="fas fa-user-plus"></i> Registrarse</a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Eliminamos el menú móvil independiente -->

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- Pie de página -->
        <footer class="main-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-logo">
                        <h2>TASKLY</h2>
                        <p>La plataforma para encontrar y ofrecer servicios freelance</p>
                    </div>

                    <div class="footer-links">
                        <div class="footer-column">
                            <h3>Explorar</h3>
                            <ul>
                                <li><a href="{{ route('trabajos.index') }}">Trabajos</a></li>
                                <li><a href="#">Freelancers</a></li>
                                <li><a href="#">Categorías</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h3>Acerca de</h3>
                            <ul>
                                <li><a href="/footer/sobre_nosotros">Sobre Nosotros</a></li>
                                <li><a href="/footer/como_funciona">Cómo Funciona</a></li>
                                <li><a href="#">Contacto</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h3>Legal</h3>
                            <ul>
                                <li><a href="#">Términos de Servicio</a></li>
                                <li><a href="#">Política de Privacidad</a></li>
                                <li><a href="#">Cookies</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} TASKLY. Todos los derechos reservados.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts comunes -->
    <script src="{{ asset('js/layout.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Scripts específicos de la página -->
    @yield('scripts')
</body>

</html>
