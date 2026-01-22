@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-folder-open"></i> Crear Nueva Exportación</h2>
            <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-folder-open"></i> Información de la Exportación</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('exportations.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="shipment_id" class="form-label">Despacho *</label>
                        <select class="form-select @error('shipment_id') is-invalid @enderror" id="shipment_id" name="shipment_id" required>
                            <option value="">Seleccione un despacho</option>
                            @foreach($shipments as $shipment)
                                <option value="{{ $shipment->id }}" 
                                        data-contract-id="{{ $shipment->contract_id }}"
                                        data-contract-number="{{ $shipment->contract->contract_number ?? 'N/A' }}"
                                        data-client-name="{{ $shipment->contract->client->name }}"
                                        data-stock-committed="{{ $shipment->contract->stock_committed }}"
                                        {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                    {{ $shipment->shipment_number }} - {{ $shipment->contract->client->name }} ({{ $shipment->scheduled_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @if(count($shipments) === 0)
                            <small class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay despachos disponibles. 
                                <a href="{{ route('shipments.create') }}" target="_blank">Crear un despacho primero</a>
                            </small>
                        @endif
                        @error('shipment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contract_info" class="form-label">Contrato Asociado</label>
                        <div class="card bg-light" id="contract_info_display">
                            <div class="card-body">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle"></i> Seleccione un despacho para ver el contrato asociado
                                </p>
                            </div>
                        </div>
                        <input type="hidden" id="contract_id_hidden" name="contract_id" value="">
                    </div>

                    <div class="mb-3">
                        <label for="export_date" class="form-label">Fecha de Exportación</label>
                        <input type="date" class="form-control @error('export_date') is-invalid @enderror"
                               id="export_date" name="export_date" value="{{ old('export_date') }}">
                        @error('export_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Se creará automáticamente una carpeta digital para almacenar todos los documentos de esta exportación.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('exportations.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Crear Exportación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shipmentSelect = document.getElementById('shipment_id');
    const contractInfoDisplay = document.getElementById('contract_info_display');
    const contractIdHidden = document.getElementById('contract_id_hidden');

    shipmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const contractId = selectedOption.getAttribute('data-contract-id');
            const contractNumber = selectedOption.getAttribute('data-contract-number');
            const clientName = selectedOption.getAttribute('data-client-name');
            const stockCommitted = parseFloat(selectedOption.getAttribute('data-stock-committed')).toLocaleString('es-CL', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            contractInfoDisplay.innerHTML = `
                <div class="card-body">
                    <h6 class="mb-2"><i class="fas fa-file-contract"></i> Información del Contrato</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">N° Contrato:</dt>
                        <dd class="col-sm-8"><strong>${contractNumber}</strong></dd>
                        <dt class="col-sm-4">Cliente:</dt>
                        <dd class="col-sm-8">${clientName}</dd>
                        <dt class="col-sm-4">Stock Comprometido:</dt>
                        <dd class="col-sm-8">${stockCommitted} kg</dd>
                    </dl>
                </div>
            `;
            contractIdHidden.value = contractId;
        } else {
            contractInfoDisplay.innerHTML = `
                <div class="card-body">
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle"></i> Seleccione un despacho para ver el contrato asociado
                    </p>
                </div>
            `;
            contractIdHidden.value = '';
        }
    });

    // Trigger change si hay un valor seleccionado (old input)
    if (shipmentSelect.value) {
        shipmentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection


