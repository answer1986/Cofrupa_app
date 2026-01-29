@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Crear Nuevo Proveedor</h2>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Información del Proveedor</h5>
            </div>
            <div class="card-body">
                @if(isset($incompleteSuppliers) && $incompleteSuppliers->count() > 0)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Proveedores pendientes de completar:</strong> 
                    Tiene {{ $incompleteSuppliers->count() }} proveedor(es) creado(s) desde recepción que necesitan completar sus datos.
                    <ul class="mb-0 mt-2">
                        @foreach($incompleteSuppliers as $inc)
                        <li>{{ $inc->name }} (creado el {{ $inc->created_at->format('d/m/Y H:i') }})</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    
                    @if(isset($incompleteSupplierData) && !empty($incompleteSupplierData))
                    <input type="hidden" name="incomplete_supplier_id" value="{{ $incompleteSupplierData['supplier_id'] }}">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> 
                        Completando datos del proveedor: <strong>{{ $incompleteSupplierData['name'] }}</strong>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-building"></i> Nombre del Proveedor *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') ?? ($incompleteSupplierData['name'] ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">
                                <i class="fas fa-briefcase"></i> Razón Social
                            </label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                   id="business_name" name="business_name" value="{{ old('business_name') }}">
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="csg_code" class="form-label">
                                <i class="fas fa-barcode"></i> Código CSG
                            </label>
                            <input type="text" class="form-control @error('csg_code') is-invalid @enderror"
                                   id="csg_code" name="csg_code" value="{{ old('csg_code') }}"
                                   placeholder="Ingrese el código CSG">
                            @error('csg_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="internal_code" class="form-label">
                                <i class="fas fa-hashtag"></i> Código Interno
                            </label>
                            <input type="text" class="form-control @error('internal_code') is-invalid @enderror"
                                   id="internal_code" name="internal_code" value="{{ old('internal_code') }}"
                                   placeholder="Se generará automáticamente si se deja vacío" readonly>
                            <small class="form-text text-muted">Se asignará automáticamente al guardar</small>
                            @error('internal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Ubicación *
                            </label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($incompleteSupplierData) && !empty($incompleteSupplierData))
                            <small class="text-muted">Este campo es requerido para completar el proveedor</small>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Número de Teléfono
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="business_type" class="form-label">
                            <i class="fas fa-industry"></i> Giro Comercial
                        </label>
                        <input type="text" class="form-control @error('business_type') is-invalid @enderror"
                               id="business_type" name="business_type" value="{{ old('business_type') }}"
                               placeholder="Ej: Productor de ciruelas, Agrícola, etc.">
                        @error('business_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Datos de Facturación -->
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-file-invoice-dollar"></i> Datos de Facturación</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_id" class="form-label">
                                <i class="fas fa-id-card"></i> RUT / Tax ID
                            </label>
                            <input type="text" class="form-control @error('tax_id') is-invalid @enderror"
                                   id="tax_id" name="tax_id" value="{{ old('tax_id') }}"
                                   placeholder="Ej: 77.706.225-5">
                            @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información Bancaria -->
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-university"></i> Información Bancaria</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_name" class="form-label">
                                <i class="fas fa-building"></i> Banco
                            </label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                   id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                                   placeholder="Ej: Banco Itaú">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bank_account_type" class="form-label">
                                <i class="fas fa-wallet"></i> Tipo de Cuenta
                            </label>
                            <select class="form-control @error('bank_account_type') is-invalid @enderror"
                                    id="bank_account_type" name="bank_account_type">
                                <option value="">Seleccione...</option>
                                <option value="corriente" {{ old('bank_account_type') == 'corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                <option value="vista" {{ old('bank_account_type') == 'vista' ? 'selected' : '' }}>Cuenta Vista</option>
                                <option value="ahorro" {{ old('bank_account_type') == 'ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                            </select>
                            @error('bank_account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_number" class="form-label">
                                <i class="fas fa-hashtag"></i> Número de Cuenta
                            </label>
                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror"
                                   id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}"
                                   placeholder="Ej: 0228557656">
                            @error('bank_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Crear Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection