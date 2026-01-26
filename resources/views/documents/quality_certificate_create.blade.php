@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-certificate"></i> Crear Certificado de Calidad</h2>
                <a href="{{ route('documents.quality-certificate.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona el contrato y completa los datos del certificado</strong><br>
        <small>Los campos se rellenarán automáticamente con los datos del contrato seleccionado, pero puedes modificarlos.</small>
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
                                    data-container="{{ $contract->container_number }}"
                                    data-booking="{{ $contract->booking_number }}"
                                    data-contract-number="{{ $contract->contract_number }}"
                                    data-product="{{ $contract->product_description }}"
                                    data-packing="{{ $contract->packing }}">
                                {{ $contract->contract_number }} - {{ $contract->client->name }} 
                                @if($contract->product_description)
                                    - {{ Str::limit($contract->product_description, 40) }}
                                @endif
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
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Certificado
                    </button>
                    <a href="{{ route('documents.quality-certificate.list') }}" class="btn btn-outline-secondary">
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
        form.action = `/documents/quality-certificate/${selectedOption.value}`;
        
        // Cargar datos del contrato
        document.getElementById('container_nr').value = selectedOption.dataset.container || '';
        document.getElementById('bl_nr').value = selectedOption.dataset.booking || '';
        document.getElementById('invoice_nr').value = selectedOption.dataset.contractNumber || '';
        document.getElementById('product').value = selectedOption.dataset.product || "Chilean D'Agen Prunes Natural condition";
        document.getElementById('size').value = selectedOption.dataset.packing || '120/144 LOT COF 81';
    }
});
</script>
@endsection
