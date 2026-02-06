@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck-loading"></i> Nuevo Despacho a Planta</h2>
                <a href="{{ route('plant-shipments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('plant-shipments.store') }}" method="POST">
        @csrf

        <!-- Información del Despacho -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información del Despacho</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="plant_id" class="form-label">Planta Destino *</label>
                        <select class="form-control @error('plant_id') is-invalid @enderror" id="plant_id" name="plant_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->code }} - {{ $plant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('plant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="process_order_id" class="form-label">Orden de Proceso</label>
                        <select class="form-control" id="process_order_id" name="process_order_id">
                            <option value="">Seleccione...</option>
                            @foreach($processOrders as $order)
                                <option value="{{ $order->id }}" {{ old('process_order_id') == $order->id ? 'selected' : '' }}>
                                    #{{ $order->order_number }} - {{ $order->product_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="destination" class="form-label">Lugar/Destino *</label>
                        <input type="text" class="form-control @error('destination') is-invalid @enderror" id="destination" name="destination" value="{{ old('destination') }}" required placeholder="Ej: Planta Curicó">
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="guide_number" class="form-label">Número de Guía *</label>
                        <input type="text" class="form-control @error('guide_number') is-invalid @enderror" id="guide_number" name="guide_number" value="{{ old('guide_number') }}" required placeholder="Ej: G-2026-001">
                        @error('guide_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="shipment_date" class="form-label">Fecha de Despacho *</label>
                        <input type="date" class="form-control @error('shipment_date') is-invalid @enderror" id="shipment_date" name="shipment_date" value="{{ old('shipment_date', date('Y-m-d')) }}" required>
                        @error('shipment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="driver_name" class="form-label">Chofer *</label>
                        <input type="text" class="form-control @error('driver_name') is-invalid @enderror" id="driver_name" name="driver_name" value="{{ old('driver_name') }}" required placeholder="Nombre del chofer">
                        @error('driver_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="vehicle_plate" class="form-label">Patente *</label>
                        <input type="text" class="form-control @error('vehicle_plate') is-invalid @enderror" id="vehicle_plate" name="vehicle_plate" value="{{ old('vehicle_plate') }}" required placeholder="Ej: ABCD12">
                        @error('vehicle_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="bin_type" class="form-label">Tipo de Bins</label>
                        <input type="text" class="form-control" id="bin_type" name="bin_type" value="{{ old('bin_type') }}" placeholder="Ej: Plástico, Madera">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="shipment_cost" class="form-label">Valor/Costo</label>
                        <input type="number" step="0.01" class="form-control" id="shipment_cost" name="shipment_cost" value="{{ old('shipment_cost') }}" min="0" placeholder="0.00">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="payment_status" class="form-label">Estado de Pago *</label>
                        <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="unpaid" {{ old('payment_status', 'unpaid') == 'unpaid' ? 'selected' : '' }}>No Pagado</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_kilos_display" class="form-label">Total Kilos</label>
                        <input type="text" class="form-control bg-light" id="total_kilos_display" readonly value="0.00 kg">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Observaciones adicionales">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selección de Bins a Despachar -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Bins a Despachar (Rebajar Stock)</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Seleccione los bins procesados que desea despachar. El stock se rebajará automáticamente.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="binsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">Sel.</th>
                                <th>Número de Bin</th>
                                <th>Tarja</th>
                                <th>Calibre</th>
                                <th>Stock Disponible (kg)</th>
                                <th width="15%">Kilos a Enviar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableBins as $bin)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input bin-checkbox" data-bin-id="{{ $bin->id }}" data-available="{{ $bin->available_kg }}">
                                    </td>
                                    <td>{{ $bin->current_bin_number }}</td>
                                    <td>{{ $bin->tarja_number ?? '-' }}</td>
                                    <td>{{ $bin->calibre ?? '-' }}</td>
                                    <td><strong>{{ number_format($bin->available_kg, 2) }} kg</strong></td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control bin-kilos" data-bin-id="{{ $bin->id }}" min="0" max="{{ $bin->available_kg }}" placeholder="0.00" disabled>
                                        <input type="hidden" name="bins[{{ $bin->id }}][id]" value="{{ $bin->id }}" disabled class="bin-hidden-id">
                                        <input type="hidden" name="bins[{{ $bin->id }}][kilos]" class="bin-hidden-kilos" disabled>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No hay bins con stock disponible para despachar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Crear Despacho
                </button>
                <a href="{{ route('plant-shipments.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.bin-checkbox');
    const totalDisplay = document.getElementById('total_kilos_display');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const binId = this.dataset.binId;
            const kilosInput = document.querySelector(`.bin-kilos[data-bin-id="${binId}"]`);
            const hiddenId = kilosInput.parentElement.querySelector('.bin-hidden-id');
            const hiddenKilos = kilosInput.parentElement.querySelector('.bin-hidden-kilos');

            if (this.checked) {
                kilosInput.disabled = false;
                hiddenId.disabled = false;
                hiddenKilos.disabled = false;
                kilosInput.value = this.dataset.available;
                hiddenKilos.value = this.dataset.available;
            } else {
                kilosInput.disabled = true;
                hiddenId.disabled = true;
                hiddenKilos.disabled = true;
                kilosInput.value = '';
                hiddenKilos.value = '';
            }
            calculateTotal();
        });
    });

    document.querySelectorAll('.bin-kilos').forEach(input => {
        input.addEventListener('input', function() {
            const hiddenKilos = this.parentElement.querySelector('.bin-hidden-kilos');
            hiddenKilos.value = this.value;
            calculateTotal();
        });
    });

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.bin-kilos:not([disabled])').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        totalDisplay.value = total.toFixed(2) + ' kg';
    }
});
</script>
@endsection
