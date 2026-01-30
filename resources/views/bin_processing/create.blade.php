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
                                    <input type="hidden" id="external_service_client_id" name="external_service_client_id" value="{{ old('external_service_client_id') }}">
                                    <input type="text" class="form-control @error('external_service_client') is-invalid @enderror" id="external_service_client" name="external_service_client" value="{{ old('external_service_client') }}" placeholder="Nombre de la exportadora o cliente">
                                    @error('external_service_client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#quickExternalClientModal" style="width: 100%;">
                                        <i class="fas fa-plus"></i> Crear Cliente Externo Rápido
                                    </button>
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

    <!-- Step 2: Datos generales del procesamiento (aparte) -->
    <div class="row mb-4" id="step2Section">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Paso 2: Datos generales del procesamiento</h5>
                    <small>Proveedor, fechas y tipo de fruta. Luego complete bin por bin.</small>
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
                                <input type="text" class="form-control @error('csg_code') is-invalid @enderror" id="csg_code" name="csg_code" value="{{ old('csg_code') }}" placeholder="Se llena desde el proveedor" readonly>
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
                                <input type="text" class="form-control @error('fruit_type') is-invalid @enderror" id="fruit_type" name="fruit_type" value="{{ old('fruit_type') }}" placeholder="Ej: Ciruela, Durazno">
                                @error('fruit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="btnStep2Next">
                            <i class="fas fa-arrow-right"></i> Siguiente: Seleccionar bins
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Selección de Bins fuente -->
    <div class="row mb-4" id="step3Section" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Paso 3: Seleccionar Bins a procesar</h5>
                    <small class="text-muted">Indique la cantidad de bins e indique «Generar tabla», o marque los bins que desee. Se generará una fila por bin (001, 002, … N).</small>
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
                                                <input class="form-check-input source-bin-cb" type="checkbox" name="source_bin_ids[]" value="{{ $bin->id }}" id="bin_{{ $bin->id }}" data-weight="{{ $bin->current_weight }}">
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
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <button type="button" class="btn btn-secondary" id="btnStep3Back">
                                <i class="fas fa-arrow-left"></i> Volver
                            </button>
                            <div class="d-flex align-items-center gap-2">
                                <label class="mb-0 text-muted small">Cantidad de bins:</label>
                                <input type="number" id="binsToGenerateCount" class="form-control form-control-sm" value="1" min="1" style="width: 80px;" title="Número de bins a incluir en la tabla (001 hasta N)">
                                <button type="button" class="btn btn-primary" id="btnStep3Next">
                                    <i class="fas fa-list"></i> Generar tabla (001 hasta N)
                                </button>
                            </div>
                        </div>
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

    <!-- Step 4: Tabla de bins resultantes (001, 002, ... N) con avance -->
    <div class="row mb-4" id="step4Section" style="display: none;">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0"><i class="fas fa-table"></i> Paso 4: Datos por bin resultante</h5>
                    <div>
                        <span class="badge bg-light text-dark fs-6" id="progressBadge">Completados 0 de 0</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Complete los datos de cada bin. El avance se actualiza al llenar los campos obligatorios (Número bin, Calibre final).</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="binsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Número Bin Resultante *</th>
                                    <th>Número Tarja</th>
                                    <th>Peso Neto (kg)</th>
                                    <th>Bins Cofrupa</th>
                                    <th>Nº Lote</th>
                                    <th>Daño %</th>
                                    <th>Calibre Final *</th>
                                    <th>Unid/libra prom.</th>
                                    <th>Notas Desperfecto</th>
                                    <th>Observaciones frutas</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody id="binsTableBody">
                                <!-- Filas generadas por JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Bins procesados por día -->
                    <div class="mt-4">
                        <h6><i class="fas fa-calendar-day"></i> Bins procesados por día (opcional)</h6>
                        <div id="binsPerDayContainer"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addBinsPerDay()">
                            <i class="fas fa-plus"></i> Agregar día
                        </button>
                        <input type="hidden" name="bins_processed_per_day" id="bins_processed_per_day" value="">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="btnStep4Back">
                            <i class="fas fa-arrow-left"></i> Volver a selección
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-save"></i> Guardar procesamiento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
let binsPerDayCount = 0;

const CALIBRE_OPTIONS = '<option value="">Seleccionar</option><option value="80-90">80-90 u/libra</option><option value="120-x">120-x u/libra</option><option value="90-100">90-100 u/libra</option><option value="70-90">70-90 u/libra</option><option value="Grande 50-60">Grande 50-60</option><option value="Mediana 40-50">Mediana 40-50</option><option value="Pequeña 30-40">Pequeña 30-40</option>';
const CALIBRE_PROMEDIO_OPTIONS = '<option value="">-</option><option value="40-50">40-50</option><option value="50-60">50-60</option><option value="60-70">60-70</option><option value="70-80">70-80</option><option value="80-90">80-90</option><option value="90-100">90-100</option><option value="100-110">100-110</option><option value="110-120">110-120</option><option value="120">120+</option>';

function goToStep2() {
    document.getElementById('step2Section').style.display = 'block';
    document.getElementById('step3Section').style.display = 'none';
    document.getElementById('step4Section').style.display = 'none';
}
function goToStep3() {
    document.getElementById('step2Section').style.display = 'block';
    document.getElementById('step3Section').style.display = 'block';
    document.getElementById('step4Section').style.display = 'none';
    document.getElementById('step3Section').scrollIntoView({ behavior: 'smooth' });
}
function goToStep4() {
    const allCheckboxes = document.querySelectorAll('.source-bin-cb');
    const countInput = document.getElementById('binsToGenerateCount');
    const n = countInput ? Math.max(1, parseInt(countInput.value, 10) || 1) : 1;

    // Si el usuario indicó cantidad: marcar los primeros N bins
    if (countInput && n > 0) {
        allCheckboxes.forEach(function(cb, i) {
            cb.checked = i < n;
        });
    }

    const checked = document.querySelectorAll('.source-bin-cb:checked');
    if (checked.length === 0) {
        alert('Seleccione al menos un bin para procesar o indique una cantidad.');
        return;
    }
    if (allCheckboxes.length > 0 && n > allCheckboxes.length) {
        if (countInput) countInput.value = allCheckboxes.length;
    }
    buildBinsTable(checked);
    document.getElementById('step4Section').style.display = 'block';
    document.getElementById('step4Section').scrollIntoView({ behavior: 'smooth' });
    updateProgress();
}
function buildBinsTable(checkedBoxes) {
    const tbody = document.getElementById('binsTableBody');
    tbody.innerHTML = '';
    Array.from(checkedBoxes).forEach((cb, i) => {
        const num = String(i + 1).padStart(3, '0');
        const sourceBinId = cb.value;
        const tr = document.createElement('tr');
        tr.className = 'bin-row';
        tr.innerHTML = `
            <td class="align-middle">${i + 1}</td>
            <td><input type="hidden" name="bins[${i}][source_bin_id]" value="${sourceBinId}"><input type="text" class="form-control form-control-sm bin-new-number" name="bins[${i}][new_bin_number]" value="${num}" placeholder="001" required></td>
            <td><input type="text" class="form-control form-control-sm" name="bins[${i}][numero_tarja]" placeholder="Tarja"></td>
            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm bin-net-weight" name="bins[${i}][net_weight]" placeholder="0"></td>
            <td><input type="number" min="0" class="form-control form-control-sm" name="bins[${i}][cofrupa_plastic_bins_count]" value="0" placeholder="0"></td>
            <td><input type="text" class="form-control form-control-sm" name="bins[${i}][numero_lote]" placeholder="Lote"></td>
            <td><input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm" name="bins[${i}][dano_total]" placeholder="%"></td>
            <td><select class="form-select form-select-sm bin-calibre" name="bins[${i}][processed_calibre]" required>${CALIBRE_OPTIONS}</select></td>
            <td><select class="form-select form-select-sm" name="bins[${i}][calibre_promedio]">${CALIBRE_PROMEDIO_OPTIONS}</select></td>
            <td><input type="text" class="form-control form-control-sm" name="bins[${i}][defect_notes]" placeholder="Notas"></td>
            <td><input type="text" class="form-control form-control-sm" name="bins[${i}][observations]" placeholder="Obs."></td>
            <td><input type="text" class="form-control form-control-sm" name="bins[${i}][notes]" placeholder="Notas"></td>
        `;
        tbody.appendChild(tr);
    });
    tbody.querySelectorAll('.bin-new-number, .bin-calibre').forEach(el => {
        el.addEventListener('input', updateProgress);
        el.addEventListener('change', updateProgress);
    });
}
function updateProgress() {
    const rows = document.querySelectorAll('#binsTableBody tr.bin-row');
    let completed = 0;
    rows.forEach(tr => {
        const num = tr.querySelector('.bin-new-number');
        const cal = tr.querySelector('.bin-calibre');
        if (num && num.value.trim() && cal && cal.value) completed++;
    });
    const total = rows.length;
    document.getElementById('progressBadge').textContent = 'Completados ' + completed + ' de ' + total;
}
function uncheckSourceBinsForSubmit() {
    document.querySelectorAll('.source-bin-cb').forEach(cb => cb.removeAttribute('name'));
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('processingForm');
    const submitBtn = document.getElementById('submitBtn');
    const supplierSelect = document.getElementById('supplier_id');
    const csgInput = document.getElementById('csg_code');

    if (supplierSelect) {
        supplierSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const csgCode = selectedOption.getAttribute('data-csg') || '';
            if (csgInput) csgInput.value = csgCode;
        });
        if (supplierSelect.value) supplierSelect.dispatchEvent(new Event('change'));
    }
    toggleProcessType();

    document.getElementById('btnStep2Next').addEventListener('click', function() {
        document.getElementById('step3Section').style.display = 'block';
        document.getElementById('step3Section').scrollIntoView({ behavior: 'smooth' });
    });
    const btnStep3Back = document.getElementById('btnStep3Back');
    if (btnStep3Back) btnStep3Back.addEventListener('click', function() {
        document.getElementById('step3Section').style.display = 'none';
    });
    const btnStep3Next = document.getElementById('btnStep3Next');
    if (btnStep3Next) {
        btnStep3Next.addEventListener('click', goToStep4);
        var totalBins = document.querySelectorAll('.source-bin-cb').length;
        var countInput = document.getElementById('binsToGenerateCount');
        if (countInput && totalBins > 0) {
            countInput.max = totalBins;
            countInput.placeholder = '1-' + totalBins;
        }
    }
    document.getElementById('btnStep4Back').addEventListener('click', function() {
        document.getElementById('step4Section').style.display = 'none';
        document.getElementById('step3Section').scrollIntoView({ behavior: 'smooth' });
    });

    form.addEventListener('submit', function(e) {
        const tableRows = document.querySelectorAll('#binsTableBody tr.bin-row');
        if (tableRows.length === 0) {
            e.preventDefault();
            alert('Genere la tabla de bins (Paso 3 → Generar tabla) y complete al menos un bin.');
            return false;
        }
        uncheckSourceBinsForSubmit();
        saveBinsPerDay();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
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
        const clientIdField = document.getElementById('external_service_client_id');
        if (clientIdField) clientIdField.value = '';
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

// Al editar manualmente el nombre del cliente externo, limpiar el id para guardar el texto
document.addEventListener('DOMContentLoaded', function() {
    const clientInput = document.getElementById('external_service_client');
    const clientIdInput = document.getElementById('external_service_client_id');
    if (clientInput && clientIdInput) {
        clientInput.addEventListener('input', function() {
            clientIdInput.value = '';
        });
    }
});

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

<!-- Modal para Crear Cliente Externo Rápido -->
<div class="modal fade" id="quickExternalClientModal" tabindex="-1" aria-labelledby="quickExternalClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickExternalClientModalLabel">
                    <i class="fas fa-plus-circle"></i> Crear Cliente Externo Rápido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickExternalClientForm" action="{{ route('bin_processing.quick-create-external-client') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Solo ingrese el nombre de la exportadora o cliente. Podrá completar los demás datos después en la sección de clientes.
                    </div>
                    <div class="mb-3">
                        <label for="quick_external_client_name" class="form-label">Nombre del Cliente / Exportadora <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_external_client_name" name="name" required placeholder="Ej: Exportadora Frutícola SA">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Crear y Usar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Manejar creación rápida de cliente externo
document.getElementById('quickExternalClientForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('external_service_client').value = data.client.name;
            document.getElementById('external_service_client_id').value = data.client.id;

            const modalElement = document.getElementById('quickExternalClientModal');
            if (typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                } else {
                    const bsModal = new bootstrap.Modal(modalElement);
                    bsModal.hide();
                }
            } else {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }

            document.getElementById('quick_external_client_name').value = '';
            alert('Cliente creado exitosamente. Recuerde completar los datos en la sección de clientes.');
        } else {
            alert('Error: ' + (data.message || 'No se pudo crear el cliente'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al crear el cliente. Por favor, intente nuevamente.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection