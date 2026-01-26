@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-tools"></i> Registrar Mantención</h2>
        </div>
    </div>

    <form action="{{ route('processing.maintenances.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="machine_id">Máquina *</label>
                        <select class="form-control" id="machine_id" name="machine_id" required>
                            <option value="">Seleccione una máquina</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                                    {{ $machine->name }} ({{ $machine->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('machine_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="maintenance_date">Fecha de Mantención *</label>
                        <input type="date" class="form-control" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', date('Y-m-d')) }}" required>
                        @error('maintenance_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="maintenance_type">Tipo de Mantención *</label>
                        <select class="form-control" id="maintenance_type" name="maintenance_type" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="preventive" {{ old('maintenance_type') == 'preventive' ? 'selected' : '' }}>Preventiva</option>
                            <option value="corrective" {{ old('maintenance_type') == 'corrective' ? 'selected' : '' }}>Correctiva</option>
                            <option value="predictive" {{ old('maintenance_type') == 'predictive' ? 'selected' : '' }}>Predictiva</option>
                            <option value="emergency" {{ old('maintenance_type') == 'emergency' ? 'selected' : '' }}>Emergencia</option>
                        </select>
                        @error('maintenance_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="periodicity">Periodicidad *</label>
                        <select class="form-control" id="periodicity" name="periodicity" required>
                            <option value="">Seleccione periodicidad</option>
                            <option value="daily" {{ old('periodicity') == 'daily' ? 'selected' : '' }}>Diaria</option>
                            <option value="weekly" {{ old('periodicity') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ old('periodicity') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="quarterly" {{ old('periodicity') == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                            <option value="biannual" {{ old('periodicity') == 'biannual' ? 'selected' : '' }}>Semestral</option>
                            <option value="annual" {{ old('periodicity') == 'annual' ? 'selected' : '' }}>Anual</option>
                            <option value="as_needed" {{ old('periodicity') == 'as_needed' ? 'selected' : '' }}>Según necesidad</option>
                        </select>
                        @error('periodicity')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="next_maintenance_date">Próxima Mantención</label>
                        <input type="date" class="form-control" id="next_maintenance_date" name="next_maintenance_date" value="{{ old('next_maintenance_date') }}">
                        @error('next_maintenance_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="technician">Técnico</label>
                        <input type="text" class="form-control" id="technician" name="technician" value="{{ old('technician') }}" placeholder="Nombre del técnico">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cost">Costo</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="{{ old('cost') }}" placeholder="0.00">
                        @error('cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description">Descripción del Trabajo Realizado</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="observations">Observaciones</label>
                        <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar Mantención</button>
                <a href="{{ route('processing.maintenances.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
