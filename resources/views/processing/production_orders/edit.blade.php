@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-edit"></i> Editar Orden de Producción: {{ $productionOrder->order_number }}</h2>
        </div>
    </div>

    <form action="{{ route('processing.production-orders.update', $productionOrder->id) }}" method="POST" id="productionOrderForm">
        @csrf
        @method('PUT')
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="contract_id" class="form-label">Contrato</label>
                        <select class="form-control" id="contract_id" name="contract_id">
                            <option value="">Seleccione...</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ old('contract_id', $productionOrder->contract_id) == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_number }} - {{ $contract->client->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="plant_id" class="form-label">Planta de Proceso *</label>
                        <select class="form-control" id="plant_id" name="plant_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant->id }}" {{ old('plant_id', $productionOrder->plant_id) == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="order_number" class="form-label">N° Orden *</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="{{ old('order_number', $productionOrder->order_number) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ old('product', $productionOrder->product) }}" placeholder="Ej: NATURAL CONDITION, CONCENTRADO">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="output_caliber" class="form-label">Calibre Salida / Tipo</label>
                        <input type="text" class="form-control" id="output_caliber" name="output_caliber" value="{{ old('output_caliber', $productionOrder->output_caliber) }}" placeholder="Ej: 50-60, EX 70-80, LOTE 1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="order_quantity_kg" class="form-label">Cantidad (kilos) *</label>
                        <input type="number" step="0.01" class="form-control" id="order_quantity_kg" name="order_quantity_kg" value="{{ old('order_quantity_kg', $productionOrder->order_quantity_kg) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="booking_number" class="form-label">Número de Reserva</label>
                        <input type="text" class="form-control" id="booking_number" name="booking_number" value="{{ old('booking_number', $productionOrder->booking_number) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vessel" class="form-label">Motonave</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="{{ old('vessel', $productionOrder->vessel) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Control de Tiempos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="entry_date" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ old('entry_date', $productionOrder->entry_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="entry_time" class="form-label">Hora de Ingreso</label>
                        <input type="time" class="form-control" id="entry_time" name="entry_time" value="{{ old('entry_time', $productionOrder->entry_time ? \Carbon\Carbon::parse($productionOrder->entry_time)->format('H:i') : '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="completion_date" class="form-label">Fecha de Término</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date" value="{{ old('completion_date', $productionOrder->completion_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="completion_time" class="form-label">Hora de Término</label>
                        <input type="time" class="form-control" id="completion_time" name="completion_time" value="{{ old('completion_time', $productionOrder->completion_time ? \Carbon\Carbon::parse($productionOrder->completion_time)->format('H:i') : '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nominal_kg_per_hour" class="form-label">KG/Hora Nominal</label>
                        <input type="number" step="0.01" class="form-control" id="nominal_kg_per_hour" name="nominal_kg_per_hour" value="{{ old('nominal_kg_per_hour', $productionOrder->nominal_kg_per_hour) }}">
                        <small class="text-muted">Se usa para calcular horas estimadas automáticamente</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="produced_kilos" class="form-label">Kilos Producidos</label>
                        <input type="number" step="0.01" class="form-control" id="produced_kilos" name="produced_kilos" value="{{ old('produced_kilos', $productionOrder->produced_kilos) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="discard_kg" class="form-label">Descarte (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="discard_kg" name="discard_kg" value="{{ old('discard_kg', $productionOrder->discard_kg ?? 0) }}" min="0">
                        <small class="text-muted">Material no utilizado en producción</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discard_reason" class="form-label">Razón del Descarte</label>
                        <input type="text" class="form-control" id="discard_reason" name="discard_reason" value="{{ old('discard_reason', $productionOrder->discard_reason) }}" placeholder="Ej: Fruta dañada, calibre incorrecto">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discard_status" class="form-label">Estado del Descarte</label>
                        <select class="form-control" id="discard_status" name="discard_status">
                            <option value="pending" {{ old('discard_status', $productionOrder->discard_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pendiente Recuperación</option>
                            <option value="recovered" {{ old('discard_status', $productionOrder->discard_status) == 'recovered' ? 'selected' : '' }}>Recuperado</option>
                            <option value="disposed" {{ old('discard_status', $productionOrder->discard_status) == 'disposed' ? 'selected' : '' }}>Desechado</option>
                        </select>
                    </div>
                </div>

                @if($productionOrder->estimated_hours || $productionOrder->actual_hours || $productionOrder->delay_hours)
                    <div class="alert alert-info">
                        <strong>Cálculos Actuales:</strong>
                        <ul class="mb-0">
                            <li>Horas Estimadas: {{ $productionOrder->estimated_hours ? number_format($productionOrder->estimated_hours, 2, ',', '.') : 'N/A' }}</li>
                            <li>Horas Reales: {{ $productionOrder->actual_hours ? number_format($productionOrder->actual_hours, 2, ',', '.') : 'N/A' }}</li>
                            <li>Atraso: <span class="{{ $productionOrder->delay_hours > 0 ? 'text-danger fw-bold' : '' }}">{{ $productionOrder->delay_hours ? number_format($productionOrder->delay_hours, 2, ',', '.') : '0' }} horas</span></li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Información de Producción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="production_program" class="form-label">Programa de Producción</label>
                        <input type="text" class="form-control" id="production_program" name="production_program" value="{{ old('production_program', $productionOrder->production_program) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sorbate_solution" class="form-label">Solución Sorbato</label>
                        <input type="number" step="0.01" class="form-control" id="sorbate_solution" name="sorbate_solution" value="{{ old('sorbate_solution', $productionOrder->sorbate_solution) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="delay_reason" class="form-label">Razón del Atraso</label>
                        <textarea class="form-control" id="delay_reason" name="delay_reason" rows="3" placeholder="Ej: Corte de energía, Problema pesa packing, Fruta dura...">{{ old('delay_reason', $productionOrder->delay_reason) }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Estado *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending" {{ old('status', $productionOrder->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ old('status', $productionOrder->status) == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completed" {{ old('status', $productionOrder->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="delayed" {{ old('status', $productionOrder->status) == 'delayed' ? 'selected' : '' }}>Retrasado</option>
                            <option value="cancelled" {{ old('status', $productionOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $productionOrder->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Orden
                </button>
                <a href="{{ route('processing.production-orders.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderQuantity = document.getElementById('order_quantity_kg');
    const nominalKgPerHour = document.getElementById('nominal_kg_per_hour');
    const entryDate = document.getElementById('entry_date');
    const entryTime = document.getElementById('entry_time');
    const completionDate = document.getElementById('completion_date');
    const completionTime = document.getElementById('completion_time');

    function calculateEstimatedHours() {
        if (orderQuantity.value && nominalKgPerHour.value) {
            const estimated = parseFloat(orderQuantity.value) / parseFloat(nominalKgPerHour.value);
            console.log('Horas estimadas:', estimated.toFixed(2));
        }
    }

    orderQuantity.addEventListener('input', calculateEstimatedHours);
    nominalKgPerHour.addEventListener('input', calculateEstimatedHours);
});
</script>
@endsection

