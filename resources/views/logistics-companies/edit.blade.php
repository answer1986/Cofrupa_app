@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Editar Empresa Logística</h2>
            <a href="{{ route('logistics-companies.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Información de la Empresa Logística</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('logistics-companies.update', $logisticsCompany->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre de la Empresa *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $logisticsCompany->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                   id="code" name="code" value="{{ old('code', $logisticsCompany->code) }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_name" class="form-label">Nombre de Contacto</label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                   id="contact_name" name="contact_name" value="{{ old('contact_name', $logisticsCompany->contact_name) }}">
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Email de Contacto</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                   id="contact_email" name="contact_email" value="{{ old('contact_email', $logisticsCompany->contact_email) }}" autocomplete="email">
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Teléfono de Contacto</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $logisticsCompany->contact_phone) }}" 
                               pattern="^\+[0-9]{11}$" 
                               placeholder="+56912345678"
                               maxlength="12">
                        <small class="form-text text-muted">Formato: + seguido de 11 números (ejemplo: +56912345678)</small>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address', $logisticsCompany->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $logisticsCompany->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Activa
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $logisticsCompany->notes) }}</textarea>
                        @error('notes')
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
                                   id="tax_id" name="tax_id" value="{{ old('tax_id', $logisticsCompany->tax_id) }}"
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
                                   id="bank_name" name="bank_name" value="{{ old('bank_name', $logisticsCompany->bank_name) }}"
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
                                <option value="corriente" {{ old('bank_account_type', $logisticsCompany->bank_account_type) == 'corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                <option value="vista" {{ old('bank_account_type', $logisticsCompany->bank_account_type) == 'vista' ? 'selected' : '' }}>Cuenta Vista</option>
                                <option value="ahorro" {{ old('bank_account_type', $logisticsCompany->bank_account_type) == 'ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
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
                                   id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $logisticsCompany->bank_account_number) }}"
                                   placeholder="Ej: 0228557656">
                            @error('bank_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('logistics-companies.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar Empresa Logística</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('contact_phone');
    
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value;
        value = value.replace(/[^0-9+]/g, '');
        if (value.length > 0 && value[0] !== '+') {
            value = '+' + value.replace(/\+/g, '');
        }
        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        e.target.value = value;
    });
    
    phoneInput.addEventListener('keypress', function(e) {
        if (e.key === '+' && this.value.includes('+')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

