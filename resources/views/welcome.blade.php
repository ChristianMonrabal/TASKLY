<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    @if (Auth::check())
        <h1>¡Bienvenido!</h1>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <p>Has iniciado sesión con el correo: <strong>{{ Auth::user()->email }}</strong></p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>
    @else
        <h1>Taskly</h1>
        <p>No has iniciado sesión.</p>
    @endif
</body>
</html>
