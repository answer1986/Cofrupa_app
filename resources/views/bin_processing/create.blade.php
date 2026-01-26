@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-balance-scale"></i> Nuevo Procesamiento de Bins</h2>
            <a href="{{ route('bin_processing.index') }}" class="btn btn-secondary">
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

<form action="{{ route('bin_processing.store') }}" method="POST" id="processingForm">
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
                                <label for="new_bin_number" class="form-label">Número del Bin Resultante <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('new_bin_number') is-invalid @enderror" id="new_bin_number" name="new_bin_number" value="{{ old('new_bin_number') }}" placeholder="Ej: PROC-001" required>
                                @error('new_bin_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_tarja" class="form-label">Número de Tarja</label>
                                <input type="text" class="form-control @error('numero_tarja') is-invalid @enderror" id="numero_tarja" name="numero_tarja" value="{{ old('numero_tarja') }}" placeholder="Ej: TARJA-001">
                                @error('numero_tarja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_lote" class="form-label">Número de Lote</label>
                                <input type="text" class="form-control @error('numero_lote') is-invalid @enderror" id="numero_lote" name="numero_lote" value="{{ old('numero_lote') }}" placeholder="Ej: LOTE-PROC-001">
                                @error('numero_lote')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dano_total" class="form-label">Daño Total (%)</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('dano_total') is-invalid @enderror" id="dano_total" name="dano_total" value="{{ old('dano_total') }}" placeholder="Ej: 5.5">
                                @error('dano_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="processed_calibre" class="form-label">Calibre Final <span class="text-danger">*</span></label>
                                <select class="form-select @error('processed_calibre') is-invalid @enderror" id="processed_calibre" name="processed_calibre" required>
                                    <option value="">Seleccionar calibre</option>
                                    <option value="80-90">80-90 unidades/libra</option>
                                    <option value="120-x">120-x unidades/libra</option>
                                    <option value="90-100">90-100 unidades/libra</option>
                                    <option value="70-90">70-90 unidades/libra</option>
                                    <option value="Grande 50-60">Grande (50-60 unidades/libra)</option>
                                    <option value="Mediana 40-50">Mediana (40-50 unidades/libra)</option>
                                    <option value="Pequeña 30-40">Pequeña (30-40 unidades/libra)</option>
                                </select>
                                @error('processed_calibre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="calibre_promedio" class="form-label">Calibre Promedio</label>
                                <select class="form-select @error('calibre_promedio') is-invalid @enderror" id="calibre_promedio" name="calibre_promedio">
                                    <option value="">Seleccionar calibre promedio</option>
                                    <option value="40-50">40-50 unidades/libra</option>
                                    <option value="50-60">50-60 unidades/libra</option>
                                    <option value="60-70">60-70 unidades/libra</option>
                                    <option value="70-80">70-80 unidades/libra</option>
                                    <option value="80-90">80-90 unidades/libra</option>
                                    <option value="90-100">90-100 unidades/libra</option>
                                    <option value="100-110">100-110 unidades/libra</option>
                                    <option value="110-120">110-120 unidades/libra</option>
                                    <option value="120">120+ unidades/libra</option>
                                </select>
                                @error('calibre_promedio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas del Procesamiento</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Observaciones del procesamiento">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Source Bins Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Seleccionar Bins a Mezclar</h5>
                </div>
                <div class="card-body">
                    @if($availableBins->isNotEmpty())
                        @foreach($availableBins as $supplierName => $bins)
                        <div class="mb-4">
                            <h6 class="text-primary">{{ $supplierName }}</h6>
                            <div class="row">
                                @foreach($bins as $bin)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="source_bin_ids[]" value="{{ $bin->id }}" id="bin_{{ $bin->id }}">
                                                <label class="form-check-label" for="bin_{{ $bin->id }}">
                                                    <strong>{{ $bin->current_bin_number }}</strong><br>
                                                    <small class="text-muted">
                                                        Peso: {{ number_format($bin->current_weight, 2) }} kg<br>
                                                        Calibre: {{ $bin->current_calibre_display }}<br>
                                                        Estado: <span class="badge bg-{{ $bin->status === 'received' ? 'info' : 'success' }}">{{ $bin->status_display }}</span>
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>No hay bins disponibles para procesar.</p>
                            <a href="{{ route('bin_reception.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Recibir Bins Primero
                            </a>
                        </div>
                    @endif

                    @error('source_bin_ids')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
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
                        <a href="{{ route('bin_processing.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-balance-scale"></i> Procesar Bins y Generar QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('processingForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('input[name="source_bin_ids[]"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Debe seleccionar al menos un bin para procesar.');
            return false;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    });
});
</script>
@endsection