@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-clipboard-list"></i> Guía de Despacho - Contrato {{ $contract->contract_number ?? 'N/A' }}</h2>
                <a href="{{ route('documents.dispatch-guides.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Edita los datos antes de generar el PDF</strong><br>
        <small>Guía de Despacho que autoriza el despacho de mercancías desde el almacén o planta hacia el puerto de embarque.</small>
    </div>

    <form action="{{ route('documents.dispatch-guides.store', $contract->id) }}" method="POST">
        @csrf
        
        @include('documents.partials.contract_fields')

        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dispatch_date" class="form-label">Fecha de Despacho *</label>
                        <input type="date" class="form-control" id="dispatch_date" name="dispatch_date" value="{{ old('dispatch_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dispatch_number" class="form-label">Número de Guía *</label>
                        <input type="text" class="form-control" id="dispatch_number" name="dispatch_number" value="{{ old('dispatch_number', 'GD-' . $contract->contract_number) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="origin_location" class="form-label">Origen / Ubicación *</label>
                        <input type="text" class="form-control" id="origin_location" name="origin_location" value="{{ old('origin_location', $contract->seller_address ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Información del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="client_name" class="form-label">Cliente *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $contract->client->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="consignee" class="form-label">Consignatario</label>
                        <input type="text" class="form-control" id="consignee" name="consignee" value="{{ old('consignee', $contract->consignee_name) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Producto -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Información del Producto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto *</label>
                        <textarea class="form-control" id="product_description" name="product_description" rows="2" required>{{ old('product_description', $contract->product_description) }}</textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantity" class="form-label">Cantidad (kg) *</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $contract->stock_committed) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="packing" class="form-label">Empaque</label>
                        <input type="text" class="form-control" id="packing" name="packing" value="{{ old('packing', $contract->packing) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Destino -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Destino</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destino / Puerto *</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination', $contract->destination_port) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vessel" class="form-label">Buque / Vessel</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="{{ old('vessel', $contract->vessel_name) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="notes" class="form-label">Notas Adicionales</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <a href="{{ route('documents.dispatch-guides.list') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar y Generar PDF
            </button>
        </div>
    </form>
</div>
@endsection
