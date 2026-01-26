@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice"></i> Instructivo de Embarque - Contrato {{ $contract->contract_number }}</h2>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Edita los datos antes de generar el PDF</strong><br>
        <small>Instructivo de Embarque para la naviera con detalles del envío.</small>
    </div>

    <form action="{{ route('documents.shipping-instructions.store', $contract->id) }}" method="POST">
        @csrf
        
        @include('documents.partials.contract_fields')

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
                    <div class="col-md-3 mb-3">
                        <label for="flete" class="form-label">FLETE *</label>
                        <input type="text" class="form-control" id="flete" name="flete" value="{{ $shipping['flete'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="deposito" class="form-label">DEPÓSITO</label>
                        <input type="text" class="form-control" id="deposito" name="deposito" value="{{ $shipping['deposito'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="modalidad_venta" class="form-label">MODALIDAD DE VENTA</label>
                        <input type="text" class="form-control" id="modalidad_venta" name="modalidad_venta" value="{{ $shipping['modalidad_venta'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="forma_pago" class="form-label">FORMA DE PAGO</label>
                        <input type="text" class="form-control" id="forma_pago" name="forma_pago" value="{{ $shipping['forma_pago'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="precio_venta" class="form-label">PRECIO DE VENTA CFR</label>
                        <input type="text" class="form-control" id="precio_venta" name="precio_venta" value="{{ $shipping['precio_venta'] }}">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="documents" class="form-label">DOCUMENTS</label>
                        <textarea class="form-control" id="documents" name="documents" rows="2">{{ $shipping['documents'] }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="hs_code" class="form-label">HS CODE</label>
                        <input type="text" class="form-control" id="hs_code" name="hs_code" value="{{ $shipping['hs_code'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="valor_fob" class="form-label">VALOR FOB TOTAL</label>
                        <input type="text" class="form-control" id="valor_fob" name="valor_fob" value="{{ $shipping['valor_fob'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="etd" class="form-label">ETD *</label>
                        <input type="text" class="form-control" id="etd" name="etd" value="{{ $shipping['etd'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cut_off" class="form-label">Cut Off Documental *</label>
                        <input type="text" class="form-control" id="cut_off" name="cut_off" value="{{ $shipping['cut_off'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="matriz" class="form-label">Matriz</label>
                        <input type="text" class="form-control" id="matriz" name="matriz" value="{{ $shipping['matriz'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="stacking" class="form-label">Stacking</label>
                        <input type="text" class="form-control" id="stacking" name="stacking" value="{{ $shipping['stacking'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="terminal_entrega" class="form-label">Terminal de entrega</label>
                        <input type="text" class="form-control" id="terminal_entrega" name="terminal_entrega" value="{{ $shipping['terminal_entrega'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="puerto_ingreso" class="form-label">Puerto de ingreso</label>
                        <input type="text" class="form-control" id="puerto_ingreso" name="puerto_ingreso" value="{{ $shipping['puerto_ingreso'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="horario_stacking" class="form-label">Horario de Stacking</label>
                        <input type="text" class="form-control" id="horario_stacking" name="horario_stacking" value="{{ $shipping['horario_stacking'] }}">
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
                    <div class="col-md-6 mb-3">
                        <label for="detail" class="form-label">DETAIL *</label>
                        <textarea class="form-control" id="detail" name="detail" rows="3" required>{{ $shipping['detail'] }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="unit_price" class="form-label">US$ P/KG</label>
                        <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ $shipping['unit_price'] }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_boxes" class="form-label">TOTAL BOXES *</label>
                        <input type="text" class="form-control" id="total_boxes" name="total_boxes" value="{{ $shipping['total_boxes'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_net_weight" class="form-label">TOTAL NET WEIGHT *</label>
                        <input type="text" class="form-control" id="total_net_weight" name="total_net_weight" value="{{ $shipping['total_net_weight'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="total_pallet" class="form-label">TOTAL PALLET</label>
                        <input type="text" class="form-control" id="total_pallet" name="total_pallet" value="{{ $shipping['total_pallet'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="total_gross_weight" class="form-label">TOTAL GROSS WEIGHT</label>
                        <input type="text" class="form-control" id="total_gross_weight" name="total_gross_weight" value="{{ $shipping['total_gross_weight'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="net_boxes" class="form-label">NET BOXES</label>
                        <input type="text" class="form-control" id="net_boxes" name="net_boxes" value="{{ $shipping['net_boxes'] }}">
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="gross_bags" class="form-label">GROSS BAGS</label>
                        <input type="text" class="form-control" id="gross_bags" name="gross_bags" value="{{ $shipping['gross_bags'] }}">
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

        <!-- Botones de Acción -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" id="previewBtn" class="btn btn-info btn-lg">
                        <i class="fas fa-eye"></i> Vista Previa
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#sendModal">
                        <i class="fas fa-paper-plane"></i> Enviar por Email
                    </button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Generar y Guardar
                    </button>
                    <a href="{{ route('exportations.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Email -->
<div class="modal fade" id="sendModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-paper-plane"></i> Enviar Instructivo de Embarque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sendForm" method="POST" action="{{ route('documents.shipping-instructions.send', $contract->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emails" class="form-label">Destinatarios (separados por coma) *</label>
                        <textarea class="form-control" name="emails" rows="3" required>{{ $shipping['matriz'] }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control" name="message" rows="4">Adjunto Instructivo de Embarque - Contrato {{ $contract->contract_number }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('previewBtn').addEventListener('click', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("documents.shipping-instructions.preview", $contract->id) }}';
    form.target = '_blank';
    
    const originalForm = document.querySelector('form');
    const formData = new FormData(originalForm);
    
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

document.getElementById('sendForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const originalForm = document.querySelector('form:first-of-type');
    const sendForm = document.getElementById('sendForm');
    const formData = new FormData(originalForm);
    
    for (let [key, value] of formData.entries()) {
        if (key !== '_token') {
            const existingInput = sendForm.querySelector(`input[name="${key}"]`);
            if (!existingInput) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                sendForm.appendChild(input);
            }
        }
    }
    sendForm.submit();
});
</script>
@endsection



