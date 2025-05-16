@extends('layouts.app')

@section('title', 'Factura - TASKLY')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/facturas.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="factura-container">
        <div class="factura-header">
            <div class="row">
                <div class="col-md-6">
                    <h3>TASKLY</h3>
                </div>
                <div class="col-md-6 text-end">
                    <h2>FACTURA</h2>
                    <div>Nº: {{ $datos['numero_factura'] }}</div>
                    <div>Fecha: {{ $datos['fecha'] }}</div>
                </div>
            </div>
        </div>
        
        <div class="factura-title">
            Detalle de servicio completado
        </div>
        
        <div class="factura-info">
            <div class="factura-info-item">
                <div class="factura-info-label">Cliente:</div>
                <div>{{ $datos['cliente'] }}</div>
            </div>
            <div class="factura-info-item">
                <div class="factura-info-label">Trabajador:</div>
                <div>{{ $datos['trabajador'] }}</div>
            </div>
        </div>
        
        <table class="factura-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="price">Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $datos['concepto'] }}</td>
                    <td class="price">{{ number_format($datos['subtotal'], 2) }} €</td>
                </tr>
            </tbody>
        </table>
        
        <div class="factura-totals">
            <div class="row">
                <div>Subtotal:</div>
                <div>{{ number_format($datos['subtotal'], 2) }} €</div>
            </div>
            <div class="row">
                <div>Comisión TASKLY (10%):</div>
                <div>{{ number_format($datos['comision'], 2) }} €</div>
            </div>
            <div class="row total">
                <div>TOTAL:</div>
                <div>{{ number_format($datos['total'], 2) }} €</div>
            </div>
        </div>
        
        <div class="factura-footer">
            <p>Comprobante de pago - TASKLY</p>
            
            <div class="text-center mt-2">
                <button class="btn-imprimir">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/facturas.js') }}"></script>
@endsection
