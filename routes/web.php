<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ValoracionController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ReporteController as AdminReporteController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\Admin\DashboardController;
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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PasswordRestartController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\FooterController;


// Ruta principal (index) - Accesible sin autenticación
Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');

// Rutas de autenticación - Accesibles sin estar logueado
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {return redirect('/signin');})->name('login');
Route::get('auth/google',           [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback',  [GoogleController::class, 'handleGoogleCallback']);

// Rutas para ver trabajos sin autenticación
Route::get('/trabajos/todos', [TrabajoController::class, 'todos'])->name('trabajos.todos');
Route::get('/trabajos/nuevos', [TrabajoController::class, 'nuevos'])->name('trabajos.nuevos');
Route::get('/trabajos/categoria-json/{categoria_id}', [TrabajoController::class, 'categoriaJson'])->name('trabajos.categoria.json');
Route::get('/trabajos/buscar-json', [TrabajoController::class, 'buscarJson'])->name('trabajos.buscar.json');
Route::get('/trabajos/filtrar-simple', [TrabajoController::class, 'filtrarSimple'])->name('trabajos.filtrar.simple');
Route::get('/trabajos/categoria/{categoria_id}', [TrabajoController::class, 'filtrarPorCategoria'])->name('trabajos.categoria');
Route::get('/trabajos/buscar', [TrabajoController::class, 'buscar'])->name('trabajos.buscar');
Route::get('/trabajos/{id}', [TrabajoController::class, 'mostrarDetalle'])->name('trabajos.detalle');

// Rutas para el formulario de contacto - Accesibles sin estar logueado
Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarContacto'])->name('contacto.enviar');

// Rutas para páginas del footer
Route::get('/footer/terminos-servicio', [FooterController::class, 'terminosServicio'])->name('footer.terminos');
Route::get('/footer/politica-privacidad', [FooterController::class, 'politicaPrivacidad'])->name('footer.privacidad');
Route::get('/footer/cookies', [FooterController::class, 'cookies'])->name('footer.cookies');
Route::get('/footer/sobre_nosotros', [FooterController::class, 'sobreNosotros'])->name('footer.sobre_nosotros');
Route::get('/footer/como-funciona', [FooterController::class, 'comoFunciona'])->name('footer.como_funciona');
Route::get('/footer/freelancers', [FooterController::class, 'freelancers'])->name('footer.freelancers');

// Todas las demás rutas requieren autenticación
Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/datos-bancarios', [ProfileController::class, 'showDatosBancarios'])->name('profile.datos-bancarios');
    Route::put('/profile/datos-bancarios', [ProfileController::class, 'updateDatosBancarios'])->name('profile.datos-bancarios.update');
    
    // Rutas para el sistema de pagos con Stripe
    Route::get('/payment/{trabajo}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/intent', [PaymentController::class, 'createIntent'])->name('payment.create-intent');
    Route::post('/payment/update-status', [PaymentController::class, 'updatePaymentStatus'])->name('payment.update-status');
    Route::get('/payment/complete', function() {return view('payment-complete');})->name('payment.complete');
    Route::get('/payment/check-config', [PaymentController::class, 'checkStripeConfig'])->name('payment.check-config');
    Route::get('/payment/factura/{trabajo}', [App\Http\Controllers\PaymentController::class, 'generarFactura'])->name('payment.factura');

    // Ruta para valoraciones (especialmente después del pago)
    Route::get('/valoraciones/{trabajador_id}', [ValoracionesController::class, 'mostrarFormularioValoracion'])->name('valoraciones.trabajador');

    // Ruta para la página de mensajes
    Route::get('/mensajes', function () {return view('mensajes.index');})->name('mensajes');

    // Ruta para mostrar todos los usuarios
    Route::get('/usuarios', function () {$users = User::with('rol')->get();return view('usuarios.index', compact('users'));})->name('usuarios.index');

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
        Route::get('/mensajes-no-leidos', 'contarMensajesNoLeidos');
    });

    // Logout
    Route::post('/logout', function () {Auth::logout();request()->session()->invalidate();request()->session()->regenerateToken();return redirect('/');})->name('logout');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

        Route::get('trabajos/completados', [AdminJobController::class, 'completadosIndex'])->name('trabajos.completados');
        Route::get('trabajos/completados/json', [AdminJobController::class, 'apiCompletados'])->name('trabajos.completados.json');
        Route::get('usuarios/json', [UsuarioController::class,'apiIndex'])->name('admin.usuarios.json');
        Route::get('trabajos/json', [AdminJobController::class,'apiIndex'])->name('admin.trabajos.json');
        Route::get('trabajos/api-estados', [AdminJobController::class,'apiEstadosTrabajo'])->name('admin.trabajos.estados');
        Route::get('valoraciones/json', [ValoracionController::class, 'apiIndex'])->name('admin.valoraciones.json');
        Route::get('categorias/json', [CategoriaController::class, 'apiIndex'])->name('admin.categorias.json');
        Route::get('logros/json', [LogroController::class, 'apiIndex'])->name('admin.logros.json');
        Route::resource('logros', LogroController::class);
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('trabajos', AdminJobController::class);
        Route::resource('valoraciones', ValoracionController::class)->parameters(['valoraciones' => 'valoracion']);
        Route::resource('categorias', CategoriaController::class);
        Route::patch('categorias/{categoria}/toggle-visible', [CategoriaController::class, 'toggleVisible'])->name('categorias.toggleVisible');
        Route::get('reportes',        [AdminReporteController::class, 'index'])->name('reportes.index');
        Route::get('reportes/json',   [AdminReporteController::class, 'json'])->name('reportes.json');
        Route::get('reportes/{id}',   [AdminReporteController::class, 'show'])->name('reportes.show');
        Route::put('reportes/{id}',   [AdminReporteController::class, 'update'])->name('reportes.update');
        Route::delete('reportes/{id}',[AdminReporteController::class, 'destroy'])->name('reportes.destroy');
        Route::post('usuarios/{id}/toggle-active',[AdminUsuarioController::class, 'toggleActive'])->name('usuarios.toggleActive');
        Route::post('usuarios/{id}/notify',[AdminUsuarioController::class, 'notify'])->name('usuarios.notify');

});
Route::prefix('admin/dashboard')->name('admin.dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('kpis', [DashboardController::class, 'kpis'])->name('kpis');
    Route::get('jobsByStatus', [DashboardController::class, 'jobsByStatus'])->name('jobsByStatus');
    Route::get('userGrowth', [DashboardController::class, 'userGrowth'])->name('userGrowth');
    Route::get('topWorkers', [DashboardController::class, 'topWorkers'])->name('topWorkers');
    Route::get('reportsBySeverity', [DashboardController::class, 'reportsBySeverity'])->name('reportsBySeverity');
});

Route::get('/trabajo/mapa/{id}', [JobController::class, 'mapa'])->name('trabajo.mapa');

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
    Route::get('/listareportes', [ReporteController::class, 'listareportes'])->name('listareportes.listareportes');

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
});

Route::patch('admin/categorias/{categoria}/toggle-visible', [CategoriaController::class, 'toggleVisible'])->name('admin.categorias.toggleVisible');

// Ruta movida a las rutas del footer con nombre 'footer.sobre_nosotros'

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

Route::get('/restart-password', [PasswordRestartController::class, 'showForm'])->name('password.restart')->middleware('signed');
Route::post('/restart-password', [PasswordRestartController::class, 'handleForm'])->name('password.restart.submit')->middleware('signed');


Route::get('/forgot-password', [PasswordRestartController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordRestartController::class, 'sendResetLink'])->name('password.email');
Route::get('/forgot-password', [PasswordRestartController::class, 'showForgotForm'])->name('forgot-password.auth');

// Rutas páginas estáticas del footer
Route::get('/equipo', function() {
    return view('footer.equipo');
})->name('footer.equipo');
