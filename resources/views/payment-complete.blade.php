@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="card-title mb-4">¡Pago Completado con Éxito!</h2>
                    <p class="card-text lead mb-4">
                        Tu pago ha sido procesado correctamente. El trabajador ha sido notificado y comenzará a trabajar en tu solicitud.
                    </p>
                    <p class="mb-5">
                        Hemos enviado un comprobante a tu correo electrónico. La plataforma TASKLY ha retenido solo el 10% como comisión, 
                        y el 90% restante ha sido transferido directamente a la cuenta del trabajador.
                    </p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('trabajos.index') }}" class="btn btn-outline-primary mx-2">
                            <i class="fas fa-home mr-2"></i> Volver al inicio
                        </a>
                        <a href="{{ route('candidatos.trabajo', session('trabajo_id')) }}" class="btn btn-primary mx-2">
                            <i class="fas fa-user-check mr-2"></i> Ver estado del trabajo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 15px;
        border: none;
    }
    
    .btn-primary, .btn-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary);
        color: white;
    }
    
    .text-success {
        color: #28a745 !important;
    }
</style>
@endsection
