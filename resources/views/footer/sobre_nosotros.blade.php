@extends('layouts.app')

@section('title', 'Sobre Nosotros - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/footer/footer-pages.css') }}">
@endsection

@section('content')
<div class="footer-container">
    <div class="footer-header">
        <h1>Sobre Nosotros</h1>
        <p>Conoce más sobre TASKLY, nuestra misión, visión y los valores que nos impulsan a crear la mejor plataforma de servicios.</p>
    </div>
    
    <div class="footer-section">
        <h2>Nuestra Misión</h2>
        <p>En TASKLY, nos dedicamos a facilitar trabajos rápidos y diarios, conectando a personas con tareas que necesitan ser realizadas de forma eficiente. Confiamos en la experiencia y reputación de nuestros usuarios para garantizar que cada trabajo se lleve a cabo de manera profesional y confiable.</p>
    </div>
    
    <div class="cookies-section">
        <h2>Nuestra Visión</h2>
        <p>Nuestro objetivo es ofrecer una solución rápida y efectiva, donde tanto empleados como trabajadores puedan encontrar lo que necesitan: tareas a realizar y la remuneración justa a cambio. Buscamos revolucionar la forma en que las personas encuentran ayuda para sus tareas cotidianas y cómo los profesionales pueden obtener ingresos de manera flexible y segura.</p>
    </div>
    
    <div class="cookies-section">
        <h2>Nuestros Valores</h2>
        <div class="values-grid">
            <div class="value-item">
                <h4>Confianza</h4>
                <p>Construimos un entorno seguro donde todos los usuarios pueden confiar en la calidad de los servicios y en los pagos justos.</p>
            </div>
            <div class="value-item">
                <h4>Transparencia</h4>
                <p>Mantenemos un sistema de valoraciones y cobros transparente para todos los usuarios.</p>
            </div>
            <div class="value-item">
                <h4>Calidad</h4>
                <p>Promovemos el trabajo bien hecho y recompensamos a quienes mantienen altos estándares de calidad.</p>
            </div>
            <div class="value-item">
                <h4>Comunidad</h4>
                <p>Creamos una comunidad colaborativa donde todos los miembros pueden crecer y prosperar.</p>
            </div>
        </div>
    </div>
    
    <div class="cookies-section">
        <h2>Nuestro Compromiso</h2>
        <p>A través de nuestra plataforma, buscamos generar un ambiente de confianza y colaboración, donde las personas puedan encontrar apoyo en su día a día, mientras reciben una compensación adecuada por su esfuerzo. Nos comprometemos a mantener una comisión justa del 10%, permitiendo que el 90% del valor de cada trabajo llegue directamente al profesional que lo realiza.</p>
        <p style="text-align: center; font-weight: bold; margin-top: 1.5rem;">Gracias por confiar en nosotros. ¡Juntos estamos construyendo una comunidad de trabajo ágil y eficiente!</p>
    </div>
    
    <p class="last-updated">Última actualización: Mayo 2025</p>
</div>
@endsection
