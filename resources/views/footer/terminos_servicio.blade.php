@extends('layouts.app')

@section('title', 'Términos de Servicio - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Términos de Servicio</h1>
        <p>Bienvenido a TASKLY. Antes de utilizar nuestros servicios, por favor lea cuidadosamente estos términos y condiciones que rigen el uso de nuestra plataforma.</p>
    </div>
    
    <div class="footer-section">
        <h2>1. Aceptación de Términos</h2>
        <p>Al acceder o utilizar TASKLY, usted acepta estar sujeto a estos Términos de Servicio. Si no está de acuerdo con alguna parte de los términos, no podrá acceder o utilizar nuestros servicios.</p>
    </div>
    
    <div class="footer-section">
        <h2>2. Registro de Cuenta</h2>
        <p>Para utilizar ciertos servicios de TASKLY, puede ser necesario registrarse y crear una cuenta. Usted es responsable de:</p>
        <ul>
            <li>Proporcionar información precisa, actual y completa durante el proceso de registro</li>
            <li>Mantener y actualizar rápidamente su información para mantenerla precisa, actual y completa</li>
            <li>Mantener la seguridad de su cuenta y contraseña</li>
            <li>Todas las actividades que ocurran bajo su cuenta</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>3. Servicios y Tareas</h2>
        <p>TASKLY es una plataforma que conecta a personas que necesitan ayuda con tareas específicas (clientes) con aquellos que ofrecen sus servicios para realizarlas (trabajadores).</p>
        <p>Como cliente o trabajador, usted acepta que:</p>
        <ul>
            <li>La descripción de la tarea debe ser clara y precisa</li>
            <li>El presupuesto debe ser razonable y acordado por ambas partes</li>
            <li>Las tareas publicadas deben ser legales según la legislación aplicable</li>
            <li>TASKLY actúa únicamente como intermediario y no es responsable del trabajo realizado</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>4. Pagos y Comisiones</h2>
        <p>Al utilizar TASKLY, usted acepta nuestro sistema de pagos y comisiones:</p>
        <ul>
            <li>TASKLY retiene una comisión del 10% sobre cada transacción completada</li>
            <li>Los pagos se procesan a través de nuestro socio de pagos Stripe</li>
            <li>Las disputas de pago deben notificarse dentro de los 7 días posteriores a la finalización de la tarea</li>
            <li>El trabajador recibirá el pago solo después de que el cliente haya confirmado la finalización satisfactoria de la tarea</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>5. Valoraciones y Reseñas</h2>
        <p>Las valoraciones y reseñas son fundamentales para mantener la calidad del servicio. Al utilizar TASKLY, usted acepta que:</p>
        <ul>
            <li>Las valoraciones deben ser honestas y basadas en la experiencia real</li>
            <li>Está prohibido manipular el sistema de valoraciones</li>
            <li>TASKLY se reserva el derecho de eliminar reseñas que violen nuestras políticas</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>6. Cancelaciones</h2>
        <p>Entendemos que las circunstancias pueden cambiar. Las políticas de cancelación son las siguientes:</p>
        <ul>
            <li>Las cancelaciones deben realizarse con al menos 24 horas de anticipación</li>
            <li>Las cancelaciones repetidas pueden resultar en penalizaciones para su cuenta</li>
            <li>En caso de fuerza mayor, contacte inmediatamente con soporte para resolver la situación</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>7. Propiedad Intelectual</h2>
        <p>Todo el contenido presente en TASKLY, incluyendo pero no limitado a textos, gráficos, logotipos, iconos, imágenes, clips de audio, descargas digitales y software, es propiedad de TASKLY o de sus proveedores de contenido y está protegido por leyes internacionales de propiedad intelectual.</p>
    </div>
    
    <div class="footer-section">
        <h2>8. Limitación de Responsabilidad</h2>
        <p>TASKLY no será responsable por daños indirectos, incidentales, especiales, consecuenciales o punitivos, o cualquier pérdida de beneficios o ingresos, ya sea incurrida directa o indirectamente, o cualquier pérdida de datos, uso, buena voluntad, u otras pérdidas intangibles, resultantes de:</p>
        <ul>
            <li>Su uso o incapacidad para usar el servicio</li>
            <li>Cualquier transacción o acuerdo entre trabajadores y clientes</li>
            <li>Acceso no autorizado, uso o alteración de sus transmisiones o contenido</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>9. Cambios en los Términos</h2>
        <p>Nos reservamos el derecho de modificar estos términos de servicio en cualquier momento. Los cambios serán efectivos inmediatamente después de la publicación de los términos modificados. Es su responsabilidad revisar periódicamente estos términos.</p>
    </div>
    
    <div class="footer-section">
        <h2>10. Ley Aplicable</h2>
        <p>Estos términos se regirán e interpretarán de acuerdo con las leyes de España, sin tener en cuenta sus disposiciones sobre conflictos de leyes.</p>
    </div>
    
    <p class="last-updated">Última actualización: Mayo 2025</p>
</div>
@endsection
