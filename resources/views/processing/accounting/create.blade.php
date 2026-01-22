@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-plus"></i> Nuevo Registro Contable</h2>
        </div>
    </div>

    <form action="{{ route('processing.accounting.store') }}" method="POST">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información de la Transacción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="transaction_type" class="form-label">Tipo de Transacción *</label>
                        <select class="form-control" id="transaction_type" name="transaction_type" required>
                            <option value="">Seleccione...</option>
                            <option value="purchase" {{ old('transaction_type') == 'purchase' ? 'selected' : '' }}>Compra (Costo)</option>
                            <option value="sale" {{ old('transaction_type') == 'sale' ? 'selected' : '' }}>Venta (Ingreso)</option>
                            <option value="payment" {{ old('transaction_type') == 'payment' ? 'selected' : '' }}>Pago</option>
                            <option value="advance" {{ old('transaction_type') == 'advance' ? 'selected' : '' }}>Abono</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="transaction_date" class="form-label">Fecha de Transacción *</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="closing_date" class="form-label">Fecha de Cierre</label>
                        <input type="date" class="form-control" id="closing_date" name="closing_date" value="{{ old('closing_date') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="supplier_id" class="form-label">Proveedor</label>
                        <select class="form-control" id="supplier_id" name="supplier_id">
                            <option value="">Seleccione...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contract_id" class="form-label">Contrato</label>
                        <select class="form-control" id="contract_id" name="contract_id">
                            <option value="">Seleccione...</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_number }} - {{ $contract->client->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Detalles del Producto y Montos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="{{ old('product_description') }}" placeholder="Ej: Chilean Prunes Natural Condition">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="size_range" class="form-label">Calibre / Rango</label>
                        <input type="text" class="form-control" id="size_range" name="size_range" value="{{ old('size_range') }}" placeholder="Ej: 120-140, 70-80">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="quantity_kg" class="form-label">Cantidad (kg) *</label>
                        <input type="number" step="0.01" class="form-control" id="quantity_kg" name="quantity_kg" value="{{ old('quantity_kg') }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="price_per_kg" class="form-label">Precio por kg *</label>
                        <input type="number" step="0.01" class="form-control" id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg') }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="total_amount_display" class="form-label">Monto Total</label>
                        <input type="text" class="form-control bg-light" id="total_amount_display" readonly placeholder="Se calcula automáticamente">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="currency" class="form-label">Moneda *</label>
                        <select class="form-control" id="currency" name="currency" required>
                            <option value="CLP" {{ old('currency', 'CLP') == 'CLP' ? 'selected' : '' }}>CLP (Pesos Chilenos)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD (Dólares)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="exchange_rate" class="form-label">Tipo de Cambio (opcional)</label>
                        <input type="number" step="0.0001" class="form-control" id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate') }}" placeholder="USD a CLP">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="advance_payment" class="form-label">Abono / Adelanto</label>
                        <input type="number" step="0.01" class="form-control" id="advance_payment" name="advance_payment" value="{{ old('advance_payment', 0) }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Información de Pago</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Método de Pago</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ old('payment_method') }}" placeholder="Ej: Transferencia, Cheque">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_status" class="form-label">Estado de Pago *</label>
                        <select class="form-control" id="payment_status" name="payment_status" required>
                            <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_name" class="form-label">Banco</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="Ej: Banco de Chile">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="bank_account" class="form-label">Cuenta Bancaria</label>
                        <input type="text" class="form-control" id="bank_account" name="bank_account" value="{{ old('bank_account') }}" placeholder="Número de cuenta">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="payment_due_date" class="form-label">Fecha Límite de Pago</label>
                        <input type="date" class="form-control" id="payment_due_date" name="payment_due_date" value="{{ old('payment_due_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="actual_payment_date" class="form-label">Fecha Real de Pago</label>
                        <input type="date" class="form-control" id="actual_payment_date" name="actual_payment_date" value="{{ old('actual_payment_date') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Notas Adicionales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Registro
                </button>
                <a href="{{ route('processing.accounting.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityKg = document.getElementById('quantity_kg');
    const pricePerKg = document.getElementById('price_per_kg');
    const totalAmountDisplay = document.getElementById('total_amount_display');

    function calculateTotal() {
        if (quantityKg.value && pricePerKg.value) {
            const total = parseFloat(quantityKg.value) * parseFloat(pricePerKg.value);
            totalAmountDisplay.value = new Intl.NumberFormat('es-CL').format(total.toFixed(2));
        } else {
            totalAmountDisplay.value = '';
        }
    }

    quantityKg.addEventListener('input', calculateTotal);
    pricePerKg.addEventListener('input', calculateTotal);
});
</script>
@endsection



