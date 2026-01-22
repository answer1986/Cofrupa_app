@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-clipboard-list"></i> Nueva Orden de Proceso</h2>
        </div>
    </div>

    <form action="{{ route('processing.orders.store') }}" method="POST">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="plant_id" class="form-label">Planta *</label>
                        <select class="form-control" id="plant_id" name="plant_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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
                    <div class="col-md-4 mb-3">
                        <label for="order_number" class="form-label">N° Orden *</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="{{ old('order_number') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="csg_code" class="form-label">CSG Code (Código de productor de SAG)</label>
                        <input type="text" class="form-control" id="csg_code" name="csg_code" value="{{ old('csg_code') }}" placeholder="Ej: ONI-08, COF-04">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="production_days" class="form-label">Tiempo de Producción (días)</label>
                        <input type="number" class="form-control" id="production_days" name="production_days" value="{{ old('production_days') }}" min="1" placeholder="Ej: 5">
                        <small class="text-muted">Se calculará automáticamente la fecha de término esperada</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="order_date" class="form-label">Fecha de Orden *</label>
                        <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expected_completion_date" class="form-label">Fecha de Término Esperada</label>
                        <input type="date" class="form-control" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" readonly>
                        <small class="text-muted">Se calcula automáticamente según días de producción</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Detalles del Producto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="{{ old('product_description') }}" placeholder="Ej: NATURAL CONDITION, CONCENTRADO">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="quantity" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" min="0">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="unit" class="form-label">Unidad</label>
                        <select class="form-control" id="unit" name="unit">
                            <option value="kg" {{ old('unit', 'kg') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>ton</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Información Adicional</h5>
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
                    <i class="fas fa-save"></i> Guardar Orden
                </button>
                <a href="{{ route('processing.orders.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderDate = document.getElementById('order_date');
    const productionDays = document.getElementById('production_days');
    const expectedCompletionDate = document.getElementById('expected_completion_date');

    function calculateExpectedDate() {
        if (orderDate.value && productionDays.value) {
            const date = new Date(orderDate.value);
            date.setDate(date.getDate() + parseInt(productionDays.value));
            expectedCompletionDate.value = date.toISOString().split('T')[0];
        } else {
            expectedCompletionDate.value = '';
        }
    }

    orderDate.addEventListener('change', calculateExpectedDate);
    productionDays.addEventListener('input', calculateExpectedDate);
});
</script>
@endsection



