@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-shopping-bag"></i> Editar Compra de Insumos #{{ $supplyPurchase->id }}</h2>
                <a href="{{ route('supply-purchases.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('supply-purchases.update', $supplyPurchase) }}" method="POST" id="supplyPurchaseForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <!-- Información General -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Compra</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="purchase_date" class="form-label">Fecha de Compra *</label>
                                <input type="date" 
                                       class="form-control @error('purchase_date') is-invalid @enderror" 
                                       id="purchase_date" 
                                       name="purchase_date" 
                                       value="{{ old('purchase_date', $supplyPurchase->purchase_date->format('Y-m-d')) }}" 
                                       required>
                                @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="supplier_name" class="form-label">Proveedor de Insumos *</label>
                                <input type="text" 
                                       class="form-control @error('supplier_name') is-invalid @enderror" 
                                       id="supplier_name" 
                                       name="supplier_name" 
                                       value="{{ old('supplier_name', $supplyPurchase->supplier_name) }}" 
                                       required
                                       placeholder="Nombre del proveedor">
                                @error('supplier_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="invoice_number" class="form-label">N° Factura / Orden</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="invoice_number" 
                                       name="invoice_number" 
                                       value="{{ old('invoice_number', $supplyPurchase->invoice_number) }}"
                                       placeholder="Número de factura">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="buyer" class="form-label">Comprador *</label>
                                <select class="form-select @error('buyer') is-invalid @enderror" id="buyer" name="buyer" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Cofrupa" {{ old('buyer', $supplyPurchase->buyer) == 'Cofrupa' ? 'selected' : '' }}>Cofrupa</option>
                                    <option value="LG" {{ old('buyer', $supplyPurchase->buyer) == 'LG' ? 'selected' : '' }}>LG</option>
                                    <option value="Comercializadora" {{ old('buyer', $supplyPurchase->buyer) == 'Comercializadora' ? 'selected' : '' }}>Comercializadora</option>
                                </select>
                                @error('buyer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_due_date" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="payment_due_date" 
                                       name="payment_due_date" 
                                       value="{{ old('payment_due_date', $supplyPurchase->payment_due_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insumos -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Insumos Comprados</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-success btn-sm" id="addInsumoBtn">
                                    <i class="fas fa-plus"></i> Agregar Insumo
                                </button>
                            </div>
                        </div>

                        <div id="insumosContainer">
                            <!-- Los insumos se agregarán aquí dinámicamente -->
                        </div>

                        <template id="insumoTemplate">
                            <div class="card mb-3 insumo-item" data-index="">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-box"></i> Insumo <span class="insumo-number"></span></h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-insumo-btn">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Nombre del Insumo *</label>
                                            <input type="text" 
                                                   class="form-control insumo-name" 
                                                   name="items[][name]" 
                                                   required
                                                   placeholder="Ej: Bolsas plásticas, Etiquetas">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Cantidad *</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0.01" 
                                                   class="form-control insumo-quantity" 
                                                   name="items[][quantity]" 
                                                   required
                                                   placeholder="100">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Unidad</label>
                                            <select class="form-select insumo-unit" name="items[][unit]">
                                                <option value="unidad">Unidad</option>
                                                <option value="kg">Kilogramos</option>
                                                <option value="litros">Litros</option>
                                                <option value="metros">Metros</option>
                                                <option value="cajas">Cajas</option>
                                                <option value="rollos">Rollos</option>
                                                <option value="paquetes">Paquetes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Precio Unitario</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   class="form-control insumo-unit-price" 
                                                   name="items[][unit_price]" 
                                                   placeholder="0.00">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Total</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   class="form-control insumo-total" 
                                                   name="items[][total]" 
                                                   readonly
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Notas</label>
                                            <textarea class="form-control insumo-notes" 
                                                      name="items[][notes]" 
                                                      rows="2" 
                                                      placeholder="Observaciones..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div id="noInsumosMessage" class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay insumos agregados. Haga clic en "Agregar Insumo" para comenzar.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Resumen de Pago -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Resumen de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="total_amount_display" class="form-label">Total Compra</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="total_amount_display" 
                                   readonly
                                   value="$0">
                        </div>
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Monto Pagado</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control" 
                                   id="amount_paid" 
                                   name="amount_paid" 
                                   value="{{ old('amount_paid', $supplyPurchase->amount_paid) }}"
                                   placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label for="amount_owed_display" class="form-label">Monto Adeudado</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="amount_owed_display" 
                                   readonly
                                   value="$0">
                        </div>
                    </div>
                </div>

                <!-- Notas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  placeholder="Observaciones generales...">{{ old('notes', $supplyPurchase->notes) }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Actualizar Compra de Insumos
                            </button>
                            <a href="{{ route('supply-purchases.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let insumoCounter = 0;

document.addEventListener('DOMContentLoaded', function() {
    initInsumosSystem();
    
    // Cargar insumos existentes
    @if($supplyPurchase->items->count() > 0)
        @foreach($supplyPurchase->items as $item)
            addExistingInsumo({
                name: '{{ $item->name }}',
                quantity: {{ $item->quantity }},
                unit: '{{ $item->unit }}',
                unit_price: {{ $item->unit_price ?? 0 }},
                total: {{ $item->total ?? 0 }},
                notes: '{{ addslashes($item->notes ?? '') }}'
            });
        @endforeach
    @endif
    
    updateTotals();
    document.getElementById('amount_paid').addEventListener('input', updateTotals);
});

function initInsumosSystem() {
    const container = document.getElementById('insumosContainer');
    const template = document.getElementById('insumoTemplate');
    const addBtn = document.getElementById('addInsumoBtn');
    const noInsumosMsg = document.getElementById('noInsumosMessage');

    if (!container || !template || !addBtn) return;

    addBtn.addEventListener('click', function() {
        addExistingInsumo({});
    });
    
    function addExistingInsumo(data) {
        const clone = template.content.cloneNode(true);
        const insumoItem = clone.querySelector('.insumo-item');
        insumoCounter++;
        
        insumoItem.setAttribute('data-index', insumoCounter);
        insumoItem.querySelector('.insumo-number').textContent = insumoCounter;
        
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[]', `[${insumoCounter}]`);
            }
        });
        
        // Llenar con datos si existen
        if (data.name) {
            insumoItem.querySelector('.insumo-name').value = data.name;
            insumoItem.querySelector('.insumo-quantity').value = data.quantity;
            insumoItem.querySelector('.insumo-unit').value = data.unit || 'unidad';
            insumoItem.querySelector('.insumo-unit-price').value = data.unit_price || '';
            insumoItem.querySelector('.insumo-total').value = data.total || '';
            insumoItem.querySelector('.insumo-notes').value = data.notes || '';
        }

        container.appendChild(clone);
        updateNoInsumosMessage();
        attachInsumoListeners(container.lastElementChild);
        
        if (data.total) {
            updateTotals();
        }
    }
    
    window.addExistingInsumo = addExistingInsumo;

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-insumo-btn')) {
            const insumoItem = e.target.closest('.insumo-item');
            insumoItem.remove();
            updateInsumoNumbers();
            updateNoInsumosMessage();
            updateTotals();
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('insumo-quantity') || e.target.classList.contains('insumo-unit-price')) {
            calculateInsumoTotal(e.target.closest('.insumo-item'));
            updateTotals();
        }
    });

    updateNoInsumosMessage();
}

function attachInsumoListeners(insumoItem) {
    const quantityInput = insumoItem.querySelector('.insumo-quantity');
    const unitPriceInput = insumoItem.querySelector('.insumo-unit-price');
    
    if (quantityInput && unitPriceInput) {
        quantityInput.addEventListener('input', () => {
            calculateInsumoTotal(insumoItem);
            updateTotals();
        });
        unitPriceInput.addEventListener('input', () => {
            calculateInsumoTotal(insumoItem);
            updateTotals();
        });
    }
}

function calculateInsumoTotal(insumoItem) {
    const quantity = parseFloat(insumoItem.querySelector('.insumo-quantity').value) || 0;
    const unitPrice = parseFloat(insumoItem.querySelector('.insumo-unit-price').value) || 0;
    const totalInput = insumoItem.querySelector('.insumo-total');
    
    if (totalInput) {
        totalInput.value = (quantity * unitPrice).toFixed(2);
    }
}

function updateTotals() {
    const items = document.querySelectorAll('.insumo-item');
    let totalAmount = 0;
    
    items.forEach(item => {
        const total = parseFloat(item.querySelector('.insumo-total').value) || 0;
        totalAmount += total;
    });
    
    const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const amountOwed = totalAmount - amountPaid;
    
    document.getElementById('total_amount_display').value = '$' + totalAmount.toLocaleString('es-CL', {minimumFractionDigits: 2});
    document.getElementById('amount_owed_display').value = '$' + amountOwed.toLocaleString('es-CL', {minimumFractionDigits: 2});
}

function updateInsumoNumbers() {
    const items = document.querySelectorAll('.insumo-item');
    items.forEach((item, index) => {
        item.querySelector('.insumo-number').textContent = index + 1;
    });
}

function updateNoInsumosMessage() {
    const container = document.getElementById('insumosContainer');
    const noInsumosMsg = document.getElementById('noInsumosMessage');
    
    if (!container || !noInsumosMsg) return;
    
    if (container.children.length === 0) {
        noInsumosMsg.style.display = 'block';
    } else {
        noInsumosMsg.style.display = 'none';
    }
}
</script>
@endsection
