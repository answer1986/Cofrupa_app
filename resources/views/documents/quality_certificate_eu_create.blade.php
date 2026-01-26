@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-certificate"></i> Crear Certificado de Calidad UE</h2>
                <a href="{{ route('documents.quality-certificate-eu.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona el contrato y completa los datos del certificado</strong><br>
        <small>Certificado de Calidad para Unión Europea - Los campos se rellenarán con los datos del contrato.</small>
    </div>

    <form id="certificateForm" action="#" method="POST">
        @csrf
        
        <!-- Selector de Contrato -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Seleccionar Contrato</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="contract_id" class="form-label">Contrato *</label>
                    <select class="form-control" id="contract_id" name="contract_id" required>
                        <option value="">-- Selecciona un contrato --</option>
                        @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}" 
                                    data-client="{{ $contract->client->name }}"
                                    data-product="{{ $contract->product_description }}"
                                    data-packing="{{ $contract->packing }}"
                                    data-quantity="{{ $contract->stock_committed }}"
                                    data-contract-number="{{ $contract->contract_number }}"
                                    data-vessel="{{ $contract->vessel_name }}"
                                    data-booking="{{ $contract->booking_number }}"
                                    data-container="{{ $contract->container_number }}"
                                    data-destination="{{ $contract->destination_port }}">
                                {{ $contract->contract_number }} - {{ $contract->client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="emission_date" class="form-label">Fecha de Emisión *</label>
                        <input type="date" class="form-control" id="emission_date" name="emission_date" value="{{ $certificate['emission_date'] }}" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="client_name" class="form-label">Cliente *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $certificate['client_name'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="product" class="form-label">Producto *</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ $certificate['product'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="size" class="form-label">Tamaño *</label>
                        <input type="text" class="form-control" id="size" name="size" value="{{ $certificate['size'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Cantidad *</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $certificate['quantity'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contract_number" class="form-label">Número de Contrato *</label>
                        <input type="text" class="form-control" id="contract_number" name="contract_number" value="{{ $certificate['contract_number'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="invoice_nr" class="form-label">Número de Factura *</label>
                        <input type="text" class="form-control" id="invoice_nr" name="invoice_nr" value="{{ $certificate['invoice_nr'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="vessel" class="form-label">Nave</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="{{ $certificate['vessel'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bl_nr" class="form-label">Nº BL</label>
                        <input type="text" class="form-control" id="bl_nr" name="bl_nr" value="{{ $certificate['bl_nr'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fcl" class="form-label">FCL</label>
                        <input type="text" class="form-control" id="fcl" name="fcl" value="{{ $certificate['fcl'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="origin" class="form-label">Origen *</label>
                        <input type="text" class="form-control" id="origin" name="origin" value="{{ $certificate['origin'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destino *</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ $certificate['destination'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Certificado UE
                    </button>
                    <a href="{{ route('documents.quality-certificate-eu.list') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('contract_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        // Actualizar la acción del formulario
        const form = document.getElementById('certificateForm');
        form.action = `/documents/quality-certificate-eu/${selectedOption.value}`;
        
        // Cargar datos del contrato
        document.getElementById('client_name').value = selectedOption.dataset.client || '';
        document.getElementById('product').value = selectedOption.dataset.product || 'PITTED PRUNES';
        document.getElementById('size').value = selectedOption.dataset.packing || 'EX 70/80';
        document.getElementById('quantity').value = selectedOption.dataset.quantity ? `${selectedOption.dataset.quantity} KG` : '';
        document.getElementById('contract_number').value = selectedOption.dataset.contractNumber || '';
        document.getElementById('invoice_nr').value = selectedOption.dataset.contractNumber || '';
        document.getElementById('vessel').value = selectedOption.dataset.vessel || '';
        document.getElementById('bl_nr').value = selectedOption.dataset.booking || '';
        document.getElementById('fcl').value = selectedOption.dataset.container || '';
        document.getElementById('destination').value = selectedOption.dataset.destination || '';
    }
});
</script>
@endsection
