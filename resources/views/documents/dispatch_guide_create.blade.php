@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-clipboard-list"></i> Crear Guía de Despacho</h2>
                <a href="{{ route('documents.dispatch-guides.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona el contrato y completa los datos de la guía</strong><br>
        <small>Guía de Despacho que autoriza el despacho de mercancías desde el almacén o planta hacia el puerto de embarque.</small>
    </div>

    <form id="dispatchForm" action="#" method="POST">
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
                                    data-contract-number="{{ $contract->contract_number }}"
                                    data-client="{{ $contract->client->name }}"
                                    data-consignee="{{ $contract->consignee_name }}"
                                    data-product="{{ $contract->product_description }}"
                                    data-quantity="{{ $contract->stock_committed }}"
                                    data-packing="{{ $contract->packing }}"
                                    data-destination="{{ $contract->destination_port }}"
                                    data-vessel="{{ $contract->vessel_name }}"
                                    data-seller-address="{{ $contract->seller_address }}">
                                {{ $contract->contract_number }} - {{ $contract->client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dispatch_date" class="form-label">Fecha de Despacho *</label>
                        <input type="date" class="form-control" id="dispatch_date" name="dispatch_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dispatch_number" class="form-label">Número de Guía *</label>
                        <input type="text" class="form-control" id="dispatch_number" name="dispatch_number" value="" placeholder="GD-XXX" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="origin_location" class="form-label">Origen / Ubicación *</label>
                        <input type="text" class="form-control" id="origin_location" name="origin_location" value="Camino Lo Mackenna Parcela 7A, Buin, Chile" required>
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
                        <input type="text" class="form-control" id="client_name" name="client_name" value="" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="consignee" class="form-label">Consignatario</label>
                        <input type="text" class="form-control" id="consignee" name="consignee" value="">
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
                        <textarea class="form-control" id="product_description" name="product_description" rows="2" required></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantity" class="form-label">Cantidad (kg) *</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="packing" class="form-label">Empaque</label>
                        <input type="text" class="form-control" id="packing" name="packing" value="">
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
                        <input type="text" class="form-control" id="destination" name="destination" value="" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vessel" class="form-label">Buque / Vessel</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="">
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
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Guía de Despacho
                    </button>
                    <a href="{{ route('documents.dispatch-guides.list') }}" class="btn btn-outline-secondary">
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
        const form = document.getElementById('dispatchForm');
        form.action = `/documents/dispatch-guides/${selectedOption.value}`;
        
        // Cargar datos del contrato
        const contractNumber = selectedOption.dataset.contractNumber || '';
        document.getElementById('dispatch_number').value = 'GD-' + contractNumber;
        document.getElementById('client_name').value = selectedOption.dataset.client || '';
        document.getElementById('consignee').value = selectedOption.dataset.consignee || '';
        document.getElementById('product_description').value = selectedOption.dataset.product || '';
        document.getElementById('quantity').value = selectedOption.dataset.quantity || '';
        document.getElementById('packing').value = selectedOption.dataset.packing || '';
        document.getElementById('destination').value = selectedOption.dataset.destination || '';
        document.getElementById('vessel').value = selectedOption.dataset.vessel || '';
        document.getElementById('origin_location').value = selectedOption.dataset.sellerAddress || 'Camino Lo Mackenna Parcela 7A, Buin, Chile';
    }
});
</script>
@endsection
