@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck-loading"></i> Nueva Recepción de Bins</h2>
            <a href="{{ route('bin_reception.index') }}" class="btn btn-secondary">
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

<form action="{{ route('bin_reception.store') }}" method="POST" id="receptionForm" enctype="multipart/form-data">
    @csrf

    <!-- Reception Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de Recepción</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                data-csg="{{ $supplier->csg_code ?? '' }}"
                                                data-internal="{{ $supplier->internal_code ?? '' }}"
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Código del Proveedor</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i> CSG:</span>
                                    <input type="text" class="form-control" id="supplier_csg_code" readonly>
                                </div>
                                <div class="input-group mt-2">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i> Interno:</span>
                                    <input type="text" class="form-control" id="supplier_internal_code" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="entry_date" class="form-label">Fecha de Recepción <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('entry_date') is-invalid @enderror" id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                                @error('entry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="vehicle_plate" class="form-label">
                                    <i class="fas fa-car"></i> Patente del Vehículo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('vehicle_plate') is-invalid @enderror" 
                                       id="vehicle_plate" name="vehicle_plate" 
                                       value="{{ old('vehicle_plate') }}" 
                                       placeholder="Ej: ABC123" 
                                       style="text-transform: uppercase;" 
                                       required>
                                @error('vehicle_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="reception_weight_per_truck" class="form-label">
                                    <i class="fas fa-weight"></i> Peso por Camión (kg)
                                </label>
                                <input type="number" step="0.01" class="form-control @error('reception_weight_per_truck') is-invalid @enderror" 
                                       id="reception_weight_per_truck" name="reception_weight_per_truck" 
                                       value="{{ old('reception_weight_per_truck') }}" 
                                       placeholder="0.00">
                                @error('reception_weight_per_truck')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="lote" class="form-label">
                                    <i class="fas fa-tag"></i> Lote
                                </label>
                                <input type="text" class="form-control @error('lote') is-invalid @enderror" 
                                       id="lote" name="lote" 
                                       value="{{ old('lote') }}" 
                                       placeholder="Ej: LOTE-2025-001">
                                @error('lote')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Observaciones de la recepción">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h5 class="text-primary mb-0" id="totalNetWeightDisplay">0.00</h5>
                            <small class="text-muted">Peso Neto Fruta (kg)</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-secondary mb-0" id="totalGrossWeightDisplay">0.00</h5>
                            <small class="text-muted">Peso Bruto Total (kg)</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info mb-0" id="binsCountDisplay">0</h5>
                            <small class="text-muted">Cantidad de Bins Pesados</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-success mb-0" id="weightPerTruckDisplay">0.00</h5>
                            <small class="text-muted">Peso por Camión (kg)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bins Delivered to Supplier -->
    <div class="row mb-4" id="deliveredBinsSection" style="display: none;">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-box-open"></i> Bins Entregados al Proveedor</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Bins que fueron entregados a este proveedor y su estado de devolución:</p>
                    <div id="deliveredBinsContainer" class="row">
                        <!-- Bins entregados se cargarán aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bins Reception -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Bins Recibidos</h5>
                </div>
                <div class="card-body">
                    <!-- Existing Bins from Supplier -->
                    <div id="existingBinsSection" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-primary mb-1">Bins de Vuelta (Bins de la Empresa)</h6>
                                <p class="text-muted small mb-0">Selecciona de 1 a 5 bins para pesarlos juntos. Luego ingresa el peso y califica la suciedad.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" onclick="createWeighingGroupFromSelected()">
                                <i class="fas fa-balance-scale"></i> Crear Grupo de Pesaje
                            </button>
                        </div>
                        <div id="existingBinsContainer" class="row mb-4">
                            <!-- Existing bins will be loaded here -->
                        </div>
                        <div id="existingBinsWeighingGroups" class="mb-4">
                            <!-- Groups of existing bins for weighing will be added here -->
                        </div>
                        <hr>
                    </div>

                    <!-- New Bins Section -->
                    <div id="newBinsSection">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-success mb-0">Grupos de Pesaje</h6>
                            <button type="button" class="btn btn-sm btn-success" onclick="addWeighingGroup()">
                                <i class="fas fa-plus"></i> Agregar Grupo de Pesaje
                            </button>
                        </div>
                        <p class="text-muted small">Agrega grupos de bins pesados juntos. El sistema calculará automáticamente el peso neto de fruta restando el peso de los contenedores.</p>
                        <div id="binsContainer">
                            <!-- New weighing groups will be added here -->
                        </div>
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
                        <a href="{{ route('bin_reception.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Recibir Bins y Generar QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.star-rating {
    font-size: 1.8em;
    color: #ffc107;
    line-height: 1;
}
.star-rating .star-empty {
    color: #ddd;
}
.btn-group .btn {
    flex: 1;
}
.btn-group .btn label {
    cursor: pointer;
}
</style>

<script>
let binCount = 0;

// Peso de bins llenos (con fruta): Madera 60kg, Plástico 45kg
const FULL_BIN_WEIGHTS = {
    'wood': 60.00,    // Bin de madera lleno: 60kg
    'plastic': 45.00  // Bin de plástico lleno: 45kg
};

function calculateNetWeight(grossWeight, woodBins, plasticBins) {
    // Restamos el peso total de los bins llenos para obtener solo el peso de la fruta
    const woodWeight = woodBins * FULL_BIN_WEIGHTS.wood;
    const plasticWeight = plasticBins * FULL_BIN_WEIGHTS.plastic;
    const totalBinWeight = woodWeight + plasticWeight;
    return Math.max(0, grossWeight - totalBinWeight);
}

function updateSummary() {
    let totalGrossWeight = 0;
    let totalNetWeight = 0;
    let totalBinsCount = 0;
    
    // Sumar pesos de grupos de pesaje nuevos
    document.querySelectorAll('.weighing-group').forEach(group => {
        const grossWeight = parseFloat(group.querySelector('input[name*="[gross_weight]"]')?.value || 0);
        const woodBins = parseInt(group.querySelector('input[name*="[wood_bins_count]"]')?.value || 0);
        const plasticBins = parseInt(group.querySelector('input[name*="[plastic_bins_count]"]')?.value || 0);
        const binsInGroup = woodBins + plasticBins;
        
        if (grossWeight > 0 && binsInGroup > 0) {
            totalGrossWeight += grossWeight;
            totalBinsCount += binsInGroup;
            totalNetWeight += calculateNetWeight(grossWeight, woodBins, plasticBins);
        }
    });
    
    // Sumar pesos de grupos de bins existentes
    document.querySelectorAll('.existing-weighing-group').forEach(group => {
        const grossWeight = parseFloat(group.querySelector('input[name*="[gross_weight]"]')?.value || 0);
        const woodBins = parseInt(group.querySelector('input[name*="[wood_bins_count]"]')?.value || 0);
        const plasticBins = parseInt(group.querySelector('input[name*="[plastic_bins_count]"]')?.value || 0);
        const binsInGroup = woodBins + plasticBins;
        
        if (grossWeight > 0 && binsInGroup > 0) {
            totalGrossWeight += grossWeight;
            totalBinsCount += binsInGroup;
            totalNetWeight += calculateNetWeight(grossWeight, woodBins, plasticBins);
        }
    });
    
    document.getElementById('totalNetWeightDisplay').textContent = totalNetWeight.toFixed(2);
    document.getElementById('totalGrossWeightDisplay').textContent = totalGrossWeight.toFixed(2);
    document.getElementById('binsCountDisplay').textContent = totalBinsCount;
    
    const weightPerTruck = parseFloat(document.getElementById('reception_weight_per_truck').value) || 0;
    document.getElementById('weightPerTruckDisplay').textContent = weightPerTruck.toFixed(2);
}

function updateGroupNetWeight(groupId) {
    const group = document.getElementById(`group-${groupId}`);
    const grossWeight = parseFloat(group.querySelector('input[name*="[gross_weight]"]')?.value || 0);
    const woodBins = parseInt(group.querySelector('input[name*="[wood_bins_count]"]')?.value || 0);
    const plasticBins = parseInt(group.querySelector('input[name*="[plastic_bins_count]"]')?.value || 0);
    
    const netWeight = calculateNetWeight(grossWeight, woodBins, plasticBins);
    const netWeightDisplay = group.querySelector('.net-weight-display');
    if (netWeightDisplay) {
        netWeightDisplay.textContent = netWeight.toFixed(2) + ' kg';
        netWeightDisplay.className = 'net-weight-display ' + (netWeight > 0 ? 'text-success' : 'text-danger');
    }
    
    // Actualizar el campo hidden
    const netWeightInput = group.querySelector('input[name*="[net_fruit_weight]"]');
    if (netWeightInput) {
        netWeightInput.value = netWeight.toFixed(2);
    }
    
    updateSummary();
}

function renderStars(level) {
    const stars = {
        'limpio': 4,
        'bajo': 3,
        'mediano': 2,
        'alto': 1
    };
    
    const count = stars[level] || 0;
    let html = '';
    for (let i = 1; i <= 4; i++) {
        if (i <= count) {
            html += '<i class="fas fa-star star-rating"></i>';
        } else {
            html += '<i class="far fa-star star-rating star-empty"></i>';
        }
    }
    return html;
}

function addWeighingGroup() {
    binCount++;
    const container = document.getElementById('binsContainer');

    const groupHtml = `
        <div class="weighing-group border rounded p-3 mb-3 bg-light" id="group-${binCount}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6><i class="fas fa-balance-scale"></i> Grupo de Pesaje #${binCount}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeBin(${binCount})">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Peso Bruto del Grupo (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               name="bins[${binCount}][gross_weight]" 
                               placeholder="0.00" 
                               required 
                               onchange="updateGroupNetWeight(${binCount})"
                               oninput="updateGroupNetWeight(${binCount})">
                        <small class="text-muted">Peso total del grupo (bins + fruta)</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Bins de Madera <span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control" 
                               name="bins[${binCount}][wood_bins_count]" 
                               value="0" 
                               required 
                               onchange="updateGroupNetWeight(${binCount})"
                               oninput="updateGroupNetWeight(${binCount})">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Bins de Plástico <span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control" 
                               name="bins[${binCount}][plastic_bins_count]" 
                               value="0" 
                               required 
                               onchange="updateGroupNetWeight(${binCount})"
                               oninput="updateGroupNetWeight(${binCount})">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Peso Neto Fruta</label>
                        <div class="form-control bg-light">
                            <strong class="net-weight-display text-success">0.00 kg</strong>
                        </div>
                        <input type="hidden" name="bins[${binCount}][net_fruit_weight]" value="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Números de Bins (opcional)</label>
                        <input type="text" class="form-control" 
                               name="bins[${binCount}][bin_number]" 
                               placeholder="Ej: REC-001, REC-002">
                        <small class="text-muted">Separar con comas si hay varios</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Calibre <span class="text-danger">*</span></label>
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
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Unidades x Libra Promedio</label>
                        <input type="number" step="0.01" class="form-control" 
                               name="bins[${binCount}][unidades_per_pound_avg]" 
                               placeholder="Ej: 85.5">
                        <small class="text-muted">Promedio de unidades por libra</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Humedad (%)</label>
                        <input type="number" step="0.01" class="form-control" 
                               name="bins[${binCount}][humidity]" 
                               placeholder="Ej: 12.5"
                               min="0" max="100">
                        <small class="text-muted">Porcentaje de humedad</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-star"></i> Calificación de Suciedad de la Fruta <span class="text-danger">*</span>
                        </label>
                        <div class="btn-group w-100" role="group" style="gap: 5px;">
                            <input type="radio" class="btn-check" name="bins[${binCount}][trash_level]" id="trash_limpio_${binCount}" value="limpio" required>
                            <label class="btn btn-outline-success d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="trash_limpio_${binCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('limpio')}</div>
                                <small><strong>Limpia</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="bins[${binCount}][trash_level]" id="trash_bajo_${binCount}" value="bajo" required>
                            <label class="btn btn-outline-info d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="trash_bajo_${binCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('bajo')}</div>
                                <small><strong>Baja Suciedad</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="bins[${binCount}][trash_level]" id="trash_mediano_${binCount}" value="mediano" required>
                            <label class="btn btn-outline-warning d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="trash_mediano_${binCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('mediano')}</div>
                                <small><strong>Media Suciedad</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="bins[${binCount}][trash_level]" id="trash_alto_${binCount}" value="alto" required>
                            <label class="btn btn-outline-danger d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="trash_alto_${binCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('alto')}</div>
                                <small><strong>Alta Suciedad</strong></small>
                            </label>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Califica visualmente el nivel de suciedad de la fruta recibida (4 estrellas = limpia, 1 estrella = alta suciedad)</small>
                    </div>
                </div>
            </div>
            <div class="alert alert-info mb-0">
                <small>
                    <i class="fas fa-info-circle"></i> 
                    <strong>Cálculo automático:</strong> Peso Neto = Peso Bruto - (Bins Madera × 60kg) - (Bins Plástico × 45kg)
                </small>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', groupHtml);
    updateSummary();
}

function removeBin(binId) {
    const binElement = document.getElementById(`bin-${binId}`);
    if (binElement) {
        binElement.remove();
        updateSummary();
    }
}

let existingBinsData = [];
let existingBinGroupsCount = 0;

function loadExistingBins(supplierId) {
    if (!supplierId) {
        document.getElementById('existingBinsSection').style.display = 'none';
        document.getElementById('existingBinsContainer').innerHTML = '';
        document.getElementById('existingBinsWeighingGroups').innerHTML = '';
        existingBinsData = [];
        loadDeliveredBins(null);
        return;
    }

    // Load existing bins for this supplier via AJAX
    fetch(`/api/supplier/${supplierId}/bins`)
        .then(response => response.json())
        .then(data => {
            if (data.bins && data.bins.length > 0) {
                existingBinsData = data.bins;
                let html = '';
                data.bins.forEach(bin => {
                    html += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input existing-bin-checkbox" type="checkbox"
                                               value="${bin.id}" 
                                               data-bin-number="${bin.bin_number}"
                                               data-bin-type="${bin.type}"
                                               data-bin-type-display="${bin.type_display}"
                                               id="existing_${bin.id}"
                                               onchange="updateSelectedBinsCount()">
                                        <label class="form-check-label" for="existing_${bin.id}">
                                            <strong>${bin.bin_number}</strong><br>
                                            <small class="text-muted">
                                                Tipo: ${bin.type_display}<br>
                                                Peso Bin (Tara): ${bin.weight_capacity}kg<br>
                                                Estado: <span class="badge bg-${bin.status === 'in_use' ? 'warning' : 'secondary'}">${bin.status_display}</span>
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('existingBinsContainer').innerHTML = html;
                document.getElementById('existingBinsSection').style.display = 'block';
            } else {
                document.getElementById('existingBinsSection').style.display = 'none';
                document.getElementById('existingBinsContainer').innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error loading bins:', error);
            document.getElementById('existingBinsSection').style.display = 'none';
        });
    
    loadDeliveredBins(supplierId);
}

function updateSelectedBinsCount() {
    const selected = document.querySelectorAll('.existing-bin-checkbox:checked').length;
    const button = document.querySelector('button[onclick="createWeighingGroupFromSelected()"]');
    if (button) {
        if (selected === 0) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-balance-scale"></i> Selecciona bins primero';
        } else if (selected > 5) {
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Máximo 5 bins (seleccionados: ${selected})`;
        } else {
            button.disabled = false;
            button.innerHTML = `<i class="fas fa-balance-scale"></i> Crear Grupo (${selected} bin${selected > 1 ? 's' : ''})`;
        }
    }
}

function createWeighingGroupFromSelected() {
    const selectedCheckboxes = document.querySelectorAll('.existing-bin-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Por favor selecciona al menos un bin.');
        return;
    }
    
    if (selectedCheckboxes.length > 5) {
        alert('Máximo 5 bins por grupo de pesaje.');
        return;
    }
    
    existingBinGroupsCount++;
    const groupId = 'existing-group-' + existingBinGroupsCount;
    const container = document.getElementById('existingBinsWeighingGroups');
    
    let selectedBins = [];
    let woodBins = 0;
    let plasticBins = 0;
    
    selectedCheckboxes.forEach(checkbox => {
        const binId = checkbox.value;
        const binNumber = checkbox.getAttribute('data-bin-number');
        const binType = checkbox.getAttribute('data-bin-type');
        const binTypeDisplay = checkbox.getAttribute('data-bin-type-display');
        
        selectedBins.push({
            id: binId,
            number: binNumber,
            type: binType,
            typeDisplay: binTypeDisplay
        });
        
        if (binType === 'wood') woodBins++;
        else plasticBins++;
        
        // Deshabilitar el checkbox para que no se pueda seleccionar de nuevo
        checkbox.disabled = true;
        checkbox.checked = false;
    });
    
    const binNumbers = selectedBins.map(b => b.number).join(', ');
    
    const groupHtml = `
        <div class="existing-weighing-group border rounded p-3 mb-3 bg-light" id="${groupId}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6><i class="fas fa-balance-scale"></i> Grupo de Pesaje #${existingBinGroupsCount} - Bins: ${binNumbers}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeExistingBinGroup('${groupId}')">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info mb-0">
                        <strong>Bins en este grupo:</strong> ${binNumbers}<br>
                        <small>Bins de Madera: ${woodBins} | Bins de Plástico: ${plasticBins}</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Peso Bruto del Grupo (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               name="existing_bins[${existingBinGroupsCount}][gross_weight]" 
                               placeholder="0.00" 
                               required 
                               onchange="updateExistingGroupNetWeight('${groupId}')"
                               oninput="updateExistingGroupNetWeight('${groupId}')">
                        <small class="text-muted">Peso total del grupo (bins + fruta)</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Bins Madera</label>
                        <input type="number" min="0" class="form-control" 
                               name="existing_bins[${existingBinGroupsCount}][wood_bins_count]" 
                               value="${woodBins}" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Bins Plástico</label>
                        <input type="number" min="0" class="form-control" 
                               name="existing_bins[${existingBinGroupsCount}][plastic_bins_count]" 
                               value="${plasticBins}" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Peso Neto Fruta</label>
                        <div class="form-control bg-light">
                            <strong class="existing-net-weight-display text-success">0.00 kg</strong>
                        </div>
                        <input type="hidden" name="existing_bins[${existingBinGroupsCount}][net_fruit_weight]" value="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Calibre <span class="text-danger">*</span></label>
                        <select class="form-select" name="existing_bins[${existingBinGroupsCount}][calibre]" required>
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
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Unidades x Libra Promedio</label>
                        <input type="number" step="0.01" class="form-control" 
                               name="existing_bins[${existingBinGroupsCount}][unidades_per_pound_avg]" 
                               placeholder="Ej: 85.5">
                        <small class="text-muted">Promedio de unidades por libra</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Humedad (%)</label>
                        <input type="number" step="0.01" class="form-control" 
                               name="existing_bins[${existingBinGroupsCount}][humidity]" 
                               placeholder="Ej: 12.5"
                               min="0" max="100">
                        <small class="text-muted">Porcentaje de humedad</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-star"></i> Calificación de Suciedad de la Fruta <span class="text-danger">*</span>
                        </label>
                        <div class="btn-group w-100" role="group" style="gap: 5px;">
                            <input type="radio" class="btn-check" name="existing_bins[${existingBinGroupsCount}][trash_level]" id="existing_trash_limpio_${existingBinGroupsCount}" value="limpio" required>
                            <label class="btn btn-outline-success d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="existing_trash_limpio_${existingBinGroupsCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('limpio')}</div>
                                <small><strong>Limpia</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="existing_bins[${existingBinGroupsCount}][trash_level]" id="existing_trash_bajo_${existingBinGroupsCount}" value="bajo" required>
                            <label class="btn btn-outline-info d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="existing_trash_bajo_${existingBinGroupsCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('bajo')}</div>
                                <small><strong>Baja Suciedad</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="existing_bins[${existingBinGroupsCount}][trash_level]" id="existing_trash_mediano_${existingBinGroupsCount}" value="mediano" required>
                            <label class="btn btn-outline-warning d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="existing_trash_mediano_${existingBinGroupsCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('mediano')}</div>
                                <small><strong>Media Suciedad</strong></small>
                            </label>
                            
                            <input type="radio" class="btn-check" name="existing_bins[${existingBinGroupsCount}][trash_level]" id="existing_trash_alto_${existingBinGroupsCount}" value="alto" required>
                            <label class="btn btn-outline-danger d-flex flex-column align-items-center justify-content-center" style="min-height: 80px;" for="existing_trash_alto_${existingBinGroupsCount}">
                                <div style="font-size: 1.5em; margin-bottom: 5px;">${renderStars('alto')}</div>
                                <small><strong>Alta Suciedad</strong></small>
                            </label>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Califica visualmente el nivel de suciedad de la fruta recibida (4 estrellas = limpia, 1 estrella = alta suciedad)</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    ${selectedBins.map(bin => `
                        <input type="hidden" name="existing_bins[${existingBinGroupsCount}][bin_ids][]" value="${bin.id}">
                    `).join('')}
                </div>
            </div>
            <div class="alert alert-info mb-0">
                <small>
                    <i class="fas fa-info-circle"></i> 
                    <strong>Cálculo automático:</strong> Peso Neto = Peso Bruto - (Bins Madera × 60kg) - (Bins Plástico × 45kg)
                </small>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', groupHtml);
    updateSummary();
    updateSelectedBinsCount();
}

function updateExistingGroupNetWeight(groupId) {
    const group = document.getElementById(groupId);
    const grossWeight = parseFloat(group.querySelector('input[name*="[gross_weight]"]')?.value || 0);
    const woodBins = parseInt(group.querySelector('input[name*="[wood_bins_count]"]')?.value || 0);
    const plasticBins = parseInt(group.querySelector('input[name*="[plastic_bins_count]"]')?.value || 0);
    
    const netWeight = calculateNetWeight(grossWeight, woodBins, plasticBins);
    const netWeightDisplay = group.querySelector('.existing-net-weight-display');
    if (netWeightDisplay) {
        netWeightDisplay.textContent = netWeight.toFixed(2) + ' kg';
        netWeightDisplay.className = 'existing-net-weight-display ' + (netWeight > 0 ? 'text-success' : 'text-danger');
    }
    
    const netWeightInput = group.querySelector('input[name*="[net_fruit_weight]"]');
    if (netWeightInput) {
        netWeightInput.value = netWeight.toFixed(2);
    }
    
    updateSummary();
}

function removeExistingBinGroup(groupId) {
    const group = document.getElementById(groupId);
    if (group) {
        // Re-habilitar los checkboxes de los bins de este grupo
        const binIds = Array.from(group.querySelectorAll('input[name*="[bin_ids][]"]')).map(input => input.value);
        binIds.forEach(binId => {
            const checkbox = document.getElementById(`existing_${binId}`);
            if (checkbox) {
                checkbox.disabled = false;
            }
        });
        
        group.remove();
        updateSummary();
        updateSelectedBinsCount();
    }
}

function loadDeliveredBins(supplierId) {
    if (!supplierId) {
        document.getElementById('deliveredBinsSection').style.display = 'none';
        return;
    }

    // Load bins delivered to this supplier
    fetch(`/api/supplier/${supplierId}/delivered-bins`)
        .then(response => response.json())
        .then(data => {
            if (data.bins && data.bins.length > 0) {
                let html = '';
                data.bins.forEach(bin => {
                    const isReturned = bin.return_date ? true : false;
                    html += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 ${isReturned ? 'border-success' : 'border-warning'}">
                                <div class="card-body">
                                    <h6 class="card-title">${bin.bin_number}</h6>
                                    <small class="text-muted">
                                        Entregado: ${bin.delivery_date}<br>
                                        ${isReturned ? 
                                            `<span class="badge bg-success">Devuelto: ${bin.return_date}</span>` : 
                                            `<span class="badge bg-warning">Pendiente de devolución</span>`
                                        }
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('deliveredBinsContainer').innerHTML = html;
                document.getElementById('deliveredBinsSection').style.display = 'block';
            } else {
                document.getElementById('deliveredBinsSection').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading delivered bins:', error);
            document.getElementById('deliveredBinsSection').style.display = 'none';
        });
}

// Setup supplier change listener and form validation
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    
    supplierSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const csgCode = selectedOption.getAttribute('data-csg') || '';
        const internalCode = selectedOption.getAttribute('data-internal') || '';
        
        document.getElementById('supplier_csg_code').value = csgCode;
        document.getElementById('supplier_internal_code').value = internalCode;
        
        loadExistingBins(this.value);
    });
    
    // Initialize codes if supplier is pre-selected
    if (supplierSelect.value) {
        supplierSelect.dispatchEvent(new Event('change'));
    }
    
    // Update summary when weight per truck changes
    document.getElementById('reception_weight_per_truck').addEventListener('input', updateSummary);

    // Form validation
    document.getElementById('receptionForm').addEventListener('submit', function(e) {
        const existingGroups = document.querySelectorAll('.existing-weighing-group').length;
        const newGroups = document.querySelectorAll('.weighing-group').length;

        if (existingGroups === 0 && newGroups === 0) {
            e.preventDefault();
            alert('Debe crear al menos un grupo de pesaje (bins existentes o bins nuevos).');
            return false;
        }
        
        // Validar que todos los grupos tengan peso y calificación
        let hasErrors = false;
        document.querySelectorAll('.existing-weighing-group, .weighing-group').forEach(group => {
            const grossWeight = parseFloat(group.querySelector('input[name*="[gross_weight]"]')?.value || 0);
            const trashLevel = group.querySelector('input[name*="[trash_level]"]:checked');
            
            if (grossWeight <= 0) {
                hasErrors = true;
            }
            if (!trashLevel) {
                hasErrors = true;
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Por favor completa todos los campos requeridos en los grupos de pesaje (peso bruto y calificación de suciedad).');
            return false;
        }
    });
    
    // Initialize selected bins count
    updateSelectedBinsCount();
});
</script>
@endsection
