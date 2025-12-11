@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-boxes"></i> Editar Bin: {{ $bin->bin_number }}</h2>
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
                <h5 class="mb-0"><i class="fas fa-edit"></i> Información del Bin</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bins.update', $bin) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bin_number" class="form-label">
                                <i class="fas fa-hashtag"></i> Número del Bin *
                            </label>
                            <input type="text" class="form-control @error('bin_number') is-invalid @enderror"
                                   id="bin_number" name="bin_number" value="{{ old('bin_number', $bin->bin_number) }}" required>
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
                                <option value="wood" {{ old('type', $bin->type) == 'wood' ? 'selected' : '' }}>Madera (60kg capacidad)</option>
                                <option value="plastic" {{ old('type', $bin->type) == 'plastic' ? 'selected' : '' }}>Plástico (45kg capacidad)</option>
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
                                <option value="field" {{ old('ownership_type', $bin->ownership_type ?? 'field') == 'field' ? 'selected' : '' }}>Bin de Campo (Se entrega a proveedores)</option>
                                <option value="internal" {{ old('ownership_type', $bin->ownership_type ?? 'field') == 'internal' ? 'selected' : '' }}>Bin Interno (Propiedad de la empresa)</option>
                                <option value="supplier" {{ old('ownership_type', $bin->ownership_type ?? 'field') == 'supplier' ? 'selected' : '' }}>Bin del Proveedor (Propiedad del proveedor)</option>
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
                                   value="{{ old('weight_capacity', $bin->weight_capacity) }}" 
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
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $bin->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                                <option value="available" {{ old('status', $bin->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                                <option value="in_use" {{ old('status', $bin->status) == 'in_use' ? 'selected' : '' }}>En uso</option>
                                <option value="maintenance" {{ old('status', $bin->status) == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="damaged" {{ old('status', $bin->status) == 'damaged' ? 'selected' : '' }}>Dañado</option>
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
                                   id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $bin->delivery_date ? $bin->delivery_date->format('Y-m-d') : '') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="return_date" class="form-label">
                                <i class="fas fa-calendar-check"></i> Fecha de Devolución
                            </label>
                            <input type="date" class="form-control @error('return_date') is-invalid @enderror"
                                   id="return_date" name="return_date" value="{{ old('return_date', $bin->return_date ? $bin->return_date->format('Y-m-d') : '') }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">
                            <i class="fas fa-camera"></i> Foto del Bin
                        </label>
                        @if($bin->photo_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $bin->photo_path) }}" alt="Foto del bin" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                               id="photo" name="photo" accept="image/*">
                        <div class="form-text">
                            @if($bin->photo_path)
                                Deja vacío para mantener la foto actual.
                            @else
                                Sube una foto del bin.
                            @endif
                            Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                        </div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="damage_description" class="form-label">
                            <i class="fas fa-exclamation-triangle"></i> Descripción de Daños
                        </label>
                        <textarea class="form-control @error('damage_description') is-invalid @enderror"
                                  id="damage_description" name="damage_description" rows="3">{{ old('damage_description', $bin->damage_description) }}</textarea>
                        @error('damage_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Notas
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $bin->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('bins.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Actualizar Bin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection