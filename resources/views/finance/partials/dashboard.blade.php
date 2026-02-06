
<!-- RESUMEN OPERACIONAL Y MÁRGENES -->
@if(isset($operationStats))
<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calculator"></i> Resumen Operacional y Márgenes</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- COSTOS -->
            <div class="col-lg-6">
                <h6 class="text-muted mb-3"><i class="fas fa-money-bill-wave"></i> COSTOS DE LA OPERACIÓN</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Compras de Fruta:</strong></td>
                        <td class="text-end">${{ number_format($operationStats['total_compras'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Fletes Totales:</strong>
                            <a href="{{ route('ventas.fletes.index') }}" class="btn btn-sm btn-outline-primary ms-2" title="Ver fletes">
                                <i class="fas fa-truck"></i>
                            </a>
                        </td>
                        <td class="text-end">${{ number_format($operationStats['total_fletes'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="ps-3 small text-muted">Costo flete por kilo:</td>
                        <td class="text-end small text-muted">${{ number_format($operationStats['costo_flete_por_kilo'], 0, ',', '.') }}/kg</td>
                    </tr>
                    <tr>
                        <td><strong>Procesamiento/Calibrado:</strong></td>
                        <td class="text-end">${{ number_format($operationStats['total_procesamiento'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Otros Costos:</strong></td>
                        <td class="text-end">${{ number_format($operationStats['total_otros_costos'], 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong class="text-danger fs-5">COSTOS TOTALES:</strong></td>
                        <td class="text-end"><strong class="text-danger fs-5">${{ number_format($operationStats['costos_totales'], 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Costo por kilo:</td>
                        <td class="text-end small text-muted">${{ number_format($operationStats['costo_por_kilo'], 0, ',', '.') }}/kg</td>
                    </tr>
                </table>
            </div>

            <!-- INGRESOS Y MÁRGENES -->
            <div class="col-lg-6">
                <h6 class="text-muted mb-3"><i class="fas fa-chart-line"></i> INGRESOS Y MÁRGENES</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Ventas Totales (CLP):</strong></td>
                        <td class="text-end text-success"><strong>${{ number_format($operationStats['total_ventas'], 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Ventas (USD):</td>
                        <td class="text-end small text-muted">US$ {{ number_format($operationStats['total_ventas_usd'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Venta por kilo:</td>
                        <td class="text-end small text-muted">${{ number_format($operationStats['venta_por_kilo'], 0, ',', '.') }}/kg</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong class="fs-5">MARGEN BRUTO:</strong></td>
                        <td class="text-end">
                            <strong class="fs-5 {{ $operationStats['margen_bruto'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($operationStats['margen_bruto'], 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>% Margen:</strong></td>
                        <td class="text-end">
                            <span class="badge {{ $operationStats['porcentaje_margen'] >= 20 ? 'bg-success' : ($operationStats['porcentaje_margen'] >= 10 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                {{ number_format($operationStats['porcentaje_margen'], 1) }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Margen por kilo:</td>
                        <td class="text-end small {{ $operationStats['margen_por_kilo'] >= 0 ? 'text-success' : 'text-danger' }}">
                            ${{ number_format($operationStats['margen_por_kilo'], 0, ',', '.') }}/kg
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Barra de progreso visual del margen -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">Costos</small>
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 25px;">
                            @php
                                $costPercentage = $operationStats['total_ventas'] > 0 ? ($operationStats['costos_totales'] / $operationStats['total_ventas']) * 100 : 0;
                                $costPercentage = min($costPercentage, 100);
                            @endphp
                            <div class="progress-bar bg-danger" style="width: {{ $costPercentage }}%">
                                {{ number_format($costPercentage, 1) }}%
                            </div>
                            <div class="progress-bar bg-success" style="width: {{ 100 - $costPercentage }}%">
                                Margen: {{ number_format(100 - $costPercentage, 1) }}%
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Ventas</small>
                </div>
            </div>
        </div>

        <!-- Resumen de Kilos -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="text-center p-2 bg-light rounded">
                    <small class="text-muted d-block">Kilos Comprados</small>
                    <strong>{{ number_format($operationStats['total_kilos_comprados'], 0, ',', '.') }} kg</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-2 bg-light rounded">
                    <small class="text-muted d-block">Kilos Transportados</small>
                    <strong>{{ number_format($operationStats['total_kilos_transportados'], 0, ',', '.') }} kg</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-2 bg-light rounded">
                    <small class="text-muted d-block">Kilos Vendidos</small>
                    <strong>{{ number_format($operationStats['total_kilos_vendidos'], 0, ',', '.') }} kg</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Estadísticas de Pagos -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="text-uppercase mb-1"><i class="fas fa-check-circle"></i> Pagos Completados</h6>
                <h3 class="mb-0">${{ number_format($totalPaymentsCompleted ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="text-uppercase mb-1"><i class="fas fa-clock"></i> Pagos Pendientes</h6>
                <h3 class="mb-0">${{ number_format($totalPaymentsPending ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-chart-pie"></i> Por Método de Pago (Completados)</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(($paymentsByMethod ?? []) as $pm)
                        <span class="badge bg-info">
                            {{ ucfirst($pm->payment_method) }}: ${{ number_format($pm->total, 0, ',', '.') }} ({{ $pm->count }})
                        </span>
                    @endforeach
                    @if(empty($paymentsByMethod) || $paymentsByMethod->isEmpty())
                        <span class="text-muted small">Sin pagos completados aún</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagos Recientes -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-money-check-alt"></i> Últimos Pagos</span>
                <a href="{{ route('finance.payments.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-list"></i> Ver todos los pagos
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Método</th>
                                <th>Referencia</th>
                                <th>Beneficiario</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($recentPayments ?? []) as $p)
                                <tr>
                                    <td>{{ $p->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ ucfirst($p->payment_method) }}</td>
                                    <td><code>{{ $p->reference_number ?? '—' }}</code></td>
                                    <td>{{ $p->payee_name ?? '—' }}</td>
                                    <td class="text-end">{{ $p->currency }} {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $p->status == 'completado' ? 'success' : 'warning' }}">{{ $p->status_display }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-2">No hay pagos recientes</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resumen Deuda/Capital por Banco (registro aparte) -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark font-weight-bold d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>DEUDA / CAPITAL POR BANCO (US$)</span>
                <a href="{{ route('finance.bank-debts.create', ['company' => $company ?? request('company', 'cofrupa')]) }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-plus"></i> Registrar deuda / capital
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 text-center align-middle">
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th>BANCO</th>
                            <th>MONTO (US$)</th>
                            <th>VENCIMIENTO</th>
                            <th>USO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($debts as $debt)
                            <tr>
                                <td class="font-weight-bold">{{ strtoupper($debt->bank) }}</td>
                                <td>{{ number_format($debt->amount_usd, 2, ',', '.') }}</td>
                                <td>{{ $debt->due_date ? $debt->due_date->format('d-m-Y') : '-' }}</td>
                                <td>{{ $debt->type_display }}</td>
                                <td class="text-center">
                                    <a href="{{ route('finance.bank-debts.edit', $debt) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('finance.bank-debts.destroy', $debt) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No hay deudas/capital registrado. Use "Registrar deuda / capital" para agregar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabla General -->
<div class="card shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-center align-middle">
                    <tr>
                        <th rowspan="2">FECHA</th>
                        <th rowspan="2">FACTURA N°</th>
                        <th rowspan="2">PROVEEDOR</th>
                        <th rowspan="2">CALIBRE</th>
                        <th rowspan="2">TIPO</th>
                        <th rowspan="2">KILOS</th>
                        <th rowspan="2">T/C</th>
                        <th colspan="2">PRECIO UNITARIO</th>
                        <th colspan="2">TOTAL NETO</th>
                        <th rowspan="2">IVA $</th>
                        <th colspan="2">TOTAL FACTURA</th>
                        <th rowspan="2">SALDO</th>
                        <th rowspan="2">ACCIONES</th>
                    </tr>
                    <tr>
                        <th>($)</th>
                        <th>(US$)</th>
                        <th>($)</th>
                        <th>(US$)</th>
                        <th>($)</th>
                        <th>(US$)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $record->purchase_date->format('d-m-y') }}</td>
                            <td>{{ $record->invoice_number }}</td>
                            <td>{{ $record->supplier_name }}</td>
                            <td>{{ $record->product_caliber }}</td>
                            <td>{{ $record->type }}</td>
                            <td class="text-end">{{ number_format($record->kilos, 2, ',', '.') }}</td>
                            <td class="text-end fw-bold">{{ $record->exchange_rate ? number_format($record->exchange_rate, 2, ',', '.') : '-' }}</td>
                            
                            <!-- Unit Prices -->
                            <td class="text-end">{{ number_format($record->unit_price_clp, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($record->unit_price_usd, 2, ',', '.') }}</td>

                            <!-- Net Totals -->
                            <td class="text-end bg-warning bg-opacity-10">{{ number_format($record->total_net_clp, 0, ',', '.') }}</td>
                            <td class="text-end bg-warning bg-opacity-10">{{ number_format($record->total_net_usd, 2, ',', '.') }}</td>

                            <!-- IVA -->
                            <td class="text-end bg-warning bg-opacity-25">{{ number_format($record->iva, 0, ',', '.') }}</td>

                            <!-- Final Totals -->
                            <td class="text-end fw-bold">{{ number_format($record->total_clp, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">{{ number_format($record->total_usd, 2, ',', '.') }}</td>

                            <!-- Balance / Status -->
                            <td class="text-end">
                                @if($record->status == 'paid')
                                    <span class="badge bg-success">Pagado</span>
                                @elseif($record->status == 'pending')
                                    <span class="badge bg-danger">Pendiente</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $record->status }}</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('finance.purchases.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="text-center text-muted py-4">
                                No hay registros financieros disponibles
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
