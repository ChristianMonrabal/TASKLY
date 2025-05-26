@extends('layouts.app')

@section('title', 'Política de Privacidad - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Política de Privacidad</h1>
        <p>En TASKLY, respetamos su privacidad y nos comprometemos a proteger sus datos personales. Esta política de privacidad le informará sobre cómo recopilamos, utilizamos y protegemos su información personal.</p>
    </div>
    
    <div class="footer-section">
        <h2>1. Información que recopilamos</h2>
        <p>Podemos recopilar diferentes tipos de información, incluyendo pero no limitado a:</p>
        <ul>
            <li>Información personal identificable (nombre, correo electrónico, número de teléfono, dirección, etc.)</li>
            <li>Información de perfil (foto, educación, experiencia laboral, habilidades)</li>
            <li>Información de pago y bancaria</li>
            <li>Información técnica (dirección IP, tipo de navegador, etc.)</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>2. Cómo utilizamos su información</h2>
        <p>Utilizamos la información recopilada para varios propósitos:</p>
        <ul>
            <li>Proporcionar, operar y mantener nuestros servicios</li>
            <li>Mejorar, personalizar y ampliar nuestros servicios</li>
            <li>Comprender y analizar cómo utiliza nuestros servicios</li>
            <li>Desarrollar nuevos productos, servicios y funcionalidades</li>
            <li>Procesar transacciones y enviar notificaciones relacionadas</li>
            <li>Prevenir actividades fraudulentas</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>3. Protección de datos</h2>
        <p>Implementamos una variedad de medidas de seguridad para mantener la seguridad de su información personal cuando introduce, envía o accede a su información personal:</p>
        <ul>
            <li>Utilizamos encriptación SSL para proteger datos sensibles transmitidos</li>
            <li>Solo los empleados que necesitan realizar un trabajo específico tienen acceso a información personal identificable</li>
            <li>Los servidores en los que almacenamos información personal se mantienen en un entorno seguro</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>4. Compartir información</h2>
        <p>No vendemos, intercambiamos o transferimos su información personal identificable a terceros. Esto no incluye terceros de confianza que nos ayudan a operar nuestro sitio web, realizar negocios o brindarle servicios, siempre que dichas partes acuerden mantener esta información confidencial.</p>
        <p>Podemos compartir información en las siguientes circunstancias:</p>
        <ul>
            <li>Con proveedores de servicios que utilizamos para procesar pagos (como Stripe)</li>
            <li>Cuando sea requerido por ley o para responder a procesos legales</li>
            <li>Para proteger nuestros derechos o propiedad</li>
            <li>Para proteger la seguridad personal de usuarios de TASKLY u otros</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>5. Cookies</h2>
        <p>Utilizamos cookies para mejorar su experiencia en nuestro sitio. Para más información sobre cómo utilizamos las cookies, consulte nuestra <a href="{{ url('/footer/cookies') }}" style="color: var(--primary);">Política de Cookies</a>.</p>
    </div>
    
    <div class="footer-section">
        <h2>6. Sus derechos de privacidad</h2>
        <p>Dependiendo de su ubicación, puede tener ciertos derechos con respecto a su información personal:</p>
        <ul>
            <li>Derecho a ser informado sobre cómo se utiliza su información personal</li>
            <li>Derecho a acceder y obtener una copia de su información personal</li>
            <li>Derecho a rectificar información personal inexacta</li>
            <li>Derecho a eliminar su información personal bajo ciertas circunstancias</li>
            <li>Derecho a restringir el procesamiento de su información personal</li>
        </ul>
        <p>Para ejercer cualquiera de estos derechos, contáctenos a través de nuestro <a href="{{ route('contacto.formulario') }}" style="color: var(--primary);">formulario de contacto</a>.</p>
    </div>
    
    <div class="footer-section">
        <h2>7. Conservación de datos</h2>
        <p>Conservaremos su información personal solo durante el tiempo necesario para los fines establecidos en esta política de privacidad, a menos que un período de retención más largo sea requerido o permitido por la ley.</p>
    </div>
    
    <div class="footer-section">
        <h2>8. Menores</h2>
        <p>Nuestro servicio no está dirigido a personas menores de 18 años. No recopilamos conscientemente información personal identificable de menores de 18 años. Si descubrimos que un menor de 18 años nos ha proporcionado información personal, eliminaremos inmediatamente esta información de nuestros servidores.</p>
    </div>
    
    <div class="footer-section">
        <h2>9. Cambios a esta política</h2>
        <p>Podemos actualizar nuestra política de privacidad de vez en cuando. Le notificaremos cualquier cambio publicando la nueva política de privacidad en esta página y actualizando la fecha de "última actualización".</p>
    </div>
    
    <div class="footer-section">
        <h2>10. Contacto</h2>
        <p>Si tiene alguna pregunta sobre esta política de privacidad, puede contactarnos a través de nuestro <a href="{{ route('contacto.formulario') }}" style="color: var(--primary);">formulario de contacto</a>.</p>
    </div>
    
    <p class="last-updated">Última actualización: Mayo 2025</p>
</div>
@endsection
