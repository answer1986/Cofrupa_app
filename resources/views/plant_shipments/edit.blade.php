@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-edit"></i> Editar Despacho</h2>
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

    <form action="{{ route('plant-shipments.update', $plantShipment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Editar Información del Despacho</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No se pueden modificar los bins despachados. Para cambiar bins, debe eliminar este despacho y crear uno nuevo.
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Planta Destino</label>
                        <input type="text" class="form-control bg-light" value="{{ $plantShipment->plant->name }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número de Guía</label>
                        <input type="text" class="form-control bg-light" value="{{ $plantShipment->guide_number }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="destination" class="form-label">Lugar/Destino *</label>
                        <input type="text" class="form-control @error('destination') is-invalid @enderror" id="destination" name="destination" value="{{ old('destination', $plantShipment->destination) }}" required>
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="shipment_date" class="form-label">Fecha de Despacho *</label>
                        <input type="date" class="form-control @error('shipment_date') is-invalid @enderror" id="shipment_date" name="shipment_date" value="{{ old('shipment_date', $plantShipment->shipment_date->format('Y-m-d')) }}" required>
                        @error('shipment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="driver_name" class="form-label">Chofer *</label>
                        <input type="text" class="form-control @error('driver_name') is-invalid @enderror" id="driver_name" name="driver_name" value="{{ old('driver_name', $plantShipment->driver_name) }}" required>
                        @error('driver_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="vehicle_plate" class="form-label">Patente *</label>
                        <input type="text" class="form-control @error('vehicle_plate') is-invalid @enderror" id="vehicle_plate" name="vehicle_plate" value="{{ old('vehicle_plate', $plantShipment->vehicle_plate) }}" required>
                        @error('vehicle_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Total Kilos</label>
                        <input type="text" class="form-control bg-light" value="{{ number_format($plantShipment->total_kilos, 2) }} kg" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="shipment_cost" class="form-label">Valor/Costo</label>
                        <input type="number" step="0.01" class="form-control" id="shipment_cost" name="shipment_cost" value="{{ old('shipment_cost', $plantShipment->shipment_cost) }}" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="payment_status" class="form-label">Estado de Pago *</label>
                        <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="unpaid" {{ old('payment_status', $plantShipment->payment_status) == 'unpaid' ? 'selected' : '' }}>No Pagado</option>
                            <option value="paid" {{ old('payment_status', $plantShipment->payment_status) == 'paid' ? 'selected' : '' }}>Pagado</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $plantShipment->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Bins Despachados (No Modificable)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Número de Bin</th>
                                <th>Tarja</th>
                                <th>Calibre</th>
                                <th>Kilos Enviados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plantShipment->bins as $bin)
                                <tr>
                                    <td>{{ $bin->current_bin_number }}</td>
                                    <td>{{ $bin->tarja_number ?? '-' }}</td>
                                    <td>{{ $bin->calibre ?? '-' }}</td>
                                    <td>{{ number_format($bin->pivot->kilos_sent, 2) }} kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Actualizar Despacho
                </button>
                <a href="{{ route('plant-shipments.show', $plantShipment) }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
