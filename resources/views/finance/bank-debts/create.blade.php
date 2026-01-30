@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-university"></i> Registrar deuda / capital por banco</h2>
                <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'dashboard']) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al dashboard
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('finance.bank-debts.store') }}" method="POST">
        @csrf
        <input type="hidden" name="company" value="{{ $company }}">

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-university"></i> Datos de deuda / capital (para comprar y vender)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bank" class="form-label">Banco <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('bank') is-invalid @enderror" id="bank" name="bank" value="{{ old('bank') }}" placeholder="Ej: Banco Estado" required>
                        @error('bank')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="amount_usd" class="form-label">Monto (US$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('amount_usd') is-invalid @enderror" id="amount_usd" name="amount_usd" value="{{ old('amount_usd') }}" required>
                        @error('amount_usd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="due_date" class="form-label">Vencimiento</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Uso <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="general" {{ old('type', 'general') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="compra" {{ old('type') === 'compra' ? 'selected' : '' }}>Compra</option>
                            <option value="venta" {{ old('type') === 'venta' ? 'selected' : '' }}>Venta</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Opcional">
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'dashboard']) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
