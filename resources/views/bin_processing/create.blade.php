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

    <!-- Step 1: Tipo de Proceso -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Paso 1: Tipo de Proceso</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label"><strong>¿Este procesamiento es para?</strong></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded" style="cursor: pointer;" onclick="selectProcessType('internal')">
                                            <input class="form-check-input" type="radio" name="process_type" id="process_type_internal" value="internal" {{ old('external_service') ? '' : 'checked' }} onchange="toggleProcessType()">
                                            <label class="form-check-label" for="process_type_internal" style="cursor: pointer;">
                                                <strong><i class="fas fa-building text-primary"></i> Proceso Interno</strong>
                                                <br>
                                                <small class="text-muted">Procesamiento para uso propio de la empresa</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded" style="cursor: pointer;" onclick="selectProcessType('external')">
                                            <input class="form-check-input" type="radio" name="process_type" id="process_type_external" value="external" {{ old('external_service') ? 'checked' : '' }} onchange="toggleProcessType()">
                                            <label class="form-check-label" for="process_type_external" style="cursor: pointer;">
                                                <strong><i class="fas fa-handshake text-warning"></i> Servicio Externo</strong>
                                                <br>
                                                <small class="text-muted">Procesamiento para un cliente externo (otra exportadora)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Campos del Cliente Externo (solo si es externo) -->
                    <div id="externalClientFields" style="display: {{ old('external_service') ? 'block' : 'none' }};">
                        <hr>
                        <h6 class="text-warning"><i class="fas fa-building"></i> Información del Cliente Externo</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="external_service_client" class="form-label">Cliente Externo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('external_service_client') is-invalid @enderror" id="external_service_client" name="external_service_client" value="{{ old('external_service_client') }}" placeholder="Nombre de la exportadora o cliente">
                                    @error('external_service_client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="external_service_period_start" class="form-label">Inicio del Período <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('external_service_period_start') is-invalid @enderror" id="external_service_period_start" name="external_service_period_start" value="{{ old('external_service_period_start') }}">
                                    @error('external_service_period_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="external_service_period_end" class="form-label">Fin del Período <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('external_service_period_end') is-invalid @enderror" id="external_service_period_end" name="external_service_period_end" value="{{ old('external_service_period_end') }}">
                                    @error('external_service_period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden field for external_service -->
    <input type="hidden" id="external_service" name="external_service" value="{{ old('external_service') ? '1' : '0' }}">

    <!-- Step 2: Información del Procesamiento (Común para ambos tipos) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Paso 2: Información del Procesamiento</h5>
                    <small class="text-muted">Estos campos son comunes para procesos internos y externos - permiten medir la eficiencia del proceso</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                data-csg="{{ $supplier->csg_code ?? '' }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                <label for="csg_code" class="form-label">Número CSG</label>
                                <input type="text" class="form-control @error('csg_code') is-invalid @enderror" id="csg_code" name="csg_code" value="{{ old('csg_code') }}" placeholder="Se llena automáticamente desde el proveedor" readonly>
                                @error('csg_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="processing_start_date" class="form-label">Fecha de Inicio del Proceso <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('processing_start_date') is-invalid @enderror" id="processing_start_date" name="processing_start_date" value="{{ old('processing_start_date', date('Y-m-d')) }}" required>
                                @error('processing_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="processing_end_date" class="form-label">Fecha de Término del Proceso</label>
                                <input type="date" class="form-control @error('processing_end_date') is-invalid @enderror" id="processing_end_date" name="processing_end_date" value="{{ old('processing_end_date') }}">
                                @error('processing_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fruit_type" class="form-label">Tipo de Fruta</label>
                                <input type="text" class="form-control @error('fruit_type') is-invalid @enderror" id="fruit_type" name="fruit_type" value="{{ old('fruit_type') }}" placeholder="Ej: Ciruela, Durazno, etc.">
                                @error('fruit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
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
                                <label for="net_weight" class="form-label">Peso Neto (kg)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('net_weight') is-invalid @enderror" id="net_weight" name="net_weight" value="{{ old('net_weight') }}" placeholder="0.00">
                                @error('net_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cofrupa_plastic_bins_count" class="form-label">Bins Plásticos Marca Cofrupa</label>
                                <input type="number" min="0" class="form-control @error('cofrupa_plastic_bins_count') is-invalid @enderror" id="cofrupa_plastic_bins_count" name="cofrupa_plastic_bins_count" value="{{ old('cofrupa_plastic_bins_count', 0) }}" placeholder="0">
                                @error('cofrupa_plastic_bins_count')
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="defect_notes" class="form-label">Notas de Desperfecto</label>
                                <textarea class="form-control @error('defect_notes') is-invalid @enderror" id="defect_notes" name="defect_notes" rows="3" placeholder="Describa cualquier desperfecto encontrado durante el procesamiento">{{ old('defect_notes') }}</textarea>
                                @error('defect_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciones</label>
                                <textarea class="form-control @error('observations') is-invalid @enderror" id="observations" name="observations" rows="3" placeholder="Observaciones generales del procesamiento">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas del Procesamiento</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Notas adicionales del procesamiento">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bins Processed Per Day -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Eficiencia: Bins Procesados por Día</h5>
                    <small class="text-muted">Registre la cantidad de bins procesados cada día para medir la eficiencia del proceso</small>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Agregue cada día del proceso con la cantidad de bins procesados para analizar la eficiencia.</p>
                    <div id="binsPerDayContainer">
                        <!-- Los días se agregarán dinámicamente aquí -->
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addBinsPerDay()">
                        <i class="fas fa-plus"></i> Agregar Día
                    </button>
                    <input type="hidden" name="bins_processed_per_day" id="bins_processed_per_day" value="">
                </div>
            </div>
        </div>
    </div>


    <!-- Step 3: Selección de Bins -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Paso 3: Seleccionar Bins a Mezclar</h5>
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
let binsPerDayCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('processingForm');
    const submitBtn = document.getElementById('submitBtn');
    const supplierSelect = document.getElementById('supplier_id');
    const csgInput = document.getElementById('csg_code');

    // Update CSG code when supplier changes
    if (supplierSelect) {
        supplierSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const csgCode = selectedOption.getAttribute('data-csg') || '';
            if (csgInput) {
                csgInput.value = csgCode;
            }
        });

        // Initialize CSG if supplier is pre-selected
        if (supplierSelect.value) {
            supplierSelect.dispatchEvent(new Event('change'));
        }
        
        // Initialize process type
        toggleProcessType();
    }

    form.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('input[name="source_bin_ids[]"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Debe seleccionar al menos un bin para procesar.');
            return false;
        }

        // Save bins per day data
        saveBinsPerDay();

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    });
});

function selectProcessType(type) {
    const internalRadio = document.getElementById('process_type_internal');
    const externalRadio = document.getElementById('process_type_external');
    
    if (type === 'internal') {
        internalRadio.checked = true;
        externalRadio.checked = false;
    } else {
        internalRadio.checked = false;
        externalRadio.checked = true;
    }
    
    toggleProcessType();
}

function toggleProcessType() {
    const internalRadio = document.getElementById('process_type_internal');
    const externalRadio = document.getElementById('process_type_external');
    const externalFields = document.getElementById('externalClientFields');
    const externalServiceInput = document.getElementById('external_service');
    
    if (externalRadio && externalRadio.checked) {
        externalFields.style.display = 'block';
        if (externalServiceInput) {
            externalServiceInput.value = '1';
        }
        // Hacer requeridos los campos cuando es externo
        const clientField = document.getElementById('external_service_client');
        const periodStartField = document.getElementById('external_service_period_start');
        const periodEndField = document.getElementById('external_service_period_end');
        
        if (clientField) clientField.setAttribute('required', 'required');
        if (periodStartField) periodStartField.setAttribute('required', 'required');
        if (periodEndField) periodEndField.setAttribute('required', 'required');
    } else {
        externalFields.style.display = 'none';
        if (externalServiceInput) {
            externalServiceInput.value = '0';
        }
        // Quitar requerido cuando es interno
        const clientField = document.getElementById('external_service_client');
        const periodStartField = document.getElementById('external_service_period_start');
        const periodEndField = document.getElementById('external_service_period_end');
        
        if (clientField) {
            clientField.removeAttribute('required');
            clientField.value = '';
        }
        if (periodStartField) {
            periodStartField.removeAttribute('required');
            periodStartField.value = '';
        }
        if (periodEndField) {
            periodEndField.removeAttribute('required');
            periodEndField.value = '';
        }
    }
}

function addBinsPerDay() {
    binsPerDayCount++;
    const container = document.getElementById('binsPerDayContainer');
    const date = new Date();
    date.setDate(date.getDate() - binsPerDayCount + 1);
    const dateStr = date.toISOString().split('T')[0];

    const dayHtml = `
        <div class="row mb-2" id="day_${binsPerDayCount}">
            <div class="col-md-4">
                <input type="date" class="form-control bins-per-day-date" 
                       value="${dateStr}" 
                       onchange="updateBinsPerDay()">
            </div>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control bins-per-day-count" 
                       placeholder="Cantidad de bins procesados" 
                       onchange="updateBinsPerDay()"
                       oninput="updateBinsPerDay()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeBinsPerDay(${binsPerDayCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', dayHtml);
    updateBinsPerDay();
}

function removeBinsPerDay(dayId) {
    const dayElement = document.getElementById(`day_${dayId}`);
    if (dayElement) {
        dayElement.remove();
        updateBinsPerDay();
    }
}

function updateBinsPerDay() {
    const data = [];
    document.querySelectorAll('.bins-per-day-date').forEach((dateInput, index) => {
        const countInput = dateInput.closest('.row').querySelector('.bins-per-day-count');
        if (dateInput.value && countInput.value) {
            data.push({
                date: dateInput.value,
                count: parseInt(countInput.value) || 0
            });
        }
    });
    document.getElementById('bins_processed_per_day').value = JSON.stringify(data);
}

function saveBinsPerDay() {
    updateBinsPerDay();
}
</script>
@endsection