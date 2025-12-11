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

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Selección de Bins *</h5>
                    <small class="text-muted">Selecciona uno o más bins para esta compra</small>
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
});
</script>
@endsection