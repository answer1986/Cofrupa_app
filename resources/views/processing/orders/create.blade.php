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
                    <div class="col-md-6 mb-3">
                        <label for="raw_material" class="form-label">Materia Prima</label>
                        <input type="text" class="form-control" id="raw_material" name="raw_material" value="{{ old('raw_material') }}" placeholder="Ej: 70/80 COFRUPA TEMP 2025">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="product" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ old('product') }}" placeholder="Ej: TIERNIZADO">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" placeholder="Ej: SIN CAROZO">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="caliber" class="form-label">Calibre</label>
                        <input type="text" class="form-control" id="caliber" name="caliber" value="{{ old('caliber') }}" placeholder="Ej: EX 60/70">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" min="0" placeholder="Ej: 21.500">
                        <small class="text-muted">KILOS</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kilos_sent" class="form-label">Kilos Enviados</label>
                        <input type="number" step="0.01" class="form-control" id="kilos_sent" name="kilos_sent" value="{{ old('kilos_sent') }}" min="0" placeholder="Ej: 21.500">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kilos_produced" class="form-label">Kilos Producidos</label>
                        <input type="number" step="0.01" class="form-control" id="kilos_produced" name="kilos_produced" value="{{ old('kilos_produced') }}" min="0" placeholder="Ej: 21.500">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quality" class="form-label">Calidad</label>
                        <input type="text" class="form-control" id="quality" name="quality" value="{{ old('quality') }}" placeholder="Ej: GRADO 1 USDA A CAT-1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="labeling" class="form-label">Etiquetado</label>
                        <input type="text" class="form-control" id="labeling" name="labeling" value="{{ old('labeling') }}" placeholder="Ej: ADJUNTA">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="packaging" class="form-label">Envases</label>
                        <input type="text" class="form-control" id="packaging" name="packaging" value="{{ old('packaging') }}" placeholder="Ej: CAIAS 10 KLS COFRUPA">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="potassium_sorbate" class="form-label">Sorbato de potasio</label>
                        <input type="text" class="form-control" id="potassium_sorbate" name="potassium_sorbate" value="{{ old('potassium_sorbate') }}" placeholder="Ej: 800 PPM MAX">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="humidity" class="form-label">Humedad</label>
                        <input type="text" class="form-control" id="humidity" name="humidity" value="{{ old('humidity') }}" placeholder="Ej: 30% +-1 máximo">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stone_percentage" class="form-label">% de Carozo</label>
                        <input type="text" class="form-control" id="stone_percentage" name="stone_percentage" value="{{ old('stone_percentage') }}" placeholder="Ej: 0,5% Máx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="oil" class="form-label">Aceite</label>
                        <input type="text" class="form-control" id="oil" name="oil" value="{{ old('oil') }}" placeholder="Ej: SIN ACEITE">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="damage" class="form-label">Daños</label>
                        <input type="text" class="form-control" id="damage" name="damage" value="{{ old('damage') }}" placeholder="Ej: 5,0 % MAXIMO">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="plant_print" class="form-label">Impresión Planta</label>
                        <input type="text" class="form-control" id="plant_print" name="plant_print" value="{{ old('plant_print') }}" placeholder="Ej: ADJUNTA">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="destination" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}" placeholder="Ej: Turquia">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="loading_date" class="form-label">Fecha de carga</label>
                        <input type="text" class="form-control" id="loading_date" name="loading_date" value="{{ old('loading_date') }}" placeholder="Ej: Semana 18/19">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sag" class="form-label">SAG</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="sag" name="sag" value="1" {{ old('sag') ? 'checked' : '' }}>
                            <label class="form-check-label" for="sag">Sí</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto (adicional)</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="{{ old('product_description') }}" placeholder="Ej: NATURAL CONDITION, CONCENTRADO">
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



