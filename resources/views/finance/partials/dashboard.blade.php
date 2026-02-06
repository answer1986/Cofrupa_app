
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
