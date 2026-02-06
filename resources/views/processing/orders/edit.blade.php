@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-edit"></i> Editar Orden de Proceso: {{ $order->order_number }}</h2>
        </div>
    </div>

    <form action="{{ route('processing.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                                <option value="{{ $plant->id }}" {{ old('plant_id', $order->plant_id) == $plant->id ? 'selected' : '' }}>
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
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $order->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="order_number" class="form-label">N° Orden *</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="{{ old('order_number', $order->order_number) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="csg_code" class="form-label">CSG Code (Código de productor de SAG)</label>
                        <input type="text" class="form-control" id="csg_code" name="csg_code" value="{{ old('csg_code', $order->csg_code) }}" placeholder="Ej: ONI-08, COF-04">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="production_days" class="form-label">Tiempo de Producción (días)</label>
                        <input type="number" class="form-control" id="production_days" name="production_days" value="{{ old('production_days', $order->production_days) }}" min="1" placeholder="Ej: 5">
                        <small class="text-muted">Se calculará automáticamente la fecha de término esperada</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="order_date" class="form-label">Fecha de Orden *</label>
                        <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Semana / Año (término esperado)</label>
                        <div class="input-group">
                            <span class="input-group-text">Sem.</span>
                            @php
                                $editCompletionWeek = old('completion_week', $order->completion_week ?? ($order->expected_completion_date ? (int) $order->expected_completion_date->format('W') : null));
                                $editCompletionYear = old('completion_year', $order->completion_year ?? ($order->expected_completion_date ? (int) $order->expected_completion_date->format('o') : date('Y')));
                            @endphp
                            <select class="form-select" id="completion_week" name="completion_week" style="max-width: 90px;">
                                <option value="">-</option>
                                @for($w = 1; $w <= 56; $w++)
                                    <option value="{{ $w }}" {{ $editCompletionWeek == $w ? 'selected' : '' }}>{{ $w }}</option>
                                @endfor
                            </select>
                            <span class="input-group-text">Año</span>
                            <select class="form-select" id="completion_year" name="completion_year" style="max-width: 100px;">
                                <option value="">-</option>
                                @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                                    <option value="{{ $y }}" {{ $editCompletionYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted">Semana 1 a 56 del año</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="actual_completion_date" class="form-label">Fecha de Término Real</label>
                        <input type="date" class="form-control" id="actual_completion_date" name="actual_completion_date" value="{{ old('actual_completion_date', $order->actual_completion_date ? $order->actual_completion_date->format('Y-m-d') : '') }}">
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
                        <input type="text" class="form-control" id="raw_material" name="raw_material" value="{{ old('raw_material', $order->raw_material) }}" placeholder="Ej: 70/80 COFRUPA TEMP 2025">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="product" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ old('product', $order->product) }}" placeholder="Ej: TIERNIZADO">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $order->type) }}" placeholder="Ej: SIN CAROZO">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="caliber" class="form-label">Calibre</label>
                        <input type="text" class="form-control" id="caliber" name="caliber" value="{{ old('caliber', $order->caliber) }}" placeholder="Ej: EX 60/70">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $order->quantity) }}" min="0" placeholder="Ej: 21.500">
                        <small class="text-muted">KILOS</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kilos_sent" class="form-label">Kilos Enviados</label>
                        <input type="number" step="0.01" class="form-control" id="kilos_sent" name="kilos_sent" value="{{ old('kilos_sent', $order->kilos_sent) }}" min="0" placeholder="Ej: 21.500">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kilos_produced" class="form-label">Kilos Producidos (devueltos)</label>
                        <input type="number" step="0.01" class="form-control" id="kilos_produced" name="kilos_produced" value="{{ old('kilos_produced', $order->kilos_produced) }}" min="0" placeholder="Ej: 21.500">
                    </div>
                </div>
                <hr class="my-3">
                <h6 class="text-muted"><i class="fas fa-truck"></i> Envío a planta (petición de cupos)</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="vehicle_plate" class="form-label">Patente del camión</label>
                        <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" value="{{ old('vehicle_plate', $order->vehicle_plate) }}" placeholder="Ej: ABCD12" style="text-transform: uppercase;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="shipment_date" class="form-label">Fecha de envío</label>
                        <input type="date" class="form-control" id="shipment_date" name="shipment_date" value="{{ old('shipment_date', $order->shipment_date ? $order->shipment_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="shipment_time" class="form-label">Horario de envío</label>
                        <input type="time" class="form-control" id="shipment_time" name="shipment_time" value="{{ old('shipment_time', $order->shipment_time ? \Carbon\Carbon::parse($order->shipment_time)->format('H:i') : '') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quality" class="form-label">Calidad</label>
                        <input type="text" class="form-control" id="quality" name="quality" value="{{ old('quality', $order->quality) }}" placeholder="Ej: GRADO 1 USDA A CAT-1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="labeling" class="form-label">Etiquetado (texto)</label>
                        <input type="text" class="form-control" id="labeling" name="labeling" value="{{ old('labeling', $order->labeling) }}" placeholder="Ej: ADJUNTA">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="labeling_attachment" class="form-label">Etiquetado – Adjunto (imagen o PDF)</label>
                        <input type="file" class="form-control @error('labeling_attachment') is-invalid @enderror" id="labeling_attachment" name="labeling_attachment" accept="image/*,.pdf">
                        @error('labeling_attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Se mostrará en el PDF. Máx. 5 MB. Imagen (jpg, png, gif) o PDF.</small>
                        @if($order->labeling_attachment)
                            <div class="mt-2">
                                <p class="small mb-1">Adjunto actual:</p>
                                <div id="labelingPreview" class="border rounded p-2 bg-light">
                                    @php
                                        $ext = strtolower(pathinfo($order->labeling_attachment, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ Storage::url($order->labeling_attachment) }}" alt="Etiquetado" class="img-fluid" style="max-height: 180px;">
                                    @else
                                        <a href="{{ Storage::url($order->labeling_attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf"></i> Ver PDF adjunto</a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div id="labelingPreview" class="mt-2 d-none"></div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="packaging" class="form-label">Envases</label>
                        <input type="text" class="form-control" id="packaging" name="packaging" value="{{ old('packaging', $order->packaging) }}" placeholder="Ej: CAIAS 10 KLS COFRUPA">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="potassium_sorbate" class="form-label">Sorbato de potasio</label>
                        <input type="text" class="form-control" id="potassium_sorbate" name="potassium_sorbate" value="{{ old('potassium_sorbate', $order->potassium_sorbate) }}" placeholder="Ej: 800 PPM MAX">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="humidity" class="form-label">Humedad</label>
                        <input type="text" class="form-control" id="humidity" name="humidity" value="{{ old('humidity', $order->humidity) }}" placeholder="Ej: 30% +-1 máximo">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stone_percentage" class="form-label">% de Carozo</label>
                        <input type="text" class="form-control" id="stone_percentage" name="stone_percentage" value="{{ old('stone_percentage', $order->stone_percentage) }}" placeholder="Ej: 0,5% Máx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="oil" class="form-label">Aceite</label>
                        <input type="text" class="form-control" id="oil" name="oil" value="{{ old('oil', $order->oil) }}" placeholder="Ej: SIN ACEITE">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="damage" class="form-label">Daños</label>
                        <input type="text" class="form-control" id="damage" name="damage" value="{{ old('damage', $order->damage) }}" placeholder="Ej: 5,0 % MAXIMO">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="plant_print" class="form-label">Impresión Planta</label>
                        <input type="text" class="form-control" id="plant_print" name="plant_print" value="{{ old('plant_print', $order->plant_print) }}" placeholder="Ej: ADJUNTA">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="destination" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination', $order->destination) }}" placeholder="Ej: Turquia">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="loading_date" class="form-label">Fecha de carga</label>
                        <input type="text" class="form-control" id="loading_date" name="loading_date" value="{{ old('loading_date', $order->loading_date) }}" placeholder="Ej: Semana 18/19">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sag" class="form-label">SAG</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="sag" name="sag" value="1" {{ old('sag', $order->sag) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sag">Sí</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto (adicional)</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" value="{{ old('product_description', $order->product_description) }}" placeholder="Ej: NATURAL CONDITION, CONCENTRADO">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Estado y Progreso</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Estado *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ old('status', $order->status) == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="progress_percentage" class="form-label">Progreso (%)</label>
                        <input type="number" class="form-control" id="progress_percentage" name="progress_percentage" value="{{ old('progress_percentage', $order->progress_percentage) }}" min="0" max="100">
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar {{ $order->progress_percentage == 100 ? 'bg-success' : ($order->progress_percentage >= 50 ? 'bg-info' : 'bg-warning') }}" 
                                 role="progressbar" 
                                 style="width: {{ $order->progress_percentage }}%"
                                 aria-valuenow="{{ $order->progress_percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $order->progress_percentage }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos a enviar</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Insumos que se envían con esta orden (desde <a href="{{ route('supply-purchases.index') }}" target="_blank">Compras de Insumos</a>).</p>
                @if($insumosOptions->isEmpty())
                    <div class="alert alert-info">No hay insumos registrados. <a href="{{ route('supply-purchases.create') }}">Registre una compra de insumos</a> primero.</div>
                @else
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-success" id="addSupplyRowEdit">
                            <i class="fas fa-plus"></i> Agregar insumo
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="suppliesTableEdit">
                            <thead class="table-light">
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width: 140px;">Cantidad</th>
                                    <th style="width: 120px;">Unidad</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="suppliesTableBodyEdit">
                                @php
                                    $oldSupplies = old('supplies', $order->supplies->map(fn($s) => ['name' => $s->name, 'quantity' => $s->quantity, 'unit' => $s->unit])->toArray());
                                    if (empty($oldSupplies)) {
                                        $oldSupplies = [['name' => '', 'quantity' => '', 'unit' => 'unidad']];
                                    }
                                @endphp
                                @foreach($oldSupplies as $idx => $s)
                                <tr class="supply-row">
                                    <td>
                                        <select class="form-select form-select-sm supply-name" name="supplies[{{ $idx }}][name]">
                                            <option value="">Seleccione...</option>
                                            @foreach($insumosOptions as $opt)
                                                <option value="{{ $opt->name }}" data-unit="{{ $opt->unit }}" {{ ($s['name'] ?? '') == $opt->name ? 'selected' : '' }}>{{ $opt->name }} ({{ $opt->unit }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm supply-qty" name="supplies[{{ $idx }}][quantity]" value="{{ $s['quantity'] ?? '' }}" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm supply-unit" name="supplies[{{ $idx }}][unit]" value="{{ $s['unit'] ?? 'unidad' }}" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-supply-row" title="Quitar"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
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
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Orden
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
    const completionWeekSelect = document.getElementById('completion_week');
    const completionYearSelect = document.getElementById('completion_year');
    const progressPercentage = document.getElementById('progress_percentage');
    const progressBar = document.querySelector('.progress-bar');

    function setCompletionWeekYearFromProductionDays() {
        if (!orderDate.value || !productionDays.value || !completionWeekSelect || !completionYearSelect) return;
        var d = new Date(orderDate.value);
        d.setDate(d.getDate() + parseInt(productionDays.value));
        var iso = getISOWeek(d);
        if (iso.week <= 56) completionWeekSelect.value = iso.week;
        completionYearSelect.value = iso.year;
    }
    function getISOWeek(d) {
        d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
        var y = d.getUTCFullYear();
        var start = new Date(Date.UTC(y, 0, 1));
        var week = Math.ceil((((d - start) / 86400000) + 1) / 7);
        return { year: y, week: week };
    }

    function updateProgressBar() {
        if (progressPercentage.value) {
            progressBar.style.width = progressPercentage.value + '%';
            progressBar.setAttribute('aria-valuenow', progressPercentage.value);
            progressBar.textContent = progressPercentage.value + '%';
            
            // Cambiar color según progreso
            progressBar.className = 'progress-bar ' + 
                (progressPercentage.value == 100 ? 'bg-success' : 
                 (progressPercentage.value >= 50 ? 'bg-info' : 'bg-warning'));
        }
    }

    orderDate.addEventListener('change', setCompletionWeekYearFromProductionDays);
    productionDays.addEventListener('input', setCompletionWeekYearFromProductionDays);
    progressPercentage.addEventListener('input', updateProgressBar);

    var labelingInput = document.getElementById('labeling_attachment');
    var labelingPreview = document.getElementById('labelingPreview');
    if (labelingInput && labelingPreview) {
        labelingInput.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) {
                labelingPreview.classList.add('d-none');
                labelingPreview.innerHTML = '';
                return;
            }
            labelingPreview.classList.remove('d-none');
            var ext = (file.name.split('.').pop() || '').toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].indexOf(ext) !== -1) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    labelingPreview.innerHTML = '<p class="small mb-1">Vista previa (nuevo):</p><img src="' + e.target.result + '" class="img-fluid" style="max-height: 180px;" alt="Vista previa">';
                };
                reader.readAsDataURL(file);
            } else {
                labelingPreview.innerHTML = '<p class="small mb-0"><i class="fas fa-file-pdf"></i> PDF seleccionado: ' + file.name + '</p>';
            }
        });
    }

    var tbodyEdit = document.getElementById('suppliesTableBodyEdit');
    var addBtnEdit = document.getElementById('addSupplyRowEdit');
    var insumosOptionsEdit = @json($insumosOptions->isEmpty() ? [] : $insumosOptions->map(fn($i) => ['name' => $i->name, 'unit' => $i->unit])->values());
    if (tbodyEdit && addBtnEdit && insumosOptionsEdit.length) {
        addBtnEdit.addEventListener('click', function() {
            var idx = tbodyEdit.querySelectorAll('tr.supply-row').length;
            var opts = '<option value="">Seleccione...</option>';
            insumosOptionsEdit.forEach(function(o) {
                opts += '<option value="' + (o.name || '').replace(/"/g, '&quot;') + '" data-unit="' + (o.unit || 'unidad') + '">' + (o.name || '') + ' (' + (o.unit || 'unidad') + ')</option>';
            });
            var tr = document.createElement('tr');
            tr.className = 'supply-row';
            tr.innerHTML = '<td><select class="form-select form-select-sm supply-name" name="supplies[' + idx + '][name]">' + opts + '</select></td>' +
                '<td><input type="number" step="0.01" min="0.01" class="form-control form-control-sm supply-qty" name="supplies[' + idx + '][quantity]" placeholder="0"></td>' +
                '<td><input type="text" class="form-control form-control-sm supply-unit" name="supplies[' + idx + '][unit]" readonly placeholder="unidad"></td>' +
                '<td><button type="button" class="btn btn-sm btn-outline-danger remove-supply-row" title="Quitar"><i class="fas fa-trash"></i></button></td>';
            tbodyEdit.appendChild(tr);
        });
        tbodyEdit.addEventListener('click', function(e) {
            if (e.target.closest('.remove-supply-row') && tbodyEdit.querySelectorAll('tr.supply-row').length > 1) {
                e.target.closest('tr.supply-row').remove();
            }
        });
        tbodyEdit.addEventListener('change', function(e) {
            if (e.target.classList.contains('supply-name')) {
                var opt = e.target.options[e.target.selectedIndex];
                var row = e.target.closest('tr');
                if (row && opt) row.querySelector('.supply-unit').value = opt.getAttribute('data-unit') || 'unidad';
            }
        });
    }
});
</script>
@endsection



