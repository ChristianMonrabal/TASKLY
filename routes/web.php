<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ValoracionController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\PerfilUsuarioController;
use App\Models\User;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\Admin\LogroController;
use App\Http\Controllers\ValoracionesController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ReporteController;

// Ruta principal (index) - Accesible sin autenticación
Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');

// Rutas de autenticación - Accesibles sin estar logueado
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {
    return redirect('/signin');
})->name('login');
Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

// Rutas para ver trabajos sin autenticación
Route::get('/trabajos/todos', [TrabajoController::class, 'todos'])->name('trabajos.todos');
Route::get('/trabajos/nuevos', [TrabajoController::class, 'nuevos'])->name('trabajos.nuevos');
Route::get('/trabajos/categoria-json/{categoria_id}', [TrabajoController::class, 'categoriaJson'])->name('trabajos.categoria.json');
Route::get('/trabajos/buscar-json', [TrabajoController::class, 'buscarJson'])->name('trabajos.buscar.json');
Route::get('/trabajos/filtrar-simple', [TrabajoController::class, 'filtrarSimple'])->name('trabajos.filtrar.simple');
Route::get('/trabajos/categoria/{categoria_id}', [TrabajoController::class, 'filtrarPorCategoria'])->name('trabajos.categoria');
Route::get('/trabajos/buscar', [TrabajoController::class, 'buscar'])->name('trabajos.buscar');
Route::get('/trabajos/{id}', [TrabajoController::class, 'mostrarDetalle'])->name('trabajos.detalle');

// Todas las demás rutas requieren autenticación
Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rutas para el sistema de pagos con Stripe
    Route::get('/payment/{trabajo}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/intent', [App\Http\Controllers\PaymentController::class, 'createIntent'])->name('payment.create-intent');
    Route::post('/payment/update-status', [App\Http\Controllers\PaymentController::class, 'updatePaymentStatus'])->name('payment.update-status');
    Route::get('/payment/complete', function() {
        return view('payment-complete');
    })->name('payment.complete');
    Route::get('/payment/check-config', [App\Http\Controllers\PaymentController::class, 'checkStripeConfig'])->name('payment.check-config');
    
    // Ruta para valoraciones (especialmente después del pago)
    Route::get('/valoraciones/{trabajador_id}', [App\Http\Controllers\ValoracionesController::class, 'mostrarFormularioValoracion'])->name('valoraciones.trabajador');

    // Ruta para la página de mensajes
    Route::get('/mensajes', function () {
        return view('mensajes.index');
    })->name('mensajes');

    // Ruta para mostrar todos los usuarios
    Route::get('/usuarios', function () {
        $users = User::with('rol')->get();
        return view('usuarios.index', compact('users'));
    })->name('usuarios.index');

    // Rutas de trabajos protegidas
    Route::post('/trabajos/{id}/postular', [TrabajoController::class, 'postular'])->name('trabajos.postular');
    Route::get('/crear_trabajo', [JobController::class, 'crear'])->name('trabajos.crear');
    Route::post('/trabajos', [JobController::class, 'store'])->name('trabajos.store');
    Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])->name('trabajos.publicados');
    Route::get('/trabajo/{id}', [JobController::class, 'show'])->name('trabajos.show');
    Route::get('/trabajos/crear', [JobController::class, 'crear'])->name('trabajos.create');
    Route::get('/detalles_trabajo/{id}', [JobController::class, 'show'])->name('trabajos.detalles');
    Route::get('/candidatos_trabajo/{id}', [JobController::class, 'candidatos'])->name('trabajos.candidatos');
    Route::delete('/trabajos/{trabajo}/cancelar-postulacion', [TrabajoController::class, 'cancelarPostulacion'])->name('trabajos.cancelarPostulacion');

    // Rutas para gestión de postulaciones/candidatos
    Route::post('/postulaciones/{id}/aceptar', [PostulacionController::class, 'aceptar'])->name('postulaciones.aceptar');
    Route::post('/postulaciones/{id}/rechazar', [PostulacionController::class, 'rechazar'])->name('postulaciones.rechazar');
    Route::get('/trabajo/{id}/postulaciones', [PostulacionController::class, 'estadoPostulaciones'])->name('trabajo.postulaciones');
    Route::get('/postulaciones', [PostulacionController::class, 'misPostulaciones'])->name('postulaciones.index');

    // Chat
    Route::controller(ChatController::class)->group(function () {
        Route::get('/chat/{id?}', [ChatController::class, 'Vistachat'])->name('vista.chat');
        Route::post('/cargamensajes', 'cargamensajes');
        Route::post('/enviomensaje', 'enviomensaje');
    });

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        
        // listado/­vista de completados
        Route::get('trabajos/completados', [AdminJobController::class, 'completadosIndex'])
            ->name('trabajos.completados');

        // 2) Endpoint JSON para fetch + filtros
        Route::get('trabajos/completados/json', [AdminJobController::class, 'apiCompletados'])
            ->name('trabajos.completados.json');

        Route::resource('logros', LogroController::class);
        // resto de recursos
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('trabajos', AdminJobController::class);
        Route::resource('valoraciones', ValoracionController::class)
            ->parameters(['valoraciones' => 'valoracion']);
        Route::resource('categorias', CategoriaController::class);
        Route::patch('categorias/{categoria}/toggle-visible', [CategoriaController::class, 'toggleVisible'])
            ->name('categorias.toggleVisible');
});

    // Calendario
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/fecha/{trabajoId}', [CalendarioController::class, 'obtenerFecha']);
    Route::post('/calendario/insertar', [CalendarioController::class, 'insertar']);
    Route::post('/calendario/actualizar', [CalendarioController::class, 'actualizar']);

    // Valoraciones
    Route::get('/valoraciones', [ValoracionesController::class, 'index'])->name('valoraciones.valoraciones');
    Route::post('/valoraciones/guardar', [ValoracionesController::class, 'store'])->name('valoraciones.store');

    // Reportes
    Route::get('/reportes/{user_id}', [ReporteController::class, 'index'])->name('reportes.index');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');

    // Rutas API Admin
    // —— API para el CRUD Admin ——
    // Usuarios:
    Route::get('/usuarios',    [UsuarioController::class, 'apiIndex']);
    Route::get('api/usuarios',    [UsuarioController::class, 'apiIndex']);
    Route::get('api/usuarios/{usuario}', [UsuarioController::class, 'show']);
    // Valoraciones:
    Route::get('api/valoraciones',    [ValoracionController::class, 'apiIndex']);
    Route::get('api/valoraciones/{valoracion}', [ValoracionController::class, 'show']);
    // Trabajos:
    Route::get('api/trabajos',    [AdminJobController::class, 'apiIndex']);
    Route::get('api/trabajos/{trabajo}', [AdminJobController::class, 'show']);
    Route::get('api/estados/trabajos', [AdminJobController::class, 'apiEstadosTrabajo']);
    // Categorías:
    Route::get('api/categorias',    [CategoriaController::class, 'apiIndex']);
    Route::get('api/categorias/{categoria}', [CategoriaController::class, 'show']);

    // Logros:
    Route::get('api/logros',      [LogroController::class, 'apiIndex']);
    Route::get('api/logros/{logro}', [LogroController::class, 'show']);

    Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::patch('admin/categorias/{categoria}/toggle-visible', [CategoriaController::class, 'toggleVisible'])
    ->name('admin.categorias.toggleVisible');

Route::get('/footer/sobre_nosotros', function () {
    return view('/footer/sobre_nosotros');
});

// Route::get('/perfil/{id}', [PerfilUsuarioController::class, 'perfil'])->name('perfil.usuario');
Route::get('/perfil/{nombre}', [PerfilUsuarioController::class, 'perfilPorNombre'])->name('perfil.usuario');
Route::get('/perfil/{nombre}', [PerfilUsuarioController::class, 'mostrar'])->name('perfil.usuario');

// Editar trabajo
Route::get('/trabajos/editar/{id}', [JobController::class, 'editar'])->name('trabajos.editar');
Route::put('/trabajos/actualizar/{id}', [JobController::class, 'actualizar'])->name('trabajos.actualizar');
Route::get('/mis-trabajos', [JobController::class, 'trabajosPublicados'])->name('trabajos_publicados');

// Eliminar trabajo
Route::delete('/trabajos/{id}', [JobController::class, 'eliminar'])->name('trabajos.eliminar');
Route::put('/trabajos/actualizar/{id}', [JobController::class, 'actualizar'])->name('trabajos.actualizar');

Route::middleware('auth')->group(function () {
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    // Ruta para crear una notificación (puedes probar esto con Postman o frontend)
    Route::post('/notificaciones', [NotificacionController::class, 'store']);

    Route::post('/notificaciones/mark-all-read', [NotificacionController::class, 'markAllAsRead']);
    Route::post('/notificaciones/{id}/mark-read', [NotificacionController::class, 'markAsRead']);

    // Ruta para obtener notificaciones no leídas
    Route::get('/notificaciones/new', [NotificacionController::class, 'getNewNotifications']);
});

