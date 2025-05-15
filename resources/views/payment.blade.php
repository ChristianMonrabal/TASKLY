@extends('layouts.app')

@section('content')
<div class="container payment-container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="mb-0">Realizar Pago</h3>
                </div>
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        <!-- Columna izquierda: Extracto del pago -->
                        <div class="col-md-5 border-right">
                            <div class="payment-summary p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="/img/logo.png" alt="TASKLY" height="40" class="mr-3">
                                    <div class="payment-badge">
                                        <i class="fas fa-shield-alt"></i> Pago Seguro
                                    </div>
                                </div>
                                
                                <h4 class="mb-3">{{ $trabajo->titulo }}</h4>
                                
                                <div class="worker-info d-flex align-items-center mb-4">
                                    <div class="avatar mr-2">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <span>{{ $trabajador->nombre }}</span>
                                </div>
                                
                                <div class="divider my-4"></div>
                                
                                <div class="price-breakdown">
                                    <h5 class="mb-3">Resumen del pago</h5>
                                    
                                    <div class="fee-summary mb-3">
                                        <div class="row mb-2">
                                            <div class="col-8">Subtotal</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio, 2) }}</div>
                                        </div>
                                        <div class="row text-muted small mb-1">
                                            <div class="col-8">Comisión de la plataforma (10%)</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio * 0.1, 2) }}</div>
                                        </div>
                                        <div class="row text-muted small">
                                            <div class="col-8">El trabajador recibe</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio * 0.9, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="divider my-4"></div>
                                
                                <div class="total-amount mt-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Total a pagar:</span>
                                        <span class="font-weight-bold price-tag">€{{ number_format($trabajo->precio, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="payment-guarantee mt-4 pt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lock text-primary mr-2"></i>
                                        <small class="text-muted">Pago seguro mediante cifrado SSL</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna derecha: Método de pago -->
                        <div class="col-md-7">
                            <div class="payment-method p-4">
                                <h4 class="mb-4">Método de Pago</h4>
                                <div id="payment-message" class="alert d-none"></div>
                                
                                <div class="payment-card-header mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-credit-card text-primary mr-2"></i>
                                        <h5 class="mb-0">Tarjeta de crédito o débito</h5>
                                    </div>
                                    <div class="mt-2 mb-3 accepted-cards">
                                        <i class="fab fa-cc-visa mx-1"></i>
                                        <i class="fab fa-cc-mastercard mx-1"></i>
                                        <i class="fab fa-cc-amex mx-1"></i>
                                    </div>
                                </div>
                                
                                <form id="payment-form" data-email="{{ $usuario_actual->email }}" data-stripe-key="{{ $stripe_key }}" data-create-intent-url="{{ route('payment.create-intent') }}" data-update-status-url="{{ route('payment.update-status') }}">
                                    <input type="hidden" id="trabajo_id" value="{{ $trabajo->id }}">
                                    <input type="hidden" id="trabajador_id" value="{{ $trabajador->id }}">
                                    <input type="hidden" id="amount" value="{{ $trabajo->precio }}">
                                    
                                    <div id="payment-element" class="mb-4">
                                        <!-- El elemento de pago de Stripe se insertará aquí -->
                                    </div>
                                    
                                    <button id="submit-button" class="btn btn-primary btn-lg btn-block">
                                        <div class="spinner d-none" id="spinner"></div>
                                        <span id="button-text">Pagar ahora</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/payment.js') }}"></script>
@endsection
