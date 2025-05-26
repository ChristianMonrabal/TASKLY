@extends('layouts.app')

@section('title', 'Freelancers - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Freelancers en TASKLY</h1>
        <p>Todo lo que necesitas saber para ofrecer tus servicios profesionales y maximizar tus ingresos en nuestra plataforma.</p>
    </div>
    
    <div class="footer-section">
        <h2>Por qué unirte como freelancer</h2>
        <p>TASKLY te ofrece una plataforma completa para monetizar tus habilidades y ofrecer tus servicios a clientes que realmente valoran tu trabajo.</p>
        
        <div class="feature-grid">
            <div class="feature-item">
                <h3>Pagos seguros</h3>
                <p>Recibe tus pagos directamente en tu cuenta bancaria a través de nuestro sistema seguro con Stripe.</p>
            </div>
            <div class="feature-item">
                <h3>Construye reputación</h3>
                <p>Acumula valoraciones positivas para destacar y conseguir más clientes.</p>
            </div>
            <div class="feature-item">
                <h3>Flexibilidad total</h3>
                <p>Trabaja cuando quieras y donde quieras, eligiendo solo los proyectos que te interesen.</p>
            </div>
            <div class="feature-item">
                <h3>Comunicación directa</h3>
                <p>Chatea con tus clientes a través de nuestro sistema de mensajería integrado.</p>
            </div>
        </div>
    </div>
    
    <div class="footer-section">
        <h2>Comisiones justas</h2>
        <p>En TASKLY creemos en ofrecer condiciones justas para todos los freelancers. Por eso, nuestra comisión es una de las más competitivas del mercado.</p>
        
        <div class="fee-highlight">
            <p><strong>Solo 10% de comisión:</strong> Cuando un cliente paga por tu trabajo, TASKLY retiene únicamente el 10% como comisión por el uso de la plataforma. El 90% restante se transfiere directamente a tu cuenta.</p>
        </div>
        
        <p>Esta transparencia en las comisiones te permite calcular tus ingresos de forma precisa y establecer precios justos tanto para ti como para tus clientes.</p>
    </div>
    
    <div class="footer-section">
        <h2>Cómo empezar</h2>
        <p>Comenzar a trabajar como freelancer en TASKLY es muy sencillo. Sigue estos pasos:</p>
        
        <div class="steps-container">
            <div class="step-item">
                <h3>Crea tu perfil profesional</h3>
                <p>Regístrate en TASKLY y completa tu perfil con tus habilidades, experiencia, educación y una foto profesional. Un perfil completo genera más confianza.</p>
            </div>
            <div class="step-item">
                <h3>Configura tus datos bancarios</h3>
                <p>Añade tu información bancaria para recibir pagos. TASKLY utiliza Stripe para procesar transferencias de forma segura directamente a tu cuenta.</p>
            </div>
            <div class="step-item">
                <h3>Busca trabajos y postúlate</h3>
                <p>Explora las oportunidades disponibles utilizando nuestros filtros avanzados por categoría, precio o ubicación. Envía postulaciones personalizadas explicando por qué eres el candidato ideal.</p>
            </div>
            <div class="step-item">
                <h3>Realiza el trabajo con excelencia</h3>
                <p>Una vez aceptado, comunícate con el cliente y asegúrate de entender completamente sus requisitos. Entrega un trabajo de calidad en el tiempo acordado.</p>
            </div>
            <div class="step-item">
                <h3>Recibe tu pago y valoración</h3>
                <p>Cuando el cliente confirma que el trabajo está completo, recibirás automáticamente el pago. Pide al cliente que te deje una valoración positiva para mejorar tu perfil.</p>
            </div>
        </div>
    </div>
    
    <div class="footer-section">
        <h2>Categorías más demandadas</h2>
        <p>TASKLY ofrece trabajos en múltiples categorías. Algunas de las más solicitadas incluyen:</p>
        
        <ul>
            <li><strong>Diseño gráfico:</strong> Logos, banners, tarjetas de visita, diseño web...</li>
            <li><strong>Desarrollo web:</strong> Creación de sitios web, mantenimiento, corrección de errores...</li>
            <li><strong>Marketing digital:</strong> Gestión de redes sociales, SEO, campañas publicitarias...</li>
            <li><strong>Servicios de redacción:</strong> Artículos, corrección, traducción, copywriting...</li>
            <li><strong>Asistencia virtual:</strong> Gestión de agenda, tareas administrativas, atención al cliente...</li>
            <li><strong>Fotografía y vídeo:</strong> Sesiones fotográficas, edición, montaje de vídeos...</li>
            <li><strong>Servicios para el hogar:</strong> Montaje de muebles, reparaciones, jardinería...</li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h2>Consejos para destacar</h2>
        <p>La competencia puede ser alta en algunas categorías. Aquí tienes algunos consejos para destacar entre los demás freelancers:</p>
        
        <ul>
            <li><strong>Perfil completo:</strong> Asegúrate de completar todos los campos de tu perfil, incluyendo una foto profesional y ejemplos de trabajos anteriores.</li>
            <li><strong>Responde rápido:</strong> La velocidad de respuesta es clave para ganar clientes. Intenta responder a las consultas lo antes posible.</li>
            <li><strong>Personaliza tus propuestas:</strong> Evita enviar mensajes genéricos. Personaliza cada propuesta demostrando que has leído y entendido los requisitos del cliente.</li>
            <li><strong>Precios competitivos:</strong> Al principio, puede ser útil ofrecer precios más competitivos mientras construyes tu reputación.</li>
            <li><strong>Solicita valoraciones:</strong> Después de completar un trabajo con éxito, pide amablemente al cliente que te deje una valoración positiva.</li>
            <li><strong>Especialízate:</strong> Es mejor ser excelente en un área específica que mediocre en muchas.</li>
        </ul>
        
        <div class="text-center" style="margin-top: 2rem;">
            <p><strong>¿Listo para empezar a ganar dinero con tus habilidades?</strong></p>
            <p>Registrarte en TASKLY es el primer paso para ofrecer tus servicios profesionales y empezar a recibir ingresos.</p>
        </div>
    </div>
    
    <p class="last-updated">Última actualización: Mayo 2025</p>
</div>
@endsection
