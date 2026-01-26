@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-qrcode"></i> Detalles del Bin Procesado</h2>
            <div>
                <a href="{{ route('processed_bins.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                @if($processedBin->status !== 'delivered')
                <button class="btn btn-warning" onclick="editStatus()">
                    <i class="fas fa-edit"></i> Actualizar Estado
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Bin Procesado</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha de Ingreso:</strong> {{ $processedBin->entry_date->format('d/m/Y') }}</p>
                        <p><strong>Número del Bin:</strong> {{ $processedBin->display_bin_number }}</p>
                        <p><strong>Proveedor:</strong> {{ $processedBin->supplier->name }}</p>
                        <p><strong>Peso Real:</strong> {{ number_format($processedBin->current_weight, 2) }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Calibre:</strong> {{ $processedBin->calibre_display }}</p>
                        <p><strong>Destino:</strong> {{ $processedBin->destination ?: 'N/A' }}</p>
                        <p><strong>N° Guía:</strong> {{ $processedBin->guide_number ?: 'N/A' }}</p>
                        <p><strong>Estado:</strong>
                            <span class="badge bg-{{ $processedBin->status === 'processed' ? 'primary' : ($processedBin->status === 'shipped' ? 'warning' : 'success') }}">
                                {{ $processedBin->status_display }}
                            </span>
                        </p>
                    </div>
                </div>
                @if($processedBin->exit_date)
                <div class="row">
                    <div class="col-12">
                        <p><strong>Fecha de Salida:</strong> {{ $processedBin->exit_date->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-qrcode"></i> Código QR</h5>
            </div>
            <div class="card-body text-center">
                @if($processedBin->qr_code_url)
                    <img src="{{ $processedBin->qr_code_url }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    <p class="text-muted small">Generado: {{ $processedBin->qr_generated_at->format('d/m/Y H:i') }}</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadQR()">
                        <i class="fas fa-download"></i> Descargar QR
                    </button>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-qrcode fa-3x mb-3"></i>
                        <p>QR no generado aún</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Purchase Reference (if exists) -->
@if($processedBin->purchase && $processedBin->purchase->id)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Referencia de Compra</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Compra ID:</strong> #{{ $processedBin->purchase->id }}</p>
                        <p><strong>Fecha de Compra:</strong> {{ $processedBin->purchase->purchase_date->format('d/m/Y') }}</p>
                        <p><strong>Orden de Compra:</strong> {{ $processedBin->purchase->purchase_order ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Peso Comprado:</strong> {{ number_format($processedBin->purchase->weight_purchased, 2) }} kg</p>
                        <p><strong>Calibre Original:</strong> {{ $processedBin->purchase->calibre_display }}</p>
                        <a href="{{ route('purchases.show', $processedBin->purchase) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Ver Compra Completa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Procesamiento Independiente</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">Este bin fue procesado de manera independiente, sin referencia a una compra específica en el sistema.</p>
            </div>
        </div>
    </div>
</div>
@endif

@if($processedBin->notes)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $processedBin->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Procesado: {{ $processedBin->processed_at->format('d/m/Y H:i') }} |
                        Última actualización: {{ $processedBin->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Estado del Bin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('processed_bins.update', $processedBin) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="processed" {{ $processedBin->status === 'processed' ? 'selected' : '' }}>Procesado</option>
                            <option value="shipped" {{ $processedBin->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ $processedBin->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exit_date" class="form-label">Fecha de Salida</label>
                        <input type="date" class="form-control" id="exit_date" name="exit_date" value="{{ $processedBin->exit_date ? $processedBin->exit_date->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ $processedBin->destination }}">
                    </div>
                    <div class="mb-3">
                        <label for="guide_number" class="form-label">N° Guía</label>
                        <input type="text" class="form-control" id="guide_number" name="guide_number" value="{{ $processedBin->guide_number }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editStatus() {
    new bootstrap.Modal(document.getElementById('editStatusModal')).show();
}

function downloadQR() {
    const link = document.createElement('a');
    link.href = '{{ $processedBin->qr_code_url }}';
    link.download = 'QR_{{ $processedBin->display_bin_number }}.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection