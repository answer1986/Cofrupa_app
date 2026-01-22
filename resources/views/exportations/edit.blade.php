@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-folder-open"></i> Editar Exportación #{{ $exportation->export_number }}</h2>
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
                <form action="{{ route('exportations.update', $exportation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                        {{ old('shipment_id', $exportation->shipment_id) == $shipment->id ? 'selected' : '' }}>
                                    {{ $shipment->shipment_number }} - {{ $shipment->contract->client->name }} ({{ $shipment->scheduled_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('shipment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contract_info" class="form-label">Contrato Asociado</label>
                        <div class="card bg-light" id="contract_info_display">
                            <div class="card-body">
                                <h6 class="mb-2"><i class="fas fa-file-contract"></i> Información del Contrato</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">N° Contrato:</dt>
                                    <dd class="col-sm-8"><strong>{{ $exportation->contract->contract_number ?? 'N/A' }}</strong></dd>
                                    <dt class="col-sm-4">Cliente:</dt>
                                    <dd class="col-sm-8">{{ $exportation->contract->client->name }}</dd>
                                    <dt class="col-sm-4">Stock Comprometido:</dt>
                                    <dd class="col-sm-8">{{ number_format($exportation->contract->stock_committed, 2) }} kg</dd>
                                </dl>
                            </div>
                        </div>
                        <input type="hidden" id="contract_id_hidden" name="contract_id" value="{{ $exportation->contract_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="preparation" {{ old('status', $exportation->status) === 'preparation' ? 'selected' : '' }}>En Preparación</option>
                            <option value="in_progress" {{ old('status', $exportation->status) === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completed" {{ old('status', $exportation->status) === 'completed' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelled" {{ old('status', $exportation->status) === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="export_date" class="form-label">Fecha de Exportación</label>
                        <input type="date" class="form-control @error('export_date') is-invalid @enderror"
                               id="export_date" name="export_date" value="{{ old('export_date', $exportation->export_date ? $exportation->export_date->format('Y-m-d') : '') }}">
                        @error('export_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $exportation->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Nota:</strong> El número de exportación ({{ $exportation->export_number }}) no puede ser modificado.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('exportations.index') }}" class="btn btn-secondary">Cancelar</a>
                        <a href="{{ route('exportations.show', $exportation->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                        <button type="submit" class="btn btn-success">Actualizar Exportación</button>
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

    // Trigger change si hay un valor seleccionado
    if (shipmentSelect.value) {
        shipmentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection



