@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-recycle"></i> Registrar Descarte de Producción</h2>
                <a href="{{ route('discards.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('discards.store') }}" method="POST">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Seleccionar Orden de Producción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="production_order_id" class="form-label">Orden de Producción *</label>
                        <select class="form-control @error('production_order_id') is-invalid @enderror" 
                                id="production_order_id" 
                                name="production_order_id" 
                                required 
                                onchange="loadOrderDetails(this.value)">
                            <option value="">Seleccione una orden...</option>
                            @foreach($productionOrders as $order)
                                <option value="{{ $order->id }}" 
                                        data-order-number="{{ $order->order_number }}"
                                        data-plant="{{ $order->plant->name ?? 'N/A' }}"
                                        data-product="{{ $order->product }}"
                                        data-caliber="{{ $order->output_caliber }}"
                                        data-quantity="{{ $order->order_quantity_kg }}"
                                        data-produced="{{ $order->produced_kilos ?? 0 }}"
                                        data-output="{{ $order->output_quantity_kg ?? 0 }}"
                                        data-dispatched="{{ $order->dispatched_kg ?? 0 }}"
                                        data-existing-discard="{{ $order->discard_kg }}"
                                        {{ old('production_order_id') == $order->id ? 'selected' : '' }}>
                                    #{{ $order->order_number }} - {{ $order->plant->name ?? 'N/A' }} - {{ $order->product }} ({{ $order->output_caliber }})
                                </option>
                            @endforeach
                        </select>
                        @error('production_order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($productionOrders->isEmpty())
                            <small class="text-muted">
                                No hay órdenes de producción disponibles. 
                                <a href="{{ route('processing.production-orders.index') }}">Ver órdenes de producción</a>
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Información de la orden seleccionada -->
                <div id="orderDetailsCard" style="display: none;">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Detalles de la Orden</h6>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <strong>Planta:</strong><br>
                                <span id="detail_plant">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Producto:</strong><br>
                                <span id="detail_product">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Calibre:</strong><br>
                                <span id="detail_caliber" class="badge bg-secondary">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Kilos Enviados:</strong><br>
                                <span id="detail_quantity">-</span> kg
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Producido:</strong><br>
                                <span id="detail_produced">-</span> kg
                            </div>
                            <div class="col-md-3">
                                <strong>Producto Terminado:</strong><br>
                                <span id="detail_output">-</span> kg
                            </div>
                            <div class="col-md-3">
                                <strong>Despachado:</strong><br>
                                <span id="detail_dispatched">-</span> kg
                            </div>
                            <div class="col-md-3">
                                <strong>Rendimiento:</strong><br>
                                <span id="detail_efficiency" class="badge bg-info">-</span>
                            </div>
                        </div>
                        <div id="existingDiscardWarning" style="display: none;" class="mt-2">
                            <hr>
                            <div class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Esta orden ya tiene <strong><span id="existing_discard_amount">0</span> kg</strong> de descarte registrado.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Información del Descarte</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6 class="alert-heading"><i class="fas fa-boxes"></i> Descartes por Tipo (kg)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="discard_humid_kg" class="form-label">Descarte Húmedo *</label>
                            <input type="number" step="0.01" min="0" class="form-control discard-type-field" id="discard_humid_kg" name="discard_humid_kg" value="{{ old('discard_humid_kg', 0) }}" placeholder="0.00">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="discard_stone_kg" class="form-label">Cantidad de Cuesco *</label>
                            <input type="number" step="0.01" min="0" class="form-control discard-type-field" id="discard_stone_kg" name="discard_stone_kg" value="{{ old('discard_stone_kg', 0) }}" placeholder="0.00">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="discard_other_kg" class="form-label">Descarte Seco *</label>
                            <input type="number" step="0.01" min="0" class="form-control discard-type-field" id="discard_other_kg" name="discard_other_kg" value="{{ old('discard_other_kg', 0) }}" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Total Descarte</label>
                            <div class="form-control bg-light">
                                <strong id="totalDiscardDisplay">0.00 kg</strong>
                            </div>
                            <input type="hidden" id="discard_kg" name="discard_kg" value="{{ old('discard_kg', 0) }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="discard_reason" class="form-label">Razón del Descarte *</label>
                        <input type="text" 
                               class="form-control @error('discard_reason') is-invalid @enderror" 
                               id="discard_reason" 
                               name="discard_reason" 
                               value="{{ old('discard_reason') }}" 
                               required
                               placeholder="Ej: Fruta dañada, calibre incorrecto, humedad excesiva">
                        @error('discard_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="discard_status" class="form-label">Estado del Descarte *</label>
                        <select class="form-control @error('discard_status') is-invalid @enderror" 
                                id="discard_status" 
                                name="discard_status" 
                                required>
                            <option value="pending" {{ old('discard_status', 'pending') == 'pending' ? 'selected' : '' }}>
                                Pendiente Recuperación
                            </option>
                            <option value="recovered" {{ old('discard_status') == 'recovered' ? 'selected' : '' }}>
                                Recuperado (ya devuelto al stock)
                            </option>
                            <option value="disposed" {{ old('discard_status') == 'disposed' ? 'selected' : '' }}>
                                Desechado (no recuperable)
                            </option>
                        </select>
                        @error('discard_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3" id="recovery_location_field" style="display: none;">
                        <label for="recovery_location" class="form-label">Ubicación de Recuperación</label>
                        <input type="text" 
                               class="form-control @error('recovery_location') is-invalid @enderror" 
                               id="recovery_location" 
                               name="recovery_location" 
                               value="{{ old('recovery_location') }}"
                               placeholder="Ej: Bodega A - Estante 5">
                        @error('recovery_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Dónde se almacenará el material recuperado</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="discard_notes" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control @error('discard_notes') is-invalid @enderror" 
                                  id="discard_notes" 
                                  name="discard_notes" 
                                  rows="3" 
                                  placeholder="Observaciones sobre el estado del material, causas adicionales, etc.">{{ old('discard_notes') }}</textarea>
                        @error('discard_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Registrar Descarte
                </button>
                <a href="{{ route('discards.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function loadOrderDetails(orderId) {
    const select = document.getElementById('production_order_id');
    const selectedOption = select.options[select.selectedIndex];
    const detailsCard = document.getElementById('orderDetailsCard');
    
    if (!orderId) {
        detailsCard.style.display = 'none';
        return;
    }
    
    const ordered = parseFloat(selectedOption.dataset.quantity) || 0;
    const produced = parseFloat(selectedOption.dataset.produced) || 0;
    const output = parseFloat(selectedOption.dataset.output) || 0;
    const dispatched = parseFloat(selectedOption.dataset.dispatched) || 0;
    const efficiency = ordered > 0 ? ((produced / ordered) * 100).toFixed(2) : 0;
    
    document.getElementById('detail_plant').textContent = selectedOption.dataset.plant;
    document.getElementById('detail_product').textContent = selectedOption.dataset.product;
    document.getElementById('detail_caliber').textContent = selectedOption.dataset.caliber;
    document.getElementById('detail_quantity').textContent = ordered.toLocaleString('es-CL');
    document.getElementById('detail_produced').textContent = produced.toLocaleString('es-CL');
    document.getElementById('detail_output').textContent = output.toLocaleString('es-CL');
    document.getElementById('detail_dispatched').textContent = dispatched.toLocaleString('es-CL');
    document.getElementById('detail_efficiency').textContent = efficiency + '%';
    
    const existingDiscard = parseFloat(selectedOption.dataset.existingDiscard);
    const warningDiv = document.getElementById('existingDiscardWarning');
    if (existingDiscard > 0) {
        document.getElementById('existing_discard_amount').textContent = existingDiscard.toLocaleString('es-CL');
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
    
    detailsCard.style.display = 'block';
}

// Mostrar/ocultar campo de ubicación según el estado
document.getElementById('discard_status').addEventListener('change', function() {
    const locationField = document.getElementById('recovery_location_field');
    if (this.value === 'recovered') {
        locationField.style.display = 'block';
        document.getElementById('recovery_location').required = true;
    } else {
        locationField.style.display = 'none';
        document.getElementById('recovery_location').required = false;
    }
});

// Calcular total de descartes automáticamente
const discardFields = document.querySelectorAll('.discard-type-field');
discardFields.forEach(field => {
    field.addEventListener('input', calculateTotalDiscard);
});

function calculateTotalDiscard() {
    const humid = parseFloat(document.getElementById('discard_humid_kg').value) || 0;
    const stone = parseFloat(document.getElementById('discard_stone_kg').value) || 0;
    const other = parseFloat(document.getElementById('discard_other_kg').value) || 0;
    const total = humid + stone + other;
    
    document.getElementById('totalDiscardDisplay').textContent = total.toFixed(2) + ' kg';
    document.getElementById('discard_kg').value = total.toFixed(2);
}

// Trigger inicial si hay valor old
document.addEventListener('DOMContentLoaded', function() {
    const productionOrderSelect = document.getElementById('production_order_id');
    if (productionOrderSelect.value) {
        loadOrderDetails(productionOrderSelect.value);
    }
    
    const statusSelect = document.getElementById('discard_status');
    if (statusSelect.value === 'recovered') {
        document.getElementById('recovery_location_field').style.display = 'block';
        document.getElementById('recovery_location').required = true;
    }
    
    calculateTotalDiscard();
});
</script>
@endsection



