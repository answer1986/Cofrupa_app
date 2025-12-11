@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-search"></i> Procesar Bins - Inspección y Reordenamiento</h2>
            <a href="{{ route('processed_bins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Purchase Info (if coming from purchase) -->
@if(isset($purchase) && $purchase)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Referencia de Compra (Opcional)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha de Compra:</strong> {{ $purchase->purchase_date->format('d/m/Y') }}</p>
                        <p><strong>Proveedor:</strong> {{ $purchase->supplier->name }}</p>
                        <p><strong>Peso Comprado:</strong> {{ number_format($purchase->weight_purchased, 2) }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Calibre Original:</strong> {{ $purchase->calibre_display }}</p>
                        <p><strong>Bins Asignados:</strong> {{ $purchase->bins_display }}</p>
                        <p><strong>Orden de Compra:</strong> {{ $purchase->purchase_order ?: 'N/A' }}</p>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Los datos de la compra se usarán como referencia, pero puedes modificar la información del procesamiento.
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<form action="{{ route('processed_bins.store') }}" method="POST" id="processingForm">
    @csrf

    <!-- Processing Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Información del Procesamiento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ (isset($purchase) && $purchase && $purchase->supplier_id == $supplier->id) || old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} - {{ $supplier->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="entry_date" class="form-label">Fecha de Ingreso a Instalaciones <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('entry_date') is-invalid @enderror" id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                                @error('entry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exit_date" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control @error('exit_date') is-invalid @enderror" id="exit_date" name="exit_date" value="{{ old('exit_date') }}">
                                @error('exit_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="destination" class="form-label">Destino</label>
                                <input type="text" class="form-control @error('destination') is-invalid @enderror" id="destination" name="destination" value="{{ old('destination') }}" placeholder="Ej: Planta de Procesamiento">
                                @error('destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guide_number" class="form-label">N° Guía o Proceso</label>
                                <input type="text" class="form-control @error('guide_number') is-invalid @enderror" id="guide_number" name="guide_number" value="{{ old('guide_number') }}" placeholder="Ej: GUIA-001">
                                @error('guide_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Observaciones del procesamiento">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bins Processing -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Bins Procesados</h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addBin()">
                        <i class="fas fa-plus"></i> Agregar Bin
                    </button>
                </div>
                <div class="card-body">
                    <div id="binsContainer">
                        <!-- Bin rows will be added here -->
                    </div>

                    @error('bins')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('processed_bins.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Procesar Bins y Generar QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($purchase) && $purchase)
    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
    @endif
</form>

<script>
let binCount = 0;

function addBin() {
    binCount++;
    const container = document.getElementById('binsContainer');

    const binHtml = `
        <div class="bin-row border rounded p-3 mb-3" id="bin-${binCount}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Bin #${binCount}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeBin(${binCount})">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Número del Bin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="bins[${binCount}][bin_number]" placeholder="Ej: BIN-001, PROC-001" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Peso Real (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="bins[${binCount}][weight]" placeholder="0.00" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Calibre Real <span class="text-danger">*</span></label>
                        <select class="form-select" name="bins[${binCount}][calibre]" required>
                            <option value="">Seleccionar calibre</option>
                            <option value="80-90">80-90 unidades/libra</option>
                            <option value="120-x">120-x unidades/libra</option>
                            <option value="90-100">90-100 unidades/libra</option>
                            <option value="70-90">70-90 unidades/libra</option>
                            <option value="Grande 50-60">Grande (50-60 unidades/libra)</option>
                            <option value="Mediana 40-50">Mediana (40-50 unidades/libra)</option>
                            <option value="Pequeña 30-40">Pequeña (30-40 unidades/libra)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', binHtml);
}

function removeBin(binId) {
    const binElement = document.getElementById(`bin-${binId}`);
    if (binElement) {
        binElement.remove();
    }
}

// Add first bin by default and setup supplier change listener
document.addEventListener('DOMContentLoaded', function() {
    addBin();

    // Filter bins by supplier
    document.getElementById('supplier_id').addEventListener('change', function() {
        const supplierId = this.value;
        // You can add AJAX call here to filter bins by supplier if needed
        console.log('Supplier changed to:', supplierId);
    });
});
</script>
@endsection