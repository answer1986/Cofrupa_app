@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shopping-cart"></i> Registrar Nueva Compra</h2>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Información Básica -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Básica</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Comprador *
                            </label>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_cofrupa" name="buyer" value="Cofrupa" {{ old('buyer', 'Cofrupa') == 'Cofrupa' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="buyer_cofrupa">Cofrupa</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_lg" name="buyer" value="LG" {{ old('buyer') == 'LG' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="buyer_lg">LG</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_comercializadora" name="buyer" value="Comercializadora" {{ old('buyer') == 'Comercializadora' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="buyer_comercializadora">Comercializadora</label>
                                    </div>
                                </div>
                            </div>
                            @error('buyer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="supplier_id" class="form-label">
                                <i class="fas fa-truck"></i> Proveedor *
                            </label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} - {{ $supplier->location }}
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="purchase_date" class="form-label">
                                <i class="fas fa-calendar"></i> Fecha de Compra *
                            </label>
                            <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="purchase_order" class="form-label">
                                <i class="fas fa-file-invoice"></i> Orden de Compra o Contrato
                            </label>
                            <input type="text" class="form-control @error('purchase_order') is-invalid @enderror"
                                   id="purchase_order" name="purchase_order" value="{{ old('purchase_order') }}"
                                   placeholder="Ej: OC-001, COMP-2024-001, Contrato XYZ">
                            @error('purchase_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="purchase_type" class="form-label">
                                <i class="fas fa-shopping-cart"></i> Tipo de Compra *
                            </label>
                            <div class="input-group">
                                <select class="form-select @error('purchase_type') is-invalid @enderror" id="purchase_type" name="purchase_type" required>
                                    <option value="">Seleccione tipo de compra</option>
                                    <option value="fruta" {{ old('purchase_type', 'fruta') == 'fruta' ? 'selected' : '' }}>Fruta</option>
                                    <option value="pure_fruta" {{ old('purchase_type') == 'pure_fruta' ? 'selected' : '' }}>Puré de Fruta</option>
                                    <option value="descarte" {{ old('purchase_type') == 'descarte' ? 'selected' : '' }}>Descarte</option>
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
                    </div>
                </div>
            </div>

            <!-- Información del Producto -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-apple-alt"></i> Información del Producto</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="weight_purchased" class="form-label">
                                <i class="fas fa-weight"></i> Peso Comprado (kg) *
                            </label>
                            <input type="number" step="0.01" class="form-control @error('weight_purchased') is-invalid @enderror"
                                   id="weight_purchased" name="weight_purchased" value="{{ old('weight_purchased') }}" required>
                            @error('weight_purchased')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="calibre" class="form-label">
                                <i class="fas fa-tag"></i> Calibre *
                            </label>
                            <select class="form-select @error('calibre') is-invalid @enderror" id="calibre" name="calibre" required>
                                <option value="">Seleccione calibre</option>
                                <option value="80-90" {{ old('calibre') == '80-90' ? 'selected' : '' }}>80-90 unidades/libra</option>
                                <option value="120-x" {{ old('calibre') == '120-x' ? 'selected' : '' }}>120-x unidades/libra</option>
                                <option value="90-100" {{ old('calibre') == '90-100' ? 'selected' : '' }}>90-100 unidades/libra</option>
                                <option value="70-90" {{ old('calibre') == '70-90' ? 'selected' : '' }}>70-90 unidades/libra</option>
                                <option value="Grande 50-60" {{ old('calibre') == 'Grande 50-60' ? 'selected' : '' }}>Grande (50-60 unidades/libra)</option>
                                <option value="Mediana 40-50" {{ old('calibre') == 'Mediana 40-50' ? 'selected' : '' }}>Mediana (40-50 unidades/libra)</option>
                                <option value="Pequeña 30-40" {{ old('calibre') == 'Pequeña 30-40' ? 'selected' : '' }}>Pequeña (30-40 unidades/libra)</option>
                            </select>
                            @error('calibre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="units_per_pound" class="form-label">
                                <i class="fas fa-hashtag"></i> Unidades x Libra *
                            </label>
                            <input type="number" class="form-control @error('units_per_pound') is-invalid @enderror"
                                   id="units_per_pound" name="units_per_pound" value="{{ old('units_per_pound') }}" required>
                            @error('units_per_pound')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Bins -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Gestión de Bins</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info border-0 bg-light">
                                <i class="fas fa-boxes text-primary me-2"></i>
                                <strong>Bins internos disponibles:</strong> {{ $bins->count() }}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="use_supplier_bins">
                                <label class="form-check-label" for="use_supplier_bins">
                                    <i class="fas fa-question-circle"></i> ¿Ocupamos bins del productor?
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bins del Productor -->
                    <div class="row" id="supplier_bins_row" style="display: none;">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="supplier_bins_count" class="form-label">
                                <i class="fas fa-boxes"></i> Cantidad de Bins Propios del Vendedor
                            </label>
                            <input type="number" class="form-control @error('supplier_bins_count') is-invalid @enderror"
                                   id="supplier_bins_count" name="supplier_bins_count" value="{{ old('supplier_bins_count') }}" min="0">
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
                            <div class="form-text">Suba una foto que identifique el tipo de bins del vendedor</div>
                        </div>
                    </div>

                    <!-- Bins por Enviar (Múltiples Solicitudes) -->
                    <div id="bins_to_send_row" style="display: none;">
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
                        <input type="hidden" id="bins_to_send_json" name="bins_to_send">
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Información Financiera</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="price_in_usd" name="price_in_usd" value="1" {{ old('price_in_usd') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="price_in_usd">
                                    <i class="fas fa-dollar-sign text-success"></i> Precios en USD
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="unit_price" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Precio Unitario (<span id="currency-label">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror"
                                   id="unit_price" name="unit_price" value="{{ old('unit_price') }}">
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="amount_paid" class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Monto Pagado (<span id="currency-label-paid">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror"
                                   id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}">
                            @error('amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label for="total_amount" class="form-label">
                                <i class="fas fa-calculator"></i> Total Calculado (<span id="currency-label-total">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control" id="total_amount" readonly>
                            <div class="form-text">Total calculado automáticamente</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="payment_due_date" class="form-label">
                                <i class="fas fa-calendar-times"></i> Fecha Límite de Pago
                            </label>
                            <input type="date" class="form-control @error('payment_due_date') is-invalid @enderror"
                                   id="payment_due_date" name="payment_due_date" value="{{ old('payment_due_date') }}">
                            <div class="form-text">Fecha en la que debe pagarse el saldo pendiente</div>
                            @error('payment_due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              id="notes" name="notes" rows="3" placeholder="Observaciones adicionales...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar Compra
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate total when weight and unit price change
    function calculateTotal() {
        const weight = parseFloat(document.getElementById('weight_purchased').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
        const total = weight * unitPrice;
        document.getElementById('total_amount').value = total.toFixed(2);
    }

    // Update currency labels
    function updateCurrencyLabels() {
        const isUsd = document.getElementById('price_in_usd').checked;
        const currency = isUsd ? 'USD' : 'CLP';
        document.getElementById('currency-label').textContent = currency;
        document.getElementById('currency-label-paid').textContent = currency;
        document.getElementById('currency-label-total').textContent = currency;
    }

    // Add event listeners
    document.getElementById('weight_purchased').addEventListener('input', calculateTotal);
    document.getElementById('unit_price').addEventListener('input', calculateTotal);
    document.getElementById('price_in_usd').addEventListener('change', updateCurrencyLabels);

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

    // Mostrar/ocultar opciones de edición según checkbox principal
    enableEditCheckbox.addEventListener('change', function() {
        if (this.checked) {
            addCheckboxContainer.style.display = 'block';
            if (purchaseTypeSelect.value !== '') {
                removeContainer.style.display = 'block';
            }
        } else {
            addCheckboxContainer.style.display = 'none';
            addContainer.style.display = 'none';
            removeContainer.style.display = 'none';
            enableAddCheckbox.checked = false;
            enableRemoveCheckbox.checked = false;
            newPurchaseTypeInput.value = '';
        }
    });

    enableAddCheckbox.addEventListener('change', function() {
        if (this.checked) {
            addContainer.style.display = 'flex';
            newPurchaseTypeInput.focus();
        } else {
            addContainer.style.display = 'none';
            newPurchaseTypeInput.value = '';
        }
    });

    purchaseTypeSelect.addEventListener('change', function() {
        if (this.value === '') {
            removeContainer.style.display = 'none';
            enableRemoveCheckbox.checked = false;
        } else if (enableEditCheckbox.checked) {
            removeContainer.style.display = 'block';
            enableRemoveCheckbox.checked = false;
        }
    });

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
        
        newPurchaseTypeInput.value = '';
        addContainer.style.display = 'none';
        enableAddCheckbox.checked = false;
        
        if (enableEditCheckbox.checked) {
            removeContainer.style.display = 'block';
        }
    });

    newPurchaseTypeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addPurchaseTypeBtn.click();
        }
    });

    Array.from(purchaseTypeSelect.options).forEach(option => {
        if (option.value === 'fruta' || option.value === 'pure_fruta' || option.value === 'descarte') {
            option.setAttribute('data-default', 'true');
        }
    });

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

    function updateBinsRequestsList() {
        binsRequestsList.innerHTML = '';
        let total = 0;

        binsRequests.forEach((request, index) => {
            total += request.quantity;
            const requestDiv = document.createElement('div');
            requestDiv.className = 'card mb-2';
            requestDiv.innerHTML = `
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Solicitud ${index + 1}:</strong> ${request.quantity} bins
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

    if (useSupplierBinsCheckbox && supplierBinsRow && binsToSendRow) {
        useSupplierBinsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                supplierBinsRow.style.display = 'flex';
                binsToSendRow.style.display = 'none';
                binsRequests = [];
                updateBinsRequestsList();
            } else {
                supplierBinsRow.style.display = 'none';
                binsToSendRow.style.display = 'block';
                if (supplierBinsCount) supplierBinsCount.value = '';
                if (supplierBinsPhoto) supplierBinsPhoto.value = '';
            }
        });
    }

    // Initialize
    calculateTotal();
    updateCurrencyLabels();
});
</script>
@endsection
