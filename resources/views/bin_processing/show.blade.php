@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-balance-scale"></i> Detalles del Bin Procesado</h2>
            <a href="{{ route('bin_processing.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Bin Procesado</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Número del Bin:</strong> {{ $bin->current_bin_number }}</p>
                        <p><strong>Fecha de Procesamiento:</strong> {{ $bin->processing_date ? $bin->processing_date->format('d/m/Y') : 'N/A' }}</p>
                        <p><strong>Peso Procesado:</strong> {{ number_format($bin->processed_weight ?? $bin->original_weight, 2) }} kg</p>
                        <p><strong>Calibre Procesado:</strong> {{ $bin->processed_calibre ?? $bin->original_calibre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado:</strong>
                            <span class="badge bg-success">{{ $bin->status_display }}</span>
                        </p>
                        <p><strong>Procesado:</strong> {{ $bin->processed_at ? $bin->processed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        @if($bin->processing_history)
                            <p><strong>Historial:</strong> {{ count($bin->processing_history) }} operaciones</p>
                        @endif
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
                    <p class="text-muted small">Actualizado: {{ $bin->qr_updated_at ? $bin->qr_updated_at->format('d/m/Y H:i') : $bin->qr_generated_at->format('d/m/Y H:i') }}</p>
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

@if($bin->processing_history && count($bin->processing_history) > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Procesamiento</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($bin->processing_history as $entry)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>{{ ucfirst($entry['action']) }}</h6>
                            <p class="text-muted mb-1">{{ $entry['date'] }}</p>
                            @if(isset($entry['source_bins']))
                                <p><strong>Bins fuente:</strong> {{ implode(', ', $entry['source_bins']) }}</p>
                            @endif
                            @if(isset($entry['weight_used']))
                                <p><strong>Peso usado:</strong> {{ $entry['weight_used'] }} kg</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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