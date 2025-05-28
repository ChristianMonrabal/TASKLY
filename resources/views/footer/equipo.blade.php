@extends('layouts.app')

@section('title', 'Nuestro Equipo | TASKLY')

@section('styles')
<style>
    .team-section {
        padding: 60px 0;
    }
    
    .team-title {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .team-title h1 {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .team-title p {
        max-width: 800px;
        margin: 0 auto;
        color: var(--text-muted);
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Cambiado a exactamente 2 columnas */
        gap: 30px;
        margin-top: 40px;
    }
    
    /* Asegurar que en móvil se vea una columna */
    @media (max-width: 768px) {
        .team-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .team-member {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }
    
    .team-member:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .member-photo {
        height: 250px;
        overflow: hidden;
        position: relative;
    }
    
    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .team-member:hover .member-photo img {
        transform: scale(1.05);
    }
    
    .member-info {
        padding: 25px 20px;
    }
    
    .member-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 5px;
    }
    
    .member-role {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 15px;
        display: block;
    }
    
    .member-bio {
        color: var(--text-muted);
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .social-links {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f5f5f5;
        color: var(--text);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    .social-links a:hover {
        background-color: var(--primary);
        color: white;
    }
    
    .mission-section {
        background-color: #f9f9f9;
        padding: 60px 0;
        margin-top: 50px;
    }
    
    .mission-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .mission-content h2 {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .mission-content p {
        color: var(--text);
        line-height: 1.8;
        margin-bottom: 20px;
    }
    
    .values {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
    }
    
    .value-item {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        width: 200px;
        text-align: center;
    }
    
    .value-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    .value-title {
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text);
    }
    
    /* Modo oscuro */
    body.dark-mode .team-member {
        background-color: var(--dark-card);
    }
    
    body.dark-mode .mission-section {
        background-color: var(--dark-bg);
    }
    
    body.dark-mode .value-item {
        background-color: var(--dark-card);
    }
    
    body.dark-mode .social-links a {
        background-color: #333;
        color: #eee;
    }
    
    @media (max-width: 768px) {
        .team-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <section class="team-section">
        <div class="team-title">
            <h1>Nuestro Equipo</h1>
            <p>TASKLY fue creado por un equipo apasionado de profesionales dedicados a revolucionar el mundo del trabajo freelance en España.</p>
        </div>
        
        <div class="team-grid">
            <div class="team-member">
                <div class="member-photo">
                    <img src="{{ asset('img/team/christian.jpg') }}" alt="Christian Monrabal">
                </div>
                <div class="member-info">
                    <h3 class="member-name">Christian Monrabal</h3>
                    <span class="member-role">Fundador & CEO</span>
                    <p class="member-bio">Visionario detrás de TASKLY. Con más de 10 años en el sector tecnológico, Christian ha liderado el desarrollo de la plataforma desde su concepción.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-photo">
                    <img src="{{ asset('img/profile_images/perfil_default.png') }}" alt="Daniel" >
                </div>
                <div class="member-info">
                    <h3 class="member-name">Daniel</h3>
                    <span class="member-role">Diseñador UX/UI</span>
                    <p class="member-bio">Apasionado por crear experiencias de usuario excepcionales. Daniel ha diseñado la interfaz intuitiva y moderna que define TASKLY.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-photo">
                    <img src="{{ asset('img/team/user-default.png') }}" alt="Juan">
                </div>
                <div class="member-info">
                    <h3 class="member-name">Juan</h3>
                    <span class="member-role">Director de Marketing</span>
                    <p class="member-bio">Estratega creativo con amplia experiencia en marketing digital. Juan lidera las estrategias de crecimiento y posicionamiento de TASKLY en el mercado español.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-photo">
                    <img src="{{ asset('img/team/alex.jpg') }}" alt="Alex">
                </div>
                <div class="member-info">
                    <h3 class="member-name">Alex</h3>
                    <span class="member-role">CTO & Desarrollador Principal</span>
                    <p class="member-bio">Ingeniero de software especializado en arquitecturas escalables. Alex ha sido fundamental en la implementación técnica de TASKLY.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="mission-section">
        <div class="mission-content">
            <h2>Nuestra Misión</h2>
            <p>En TASKLY, creemos en conectar talento con oportunidades. Nuestra misión es crear un ecosistema donde profesionales freelance puedan encontrar proyectos relevantes y donde empresas y particulares puedan encontrar el talento adecuado para sus necesidades.</p>
            <p>Nos esforzamos por ofrecer una plataforma segura, transparente y eficiente que beneficie a todas las partes involucradas.</p>
            
            <div class="values">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="value-title">Seguridad</h4>
                    <p>Protegemos a usuarios y pagos</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="value-title">Confianza</h4>
                    <p>Transparencia en cada interacción</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4 class="value-title">Innovación</h4>
                    <p>Mejoramos constantemente</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="value-title">Comunidad</h4>
                    <p>Construimos relaciones</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
