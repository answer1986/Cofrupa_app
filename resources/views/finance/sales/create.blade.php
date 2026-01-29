@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-hand-holding-usd"></i> Nueva Venta - {{ ucwords(str_replace('_', ' ', $company)) }}</h2>
                <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'sales']) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('finance.sales.store') }}" method="POST" id="saleForm">
        @csrf
        <input type="hidden" name="company" value="{{ $company }}">

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Datos de la Venta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="sale_date" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                @error('sale_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="invoice_number" class="form-label">N° Factura</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}">
                            </div>
                            @if($company === 'cofrupa')
                            <div class="col-md-4 mb-3">
                                <label for="contract_number" class="form-label">N° Contrato</label>
                                <input type="text" class="form-control" id="contract_number" name="contract_number" value="{{ old('contract_number') }}">
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_name" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                                @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="caliber" class="form-label">Calibre</label>
                                <input type="text" class="form-control" id="caliber" name="caliber" value="{{ old('caliber') }}" placeholder="Ej: 60/70">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kilos" class="form-label">Kilos <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('kilos') is-invalid @enderror" id="kilos" name="kilos" value="{{ old('kilos') }}" required>
                                @error('kilos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            @if($company === 'cofrupa')
                            <div class="col-md-6 mb-3">
                                <label for="destination_port" class="form-label">Puerto Destino</label>
                                <input type="text" class="form-control" id="destination_port" name="destination_port" value="{{ old('destination_port') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destination_country" class="form-label">País Destino</label>
                                <input type="text" class="form-control" id="destination_country" name="destination_country" value="{{ old('destination_country') }}">
                            </div>
                            @else
                            <div class="col-md-6 mb-3">
                                <label for="destination" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Precios y Pagos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="exchange_rate" class="form-label">Tipo de Cambio (T/C)</label>
                                <input type="number" step="0.0001" min="0" class="form-control" id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit_price_clp" class="form-label">Precio Unitario (CLP)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="unit_price_clp" name="unit_price_clp" value="{{ old('unit_price_clp') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="unit_price_usd" class="form-label">Precio Unitario (USD)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="unit_price_usd" name="unit_price_usd" value="{{ old('unit_price_usd') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="net_price_clp" class="form-label">Precio Neto (CLP)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="net_price_clp" name="net_price_clp" value="{{ old('net_price_clp') }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="net_price_usd" class="form-label">Precio Neto (USD)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="net_price_usd" name="net_price_usd" value="{{ old('net_price_usd') }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_sale_clp" class="form-label">Total Venta CLP</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_sale_clp" name="total_sale_clp" value="{{ old('total_sale_clp') }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_sale_usd" class="form-label">Total Venta USD</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_sale_usd" name="total_sale_usd" value="{{ old('total_sale_usd') }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="iva_clp" class="form-label">IVA (CLP)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="iva_clp" name="iva_clp" value="{{ old('iva_clp', 0) }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="gross_total" class="form-label">Bruto</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="gross_total" name="gross_total" value="{{ old('gross_total') }}" readonly>
                            </div>
                        </div>
                        @if($company === 'cofrupa')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_usd" class="form-label">Abono (USD)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="payment_usd" name="payment_usd" value="{{ old('payment_usd', 0) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="balance_usd" class="form-label">Saldo (USD)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="balance_usd" name="balance_usd" value="{{ old('balance_usd') }}" readonly>
                            </div>
                        </div>
                        @endif
                        @if($company !== 'comercializadora')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_term_days" class="form-label">Plazo (días)</label>
                                <input type="number" min="0" class="form-control" id="payment_term_days" name="payment_term_days" value="{{ old('payment_term_days') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">Fecha Pago</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date') }}">
                            </div>
                        </div>
                        @endif
                        @if($company === 'luis_gonzalez')
                        <div class="mb-3">
                            <label for="bank" class="form-label">Banco</label>
                            <input type="text" class="form-control" id="bank" name="bank" value="{{ old('bank') }}">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Estado</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="paid" name="paid" value="1" {{ old('paid') ? 'checked' : '' }}>
                            <label class="form-check-label" for="paid">Pago realizado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="with_iva" name="with_iva" value="1" {{ old('with_iva', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="with_iva">Con IVA</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Observaciones...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Guardar Venta
                            </button>
                            <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'sales']) }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kilos = document.getElementById('kilos');
    const unitClp = document.getElementById('unit_price_clp');
    const unitUsd = document.getElementById('unit_price_usd');
    const netClp = document.getElementById('net_price_clp');
    const netUsd = document.getElementById('net_price_usd');
    const totalClp = document.getElementById('total_sale_clp');
    const totalUsd = document.getElementById('total_sale_usd');
    const iva = document.getElementById('iva_clp');
    const gross = document.getElementById('gross_total');
    const payment = document.getElementById('payment_usd');
    const balance = document.getElementById('balance_usd');

    function calculate() {
        const k = parseFloat(kilos.value) || 0;
        const pClp = parseFloat(unitClp.value) || 0;
        const pUsd = parseFloat(unitUsd.value) || 0;

        netClp.value = (k * pClp).toFixed(2);
        netUsd.value = (k * pUsd).toFixed(2);

        totalClp.value = netClp.value;
        totalUsd.value = netUsd.value;

        const ivaVal = parseFloat(iva.value) || 0;
        const tUsd = parseFloat(totalUsd.value) || 0;
        gross.value = (tUsd + ivaVal).toFixed(2);

        if (balance && payment) {
            const pay = parseFloat(payment.value) || 0;
            balance.value = (tUsd - pay).toFixed(2);
        }
    }

    [kilos, unitClp, unitUsd, iva, payment].forEach(el => {
        if (el) el.addEventListener('input', calculate);
    });

    calculate();
});
</script>
@endsection
