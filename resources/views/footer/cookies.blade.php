@extends('layouts.app')

@section('title', 'Política de Cookies - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Política de Cookies</h1>
        <p>Esta política de cookies explica qué son las cookies, cómo las utilizamos en TASKLY y cómo puede gestionar sus preferencias de cookies.</p>
    </div>
    
    <div class="footer-section">
        <h2>1. ¿Qué son las cookies?</h2>
        <p>Las cookies son pequeños archivos de texto que se almacenan en su dispositivo (ordenador, tableta o móvil) cuando visita un sitio web. Las cookies se utilizan ampliamente para hacer que los sitios web funcionen de manera más eficiente, así como para proporcionar información a los propietarios del sitio.</p>
    </div>
    
    <div class="cookies-section">
        <h2>2. Cómo utilizamos las cookies</h2>
        <p>En TASKLY utilizamos cookies por varias razones, incluyendo:</p>
        <ul>
            <li>Cookies esenciales: Necesarias para el funcionamiento del sitio web</li>
            <li>Cookies de funcionalidad: Permiten recordar sus preferencias</li>
            <li>Cookies de rendimiento: Nos ayudan a entender cómo interactúa con nuestro sitio</li>
            <li>Cookies de publicidad/seguimiento: Proporcionan anuncios relevantes y rastrean su navegación en diferentes sitios</li>
        </ul>
    </div>
    
    <div class="cookies-section">
        <h2>3. Tipos de cookies que utilizamos</h2>
        <p>A continuación se detallan los tipos de cookies que utilizamos en TASKLY:</p>
        
        <table class="footer-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Nombre</th>
                    <th>Propósito</th>
                    <th>Duración</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Esenciales</td>
                    <td>XSRF-TOKEN</td>
                    <td>Protección contra ataques CSRF</td>
                    <td>Sesión</td>
                </tr>
                <tr>
                    <td>Esenciales</td>
                    <td>taskly_session</td>
                    <td>Mantener su sesión iniciada</td>
                    <td>2 horas</td>
                </tr>
                <tr>
                    <td>Esenciales</td>
                    <td>remember_web</td>
                    <td>Recordar el inicio de sesión</td>
                    <td>5 días</td>
                </tr>
                <tr>
                    <td>Funcionalidad</td>
                    <td>theme_preference</td>
                    <td>Almacenar su preferencia de tema (claro/oscuro)</td>
                    <td>1 año</td>
                </tr>
                <tr>
                    <td>Funcionalidad</td>
                    <td>language</td>
                    <td>Recordar su idioma preferido</td>
                    <td>1 año</td>
                </tr>
                <tr>
                    <td>Rendimiento</td>
                    <td>_ga</td>
                    <td>Google Analytics - Distinguir usuarios</td>
                    <td>2 años</td>
                </tr>
                <tr>
                    <td>Rendimiento</td>
                    <td>_gid</td>
                    <td>Google Analytics - Distinguir usuarios</td>
                    <td>24 horas</td>
                </tr>
                <tr>
                    <td>Rendimiento</td>
                    <td>_gat</td>
                    <td>Google Analytics - Limitar la tasa de solicitudes</td>
                    <td>1 minuto</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="cookies-section">
        <h2>4. Control de cookies</h2>
        <p>La mayoría de los navegadores web permiten controlar la mayoría de las cookies a través de sus configuraciones. Puede configurar su navegador para que rechace todas las cookies, solo acepte las cookies de origen, o le notifique cuando se envía una cookie. Sin embargo, si rechaza las cookies, es posible que algunas partes de nuestro sitio no funcionen correctamente.</p>
        <p>Puede encontrar información sobre cómo administrar las cookies en los navegadores más populares en los siguientes enlaces:</p>
        <ul>
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" style="color: var(--primary);">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" style="color: var(--primary);">Mozilla Firefox</a></li>
            <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" style="color: var(--primary);">Safari</a></li>
            <li><a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" style="color: var(--primary);">Microsoft Edge</a></li>
        </ul>
    </div>
    
    <div class="cookies-section">
        <h2>5. Cookies de terceros</h2>
        <p>Algunos de nuestros socios pueden establecer cookies en su dispositivo cuando visita nuestro sitio web. Estos socios incluyen:</p>
        <ul>
            <li><strong>Google Analytics:</strong> Utilizamos Google Analytics para analizar cómo los usuarios utilizan nuestro sitio web. Para más información sobre las cookies de Google Analytics, visite la <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage" target="_blank" style="color: var(--primary);">página de privacidad de Google Analytics</a>.</li>
            <li><strong>Stripe:</strong> Nuestro procesador de pagos, Stripe, utiliza cookies para ayudar a prevenir el fraude y mejorar la experiencia de pago. Para más información, visite la <a href="https://stripe.com/cookies-policy/legal" target="_blank" style="color: var(--primary);">política de cookies de Stripe</a>.</li>
        </ul>
    </div>
    
    <div class="cookies-section">
        <h2>6. Cambios en nuestra política de cookies</h2>
        <p>Podemos actualizar nuestra política de cookies de vez en cuando. Cualquier cambio se publicará en esta página con una fecha de actualización revisada.</p>
    </div>
    
    <div class="cookies-section">
        <h2>7. Contacto</h2>
        <p>Si tiene alguna pregunta sobre nuestra política de cookies, por favor contáctenos a través de nuestro <a href="{{ route('contacto.formulario') }}" style="color: var(--primary);">formulario de contacto</a>.</p>
    </div>
    
    <p class="last-updated">Última actualización: 26 de mayo de 2025</p>
</div>
@endsection
