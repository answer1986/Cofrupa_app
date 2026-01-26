@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice"></i> Crear Instructivo de Embarque</h2>
                <a href="{{ route('documents.shipping-instructions.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona el contrato y completa los datos del embarque</strong><br>
        <small>Instructivo de Embarque para la naviera con detalles del envío.</small>
    </div>

    <form id="shippingForm" action="#" method="POST">
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
                                    data-consignee="{{ $contract->consignee_name }}"
                                    data-container="{{ $contract->container_number }}"
                                    data-booking="{{ $contract->booking_number }}"
                                    data-vessel="{{ $contract->vessel_name }}"
                                    data-destination="{{ $contract->destination_port }}"
                                    data-incoterm="{{ $contract->incoterm }}">
                                {{ $contract->contract_number }} - {{ $contract->client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Información del Agente y Contrato -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="agent_name" class="form-label">Agente / Shipping Agency *</label>
                        <input type="text" class="form-control" id="agent_name" name="agent_name" value="{{ $shipping['agent_name'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ref_contract" class="form-label">REF: CONTRACT *</label>
                        <input type="text" class="form-control" id="ref_contract" name="ref_contract" value="{{ $shipping['ref_contract'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boarding Instructions -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">BOARDING INSTRUCTIONS</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="csnee" class="form-label">CSNEE *</label>
                        <input type="text" class="form-control" id="csnee" name="csnee" value="{{ $shipping['csnee'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contract_number" class="form-label">CONTRACT *</label>
                        <input type="text" class="form-control" id="contract_number" name="contract_number" value="{{ $shipping['contract_number'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="numbers_container" class="form-label">NUMBERS CONTAINER *</label>
                        <input type="text" class="form-control" id="numbers_container" name="numbers_container" value="{{ $shipping['numbers_container'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="booking" class="form-label">BOOKING *</label>
                        <input type="text" class="form-control" id="booking" name="booking" value="{{ $shipping['booking'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="carrier" class="form-label">CARRIER *</label>
                        <input type="text" class="form-control" id="carrier" name="carrier" value="{{ $shipping['carrier'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="ship" class="form-label">SHIP *</label>
                        <input type="text" class="form-control" id="ship" name="ship" value="{{ $shipping['ship'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="loading_port" class="form-label">LOADING PORT *</label>
                        <input type="text" class="form-control" id="loading_port" name="loading_port" value="{{ $shipping['loading_port'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="destination_port" class="form-label">DESTINATION PORT *</label>
                        <input type="text" class="form-control" id="destination_port" name="destination_port" value="{{ $shipping['destination_port'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="destination_final" class="form-label">DESTINATION FINAL *</label>
                        <input type="text" class="form-control" id="destination_final" name="destination_final" value="{{ $shipping['destination_final'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="clausula_venta" class="form-label">CLÁUSULA VENTA *</label>
                        <input type="text" class="form-control" id="clausula_venta" name="clausula_venta" value="{{ $shipping['clausula_venta'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="flete" class="form-label">FLETE *</label>
                        <input type="text" class="form-control" id="flete" name="flete" value="{{ $shipping['flete'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="etd" class="form-label">ETD *</label>
                        <input type="text" class="form-control" id="etd" name="etd" value="{{ $shipping['etd'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cut_off" class="form-label">Cut Off Documental *</label>
                        <input type="text" class="form-control" id="cut_off" name="cut_off" value="{{ $shipping['cut_off'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boarding Advice -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">BOARDING ADVICE</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="container_type" class="form-label">CONTAINER *</label>
                        <input type="text" class="form-control" id="container_type" name="container_type" value="{{ $shipping['container_type'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="net_weight" class="form-label">NET WEIGHT *</label>
                        <input type="text" class="form-control" id="net_weight" name="net_weight" value="{{ $shipping['net_weight'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_boxes" class="form-label">TOTAL BOXES *</label>
                        <input type="text" class="form-control" id="total_boxes" name="total_boxes" value="{{ $shipping['total_boxes'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_net_weight" class="form-label">TOTAL NET WEIGHT *</label>
                        <input type="text" class="form-control" id="total_net_weight" name="total_net_weight" value="{{ $shipping['total_net_weight'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="detail" class="form-label">DETAIL *</label>
                        <textarea class="form-control" id="detail" name="detail" rows="3" required>{{ $shipping['detail'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipper & Consignee -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">SHIPPER & CONSIGNEE</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="shipper_info" class="form-label">SHIPPER *</label>
                        <textarea class="form-control" id="shipper_info" name="shipper_info" rows="3" required>{{ $shipping['shipper_info'] }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="consignee_info" class="form-label">CONSIGNEE *</label>
                        <textarea class="form-control" id="consignee_info" name="consignee_info" rows="5" required>{{ $shipping['consignee_info'] }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notify_info" class="form-label">NOTIFY</label>
                        <textarea class="form-control" id="notify_info" name="notify_info" rows="5">{{ $shipping['notify_info'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Instructivo
                    </button>
                    <a href="{{ route('documents.shipping-instructions.list') }}" class="btn btn-outline-secondary">
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
        const form = document.getElementById('shippingForm');
        form.action = `/documents/shipping-instructions/${selectedOption.value}`;
        
        // Cargar datos del contrato
        const contractNumber = selectedOption.dataset.contractNumber || '';
        document.getElementById('ref_contract').value = 'REF: CONTRACT ' + contractNumber;
        document.getElementById('csnee').value = selectedOption.dataset.consignee || '';
        document.getElementById('contract_number').value = contractNumber;
        document.getElementById('numbers_container').value = selectedOption.dataset.container || '1 X 20\' DRY ST';
        document.getElementById('booking').value = selectedOption.dataset.booking || '';
        document.getElementById('ship').value = selectedOption.dataset.vessel || '';
        document.getElementById('destination_port').value = selectedOption.dataset.destination || '';
        document.getElementById('destination_final').value = selectedOption.dataset.destination || '';
        document.getElementById('clausula_venta').value = selectedOption.dataset.incoterm || 'CFR';
    }
});
</script>
@endsection
