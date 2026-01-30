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
                            <label for="supplier_id" class="form-label">
                                <i class="fas fa-truck"></i> Proveedor *
                            </label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" name="supplier_id" required>
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
                            <button type="button" class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#quickSupplierModal" style="width: 100%;">
                                <i class="fas fa-plus"></i> Crear Proveedor Rápido
                            </button>
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
<!-- Modal Crear Proveedor Rápido (mismo comportamiento que Recepción de Bins) -->
<div class="modal fade" id="quickSupplierModal" tabindex="-1" aria-labelledby="quickSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickSupplierModalLabel">
                    <i class="fas fa-plus-circle"></i> Crear Proveedor Rápido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="quickSupplierForm" action="{{ route('purchases.quick-create-supplier') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Solo ingrese el nombre del proveedor. Podrá completar los demás datos después.
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="quick_supplier_name" class="form-label">Nombre del Proveedor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_supplier_name" name="name" required placeholder="Ej: Juan Pérez">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Crear y Seleccionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('quickSupplierForm').addEventListener('submit', function(e) {
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
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success && data.supplier) {
            var select = document.getElementById('supplier_id');
            var opt = document.createElement('option');
            opt.value = data.supplier.id;
            opt.textContent = data.supplier.name;
            opt.selected = true;
            select.appendChild(opt);
            var modalEl = document.getElementById('quickSupplierModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                function cleanupBackdrop() {
                    document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
                modalEl.addEventListener('hidden.bs.modal', cleanupBackdrop, { once: true });
                modal.hide();
            } else {
                // Por si no hay instancia: limpiar de todas formas
                setTimeout(function() {
                    document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }, 300);
            }
            document.getElementById('quick_supplier_name').value = '';
        } else {
            alert('Error: ' + (data.message || 'No se pudo crear el proveedor'));
        }
    })
    .catch(function() {
        alert('Error al crear el proveedor. Por favor, intente nuevamente.');
    })
    .finally(function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection
@endsection
