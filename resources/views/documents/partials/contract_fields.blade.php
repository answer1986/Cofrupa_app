<!-- Información del Contrato (Editable) -->
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Información del Contrato (Se actualiza al guardar)</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="contract_product_description" class="form-label">Descripción del Producto</label>
                <textarea class="form-control" id="contract_product_description" name="contract_product_description" rows="2">{{ old('contract_product_description', $contract->product_description ?? '') }}</textarea>
                <small class="text-muted">Este campo se actualizará en el contrato</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="contract_packing" class="form-label">Empaque (Packing)</label>
                <input type="text" class="form-control" id="contract_packing" name="contract_packing" value="{{ old('contract_packing', $contract->packing ?? '') }}" placeholder="Ej: 25 kg x 1000 bag = 25000 kg per container x 5 FCL">
            </div>
            <div class="col-md-4 mb-3">
                <label for="contract_crop_year" class="form-label">Año de Cosecha (Quality/CROP)</label>
                <input type="text" class="form-control" id="contract_crop_year" name="contract_crop_year" value="{{ old('contract_crop_year', $contract->crop_year ?? '') }}" placeholder="Ej: CROP 2025">
            </div>
            <div class="col-md-4 mb-3">
                <label for="contract_quality_specification" class="form-label">Especificación de Calidad</label>
                <input type="text" class="form-control" id="contract_quality_specification" name="contract_quality_specification" value="{{ old('contract_quality_specification', $contract->quality_specification ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="contract_humidity" class="form-label">Humedad (Humidity)</label>
                <input type="text" class="form-control" id="contract_humidity" name="contract_humidity" value="{{ old('contract_humidity', $contract->humidity ?? '') }}" placeholder="Ej: >15% < 21%">
            </div>
            <div class="col-md-6 mb-3">
                <label for="contract_total_defects" class="form-label">Defectos Totales (Total Defects)</label>
                <input type="text" class="form-control" id="contract_total_defects" name="contract_total_defects" value="{{ old('contract_total_defects', $contract->total_defects ?? '') }}" placeholder="Ej: Max 5%">
            </div>
        </div>
    </div>
</div>
