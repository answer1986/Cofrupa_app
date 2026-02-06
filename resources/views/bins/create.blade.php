@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-boxes"></i> Crear Nuevo Bin</h2>
            <a href="{{ route('bins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Información del Bin</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="bulkModeToggle">
                        <label class="form-check-label" for="bulkModeToggle">
                            <i class="fas fa-layer-group"></i> Creación Masiva
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('bins.store') }}" method="POST" enctype="multipart/form-data" id="binForm">
                    @csrf
                    
                    <!-- Campo oculto para indicar si es creación masiva -->
                    <input type="hidden" name="is_bulk" id="is_bulk" value="0">

                    <!-- FORMULARIO DE CREACIÓN MASIVA -->
                    <div id="bulkForm" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Creación Masiva:</strong> Use este modo para registrar grandes cantidades de bins sin detalle individual. Ideal para control de inventario.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bulk_quantity" class="form-label">
                                    <i class="fas fa-sort-numeric-up"></i> Cantidad de Bins *
                                </label>
                                <input type="number" class="form-control @error('bulk_quantity') is-invalid @enderror"
                                       id="bulk_quantity" name="bulk_quantity" min="1" value="{{ old('bulk_quantity') }}" 
                                       placeholder="Ej: 500">
                                <small class="form-text text-muted">Ingrese la cantidad total de bins a crear</small>
                                @error('bulk_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bulk_type" class="form-label">
                                    <i class="fas fa-cubes"></i> Tipo de Bin *
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        id="bulk_type" name="type">
                                    <option value="">Seleccione tipo</option>
                                    <option value="wood" {{ old('type') == 'wood' ? 'selected' : '' }}>Madera (60kg capacidad)</option>
                                    <option value="plastic" {{ old('type') == 'plastic' ? 'selected' : '' }}>Plástico (45kg capacidad)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bulk_ownership_type" class="form-label">
                                    <i class="fas fa-user-tag"></i> Tipo de Bin *
                                </label>
                                <select class="form-select @error('ownership_type') is-invalid @enderror"
                                        id="bulk_ownership_type" name="ownership_type">
                                    <option value="">Seleccione tipo</option>
                                    <option value="field" {{ old('ownership_type', 'field') == 'field' ? 'selected' : '' }}>Bin de Campo (LG)</option>
                                    <option value="internal" {{ old('ownership_type') == 'internal' ? 'selected' : '' }}>Bin Interno (COFRUPA)</option>
                                    <option value="supplier" {{ old('ownership_type') == 'supplier' ? 'selected' : '' }}>Bin del Proveedor (BINS CLIENTES)</option>
                                </select>
                                @error('ownership_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bulk_weight_capacity" class="form-label">
                                    <i class="fas fa-weight"></i> Peso del Bin Vacío (Tara) en kg *
                                </label>
                                <input type="number" step="0.01" class="form-control @error('weight_capacity') is-invalid @enderror"
                                       id="bulk_weight_capacity" name="weight_capacity" 
                                       value="{{ old('weight_capacity') }}" 
                                       placeholder="Ej: 6.00 para madera, 3.00 para plástico">
                                <small class="form-text text-muted">Peso promedio del contenedor vacío</small>
                                @error('weight_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bulk_status" class="form-label">
                                    <i class="fas fa-info-circle"></i> Estado Inicial *
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="bulk_status" name="status">
                                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>En uso</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>Dañado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bulk_prefix" class="form-label">
                                    <i class="fas fa-tag"></i> Prefijo para Números (opcional)
                                </label>
                                <input type="text" class="form-control @error('bulk_prefix') is-invalid @enderror"
                                       id="bulk_prefix" name="bulk_prefix" value="{{ old('bulk_prefix') }}" 
                                       placeholder="Ej: LG, CF, PROV">
                                <small class="form-text text-muted">Los bins se numerarán automáticamente: Prefijo-1, Prefijo-2, etc.</small>
                                @error('bulk_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bulk_notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Notas
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="bulk_notes" name="notes" rows="3" 
                                      placeholder="Ej: Lote de 500 bins de campo entregados en enero 2026">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- FORMULARIO DE CREACIÓN INDIVIDUAL -->
                    <div id="individualForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bin_number" class="form-label">
                                <i class="fas fa-hashtag"></i> Número del Bin *
                            </label>
                            <input type="text" class="form-control @error('bin_number') is-invalid @enderror"
                                   id="bin_number" name="bin_number" value="{{ old('bin_number') }}" required>
                            @error('bin_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">
                                <i class="fas fa-cubes"></i> Tipo de Bin *
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">Seleccione tipo</option>
                                <option value="wood" {{ old('type') == 'wood' ? 'selected' : '' }}>Madera (60kg capacidad)</option>
                                <option value="plastic" {{ old('type') == 'plastic' ? 'selected' : '' }}>Plástico (45kg capacidad)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ownership_type" class="form-label">
                                <i class="fas fa-user-tag"></i> Tipo de Bin *
                            </label>
                            <select class="form-select @error('ownership_type') is-invalid @enderror"
                                    id="ownership_type" name="ownership_type" required>
                                <option value="">Seleccione tipo</option>
                                <option value="field" {{ old('ownership_type', 'field') == 'field' ? 'selected' : '' }}>Bin de Campo (LG)</option>
                                <option value="internal" {{ old('ownership_type') == 'internal' ? 'selected' : '' }}>Bin Interno (COFRUPA)</option>
                                <option value="supplier" {{ old('ownership_type') == 'supplier' ? 'selected' : '' }}>Bin del Proveedor (BINS CLIENTES)</option>
                            </select>
                            <small class="form-text text-muted">Seleccione el tipo de bin. La mayoría son bins de campo que se entregan a proveedores.</small>
                            @error('ownership_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="weight_capacity" class="form-label">
                                <i class="fas fa-weight"></i> Peso del Bin Vacío (Tara) en kg *
                            </label>
                            <input type="number" step="0.01" class="form-control @error('weight_capacity') is-invalid @enderror"
                                   id="weight_capacity" name="weight_capacity" 
                                   value="{{ old('weight_capacity') }}" 
                                   placeholder="Ej: 6.00 para madera, 3.00 para plástico" 
                                   required>
                            <small class="form-text text-muted">Peso del contenedor vacío. Puede ser cualquier valor según el bin.</small>
                            @error('weight_capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label">
                                <i class="fas fa-truck"></i> Proveedor Asignado
                            </label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                    id="supplier_id" name="supplier_id">
                                <option value="">Sin asignar</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} - {{ $supplier->location }}
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-info-circle"></i> Estado *
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Disponible</option>
                                <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>En uso</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>Dañado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="delivery_date" class="form-label">
                                <i class="fas fa-calendar"></i> Fecha de Entrega
                            </label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                   id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="return_date" class="form-label">
                                <i class="fas fa-calendar-check"></i> Fecha de Devolución
                            </label>
                            <input type="date" class="form-control @error('return_date') is-invalid @enderror"
                                   id="return_date" name="return_date" value="{{ old('return_date') }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">
                            <i class="fas fa-camera"></i> Foto del Bin
                        </label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                               id="photo" name="photo" accept="image/*">
                        <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="damage_description" class="form-label">
                            <i class="fas fa-exclamation-triangle"></i> Descripción de Daños
                        </label>
                        <textarea class="form-control @error('damage_description') is-invalid @enderror"
                                  id="damage_description" name="damage_description" rows="3">{{ old('damage_description') }}</textarea>
                        @error('damage_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Notas
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>
                    <!-- FIN FORMULARIO INDIVIDUAL -->

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('bins.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Crear Bin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bulkModeToggle = document.getElementById('bulkModeToggle');
    const bulkForm = document.getElementById('bulkForm');
    const individualForm = document.getElementById('individualForm');
    const isBulkInput = document.getElementById('is_bulk');
    const submitButton = document.querySelector('button[type="submit"]');
    
    // Formulario Individual
    const typeSelect = document.getElementById('type');
    const weightCapacityInput = document.getElementById('weight_capacity');
    
    // Formulario Masivo
    const bulkTypeSelect = document.getElementById('bulk_type');
    const bulkWeightCapacityInput = document.getElementById('bulk_weight_capacity');
    
    // Peso por defecto según el tipo
    const defaultWeights = {
        'wood': 6.00,
        'plastic': 3.00
    };
    
    // Toggle entre modo individual y masivo
    bulkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            // Modo Masivo
            bulkForm.style.display = 'block';
            individualForm.style.display = 'none';
            isBulkInput.value = '1';
            submitButton.innerHTML = '<i class="fas fa-save"></i> Crear Bins en Masa';
            
            // Deshabilitar campos individuales para que no se envíen
            disableFormFields(individualForm, true);
            disableFormFields(bulkForm, false);
        } else {
            // Modo Individual
            bulkForm.style.display = 'none';
            individualForm.style.display = 'block';
            isBulkInput.value = '0';
            submitButton.innerHTML = '<i class="fas fa-save"></i> Crear Bin';
            
            // Habilitar campos individuales
            disableFormFields(individualForm, false);
            disableFormFields(bulkForm, true);
        }
    });
    
    // Función para deshabilitar/habilitar campos de un formulario
    function disableFormFields(container, disable) {
        const fields = container.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            if (disable) {
                field.setAttribute('disabled', 'disabled');
                // Remover el atributo required cuando está deshabilitado
                if (field.hasAttribute('required')) {
                    field.setAttribute('data-was-required', 'true');
                    field.removeAttribute('required');
                }
            } else {
                field.removeAttribute('disabled');
                // Restaurar el atributo required si lo tenía
                if (field.getAttribute('data-was-required') === 'true') {
                    field.setAttribute('required', 'required');
                    field.removeAttribute('data-was-required');
                }
            }
        });
    }
    
    // Auto-completar peso en modo individual
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            if (selectedType && !weightCapacityInput.value) {
                weightCapacityInput.value = defaultWeights[selectedType] || '';
            }
        });
    }
    
    // Auto-completar peso en modo masivo
    if (bulkTypeSelect) {
        bulkTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            if (selectedType && !bulkWeightCapacityInput.value) {
                bulkWeightCapacityInput.value = defaultWeights[selectedType] || '';
            }
        });
    }
    
    // Inicializar: deshabilitar campos del formulario masivo por defecto
    disableFormFields(bulkForm, true);
});
</script>
@endsection