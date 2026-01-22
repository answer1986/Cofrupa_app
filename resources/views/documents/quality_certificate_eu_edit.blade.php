@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-certificate"></i> Certificado de Calidad EU - Contrato {{ $contract->contract_number }}</h2>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Carpetas
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Edita los datos antes de generar el PDF</strong><br>
        <small>Los campos se rellenan automáticamente con los datos del contrato. Puedes modificarlos antes de guardar.</small>
    </div>

    <form action="{{ route('documents.quality-certificate-eu.store', $contract->id) }}" method="POST">
        @csrf
        
        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General del Certificado</h5>
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
                    <div class="col-md-6 mb-3">
                        <label for="product" class="form-label">Producto *</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ $certificate['product'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="size" class="form-label">Tamaño *</label>
                        <input type="text" class="form-control" id="size" name="size" value="{{ $certificate['size'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantity" class="form-label">Cantidad *</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $certificate['quantity'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="contract_number" class="form-label">Número de Contrato *</label>
                        <input type="text" class="form-control" id="contract_number" name="contract_number" value="{{ $certificate['contract_number'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="invoice_nr" class="form-label">Nº Factura *</label>
                        <input type="text" class="form-control" id="invoice_nr" name="invoice_nr" value="{{ $certificate['invoice_nr'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vessel" class="form-label">Vessel (M/N)</label>
                        <input type="text" class="form-control" id="vessel" name="vessel" value="{{ $certificate['vessel'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bl_nr" class="form-label">Nº B/L</label>
                        <input type="text" class="form-control" id="bl_nr" name="bl_nr" value="{{ $certificate['bl_nr'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fcl" class="form-label">FCL</label>
                        <input type="text" class="form-control" id="fcl" name="fcl" value="{{ $certificate['fcl'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="origin" class="form-label">Origen (FROM) *</label>
                        <input type="text" class="form-control" id="origin" name="origin" value="{{ $certificate['origin'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destino (TO) *</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ $certificate['destination'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Análisis Organoléptico -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Análisis Organoléptico</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="colour" class="form-label">Color *</label>
                        <input type="text" class="form-control" id="colour" name="colour" value="{{ $certificate['colour'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="flavour" class="form-label">Sabor *</label>
                        <input type="text" class="form-control" id="flavour" name="flavour" value="{{ $certificate['flavour'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="texture" class="form-label">Textura *</label>
                        <input type="text" class="form-control" id="texture" name="texture" value="{{ $certificate['texture'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Análisis Químico -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Análisis Químico</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="moisture" class="form-label">Humedad *</label>
                        <input type="text" class="form-control" id="moisture" name="moisture" value="{{ $certificate['moisture'] }}" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="moisture_method" class="form-label">Método de Medición</label>
                        <input type="text" class="form-control" id="moisture_method" name="moisture_method" value="{{ $certificate['moisture_method'] }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="potassium_sorbate" class="form-label">Sorbato de Potasio *</label>
                        <input type="text" class="form-control" id="potassium_sorbate" name="potassium_sorbate" value="{{ $certificate['potassium_sorbate'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="oil" class="form-label">Aceite</label>
                        <input type="text" class="form-control" id="oil" name="oil" value="{{ $certificate['oil'] }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Análisis Físico -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Análisis Físico</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="fragments_pits" class="form-label">Fragmentos de Carozos *</label>
                        <input type="text" class="form-control" id="fragments_pits" name="fragments_pits" value="{{ $certificate['fragments_pits'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="units_per_pound" class="form-label">Unidades por Libra *</label>
                        <input type="text" class="form-control" id="units_per_pound" name="units_per_pound" value="{{ $certificate['units_per_pound'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="defects" class="form-label">Defectos (%) *</label>
                        <input type="text" class="form-control" id="defects" name="defects" value="{{ $certificate['defects'] }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="usda_grade" class="form-label">Grado USDA *</label>
                        <input type="text" class="form-control" id="usda_grade" name="usda_grade" value="{{ $certificate['usda_grade'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="usda_reference" class="form-label">Referencia USDA</label>
                        <input type="text" class="form-control" id="usda_reference" name="usda_reference" value="{{ $certificate['usda_reference'] }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Microbiología -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Análisis Microbiológico</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="total_plate_count" class="form-label">Conteo Total en Placa *</label>
                        <input type="text" class="form-control" id="total_plate_count" name="total_plate_count" value="{{ $certificate['total_plate_count'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="moulds" class="form-label">Mohos *</label>
                        <input type="text" class="form-control" id="moulds" name="moulds" value="{{ $certificate['moulds'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="yeasts" class="form-label">Levaduras *</label>
                        <input type="text" class="form-control" id="yeasts" name="yeasts" value="{{ $certificate['yeasts'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="e_coli" class="form-label">E. COLI *</label>
                        <input type="text" class="form-control" id="e_coli" name="e_coli" value="{{ $certificate['e_coli'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="salmonella" class="form-label">Salmonella *</label>
                        <input type="text" class="form-control" id="salmonella" name="salmonella" value="{{ $certificate['salmonella'] }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="aflatoxine_individual" class="form-label">Aflatoxinas B1, B2, G1 y G2 *</label>
                        <input type="text" class="form-control" id="aflatoxine_individual" name="aflatoxine_individual" value="{{ $certificate['aflatoxine_individual'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="aflatoxine_total" class="form-label">Aflatoxinas Total *</label>
                        <input type="text" class="form-control" id="aflatoxine_total" name="aflatoxine_total" value="{{ $certificate['aflatoxine_total'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fechas de Producción y Expiración -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Fechas de Producción y Expiración</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="production_date" class="form-label">Fecha de Producción *</label>
                        <input type="text" class="form-control" id="production_date" name="production_date" value="{{ $certificate['production_date'] }}" required placeholder="Ej: OCTOBER 2025">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Fecha de Expiración *</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" value="{{ $certificate['expiry_date'] }}" required placeholder="Ej: OCTOBER 2026">
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" id="previewBtn" class="btn btn-info btn-lg">
                        <i class="fas fa-eye"></i> Vista Previa del Certificado
                    </button>
                    <button type="button" id="sendBtn" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#sendModal">
                        <i class="fas fa-paper-plane"></i> Enviar por Email
                    </button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Generar y Guardar Certificado
                    </button>
                    <a href="{{ route('exportations.index') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal para enviar por email -->
<div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="sendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendModalLabel">
                    <i class="fas fa-paper-plane"></i> Enviar Certificado de Calidad EU por Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendForm" method="POST" action="{{ route('documents.quality-certificate-eu.send', $contract->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> El certificado se generará con los datos actuales del formulario y se enviará a los destinatarios especificados.
                    </div>
                    
                    <div class="mb-3">
                        <label for="emails" class="form-label">Destinatarios (separados por coma) *</label>
                        <textarea class="form-control" id="emails" name="emails" rows="3" required 
                            placeholder="ejemplo@correo.com, otro@correo.com">{{ $contract->contact_email ?? ($contract->client->email ?? '') }}</textarea>
                        <small class="text-muted">Puedes ingresar múltiples emails separados por coma (,) o punto y coma (;)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje personalizado (opcional)</label>
                        <textarea class="form-control" id="message" name="message" rows="4" 
                            placeholder="Mensaje que acompañará al certificado...">Adjunto encontrará el Certificado de Calidad (EU) correspondiente al contrato {{ $contract->contract_number }}</textarea>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Nota:</strong> El certificado NO se guardará automáticamente. Si deseas guardarlo, usa el botón "Generar y Guardar Certificado" después de enviarlo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview
document.getElementById('previewBtn').addEventListener('click', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("documents.quality-certificate-eu.preview", $contract->id) }}';
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

// Enviar por email
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



