@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-bolt"></i> Compra Rápida</h2>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Registro Rápido de Compra</h5>
                <small>Complete los datos esenciales. Podrá agregar más detalles después.</small>
            </div>
            <div class="card-body">
                <form action="{{ route('purchases.quick-store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-truck"></i> Proveedor *
                            </label>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="new_supplier_check" name="new_supplier_check" {{ old('new_supplier_check') ? 'checked' : '' }}>
                                <label class="form-check-label" for="new_supplier_check">
                                    Nuevo Proveedor
                                </label>
                            </div>

                            <div id="existing_supplier_container" class="{{ old('new_supplier_check') ? 'd-none' : '' }}">
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id">
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="new_supplier_container" class="{{ old('new_supplier_check') ? '' : 'd-none' }}">
                                <input type="text" class="form-control @error('new_supplier_name') is-invalid @enderror"
                                       id="new_supplier_name" name="new_supplier_name"
                                       value="{{ old('new_supplier_name') }}"
                                       placeholder="Ingrese el nombre del nuevo proveedor">
                                @error('new_supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="buyer" class="form-label">
                                <i class="fas fa-user-tag"></i> Comprador *
                            </label>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="buyer_cofrupa" name="buyer" value="Cofrupa" {{ old('buyer', 'Cofrupa') == 'Cofrupa' ? 'checked' : '' }} required>
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
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label for="purchase_date" class="form-label">
                                <i class="fas fa-calendar"></i> Fecha de Compra *
                            </label>
                            <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                   id="purchase_date" name="purchase_date" 
                                   value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight_purchased" class="form-label">
                                <i class="fas fa-weight"></i> Peso (kg) *
                            </label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('weight_purchased') is-invalid @enderror"
                                   id="weight_purchased" name="weight_purchased" 
                                   value="{{ old('weight_purchased') }}" 
                                   placeholder="Ej: 1000.50" required>
                            @error('weight_purchased')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="calibre" class="form-label">
                                <i class="fas fa-ruler"></i> Calibre *
                            </label>
                            <select class="form-select @error('calibre') is-invalid @enderror" 
                                    id="calibre" name="calibre" required>
                                <option value="">Seleccione calibre</option>
                                <option value="80-90" {{ old('calibre') == '80-90' ? 'selected' : '' }}>80-90</option>
                                <option value="120-x" {{ old('calibre') == '120-x' ? 'selected' : '' }}>120-x</option>
                                <option value="90-100" {{ old('calibre') == '90-100' ? 'selected' : '' }}>90-100</option>
                                <option value="70-90" {{ old('calibre') == '70-90' ? 'selected' : '' }}>70-90</option>
                                <option value="Grande 50-60" {{ old('calibre') == 'Grande 50-60' ? 'selected' : '' }}>Grande 50-60</option>
                                <option value="Mediana 40-50" {{ old('calibre') == 'Mediana 40-50' ? 'selected' : '' }}>Mediana 40-50</option>
                                <option value="Pequeña 30-40" {{ old('calibre') == 'Pequeña 30-40' ? 'selected' : '' }}>Pequeña 30-40</option>
                            </select>
                            @error('calibre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="units_per_pound" class="form-label">
                                <i class="fas fa-calculator"></i> Unidades por Libra *
                            </label>
                            <input type="number" min="1" 
                                   class="form-control @error('units_per_pound') is-invalid @enderror"
                                   id="units_per_pound" name="units_per_pound" 
                                   value="{{ old('units_per_pound') }}" 
                                   placeholder="Ej: 12" required>
                            @error('units_per_pound')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit_price" class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Valor del Negocio (Precio x Kg)
                            </label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('unit_price') is-invalid @enderror"
                                   id="unit_price" name="unit_price" 
                                   value="{{ old('unit_price') }}" 
                                   placeholder="Ej: 500">
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Nota / Observaciones
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2" 
                                  placeholder="Ingrese detalles adicionales si es necesario...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Nota:</strong> Después de guardar, será redirigido a la página de edición para completar los detalles adicionales (precios, pagos, bins, etc.).
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-bolt"></i> Guardar y Completar Después
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const check = document.getElementById('new_supplier_check');
        const existingContainer = document.getElementById('existing_supplier_container');
        const newContainer = document.getElementById('new_supplier_container');
        const supplierSelect = document.getElementById('supplier_id');
        const newSupplierInput = document.getElementById('new_supplier_name');

        function toggleSupplierFields() {
            if (check.checked) {
                existingContainer.classList.add('d-none');
                newContainer.classList.remove('d-none');
                supplierSelect.removeAttribute('required');
                newSupplierInput.setAttribute('required', 'required');
            } else {
                existingContainer.classList.remove('d-none');
                newContainer.classList.add('d-none');
                supplierSelect.setAttribute('required', 'required');
                newSupplierInput.removeAttribute('required');
            }
        }

        check.addEventListener('change', toggleSupplierFields);
        
        // Run on load in case of validation errors calling back old input
        toggleSupplierFields();
    });
</script>
@endsection
@endsection
