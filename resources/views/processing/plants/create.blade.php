@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-building"></i> Nueva Planta</h2>
        </div>
    </div>

    <form action="{{ route('processing.plants.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Código *</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact_person" class="form-label">Persona de Contacto</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Planta Activa</label>
                        </div>
                    </div>
                </div>

                <!-- Datos de Facturación -->
                <hr class="my-4">
                <h5 class="mb-3"><i class="fas fa-file-invoice-dollar"></i> Datos de Facturación</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tax_id" class="form-label">
                            <i class="fas fa-id-card"></i> RUT / Tax ID
                        </label>
                        <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id') }}"
                               placeholder="Ej: 77.706.225-5">
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
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                               placeholder="Ej: Banco Itaú">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bank_account_type" class="form-label">
                            <i class="fas fa-wallet"></i> Tipo de Cuenta
                        </label>
                        <select class="form-control" id="bank_account_type" name="bank_account_type">
                            <option value="">Seleccione...</option>
                            <option value="corriente" {{ old('bank_account_type') == 'corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                            <option value="vista" {{ old('bank_account_type') == 'vista' ? 'selected' : '' }}>Cuenta Vista</option>
                            <option value="ahorro" {{ old('bank_account_type') == 'ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_account_number" class="form-label">
                            <i class="fas fa-hashtag"></i> Número de Cuenta
                        </label>
                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}"
                               placeholder="Ej: 0228557656">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('processing.plants.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection

