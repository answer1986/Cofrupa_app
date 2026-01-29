
<!-- Resumen Deuda por Banco -->
<div class="row mb-5">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark font-weight-bold text-center">
                DEUDA POR BANCO Y FECHA (US$)
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 text-center">
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th>BANCO</th>
                            <th>MONTO (US)</th>
                            <th>VENCIMIENTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($debts as $debt)
                            <tr>
                                <td class="font-weight-bold">{{ strtoupper($debt->bank) }}</td>
                                <td>{{ number_format($debt->total_debt, 2, ',', '.') }}</td>
                                <td>-</td> <!-- Vencimiento logic needs to be defined, potentially adding payment_due_date to grouping -->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">No hay deudas registradas</td>
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
