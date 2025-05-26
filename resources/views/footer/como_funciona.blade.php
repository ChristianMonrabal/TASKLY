@extends('layouts.app')

@section('title', 'Cómo Funciona TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Cómo Funciona TASKLY</h1>
        <p>Aprende a sacar el máximo partido a nuestra plataforma, tanto si buscas profesionales para realizar trabajos como si ofreces tus servicios.</p>
    </div>
    
    <div class="footer-section">
        <h2>Para quienes buscan servicios</h2>
        <ol>
            <li><strong>Publica tu trabajo</strong> - Describe la tarea que necesitas, establece un precio y añade imágenes si es necesario.</li>
            <li><strong>Recibe postulaciones</strong> - Profesionales interesados se postularán a tu trabajo.</li>
            <li><strong>Elige al candidato</strong> - Revisa perfiles, valoraciones y elige al mejor profesional para tu tarea.</li>
            <li><strong>Realiza el pago</strong> - Una vez completado el trabajo, realiza el pago de forma segura a través de nuestra plataforma.</li>
            <li><strong>Valora el servicio</strong> - Califica al profesional y ayuda a mantener la calidad de nuestra comunidad.</li>
        </ol>
    </div>
    
    <div class="footer-section">
        <h2>Para profesionales</h2>
        <ol>
            <li><strong>Crea tu perfil</strong> - Destaca tus habilidades, experiencia y establece tus datos bancarios para recibir pagos.</li>
            <li><strong>Encuentra trabajos</strong> - Explora trabajos disponibles utilizando nuestros filtros por categoría, precio o ubicación.</li>
            <li><strong>Postúlate</strong> - Envía tu postulación a los trabajos que te interesen.</li>
            <li><strong>Realiza el trabajo</strong> - Completa la tarea con profesionalidad y calidad.</li>
            <li><strong>Recibe tu pago</strong> - Cobra automáticamente (90% del precio acordado) cuando el cliente confirma la finalización del trabajo.</li>
        </ol>
    </div>
    
    <div class="footer-section">
        <h2>Sistema de pagos</h2>
        <p>TASKLY utiliza Stripe como plataforma de pagos segura. Cuando un trabajo se completa:</p>
        <ul>
            <li>El cliente realiza el pago del monto acordado.</li>
            <li>TASKLY retiene una comisión del 10% por el uso de la plataforma.</li>
            <li>El 90% restante se transfiere directamente a la cuenta del profesional.</li>
            <li>Todo el proceso es automático y seguro.</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>Comunicación</h2>
        <p>TASKLY cuenta con un sistema de mensajería interno para facilitar la comunicación entre clientes y profesionales:</p>
        <ul>
            <li>Envía y recibe mensajes directamente desde la plataforma.</li>
            <li>Organiza tus conversaciones en una interfaz intuitiva.</li>
            <li>Coordina detalles y resuelve dudas sin salir de TASKLY.</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>Valoraciones</h2>
        <p>Las valoraciones son fundamentales para construir confianza en nuestra comunidad:</p>
        <ul>
            <li>Después de cada trabajo, tanto clientes como profesionales pueden valorarse mutuamente.</li>
            <li>Las puntuaciones y comentarios son públicos en los perfiles.</li>
            <li>Un buen historial de valoraciones aumenta tus posibilidades de ser elegido o atraer mejores profesionales.</li>
        </ul>
    </div>
    
    <p class="last-updated">Última actualización: Mayo 2025</p>
</div>
@endsection
