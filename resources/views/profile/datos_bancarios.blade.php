@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/datos_bancarios.css') }}">
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('profile') }}" class="btn btn-outline-secondary" data-aos="fade-right" data-aos-duration="800">
            <i class="fas fa-arrow-left me-1"></i> Volver al Perfil
        </a>
        <h2 class="m-0 text-center" data-aos="fade-up" data-aos-duration="1000">Datos Bancarios <i class="fas fa-credit-card ms-2" style="color: #EC6A6A;"></i></h2>
        <div style="width: 120px;"></div> <!-- Espacio para equilibrar el diseño -->
    </div>
    
    <form action="{{ route('profile.datos-bancarios.update') }}" method="POST" id="form-datos-bancarios">
        
        {{-- <div class="border-bottom mb-4 pb-2">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Configura tus datos bancarios para recibir pagos por tus trabajos completados en TASKLY.
            </p>
        </div> --}}
        @csrf
        @method('PUT')

        <div class="form-row" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
            <div class="form-group">
                <label for="titular">Titular de la Cuenta:</label>
                <input type="text" name="titular" id="titular" value="{{ old('titular', $datosBancarios->titular ?? '') }}">
            </div>
            <div class="form-group">
                <label for="iban">IBAN:</label>
                <input type="text" name="iban" id="iban" value="{{ old('iban', $datosBancarios->iban ?? '') }}">
            </div>
        </div>

        <div class="form-row" data-aos="fade-up" data-aos-delay="400" data-aos-duration="800">
            <div class="form-group">
                <label for="nombre_banco">Nombre del Banco:</label>
                <input type="text" name="nombre_banco" id="nombre_banco" value="{{ old('nombre_banco', $datosBancarios->nombre_banco ?? '') }}">
            </div>
            <div class="form-group">
                <label for="stripe_account_id">ID de Cuenta Stripe:</label>
                <input type="text" name="stripe_account_id" id="stripe_account_id" 
                       value="{{ old('stripe_account_id', $datosBancarios->stripe_account_id ?? '') }}" 
                       placeholder="acct_1XXXXXXXXX">
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5 mb-3 text-center">
            <button type="submit" class="btn btn-lg btn-guardar-datos" data-aos="zoom-in" data-aos-delay="600" data-aos-duration="800">
                <i class="fas fa-save me-2"></i> Guardar
            </button>
        </div>
    </form>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session("success") }}',
                    confirmButtonColor: '#EC6A6A'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#EC6A6A'
                });
            });
        </script>
    @endif

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/validacion_datos_bancarios.js') }}"></script>

<style>
    .error-message {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    input.invalid {
        border-color: #dc3545 !important;
    }
    
    button.disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
</style>
