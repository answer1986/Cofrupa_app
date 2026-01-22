@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-clipboard-list"></i> Nueva Orden de Producción</h2>
        </div>
    </div>

    <form action="{{ route('processing.production-orders.store') }}" method="POST" id="productionOrderForm">
        @csrf
        
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
                                <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
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
                                <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}
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
                        <label for="product" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ old('product') }}" placeholder="Ej: NATURAL CONDITION, CONCENTRADO">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="output_caliber" class="form-label">Calibre Salida / Tipo</label>
                        <input type="text" class="form-control" id="output_caliber" name="output_caliber" value="{{ old('output_caliber') }}" placeholder="Ej: 50-60, EX 70-80, LOTE 1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="order_quantity_kg" class="form-label">Cantidad (kilos) *</label>
                        <input type="number" step="0.01" class="form-control" id="order_quantity_kg" name="order_quantity_kg" value="{{ old('order_quantity_kg') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="booking_number" class="form-label">Número de Reserva</label>
                        <input type="text" class="form-control" id="booking_number" name="booking_number" value="{{ old('booking_number') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vessel" class="form-label">Motonave</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="{{ old('vessel') }}">
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
                        <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ old('entry_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="entry_time" class="form-label">Hora de Ingreso</label>
                        <input type="time" class="form-control" id="entry_time" name="entry_time" value="{{ old('entry_time') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="completion_date" class="form-label">Fecha de Término</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date" value="{{ old('completion_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="completion_time" class="form-label">Hora de Término</label>
                        <input type="time" class="form-control" id="completion_time" name="completion_time" value="{{ old('completion_time') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nominal_kg_per_hour" class="form-label">KG/Hora Nominal</label>
                        <input type="number" step="0.01" class="form-control" id="nominal_kg_per_hour" name="nominal_kg_per_hour" value="{{ old('nominal_kg_per_hour') }}">
                        <small class="text-muted">Se usa para calcular horas estimadas automáticamente</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="produced_kilos" class="form-label">Kilos Producidos</label>
                        <input type="number" step="0.01" class="form-control" id="produced_kilos" name="produced_kilos" value="{{ old('produced_kilos') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="discard_kg" class="form-label">Descarte (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="discard_kg" name="discard_kg" value="{{ old('discard_kg', 0) }}" min="0">
                        <small class="text-muted">Material no utilizado en producción</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discard_reason" class="form-label">Razón del Descarte</label>
                        <input type="text" class="form-control" id="discard_reason" name="discard_reason" value="{{ old('discard_reason') }}" placeholder="Ej: Fruta dañada, calibre incorrecto">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discard_status" class="form-label">Estado del Descarte</label>
                        <select class="form-control" id="discard_status" name="discard_status">
                            <option value="pending" {{ old('discard_status', 'pending') == 'pending' ? 'selected' : '' }}>Pendiente Recuperación</option>
                            <option value="recovered" {{ old('discard_status') == 'recovered' ? 'selected' : '' }}>Recuperado</option>
                            <option value="disposed" {{ old('discard_status') == 'disposed' ? 'selected' : '' }}>Desechado</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Cálculo Automático:</strong> 
                    Al ingresar la fecha y hora de término, se calcularán automáticamente:
                    <ul class="mb-0">
                        <li>Horas Estimadas = Cantidad (kg) / KG/Hora Nominal</li>
                        <li>Horas Reales = Diferencia entre ingreso y término</li>
                        <li>Atraso = Horas Reales - Horas Estimadas</li>
                    </ul>
                </div>
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
                        <input type="text" class="form-control" id="production_program" name="production_program" value="{{ old('production_program') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sorbate_solution" class="form-label">Solución Sorbato</label>
                        <input type="number" step="0.01" class="form-control" id="sorbate_solution" name="sorbate_solution" value="{{ old('sorbate_solution') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="delay_reason" class="form-label">Razón del Atraso</label>
                        <textarea class="form-control" id="delay_reason" name="delay_reason" rows="3" placeholder="Ej: Corte de energía, Problema pesa packing, Fruta dura...">{{ old('delay_reason') }}</textarea>
                    </div>
                </div>

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
                <a href="{{ route('processing.production-orders.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productionOrderForm');
    const orderQuantity = document.getElementById('order_quantity_kg');
    const nominalKgPerHour = document.getElementById('nominal_kg_per_hour');
    const entryDate = document.getElementById('entry_date');
    const entryTime = document.getElementById('entry_time');
    const completionDate = document.getElementById('completion_date');
    const completionTime = document.getElementById('completion_time');

    // Calcular horas estimadas cuando cambian cantidad o kg/hora nominal
    function calculateEstimatedHours() {
        if (orderQuantity.value && nominalKgPerHour.value) {
            const estimated = parseFloat(orderQuantity.value) / parseFloat(nominalKgPerHour.value);
            // Mostrar en un campo readonly si existe
            const estimatedField = document.getElementById('estimated_hours_display');
            if (estimatedField) {
                estimatedField.value = estimated.toFixed(2);
            }
        }
    }

    orderQuantity.addEventListener('input', calculateEstimatedHours);
    nominalKgPerHour.addEventListener('input', calculateEstimatedHours);

    // Calcular atraso cuando cambian fechas/horas
    function calculateDelay() {
        if (entryDate.value && entryTime.value && completionDate.value && completionTime.value) {
            const entry = new Date(entryDate.value + 'T' + entryTime.value);
            const completion = new Date(completionDate.value + 'T' + completionTime.value);
            const diffHours = (completion - entry) / (1000 * 60 * 60);
            
            if (diffHours > 0) {
                const delayField = document.getElementById('delay_hours_display');
                if (delayField) {
                    delayField.value = diffHours.toFixed(2);
                }
            }
        }
    }

    entryDate.addEventListener('change', calculateDelay);
    entryTime.addEventListener('change', calculateDelay);
    completionDate.addEventListener('change', calculateDelay);
    completionTime.addEventListener('change', calculateDelay);
});
</script>
@endsection

