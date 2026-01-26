@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-truck"></i> Crear Instructivo de Transporte</h2>
                <a href="{{ route('documents.transport-instructions.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona el contrato y completa los datos del transporte</strong><br>
        <small>Instructivo de Transporte para coordinación logística terrestre.</small>
    </div>

    <form id="transportForm" action="#" method="POST">
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
                                    data-booking="{{ $contract->booking_number }}"
                                    data-vessel="{{ $contract->vessel_name }}"
                                    data-container="{{ $contract->container_number }}"
                                    data-product="{{ $contract->product_description }}"
                                    data-quantity="{{ $contract->stock_committed }}"
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
                        <input type="date" class="form-control" id="emission_date" name="emission_date" value="{{ $transport['emission_date'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="transport_company" class="form-label">Empresa de Transporte *</label>
                        <input type="text" class="form-control" id="transport_company" name="transport_company" value="{{ $transport['transport_company'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact_info" class="form-label">Contacto *</label>
                        <input type="text" class="form-control" id="contact_info" name="contact_info" value="{{ $transport['contact_info'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="client_name" class="form-label">Cliente *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $transport['client_name'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="client_reference" class="form-label">Referencia del Cliente *</label>
                        <input type="text" class="form-control" id="client_reference" name="client_reference" value="{{ $transport['client_reference'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="booking_number" class="form-label">Número de Booking *</label>
                        <input type="text" class="form-control" id="booking_number" name="booking_number" value="{{ $transport['booking_number'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="shipping_company" class="form-label">Naviera *</label>
                        <input type="text" class="form-control" id="shipping_company" name="shipping_company" value="{{ $transport['shipping_company'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vessel_name" class="form-label">Nombre del Buque</label>
                        <input type="text" class="form-control" id="vessel_name" name="vessel_name" value="{{ $transport['vessel_name'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="container_type_quantity" class="form-label">Tipo/Cantidad de Contenedor *</label>
                        <input type="text" class="form-control" id="container_type_quantity" name="container_type_quantity" value="{{ $transport['container_type_quantity'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Detalles del Producto y Carga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_quantity" class="form-label">Producto/Cantidad *</label>
                        <input type="text" class="form-control" id="product_quantity" name="product_quantity" value="{{ $transport['product_quantity'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="net_weight_per_unit" class="form-label">Peso Neto por Unidad *</label>
                        <input type="text" class="form-control" id="net_weight_per_unit" name="net_weight_per_unit" value="{{ $transport['net_weight_per_unit'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="gross_weight_per_unit" class="form-label">Peso Bruto por Unidad *</label>
                        <input type="text" class="form-control" id="gross_weight_per_unit" name="gross_weight_per_unit" value="{{ $transport['gross_weight_per_unit'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Instrucciones de Retiro y Carga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="empty_pickup_location" class="form-label">Lugar de Retiro Vacío *</label>
                        <input type="text" class="form-control" id="empty_pickup_location" name="empty_pickup_location" value="{{ $transport['empty_pickup_location'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="empty_pickup_date" class="form-label">Fecha de Retiro</label>
                        <input type="text" class="form-control" id="empty_pickup_date" name="empty_pickup_date" value="{{ $transport['empty_pickup_date'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="loading_address" class="form-label">Dirección de Carga *</label>
                        <input type="text" class="form-control" id="loading_address" name="loading_address" value="{{ $transport['loading_address'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="loading_date" class="form-label">Fecha de Carga</label>
                        <input type="text" class="form-control" id="loading_date" name="loading_date" value="{{ $transport['loading_date'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="loading_contact" class="form-label">Contacto en Lugar de Carga *</label>
                        <input type="text" class="form-control" id="loading_contact" name="loading_contact" value="{{ $transport['loading_contact'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Instrucciones de Stacking</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="stacking_terminal" class="form-label">Terminal de Stacking *</label>
                        <input type="text" class="form-control" id="stacking_terminal" name="stacking_terminal" value="{{ $transport['stacking_terminal'] }}" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="stacking_address" class="form-label">Dirección de Stacking *</label>
                        <input type="text" class="form-control" id="stacking_address" name="stacking_address" value="{{ $transport['stacking_address'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="stacking_date" class="form-label">Fecha de Stacking</label>
                        <input type="text" class="form-control" id="stacking_date" name="stacking_date" value="{{ $transport['stacking_date'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cut_off" class="form-label">Cut Off</label>
                        <input type="text" class="form-control" id="cut_off" name="cut_off" value="{{ $transport['cut_off'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sail_date" class="form-label">Fecha de Zarpe</label>
                        <input type="text" class="form-control" id="sail_date" name="sail_date" value="{{ $transport['sail_date'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="destination" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ $transport['destination'] }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="observations" class="form-label">Observaciones Adicionales</label>
                    <textarea class="form-control" id="observations" name="observations" rows="3">{{ $transport['observations'] }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Instructivo de Transporte
                    </button>
                    <a href="{{ route('documents.transport-instructions.list') }}" class="btn btn-outline-secondary">
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
        const form = document.getElementById('transportForm');
        form.action = `/documents/transport-instructions/${selectedOption.value}`;
        
        // Cargar datos del contrato
        document.getElementById('client_reference').value = selectedOption.dataset.contractNumber || '';
        document.getElementById('booking_number').value = selectedOption.dataset.booking || '';
        document.getElementById('vessel_name').value = selectedOption.dataset.vessel || '';
        document.getElementById('container_type_quantity').value = selectedOption.dataset.container || '1 X 20\' DRY ST';
        document.getElementById('product_quantity').value = selectedOption.dataset.product || '';
        
        const quantity = selectedOption.dataset.quantity;
        if (quantity) {
            document.getElementById('net_weight_per_unit').value = quantity + ' KG';
            document.getElementById('gross_weight_per_unit').value = (parseFloat(quantity) * 1.05).toFixed(2) + ' KG';
        }
        
        document.getElementById('destination').value = selectedOption.dataset.destination || '';
    }
});
</script>
@endsection
