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
                    <img src="{{ asset('img/icon.png') }}" alt="TASKLY Logo" class="factura-logo" style="height: 50px; margin-bottom: 10px;">
                </div>
                <div class="col-md-6 text-end">
                    <h2>FACTURA</h2>
                    <div><strong>Número:</strong> {{ $datos['numero_factura'] }}</div>
                    <div><strong>Fecha:</strong> {{ $datos['fecha'] }}</div>
                    <div><strong>Referencia de pago:</strong> {{ $datos['transaccion_id'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        
        
        <div class="factura-parties">
            <div class="row">
                <div class="col-md-6">
                    <div class="party-box">
                        <h5>Cliente (Pagador):</h5>
                        <p><strong>{{ $datos['cliente'] }}</strong></p>
                        <p><strong>NIF/CIF:</strong> {{ $datos['cliente_nif'] ?? 'No disponible' }}</p>
                        <p><strong>Dirección:</strong> {{ $datos['cliente_direccion'] ?? 'No disponible' }}</p>
                        <p><strong>CP/Ciudad:</strong> {{ $datos['cliente_cp'] ?? '' }} {{ $datos['cliente_ciudad'] ?? '' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="party-box">
                        <h5>Prestador del servicio:</h5>
                        <p><strong>{{ $datos['trabajador'] }}</strong></p>
                        <p><strong>NIF/CIF:</strong> {{ $datos['trabajador_nif'] ?? 'No disponible' }}</p>
                        <p><strong>Dirección:</strong> {{ $datos['trabajador_direccion'] ?? 'No disponible' }}</p>
                        <p><strong>CP/Ciudad:</strong> {{ $datos['trabajador_cp'] ?? '' }} {{ $datos['trabajador_ciudad'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        
        <table class="factura-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="price">Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $datos['concepto'] }}</td>
                    <td class="text-center">1</td>
                    <td class="price">{{ number_format($datos['subtotal'], 2) }} €</td>
                </tr>
            </tbody>
        </table>
        
        <div class="factura-totals">
            <div class="row">
                <div>Importe del servicio:</div>
                <div>{{ number_format($datos['subtotal'], 2) }} €</div>
            </div>
            <div class="row">
                <div>Comisión plataforma TASKLY (10%):</div>
                <div>{{ number_format($datos['comision'], 2) }} €</div>
            </div>
            <div class="row total">
                <div>TOTAL PAGADO:</div>
                <div>{{ number_format($datos['total'], 2) }} €</div>
            </div>
        </div>
        
       
        
        <div class="factura-notes mt-4">
            <p><strong>Notas:</strong></p>
            <ul>
                <li>Este documento sirve como comprobante de pago del servicio realizado.</li>
                <li>TASKLY actúa como plataforma intermediaria entre el cliente y el prestador del servicio.</li>
                <li>La comisión del 10% corresponde a los servicios de gestión e intermediación de TASKLY.</li>
                <li>Para cualquier consulta relacionada con este pago, contacte con soporte@taskly.com</li>
            </ul>
        </div>

        <div class="factura-footer mt-4">
            <div class="row">
                <div class="col-md-8">
                    <p>Comprobante generado por TASKLY - Plataforma de servicios freelance</p>
                </div>
                <div class="col-md-4 text-end">
                    <p>Fecha emisión: {{ $datos['fecha'] }}</p>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <button class="btn-descargar" style="background-color: #EC6A6A; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-download"></i> Guardar PDF
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Biblioteca html2pdf.js para generar PDFs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="{{ asset('js/facturas.js') }}"></script>
@endsection
