@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-truck-loading"></i> Instructivo de Transporte - Contrato {{ $contract->contract_number }}</h2>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Edita los datos antes de generar el PDF</strong><br>
        <small>Instructivo para la empresa de transporte con detalles de recogida y entrega.</small>
    </div>

    <form action="{{ route('documents.transport-instructions.store', $contract->id) }}" method="POST">
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
                        <label for="emission_date" class="form-label">Fecha de Emisión *</label>
                        <input type="date" class="form-control" id="emission_date" name="emission_date" value="{{ $transport['emission_date'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="transport_company" class="form-label">Empresa de Transporte *</label>
                        <input type="text" class="form-control" id="transport_company" name="transport_company" value="{{ $transport['transport_company'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact_info" class="form-label">Contacto (CTC)</label>
                        <input type="text" class="form-control" id="contact_info" name="contact_info" value="{{ $transport['contact_info'] }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Cliente y Referencia -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Cliente y Referencia</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="client_name" class="form-label">Cliente *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $transport['client_name'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="client_reference" class="form-label">Ref. Cliente *</label>
                        <input type="text" class="form-control" id="client_reference" name="client_reference" value="{{ $transport['client_reference'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reserva y Naviera -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Información de Reserva</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="booking_number" class="form-label">Reserva (Booking) *</label>
                        <input type="text" class="form-control" id="booking_number" name="booking_number" value="{{ $transport['booking_number'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="shipping_company" class="form-label">Cía. Naviera *</label>
                        <input type="text" class="form-control" id="shipping_company" name="shipping_company" value="{{ $transport['shipping_company'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vessel_name" class="form-label">Nave *</label>
                        <input type="text" class="form-control" id="vessel_name" name="vessel_name" value="{{ $transport['vessel_name'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedores y Producto -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Contenedores y Producto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="container_type_quantity" class="form-label">Tipo y Cantidad de Contenedores *</label>
                        <input type="text" class="form-control" id="container_type_quantity" name="container_type_quantity" value="{{ $transport['container_type_quantity'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="product_quantity" class="form-label">Producto y Cantidad *</label>
                        <input type="text" class="form-control" id="product_quantity" name="product_quantity" value="{{ $transport['product_quantity'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="net_weight_per_unit" class="form-label">Peso Neto por Unidad</label>
                        <input type="text" class="form-control" id="net_weight_per_unit" name="net_weight_per_unit" value="{{ $transport['net_weight_per_unit'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gross_weight_per_unit" class="form-label">Peso Bruto por Unidad</label>
                        <input type="text" class="form-control" id="gross_weight_per_unit" name="gross_weight_per_unit" value="{{ $transport['gross_weight_per_unit'] }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Retiro y Puertos -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Retiro y Puertos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="empty_pickup_location" class="form-label">Lugar de Retiro Vacío *</label>
                        <input type="text" class="form-control" id="empty_pickup_location" name="empty_pickup_location" value="{{ $transport['empty_pickup_location'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="loading_port" class="form-label">PUERTO EMBARQUE *</label>
                        <input type="text" class="form-control" id="loading_port" name="loading_port" value="{{ $transport['loading_port'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination_port" class="form-label">PUERTO DESTINO *</label>
                        <input type="text" class="form-control" id="destination_port" name="destination_port" value="{{ $transport['destination_port'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lugar de Cargado -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Lugar de Cargado</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="loading_location" class="form-label">Lugar de Cargado *</label>
                        <input type="text" class="form-control" id="loading_location" name="loading_location" value="{{ $transport['loading_location'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="loading_address" class="form-label">Dirección *</label>
                        <textarea class="form-control" id="loading_address" name="loading_address" rows="2" required>{{ $transport['loading_address'] }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="presentation_date" class="form-label">Fecha de Presentación *</label>
                        <input type="date" class="form-control" id="presentation_date" name="presentation_date" value="{{ $transport['presentation_date'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="presentation_time" class="form-label">Hora de Presentación *</label>
                        <input type="time" class="form-control" id="presentation_time" name="presentation_time" value="{{ $transport['presentation_time'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terminal y Stacking -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Terminal y Stacking</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="delivery_terminal" class="form-label">Terminal de Entrega *</label>
                        <input type="text" class="form-control" id="delivery_terminal" name="delivery_terminal" value="{{ $transport['delivery_terminal'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="delivery_port" class="form-label">Puerto de Entrega *</label>
                        <input type="text" class="form-control" id="delivery_port" name="delivery_port" value="{{ $transport['delivery_port'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="official_stacking" class="form-label">Stacking Oficial</label>
                        <input type="text" class="form-control" id="official_stacking" name="official_stacking" value="{{ $transport['official_stacking'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stacking_schedule" class="form-label">Horario de Stacking</label>
                        <input type="text" class="form-control" id="stacking_schedule" name="stacking_schedule" value="{{ $transport['stacking_schedule'] }}">
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
                <h5 class="modal-title"><i class="fas fa-paper-plane"></i> Enviar Instructivo de Transporte</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sendForm" method="POST" action="{{ route('documents.transport-instructions.send', $contract->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emails" class="form-label">Destinatarios (separados por coma) *</label>
                        <textarea class="form-control" name="emails" rows="3" required>{{ $transport['contact_info'] }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control" name="message" rows="4">Adjunto Instructivo de Transporte - Contrato {{ $contract->contract_number }}</textarea>
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
    form.action = '{{ route("documents.transport-instructions.preview", $contract->id) }}';
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



