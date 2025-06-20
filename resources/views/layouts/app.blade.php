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
    <link rel="stylesheet" href="{{ asset('css/simple-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/taskly-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- AOS - Animate On Scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Leaflet - Mapas interactivos -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Leaflet Geocoder para búsqueda de direcciones -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    <script type="module" src="{{ asset('js/notifications.js') }}"></script>


    <!-- Estilos específicos de la página -->
    @yield('styles')
    
    <!-- Scripts en el head -->
    @yield('head_scripts')

</head>

<body>
    <!-- Contenedor para las burbujas animadas -->
    <div class="bubbles"></div>
    
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
                            <li>
                                <a href="{{ route('trabajos.index') }}"
                                    class="nav-link {{ request()->routeIs('trabajos.index') ? 'active' : '' }}">
                                    <i class="fas fa-briefcase"></i> Inicio
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('trabajos_publicados') }}"
                                    class="nav-link {{ request()->is('trabajos_publicados') ? 'active' : '' }}">
                                    <i class="fas fa-project-diagram"></i> Mis trabajos
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/postulaciones') }}"
                                    class="nav-link {{ request()->is('postulaciones') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt"></i> Mis postulaciones
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vista.chat') }}"
                                    class="nav-link {{ request()->is('mensajes*') ? 'active' : '' }}">
                                    <i class="fas fa-envelope"></i> Mensajes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('calendario.index') }}"
                                    class="nav-link {{ request()->is('calendario*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Calendario
                                </a>
                            </li>
                            @if(Auth::check() && Auth::user()->rol_id == 1)
                                <li>
                                    <a href="{{ url('/admin/usuarios') }}"
                                        class="nav-link {{ request()->is('admin/usuarios*') ? 'active' : '' }}">
                                        <i class="fas fa-user-shield"></i> Administración
                                    </a>
                                </li>
                            @endif  
                        </ul>
                    </nav>
        
                    <div class="user-actions">
                        <!-- Botón de cambio de tema (modo oscuro/claro) - visible para todos -->
                        <button id="themeToggle" class="theme-toggle" title="Cambiar modo oscuro/claro">
                            <i id="themeIcon" class="fas fa-moon"></i>
                        </button>
                        
                        @auth
                            <div class="trabajo-icon">
                                <a href="/crear_trabajo" class="trabajo-btn" title="Añadir nuevo trabajo">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </div>

                            @auth
                                @include('partials.notifications')
                            @endauth
        
                            <div class="user-dropdown">
                                <button class="dropdown-btn">
                                    <div class="user-avatar">
                                        <img src="{{ asset('img/profile_images/' . (Auth::user()->foto_perfil ?? 'perfil_default.png')) }}"
                                            class="current-photo">
                                    </div>
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="icon">▼</span>
                                </button>
                                <div class="dropdown-content">
                                    <a href="{{ route('perfil.usuario', ['nombre' => str(Auth::user()->nombre . ' ' . Auth::user()->apellidos)->slug('-')]) }}">
                                        <i class="fas fa-user"></i> Mi Perfil
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                        </a>
                                    </form>
        
                                    <!-- Opciones adicionales solo visibles en móvil -->
                                    <div class="mobile-only">
                                        <a href="{{ route('trabajos.index') }}"><i class="fas fa-briefcase"></i> Trabajos</a>
                                        <a href="{{ url('/trabajos_publicados') }}"><i class="fas fa-project-diagram"></i> Mis Trabajos</a>
                                        <a href="{{ route('vista.chat') }}"><i class="fas fa-envelope"></i> Mensajes</a>
                                        <a href="{{ route('calendario.index') }}"><i class="fas fa-calendar-alt"></i> Calendario</a>
                                        @if(Auth::user()->rol_id == 1)
                                            <a href="{{ url('/admin/usuarios') }}"><i class="fas fa-user-shield"></i> Administración</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="auth-buttons">
                                <a href="{{ route('signin.auth') }}" class="btn btn-outline">Iniciar Sesión</a>
                                <a href="{{ route('signup.auth') }}" class="btn btn-primary">Registrarse</a>
                            </div>
        
                            <div class="mobile-menu-no-auth">
                                <button class="mobile-dropdown-btn">
                                    <span class="icon">▼</span>
                                </button>
                                <div class="mobile-dropdown-content">
                                    <a href="{{ route('trabajos.index') }}"><i class="fas fa-briefcase"></i> Trabajos</a>
                                    <a href="{{ route('signin.auth') }}"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
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
        

        <main class="main-content">
            <div class="container">
                @yield('content')
            </div>
        </main>

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
                                <li><a href="{{ route('footer.freelancers') }}">Freelancers</a></li>
                                <li><a href="#">Categorías</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h3>Acerca de</h3>
                            <ul>
                                <li><a href="{{ route('footer.sobre_nosotros') }}">Sobre Nosotros</a></li>
                                <li><a href="{{ route('footer.como_funciona') }}">Cómo Funciona</a></li>
                                <li><a href="{{ route('footer.equipo') }}">Nuestro Equipo</a></li>
                                <li><a href="{{ route('contacto.formulario') }}">Contacto</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h3>Legal</h3>
                            <ul>
                                <li><a href="{{ route('footer.terminos') }}">Términos de Servicio</a></li>
                                <li><a href="{{ route('footer.privacidad') }}">Política de Privacidad</a></li>
                                <li><a href="{{ route('footer.cookies') }}">Cookies</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} TASKLY. Todos los derechos reservados.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/tasklydaw2/" class="social-link" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/bubbles-animation.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- AOS - Animate On Scroll Library -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    
    <!-- Chatbot Asistente TASKLY -->
    <script src="{{ asset('js/chatbot.js') }}"></script>
    
    @yield('scripts')
    @vite(['resources/js/app.js'])
</body>
</html>