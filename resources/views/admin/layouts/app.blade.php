<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
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
                            <li><a href="{{ route('admin.usuarios.index') }}" class="nav-link"><i class="fas fa-users"></i> Usuarios</a></li>
                            <li><a href="{{ route('admin.trabajos.index') }}" class="nav-link"><i class="fas fa-briefcase"></i> Trabajos</a></li>
                            <li><a href="{{ route('admin.valoraciones.index') }}" class="nav-link"><i class="fas fa-star"></i> Valoraciones</a></li>
                            <li><a href="{{ route('admin.categorias.index') }}" class="nav-link"><i class="fas fa-layer-group"></i> Categorias</a></li>
                        </ul>
                    </nav>
                    <div class="user-actions">
                        @auth
                        <div class="user-dropdown">
                            <button class="dropdown-btn">
                                <div class="user-avatar">
                                    <img src="{{ asset('img/profile_images/' . (Auth::user()->foto_perfil ?? 'default.jpg')) }}" class="current-photo">
                                </div>
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="icon">▼</span>
                            </button>
                            <div class="dropdown-content">
                                <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Mi Perfil</a>
                                <a href="{{ route('admin.usuarios.index') }}"><i class="fas fa-users"></i> Usuarios</a>
                                <a href="{{ route('admin.trabajos.index') }}"><i class="fas fa-briefcase"></i> Trabajos</a>
                                <a href="{{ route('admin.valoraciones.index') }}"><i class="fas fa-star"></i> Valoraciones</a>
                                <a href="{{ route('admin.categorias.index') }}"><i class="fas fa-layer-group"></i> Categorias</a>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </a>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Eliminamos el menú móvil independiente -->
        
        <!-- Contenido principal -->
        <main class="main-content">
            <div>
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
                                <li><a href="#">Sobre Nosotros</a></li>
                                <li><a href="#">Cómo Funciona</a></li>
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
    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>
</html>
