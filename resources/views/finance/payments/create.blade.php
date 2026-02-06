@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-plus-circle"></i> Registrar Pago</h2>
                <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('finance.payments.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Datos del Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company" class="form-label">Empresa *</label>
                                <select name="company" id="company" class="form-select @error('company') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    <option value="cofrupa" {{ old('company', $company ?? '') == 'cofrupa' ? 'selected' : '' }}>Cofrupa Export</option>
                                    <option value="luis_gonzalez" {{ old('company') == 'luis_gonzalez' ? 'selected' : '' }}>Luis Gonzalez</option>
                                    <option value="comercializadora" {{ old('company') == 'comercializadora' ? 'selected' : '' }}>Comercializadora</option>
                                </select>
                                @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="payment_date" class="form-label">Fecha de Pago *</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Método de Pago *</label>
                                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="transferencia" {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                    <option value="efectivo" {{ old('payment_method') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="tarjeta" {{ old('payment_method') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                    <option value="otro" {{ old('payment_method') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reference_number" class="form-label">Nº Cheque / Transferencia</label>
                                <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}" placeholder="Ej: 12345678 o REF-001">
                                @error('reference_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Número de cheque o referencia de transferencia</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Monto *</label>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="Ej: 1500000" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="currency" class="form-label">Moneda *</label>
                                <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="CLP" {{ old('currency', 'CLP') == 'CLP' ? 'selected' : '' }}>CLP (Pesos)</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD (Dólares)</option>
                                </select>
                                @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_type" class="form-label">Tipo de Pago *</label>
                                <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                                    <option value="compra" {{ old('payment_type', 'compra') == 'compra' ? 'selected' : '' }}>Compra</option>
                                    <option value="venta" {{ old('payment_type') == 'venta' ? 'selected' : '' }}>Venta</option>
                                    <option value="gasto" {{ old('payment_type') == 'gasto' ? 'selected' : '' }}>Gasto</option>
                                    <option value="otro" {{ old('payment_type') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Estado *</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="completado" {{ old('status', 'completado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="pendiente" {{ old('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="rechazado" {{ old('status') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="anulado" {{ old('status') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="invoice_number" class="form-label">Nº Factura</label>
                                <input type="text" name="invoice_number" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" placeholder="Ej: F-001234">
                                @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="purchase_order" class="form-label">Orden de Compra</label>
                                <input type="text" name="purchase_order" id="purchase_order" class="form-control @error('purchase_order') is-invalid @enderror" value="{{ old('purchase_order') }}" placeholder="Ej: OC-2024-001">
                                @error('purchase_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="payee_name" class="form-label">Beneficiario</label>
                                <input type="text" name="payee_name" id="payee_name" class="form-control @error('payee_name') is-invalid @enderror" value="{{ old('payee_name') }}" placeholder="Proveedor o cliente">
                                @error('payee_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Detalles adicionales...">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Pago
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
