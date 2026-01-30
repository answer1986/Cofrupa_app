@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-shopping-cart"></i> Nueva Compra - {{ ucwords(str_replace('_', ' ', $company)) }}</h2>
                <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'purchases']) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('finance.purchases.store') }}" method="POST" id="purchaseForm">
        @csrf
        <input type="hidden" name="company" value="{{ $company }}">

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Datos de la Compra</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="invoice_number" class="form-label">N° Factura</label>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Ej: FC-12345">
                                @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_name" class="form-label">{{ $company === 'cofrupa' ? 'Proveedor' : 'Productor' }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}" list="suppliers_list" placeholder="Escriba o seleccione" required autocomplete="off">
                                <datalist id="suppliers_list">
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier }}">
                                    @endforeach
                                </datalist>
                                @error('supplier_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_caliber" class="form-label">Producto / Calibre</label>
                                <input type="text" class="form-control @error('product_caliber') is-invalid @enderror" id="product_caliber" name="product_caliber" value="{{ old('product_caliber') }}" list="products_list" placeholder="Escriba o seleccione">
                                <datalist id="products_list">
                                    <option value="Ciruela D'Agen">
                                    <option value="Ciruela Angelino">
                                    <option value="Ciruela Rosa">
                                    <option value="Cereza">
                                    <option value="Arándano">
                                    <option value="Frambuesa">
                                    <option value="Mora">
                                </datalist>
                                @error('product_caliber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type') }}" list="types_list" placeholder="Escriba o seleccione">
                                <datalist id="types_list">
                                    <option value="MP (Materia Prima)">
                                    <option value="Deshidratada">
                                    <option value="Congelada">
                                    <option value="Fresca">
                                    <option value="Procesada">
                                </datalist>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="kilos" class="form-label">Kilos <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('kilos') is-invalid @enderror" id="kilos" name="kilos" value="{{ old('kilos') }}" required>
                                @error('kilos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="exchange_rate" class="form-label">T/C (Tipo Cambio) <i class="fas fa-info-circle text-muted" title="Conversión CLP → USD"></i></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('exchange_rate') is-invalid @enderror" id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate') }}" placeholder="Ej: 950">
                                @error('exchange_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Precios y Totales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit_price_clp" class="form-label">Precio Unitario Neto (CLP)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="unit_price_clp" name="unit_price_clp" value="{{ old('unit_price_clp') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="unit_price_usd" class="form-label">Precio Unitario Neto (USD)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="unit_price_usd" name="unit_price_usd" value="{{ old('unit_price_usd') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_net_clp" class="form-label">Total Neto CLP</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_net_clp" name="total_net_clp" value="{{ old('total_net_clp') }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_net_usd" class="form-label">Total Neto USD</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_net_usd" name="total_net_usd" value="{{ old('total_net_usd') }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="iva" class="form-label">IVA</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="iva" name="iva" value="{{ old('iva', 0) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_clp" class="form-label">Total CLP</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_clp" name="total_clp" value="{{ old('total_clp') }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_usd" class="form-label">Total USD</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_usd" name="total_usd" value="{{ old('total_usd') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                @if(in_array($company, ['luis_gonzalez', 'comercializadora']))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-truck"></i> Costos Adicionales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="commission_per_kilo" class="form-label">Comisión ($/Kilo)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="commission_per_kilo" name="commission_per_kilo" value="{{ old('commission_per_kilo', 0) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_commission" class="form-label">Total Comisión</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_commission" name="total_commission" value="{{ old('total_commission', 0) }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="freight_per_kilo" class="form-label">Flete ($/Kilo)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="freight_per_kilo" name="freight_per_kilo" value="{{ old('freight_per_kilo', 0) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_freight" class="form-label">Total Flete</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_freight" name="total_freight" value="{{ old('total_freight', 0) }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="other_costs" class="form-label">Otros Costos</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="other_costs" name="other_costs" value="{{ old('other_costs', 0) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="final_total" class="form-label">Total Final</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="final_total" name="final_total" value="{{ old('final_total') }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="average_per_kilo" class="form-label">Promedio por Kilo</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="average_per_kilo" name="average_per_kilo" value="{{ old('average_per_kilo') }}" readonly>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Estado y Pago</h5>
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
                        <div class="mb-3">
                            <label for="bank" class="form-label">Banco <i class="fas fa-info-circle text-muted" title="Si hay deuda, se registra por banco"></i></label>
                            <input type="text" class="form-control @error('bank') is-invalid @enderror" id="bank" name="bank" value="{{ old('bank') }}" list="banks_list" placeholder="Seleccione o escriba">
                            <datalist id="banks_list">
                                @foreach($banks as $bank)
                                    <option value="{{ $bank }}">
                                @endforeach
                            </datalist>
                            @error('bank')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="with_iva" name="with_iva" value="1" {{ old('with_iva', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="with_iva">Con IVA (19%)</label>
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
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Guardar Compra
                            </button>
                            <a href="{{ route('finance.index', ['company' => $company, 'tab' => 'purchases']) }}" class="btn btn-secondary">
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
    const kilosInput = document.getElementById('kilos');
    const unitPriceClp = document.getElementById('unit_price_clp');
    const unitPriceUsd = document.getElementById('unit_price_usd');
    const exchangeRate = document.getElementById('exchange_rate');
    const totalNetClp = document.getElementById('total_net_clp');
    const totalNetUsd = document.getElementById('total_net_usd');
    const ivaInput = document.getElementById('iva');
    const totalClp = document.getElementById('total_clp');
    const totalUsd = document.getElementById('total_usd');
    const withIva = document.getElementById('with_iva');
    const commissionPerKilo = document.getElementById('commission_per_kilo');
    const totalCommission = document.getElementById('total_commission');
    const freightPerKilo = document.getElementById('freight_per_kilo');
    const totalFreight = document.getElementById('total_freight');
    const otherCosts = document.getElementById('other_costs');
    const finalTotal = document.getElementById('final_total');
    const averagePerKilo = document.getElementById('average_per_kilo');

    let lastEditedPrice = null; // Para saber qué precio se editó último (clp o usd)

    // Auto-conversión CLP ↔ USD con tipo de cambio
    unitPriceClp.addEventListener('input', function() {
        lastEditedPrice = 'clp';
        const tc = parseFloat(exchangeRate.value) || 0;
        const clp = parseFloat(unitPriceClp.value) || 0;
        if (tc > 0 && clp > 0) {
            unitPriceUsd.value = (clp / tc).toFixed(4);
        }
        calculate();
    });

    unitPriceUsd.addEventListener('input', function() {
        lastEditedPrice = 'usd';
        const tc = parseFloat(exchangeRate.value) || 0;
        const usd = parseFloat(unitPriceUsd.value) || 0;
        if (tc > 0 && usd > 0) {
            unitPriceClp.value = (usd * tc).toFixed(2);
        }
        calculate();
    });

    exchangeRate.addEventListener('input', function() {
        const tc = parseFloat(exchangeRate.value) || 0;
        if (tc > 0) {
            // Convertir basado en el último precio editado
            if (lastEditedPrice === 'clp') {
                const clp = parseFloat(unitPriceClp.value) || 0;
                if (clp > 0) unitPriceUsd.value = (clp / tc).toFixed(4);
            } else if (lastEditedPrice === 'usd') {
                const usd = parseFloat(unitPriceUsd.value) || 0;
                if (usd > 0) unitPriceClp.value = (usd * tc).toFixed(2);
            }
        }
        calculate();
    });

    // Auto-cálculo de IVA
    withIva.addEventListener('change', function() {
        calculate();
    });

    function calculate() {
        const kilos = parseFloat(kilosInput.value) || 0;
        const pClp = parseFloat(unitPriceClp.value) || 0;
        const pUsd = parseFloat(unitPriceUsd.value) || 0;

        // 1. Totales netos
        const netClp = kilos * pClp;
        const netUsd = kilos * pUsd;
        totalNetClp.value = netClp.toFixed(2);
        totalNetUsd.value = netUsd.toFixed(2);

        // 2. IVA automático (19%)
        let ivaAmount = 0;
        if (withIva.checked && netClp > 0) {
            ivaAmount = netClp * 0.19;
            ivaInput.value = ivaAmount.toFixed(2);
        } else {
            ivaInput.value = '0.00';
        }

        // 3. Totales con IVA
        totalClp.value = (netClp + ivaAmount).toFixed(2);
        totalUsd.value = netUsd.toFixed(2); // USD no lleva IVA (por lo general)

        // 4. Costos adicionales (si existen)
        if (commissionPerKilo) {
            const comm = parseFloat(commissionPerKilo.value) || 0;
            totalCommission.value = (kilos * comm).toFixed(2);
        }
        if (freightPerKilo) {
            const freight = parseFloat(freightPerKilo.value) || 0;
            totalFreight.value = (kilos * freight).toFixed(2);
        }

        // 5. Total final
        if (finalTotal) {
            const tClp = parseFloat(totalClp.value) || 0;
            const tComm = parseFloat(totalCommission ? totalCommission.value : 0) || 0;
            const tFreight = parseFloat(totalFreight ? totalFreight.value : 0) || 0;
            const others = parseFloat(otherCosts ? otherCosts.value : 0) || 0;
            const final = tClp + tComm + tFreight + others;
            finalTotal.value = final.toFixed(2);

            // Promedio por kilo
            if (kilos > 0 && averagePerKilo) {
                averagePerKilo.value = (final / kilos).toFixed(2);
            }
        }
    }

    // Event listeners
    [kilosInput, ivaInput, commissionPerKilo, freightPerKilo, otherCosts].forEach(el => {
        if (el) el.addEventListener('input', calculate);
    });

    // Cálculo inicial
    calculate();
});
</script>
@endsection
