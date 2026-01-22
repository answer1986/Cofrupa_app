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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Información de la Compra</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Comprador *
                            </label>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_cofrupa" name="buyer" value="Cofrupa" {{ old('buyer', 'Cofrupa') == 'Cofrupa' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="buyer_cofrupa">
                                            Cofrupa
                                        </label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_lg" name="buyer" value="LG" {{ old('buyer') == 'LG' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="buyer_lg">
                                            LG
                                        </label>
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
                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                    id="supplier_id" name="supplier_id" required>
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
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="purchase_type" class="form-label">
                                <i class="fas fa-shopping-cart"></i> Tipo de Compra *
                            </label>
                            <select class="form-select @error('purchase_type') is-invalid @enderror"
                                    id="purchase_type" name="purchase_type" required>
                                <option value="">Seleccione tipo de compra</option>
                                <option value="fruta" {{ old('purchase_type', 'fruta') == 'fruta' ? 'selected' : '' }}>Fruta</option>
                                <option value="pure_fruta" {{ old('purchase_type') == 'pure_fruta' ? 'selected' : '' }}>Puré de Fruta</option>
                                <option value="descarte" {{ old('purchase_type') == 'descarte' ? 'selected' : '' }}>Descarte</option>
                            </select>
                            @error('purchase_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="alert alert-info border-0 bg-light">
                                <i class="fas fa-boxes text-primary me-2"></i>
                                <strong>Bins internos disponibles:</strong> {{ $bins->count() }}
                            </div>
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
                            <select class="form-select @error('calibre') is-invalid @enderror"
                                    id="calibre" name="calibre" required>
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

                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="price_in_usd" name="price_in_usd" value="1" {{ old('price_in_usd') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="price_in_usd">
                                    <i class="fas fa-dollar-sign text-success"></i> Precios en USD
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="unit_price" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Precio Unitario (<span id="currency-label">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror"
                                   id="unit_price" name="unit_price" value="{{ old('unit_price') }}">
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="amount_paid" class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Monto Pagado (<span id="currency-label-paid">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror"
                                   id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}">
                            @error('amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="total_amount" class="form-label">
                                <i class="fas fa-calculator"></i> Total Calculado (<span id="currency-label-total">CLP</span>)
                            </label>
                            <input type="number" step="0.01" class="form-control" id="total_amount" readonly>
                            <div class="form-text">Total calculado automáticamente</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Notas
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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

    // Initialize
    calculateTotal();
    updateCurrencyLabels();
});
</script>
@endsection