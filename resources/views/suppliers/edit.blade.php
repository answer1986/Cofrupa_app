@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Editar Proveedor</h2>
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
                <h5 class="mb-0"><i class="fas fa-truck"></i> Editar Información del Proveedor</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-building"></i> Nombre del Proveedor *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">
                                <i class="fas fa-briefcase"></i> Razón Social
                            </label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                   id="business_name" name="business_name" value="{{ old('business_name', $supplier->business_name) }}">
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
                                   id="csg_code" name="csg_code" value="{{ old('csg_code', $supplier->csg_code) }}"
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
                                   id="internal_code" name="internal_code" value="{{ old('internal_code', $supplier->internal_code) }}"
                                   placeholder="Código interno del proveedor">
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
                                   id="location" name="location" value="{{ old('location', $supplier->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Número de Teléfono
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
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
                               id="business_type" name="business_type" value="{{ old('business_type', $supplier->business_type) }}"
                               placeholder="Ej: Productor de ciruelas, Agrícola, etc.">
                        @error('business_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Financial Information (Read-only) -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i> Total Pagado
                            </label>
                            <input type="text" class="form-control" value="${{ number_format($supplier->total_paid, 2) }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-credit-card"></i> Deuda Pendiente
                            </label>
                            <input type="text" class="form-control" value="${{ number_format($supplier->pending_amount, 2) }}" readonly>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection