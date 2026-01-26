@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck-loading"></i> Detalles del Bin Recibido</h2>
            <a href="{{ route('bin_reception.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Bin</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Proveedor:</strong> {{ $bin->supplier->name }}</p>
                        <p><strong>Número del Bin:</strong> {{ $bin->current_bin_number }}</p>
                        <p><strong>Fecha de Recepción:</strong> {{ $bin->entry_date->format('d/m/Y') }}</p>
                        <p><strong>Peso:</strong> {{ number_format($bin->current_weight, 2) }} kg</p>
                        <p><strong>N° Guía o Factura:</strong> {{ $bin->guide_number ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Calibre:</strong> {{ $bin->current_calibre_display }}</p>
                        <p><strong>Estado:</strong>
                            <span class="badge bg-{{ $bin->status === 'received' ? 'info' : ($bin->status === 'processed' ? 'success' : 'secondary') }}">
                                {{ $bin->status_display }}
                            </span>
                        </p>
                        <p><strong>Recibido:</strong> {{ $bin->received_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-qrcode"></i> Código QR</h5>
            </div>
            <div class="card-body text-center">
                @if($bin->qr_code_url)
                    <img src="{{ $bin->qr_code_url }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    <p class="text-muted small">Generado: {{ $bin->qr_generated_at->format('d/m/Y H:i') }}</p>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-qrcode fa-3x mb-3"></i>
                        <p>QR no generado</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($bin->notes)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $bin->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection