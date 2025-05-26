@extends('layouts.app')

@section('title', 'Contacto - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
@endsection

@section('content')
<div class="contacto-container">
    <div class="contacto-header">
        <h1>Contacta con TASKLY</h1>
        <p>¿Tienes alguna duda, sugerencia o problema con la plataforma? Estamos aquí para ayudarte. Completa el formulario y nos pondremos en contacto contigo lo antes posible.</p>
    </div>
    
    <div class="contacto-content">
        <div class="contacto-info">
            <h3>Información de contacto</h3>
            
            <div class="contacto-info-item">
                <div class="contacto-info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="contacto-info-text">
                    <h4>Ubicación</h4>
                    <p>Calle Principal 123, Barcelona, España</p>
                </div>
            </div>
            
            <div class="contacto-info-item">
                <div class="contacto-info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contacto-info-text">
                    <h4>Email</h4>
                    <p>soporte@taskly.com</p>
                </div>
            </div>
            
            <div class="contacto-info-item">
                <div class="contacto-info-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="contacto-info-text">
                    <h4>Teléfono</h4>
                    <p>+34 612 345 678</p>
                </div>
            </div>
            
            <div class="contacto-info-item">
                <div class="contacto-info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="contacto-info-text">
                    <h4>Horario de atención</h4>
                    <p>Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
            
            <div class="contacto-social">
                <h4>Síguenos en redes sociales</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="contacto-form">
            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            <div class="contacto-cards">
                <div class="form-row">
                    <div class="contacto-card">
                        <div class="contacto-card-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>Soporte técnico</h4>
                        <p>Ayuda con problemas técnicos</p>
                    </div>
                    
                    <div class="contacto-card">
                        <div class="contacto-card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Sugerencias</h4>
                        <p>Comparte tus ideas para mejorar</p>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('contacto.enviar') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') ?? Auth::user()->nombre ?? '' }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') ?? Auth::user()->email ?? '' }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" value="{{ old('asunto') }}" required>
                    @error('asunto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="tipo">Tipo de consulta</label>
                    <select id="tipo" name="tipo" required>
                        <option value="soporte" {{ old('tipo') == 'soporte' ? 'selected' : '' }}>Soporte técnico</option>
                        <option value="informacion" {{ old('tipo') == 'informacion' ? 'selected' : '' }}>Información general</option>
                        <option value="reclamacion" {{ old('tipo') == 'reclamacion' ? 'selected' : '' }}>Reclamación</option>
                        <option value="sugerencia" {{ old('tipo') == 'sugerencia' ? 'selected' : '' }}>Sugerencia</option>
                        <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
                    @error('mensaje')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Enviar mensaje
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
