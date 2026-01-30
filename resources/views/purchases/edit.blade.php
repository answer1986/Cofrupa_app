@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit"></i> Editar Compra</h2>
            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('purchases.update', $purchase) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Compra</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-building"></i> Comprador*
                                </label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="buyer_cofrupa" name="buyer" value="Cofrupa" {{ old('buyer', $purchase->buyer ?? 'Cofrupa') == 'Cofrupa' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buyer_cofrupa">
                                                Cofrupa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="buyer_lg" name="buyer" value="LG" {{ old('buyer', $purchase->buyer ?? '') == 'LG' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buyer_lg">
                                                LG
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="buyer_comercializadora" name="buyer" value="Comercializadora" {{ old('buyer', $purchase->buyer ?? '') == 'Comercializadora' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buyer_comercializadora">
                                                Comercializadora
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('buyer')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Proveedor *</label>
                                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar proveedor...</option>
                                    @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} - {{ $supplier->location }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purchase_type" class="form-label">Tipo de Compra *</label>
                                <div class="input-group">
                                    <select class="form-select @error('purchase_type') is-invalid @enderror"
                                            id="purchase_type" name="purchase_type" required>
                                        <option value="">Seleccione tipo de compra</option>
                                        <option value="fruta" {{ old('purchase_type', $purchase->purchase_type ?? 'fruta') == 'fruta' ? 'selected' : '' }}>Fruta</option>
                                        <option value="pure_fruta" {{ old('purchase_type', $purchase->purchase_type ?? '') == 'pure_fruta' ? 'selected' : '' }}>Puré de Fruta</option>
                                        <option value="descarte" {{ old('purchase_type', $purchase->purchase_type ?? '') == 'descarte' ? 'selected' : '' }}>Descarte</option>
                                    </select>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="enable_edit_purchase_types">
                                    <label class="form-check-label" for="enable_edit_purchase_types">
                                        <i class="fas fa-edit"></i> Modo edición de tipos
                                    </label>
                                </div>
                                <div class="form-check mt-2" id="remove_purchase_type_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="enable_remove_purchase_type">
                                    <label class="form-check-label text-danger" for="enable_remove_purchase_type">
                                        <i class="fas fa-trash"></i> Eliminar tipo de compra seleccionado
                                    </label>
                                </div>
                                <div class="form-check mt-2" id="add_purchase_type_checkbox_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="enable_add_purchase_type">
                                    <label class="form-check-label" for="enable_add_purchase_type">
                                        <i class="fas fa-plus-circle"></i> Agregar nuevo tipo de compra
                                    </label>
                                </div>
                                <div class="input-group mt-2" id="add_purchase_type_container" style="display: none;">
                                    <input type="text" class="form-control" id="new_purchase_type" placeholder="Nuevo tipo de compra">
                                    <button type="button" class="btn btn-outline-success" id="add_purchase_type">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                                @error('purchase_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="purchase_order" class="form-label">Orden de Compra</label>
                                <input type="text" name="purchase_order" id="purchase_order"
                                       class="form-control @error('purchase_order') is-invalid @enderror"
                                       value="{{ old('purchase_order', $purchase->purchase_order) }}">
                                @error('purchase_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="purchase_date" class="form-label">Fecha de Compra *</label>
                                <input type="date" name="purchase_date" id="purchase_date"
                                       class="form-control @error('purchase_date') is-invalid @enderror"
                                       value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_purchased" class="form-label">Peso Comprado (kg) *</label>
                                <input type="number" step="0.01" name="weight_purchased" id="weight_purchased"
                                       class="form-control @error('weight_purchased') is-invalid @enderror"
                                       value="{{ old('weight_purchased', $purchase->weight_purchased) }}" required>
                                @error('weight_purchased')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="calibre" class="form-label">Calibre *</label>
                                <select name="calibre" id="calibre" class="form-select @error('calibre') is-invalid @enderror" required>
                                    <option value="">Seleccionar calibre...</option>
                                    <option value="80-90" {{ $purchase->calibre == '80-90' ? 'selected' : '' }}>80-90 unidades/libra</option>
                                    <option value="120-x" {{ $purchase->calibre == '120-x' ? 'selected' : '' }}>120-x unidades/libra</option>
                                    <option value="90-100" {{ $purchase->calibre == '90-100' ? 'selected' : '' }}>90-100 unidades/libra</option>
                                    <option value="70-90" {{ $purchase->calibre == '70-90' ? 'selected' : '' }}>70-90 unidades/libra</option>
                                    <option value="Grande 50-60" {{ $purchase->calibre == 'Grande 50-60' ? 'selected' : '' }}>Grande (50-60 unidades/libra)</option>
                                    <option value="Mediana 40-50" {{ $purchase->calibre == 'Mediana 40-50' ? 'selected' : '' }}>Mediana (40-50 unidades/libra)</option>
                                    <option value="Pequeña 30-40" {{ $purchase->calibre == 'Pequeña 30-40' ? 'selected' : '' }}>Pequeña (30-40 unidades/libra)</option>
                                </select>
                                @error('calibre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="units_per_pound" class="form-label">Unidades por Libra *</label>
                                <input type="number" name="units_per_pound" id="units_per_pound"
                                       class="form-control @error('units_per_pound') is-invalid @enderror"
                                       value="{{ old('units_per_pound', $purchase->units_per_pound) }}" required>
                                @error('units_per_pound')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unit_price" class="form-label">Precio por Unidad ($)</label>
                                <input type="number" step="0.01" name="unit_price" id="unit_price"
                                       class="form-control @error('unit_price') is-invalid @enderror"
                                       value="{{ old('unit_price', $purchase->unit_price) }}">
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Monto Total ($)</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount"
                                       class="form-control @error('total_amount') is-invalid @enderror"
                                       value="{{ old('total_amount', $purchase->total_amount) }}">
                                <small class="form-text text-muted">Si no se especifica, se calcula automáticamente</small>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Información de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Monto Pagado ($)</label>
                        <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                               class="form-control @error('amount_paid') is-invalid @enderror"
                               value="{{ old('amount_paid', $purchase->amount_paid) }}">
                        @error('amount_paid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_due_date" class="form-label">Fecha Límite de Pago</label>
                        <input type="date" name="payment_due_date" id="payment_due_date"
                               class="form-control @error('payment_due_date') is-invalid @enderror"
                               value="{{ old('payment_due_date', $purchase->payment_due_date ? $purchase->payment_due_date->format('Y-m-d') : '') }}">
                        @error('payment_due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
                </div>
                <div class="card-body">
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $purchase->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestión de Bins -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Gestión de Bins</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="use_supplier_bins" {{ old('supplier_bins_count', $purchase->supplier_bins_count) ? 'checked' : '' }}>
                                <label class="form-check-label" for="use_supplier_bins">
                                    <i class="fas fa-question-circle"></i> ¿Ocupamos bins del productor?
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bins del Productor -->
                    <div class="row" id="supplier_bins_row" style="display: {{ old('supplier_bins_count', $purchase->supplier_bins_count) ? 'flex' : 'none' }};">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="supplier_bins_count" class="form-label">
                                <i class="fas fa-boxes"></i> Cantidad de Bins Propios del Vendedor
                            </label>
                            <input type="number" class="form-control @error('supplier_bins_count') is-invalid @enderror"
                                   id="supplier_bins_count" name="supplier_bins_count" value="{{ old('supplier_bins_count', $purchase->supplier_bins_count) }}" min="0">
                            @error('supplier_bins_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Ingrese la cantidad de bins que aporta el vendedor (ej: 7)</div>
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="supplier_bins_photo" class="form-label">
                                <i class="fas fa-camera"></i> Foto de los Bins del vendedor
                            </label>
                            <input type="file" class="form-control @error('supplier_bins_photo') is-invalid @enderror"
                                   id="supplier_bins_photo" name="supplier_bins_photo" accept="image/*">
                            @error('supplier_bins_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($purchase->supplier_bins_photo)
                                <div class="form-text">
                                    <a href="{{ asset('storage/' . $purchase->supplier_bins_photo) }}" target="_blank">Ver foto actual</a>
                                </div>
                            @endif
                            <div class="form-text">Suba una foto que identifique el tipo de bins del vendedor</div>
                        </div>
                    </div>

                    <!-- Bins por Enviar (Múltiples Solicitudes) -->
                    <div id="bins_to_send_row" style="display: {{ old('supplier_bins_count', $purchase->supplier_bins_count) ? 'none' : 'block' }};">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fas fa-truck"></i> Solicitudes de Bins por Enviar
                                </label>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Puede agregar múltiples solicitudes. Ej: Primero 10 bins, luego 15 más.
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Solicitudes -->
                        <div id="bins_requests_list" class="mb-3">
                            <!-- Las solicitudes se agregarán aquí dinámicamente -->
                        </div>

                        <!-- Formulario para agregar nueva solicitud -->
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <input type="number" class="form-control" id="new_bins_request_quantity" placeholder="Cantidad de bins" min="1">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" id="new_bins_request_notes" placeholder="Notas (opcional)">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="button" class="btn btn-success w-100" id="add_bins_request">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <!-- Total de bins -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <strong>Total de bins a enviar: <span id="total_bins_to_send">0</span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Campo oculto para enviar datos -->
                        <input type="hidden" id="bins_to_send_json" name="bins_to_send" value="{{ old('bins_to_send', $purchase->bins_to_send ? json_encode($purchase->bins_to_send) : '[]') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4" id="bin_selection_row" style="display: {{ old('supplier_bins_count', $purchase->supplier_bins_count) ? 'none' : 'flex' }};">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Selección de Bins *</h5>
                    <small class="text-muted">Selecciona uno o más bins para esta compra (solo cuando no usas bins del productor)</small>
                </div>
                <div class="card-body">
                    @error('bin_ids')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        @php
                            $selectedBins = old('bin_ids', $purchase->bins->pluck('id')->toArray());
                        @endphp

                        @foreach(\App\Models\Bin::where('status', 'available')->orWhereIn('id', $selectedBins)->orderBy('bin_number')->get() as $bin)
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input bin-checkbox" type="checkbox"
                                       name="bin_ids[]" value="{{ $bin->id }}" id="bin_{{ $bin->id }}"
                                       {{ in_array($bin->id, $selectedBins) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bin_{{ $bin->id }}">
                                    <strong>{{ $bin->bin_number }}</strong><br>
                                    <small class="text-muted">
                                        Capacidad: {{ number_format($bin->capacity, 2) }} kg |
                                        Estado: <span class="badge bg-{{ $bin->status === 'available' ? 'success' : ($bin->status === 'in_use' ? 'warning' : 'secondary') }}">
                                            {{ $bin->status === 'available' ? 'Disponible' : ($bin->status === 'in_use' ? 'En Uso' : 'Mantenimiento') }}
                                        </span>
                                    </small>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <p class="mb-1"><strong>Bins seleccionados: <span id="selected-count">0</span></strong></p>
                        <div id="selected-bins" class="text-muted small"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Compra
                    </button>
                    <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.bin-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const selectedBins = document.getElementById('selected-bins');

    function updateSelection() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        selectedCount.textContent = selected.length;

        if (selected.length > 0) {
            const binNames = selected.map(cb => {
                const label = document.querySelector(`label[for="${cb.id}"]`);
                return label ? label.querySelector('strong').textContent : cb.id;
            });
            selectedBins.textContent = 'Bins: ' + binNames.join(', ');
        } else {
            selectedBins.textContent = '';
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Initial count
    updateSelection();

    // Gestión dinámica de tipos de compra
    const purchaseTypeSelect = document.getElementById('purchase_type');
    const addPurchaseTypeBtn = document.getElementById('add_purchase_type');
    const newPurchaseTypeInput = document.getElementById('new_purchase_type');
    const enableAddCheckbox = document.getElementById('enable_add_purchase_type');
    const addContainer = document.getElementById('add_purchase_type_container');
    const addCheckboxContainer = document.getElementById('add_purchase_type_checkbox_container');
    const removeContainer = document.getElementById('remove_purchase_type_container');
    const enableRemoveCheckbox = document.getElementById('enable_remove_purchase_type');
    const enableEditCheckbox = document.getElementById('enable_edit_purchase_types');

    if (purchaseTypeSelect && addPurchaseTypeBtn && enableAddCheckbox && addContainer && addCheckboxContainer && removeContainer && enableRemoveCheckbox && enableEditCheckbox) {
        // Mostrar/ocultar opciones de edición según checkbox principal
        enableEditCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Mostrar opciones de agregar y eliminar
                addCheckboxContainer.style.display = 'block';
                if (purchaseTypeSelect.value !== '') {
                    removeContainer.style.display = 'block';
                }
            } else {
                // Ocultar todo
                addCheckboxContainer.style.display = 'none';
                addContainer.style.display = 'none';
                removeContainer.style.display = 'none';
                enableAddCheckbox.checked = false;
                enableRemoveCheckbox.checked = false;
                newPurchaseTypeInput.value = '';
            }
        });

        // Mostrar/ocultar contenedor de agregar según checkbox
        enableAddCheckbox.addEventListener('change', function() {
            if (this.checked) {
                addContainer.style.display = 'flex';
                newPurchaseTypeInput.focus();
            } else {
                addContainer.style.display = 'none';
                newPurchaseTypeInput.value = '';
            }
        });

        // Mostrar/ocultar opción de eliminar según selección
        purchaseTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            // Mostrar opción de eliminar solo si hay un tipo seleccionado (no vacío) y modo edición activo
            if (this.value === '') {
                removeContainer.style.display = 'none';
                enableRemoveCheckbox.checked = false;
            } else if (enableEditCheckbox.checked) {
                removeContainer.style.display = 'block';
                enableRemoveCheckbox.checked = false;
            }
        });

        // Eliminar tipo cuando se activa el checkbox
        enableRemoveCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const selectedIndex = purchaseTypeSelect.selectedIndex;
                const selectedOption = purchaseTypeSelect.options[selectedIndex];
                
                if (selectedIndex === 0) {
                    alert('No puede eliminar la opción por defecto');
                    this.checked = false;
                    return;
                }

                let confirmMessage = '¿Está seguro de eliminar este tipo de compra?';
                if (selectedOption.hasAttribute('data-default')) {
                    confirmMessage = '¿Está seguro de eliminar este tipo de compra por defecto?';
                }

                if (confirm(confirmMessage)) {
                    purchaseTypeSelect.remove(selectedIndex);
                    purchaseTypeSelect.value = '';
                    removeContainer.style.display = 'none';
                    this.checked = false;
                } else {
                    this.checked = false;
                }
            }
        });

        // Agregar nuevo tipo de compra
        addPurchaseTypeBtn.addEventListener('click', function() {
            const newType = newPurchaseTypeInput.value.trim();
            if (newType === '') {
                alert('Por favor ingrese un nombre para el tipo de compra');
                return;
            }

            const existingOptions = Array.from(purchaseTypeSelect.options).map(opt => opt.value.toLowerCase());
            if (existingOptions.includes(newType.toLowerCase())) {
                alert('Este tipo de compra ya existe');
                newPurchaseTypeInput.value = '';
                return;
            }

            const newOption = document.createElement('option');
            newOption.value = newType.toLowerCase().replace(/\s+/g, '_');
            newOption.textContent = newType;
            purchaseTypeSelect.appendChild(newOption);
            purchaseTypeSelect.value = newOption.value;
            
            // Limpiar input y ocultar contenedor
            newPurchaseTypeInput.value = '';
            addContainer.style.display = 'none';
            enableAddCheckbox.checked = false;
            
            // Mostrar opción de eliminar si modo edición está activo
            if (enableEditCheckbox.checked) {
                removeContainer.style.display = 'block';
            }
        });


        // Permitir agregar con Enter
        newPurchaseTypeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addPurchaseTypeBtn.click();
            }
        });

        // Marcar opciones por defecto
        Array.from(purchaseTypeSelect.options).forEach(option => {
            if (option.value === 'fruta' || option.value === 'pure_fruta' || option.value === 'descarte') {
                option.setAttribute('data-default', 'true');
            }
        });
    }

    // Gestión de bins del productor
    const useSupplierBinsCheckbox = document.getElementById('use_supplier_bins');
    const supplierBinsRow = document.getElementById('supplier_bins_row');
    const binsToSendRow = document.getElementById('bins_to_send_row');
    const supplierBinsCount = document.getElementById('supplier_bins_count');
    const supplierBinsPhoto = document.getElementById('supplier_bins_photo');
    const binsRequestsList = document.getElementById('bins_requests_list');
    const addBinsRequestBtn = document.getElementById('add_bins_request');
    const newBinsRequestQuantity = document.getElementById('new_bins_request_quantity');
    const newBinsRequestNotes = document.getElementById('new_bins_request_notes');
    const totalBinsToSend = document.getElementById('total_bins_to_send');
    const binsToSendJson = document.getElementById('bins_to_send_json');

    let binsRequests = [];

    // Cargar solicitudes existentes si hay
    @if($purchase->bins_to_send && is_array($purchase->bins_to_send))
        binsRequests = @json($purchase->bins_to_send);
    @endif

    function updateBinsRequestsList() {
        binsRequestsList.innerHTML = '';
        let total = 0;

        binsRequests.forEach((request, index) => {
            total += request.quantity || 0;
            const requestDiv = document.createElement('div');
            requestDiv.className = 'card mb-2';
            requestDiv.innerHTML = `
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Solicitud ${index + 1}:</strong> ${request.quantity || 0} bins
                            ${request.notes ? `<br><small class="text-muted">${request.notes}</small>` : ''}
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeBinsRequest(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            binsRequestsList.appendChild(requestDiv);
        });

        totalBinsToSend.textContent = total;
        binsToSendJson.value = JSON.stringify(binsRequests);
    }

    window.removeBinsRequest = function(index) {
        if (confirm('¿Está seguro de eliminar esta solicitud?')) {
            binsRequests.splice(index, 1);
            updateBinsRequestsList();
        }
    };

    if (addBinsRequestBtn) {
        addBinsRequestBtn.addEventListener('click', function() {
            const quantity = parseInt(newBinsRequestQuantity.value);
            const notes = newBinsRequestNotes.value.trim();

            if (!quantity || quantity <= 0) {
                alert('Por favor ingrese una cantidad válida de bins');
                return;
            }

            binsRequests.push({
                quantity: quantity,
                notes: notes,
                date: new Date().toISOString()
            });

            newBinsRequestQuantity.value = '';
            newBinsRequestNotes.value = '';
            updateBinsRequestsList();
        });

        newBinsRequestQuantity.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addBinsRequestBtn.click();
            }
        });
    }

    var binSelectionRow = document.getElementById('bin_selection_row');
    if (useSupplierBinsCheckbox && supplierBinsRow && binsToSendRow) {
        useSupplierBinsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                supplierBinsRow.style.display = 'flex';
                binsToSendRow.style.display = 'none';
                if (binSelectionRow) binSelectionRow.style.display = 'none';
                binsRequests = [];
                updateBinsRequestsList();
            } else {
                supplierBinsRow.style.display = 'none';
                binsToSendRow.style.display = 'block';
                if (binSelectionRow) binSelectionRow.style.display = 'flex';
                if (supplierBinsCount) supplierBinsCount.value = '';
                if (supplierBinsPhoto) supplierBinsPhoto.value = '';
            }
        });
    }

    // Inicializar lista de solicitudes
    if (binsRequestsList && totalBinsToSend) {
        updateBinsRequestsList();
    }
});
</script>
@endsection