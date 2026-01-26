@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-cog"></i> Nueva Máquina</h2>
        </div>
    </div>

    <form action="{{ route('processing.machines.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name">Nombre *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code">Código *</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="type">Tipo</label>
                        <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" placeholder="Ej: Calibradora">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="brand">Marca</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="model">Modelo</label>
                        <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serial_number">Número de Serie</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="purchase_date">Fecha de Compra</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status">Estado *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Activa</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactiva</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En Mantención</option>
                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retirada</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('processing.machines.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
