@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> Crear Nuevo Cliente</h2>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Información del Cliente</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Cliente *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Cliente *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="constant" {{ old('type') === 'constant' ? 'selected' : '' }}>Constante (con historial)</option>
                            <option value="new" {{ old('type') === 'new' ? 'selected' : '' }}>Nuevo</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" 
                                   autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+56912345678"
                                   maxlength="12">
                            <small class="form-text text-muted">Formato: + seguido de 11 números (ejemplo: +56912345678)</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customs_agency" class="form-label">Agencia de Aduana</label>
                        <input type="text" class="form-control @error('customs_agency') is-invalid @enderror"
                               id="customs_agency" name="customs_agency" value="{{ old('customs_agency') }}">
                        @error('customs_agency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value;
        // Solo permitir números y el signo + al inicio
        value = value.replace(/[^0-9+]/g, '');
        
        // Asegurar que el + esté al inicio
        if (value.length > 0 && value[0] !== '+') {
            value = '+' + value.replace(/\+/g, '');
        }
        
        // Limitar a 12 caracteres (+ y 11 números)
        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        
        e.target.value = value;
    });
    
    phoneInput.addEventListener('keypress', function(e) {
        // Si ya hay un + y se intenta escribir otro, prevenir
        if (e.key === '+' && this.value.includes('+')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection


