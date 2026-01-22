@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-certificate"></i> Certificado de Calidad - Contrato {{ $contract->contract_number }}</h2>
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

    <form action="{{ route('documents.quality-certificate.store', $contract->id) }}" method="POST">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="exporter" class="form-label">Exportador *</label>
                        <input type="text" class="form-control" id="exporter" name="exporter" value="{{ $certificate['exporter'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="production_plant" class="form-label">Planta de Producción *</label>
                        <input type="text" class="form-control" id="production_plant" name="production_plant" value="{{ $certificate['production_plant'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="container_nr" class="form-label">Nº Contenedor</label>
                        <input type="text" class="form-control" id="container_nr" name="container_nr" value="{{ $certificate['container_nr'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bl_nr" class="form-label">Nº BL</label>
                        <input type="text" class="form-control" id="bl_nr" name="bl_nr" value="{{ $certificate['bl_nr'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="invoice_nr" class="form-label">Nº Factura *</label>
                        <input type="text" class="form-control" id="invoice_nr" name="invoice_nr" value="{{ $certificate['invoice_nr'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="product" class="form-label">Producto *</label>
                        <input type="text" class="form-control" id="product" name="product" value="{{ $certificate['product'] }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="size" class="form-label">Tamaño *</label>
                        <input type="text" class="form-control" id="size" name="size" value="{{ $certificate['size'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="production_date" class="form-label">Fecha Producción *</label>
                        <input type="date" class="form-control" id="production_date" name="production_date" value="{{ $certificate['production_date'] }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="expiration_date" class="form-label">Fecha Expiración *</label>
                        <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ $certificate['expiration_date'] }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Inspección de Calidad</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="size_allowance" class="form-label">Tamaño Permitido</label>
                        <input type="text" class="form-control" id="size_allowance" name="size_allowance" value="{{ $certificate['size_allowance'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="size_result" class="form-label">Resultado Tamaño</label>
                        <input type="text" class="form-control" id="size_result" name="size_result" value="{{ $certificate['size_result'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="moisture_result" class="form-label">Nivel de Humedad (%)</label>
                        <input type="text" class="form-control" id="moisture_result" name="moisture_result" value="{{ $certificate['moisture_result'] }}" placeholder="Ej: 18">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defects_result" class="form-label">Defectos Totales (%)</label>
                        <input type="text" class="form-control" id="defects_result" name="defects_result" value="{{ $certificate['defects_result'] }}" placeholder="Ej: 4.4">
                    </div>
                </div>
            </div>
        </div>

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
                    <i class="fas fa-paper-plane"></i> Enviar Certificado de Calidad por Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendForm" method="POST" action="{{ route('documents.quality-certificate.send', $contract->id) }}">
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
                            placeholder="Mensaje que acompañará al certificado...">Adjunto encontrará el Certificado de Calidad correspondiente al contrato {{ $contract->contract_number }}</textarea>
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
    // Crear un formulario temporal para el preview
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("documents.quality-certificate.preview", $contract->id) }}';
    form.target = '_blank'; // Abrir en nueva pestaña
    
    // Copiar todos los campos del formulario original
    const originalForm = document.querySelector('form');
    const formData = new FormData(originalForm);
    
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    // Agregar token CSRF
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Enviar el formulario
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

// Enviar por email - copiar campos del formulario al formulario de envío
document.getElementById('sendForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const originalForm = document.querySelector('form:first-of-type');
    const sendForm = document.getElementById('sendForm');
    const formData = new FormData(originalForm);
    
    // Copiar todos los campos del certificado al formulario de envío
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
    
    // Ahora enviar el formulario
    sendForm.submit();
});
</script>
@endsection

