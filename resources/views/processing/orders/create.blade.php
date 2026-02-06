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
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos a enviar</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Seleccione los insumos que enviará con esta orden. Los insumos provienen de las compras registradas en <a href="{{ route('supply-purchases.index') }}" target="_blank">Compras de Insumos</a>.</p>
                @if($insumosOptions->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay insumos registrados. <a href="{{ route('supply-purchases.create') }}">Registre una compra de insumos</a> para poder seleccionarlos aquí.
                    </div>
                @else
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-success" id="addSupplyRow">
                            <i class="fas fa-plus"></i> Agregar insumo
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="suppliesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width: 140px;">Cantidad</th>
                                    <th style="width: 120px;">Unidad</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="suppliesTableBody">
                                <tr class="supply-row">
                                    <td>
                                        <select class="form-select form-select-sm supply-name" name="supplies[0][name]" data-unit="">
                                            <option value="">Seleccione insumo...</option>
                                            @foreach($insumosOptions as $opt)
                                                <option value="{{ $opt->name }}" data-unit="{{ $opt->unit }}">{{ $opt->name }} ({{ $opt->unit }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm supply-qty" name="supplies[0][quantity]" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm supply-unit" name="supplies[0][unit]" readonly placeholder="unidad">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-supply-row" title="Quitar"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
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

    // Insumos a enviar: filas dinámicas
    const addSupplyRowBtn = document.getElementById('addSupplyRow');
    const tbody = document.getElementById('suppliesTableBody');
    if (addSupplyRowBtn && tbody) {
        const insumosOptions = @json($insumosOptions->isEmpty() ? [] : $insumosOptions->map(fn($i) => ['name' => $i->name, 'unit' => $i->unit])->values());
        let supplyRowIndex = 1;

        addSupplyRowBtn.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.className = 'supply-row';
            let opts = '<option value="">Seleccione insumo...</option>';
            insumosOptions.forEach(function(o) {
                opts += '<option value="' + (o.name || '').replace(/"/g, '&quot;') + '" data-unit="' + (o.unit || 'unidad') + '">' + (o.name || '') + ' (' + (o.unit || 'unidad') + ')</option>';
            });
            tr.innerHTML = '<td><select class="form-select form-select-sm supply-name" name="supplies[' + supplyRowIndex + '][name]" data-unit="">' + opts + '</select></td>' +
                '<td><input type="number" step="0.01" min="0.01" class="form-control form-control-sm supply-qty" name="supplies[' + supplyRowIndex + '][quantity]" placeholder="0"></td>' +
                '<td><input type="text" class="form-control form-control-sm supply-unit" name="supplies[' + supplyRowIndex + '][unit]" readonly placeholder="unidad"></td>' +
                '<td><button type="button" class="btn btn-sm btn-outline-danger remove-supply-row" title="Quitar"><i class="fas fa-trash"></i></button></td>';
            tbody.appendChild(tr);
            tr.querySelector('.supply-name').addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                const unit = opt && opt.getAttribute('data-unit');
                tr.querySelector('.supply-unit').value = unit || 'unidad';
            });
            supplyRowIndex++;
        });

        tbody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-supply-row')) {
                const row = e.target.closest('tr.supply-row');
                if (tbody.querySelectorAll('tr.supply-row').length > 1) row.remove();
            }
        });

        tbody.addEventListener('change', function(e) {
            if (e.target.classList.contains('supply-name')) {
                const opt = e.target.options[e.target.selectedIndex];
                const unit = opt && opt.getAttribute('data-unit');
                const row = e.target.closest('tr');
                if (row) row.querySelector('.supply-unit').value = unit || 'unidad';
            }
        });
    }
});
</script>
@endsection



