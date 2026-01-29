<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0" style="font-size: 0.85rem;">
                <thead class="table-success">
                    <tr>
                        <th>Fecha</th>
                        <th>N° Factura</th>
                        @if($company === 'cofrupa')
                            <th>N° Contrato</th>
                        @endif
                        <th>Cliente</th>
                        <th>Calibre</th>
                        <th>Tipo</th>
                        <th>Kilos</th>
                        @if($company === 'cofrupa')
                            <th>Puerto Destino</th>
                            <th>País Destino</th>
                        @else
                            <th>Destino</th>
                        @endif
                        <th>T/C</th>
                        <th>P. Unit. ($)</th>
                        <th>P. Unit. (U$)</th>
                        @if($company === 'cofrupa')
                            <th>Total Venta (US$)</th>
                            <th>Abono (US$)</th>
                            <th>Saldo (US$)</th>
                        @else
                            <th>P. Neto ($)</th>
                            <th>P. Neto (US$)</th>
                            <th>IVA ($)</th>
                            <th>Bruto</th>
                        @endif
                        <th>Pago</th>
                        @if($company !== 'comercializadora')
                            <th>Plazo (días)</th>
                            <th>Fecha Pago</th>
                        @endif
                        <th>Estado</th>
                        @if($company === 'luis_gonzalez')
                            <th>Banco</th>
                        @endif
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="{{ $sale->paid ? 'table-light' : '' }}">
                            <td>{{ $sale->sale_date->format('d-m-Y') }}</td>
                            <td>{{ $sale->invoice_number ?: '-' }}</td>
                            @if($company === 'cofrupa')
                                <td>{{ $sale->contract_number ?: '-' }}</td>
                            @endif
                            <td>{{ $sale->client_name }}</td>
                            <td>{{ $sale->caliber ?: '-' }}</td>
                            <td>{{ $sale->type ?: '-' }}</td>
                            <td class="text-end">{{ number_format($sale->kilos, 2) }}</td>
                            @if($company === 'cofrupa')
                                <td>{{ $sale->destination_port ?: '-' }}</td>
                                <td>{{ $sale->destination_country ?: '-' }}</td>
                            @else
                                <td>{{ $sale->destination ?: '-' }}</td>
                            @endif
                            <td class="text-end">{{ $sale->exchange_rate ? number_format($sale->exchange_rate, 2) : '-' }}</td>
                            <td class="text-end">{{ $sale->unit_price_clp ? '$'.number_format($sale->unit_price_clp, 2) : '-' }}</td>
                            <td class="text-end">{{ $sale->unit_price_usd ? '$'.number_format($sale->unit_price_usd, 2) : '-' }}</td>
                            @if($company === 'cofrupa')
                                <td class="text-end"><strong>{{ $sale->total_sale_usd ? '$'.number_format($sale->total_sale_usd, 0) : '-' }}</strong></td>
                                <td class="text-end">{{ $sale->payment_usd ? '$'.number_format($sale->payment_usd, 0) : '-' }}</td>
                                <td class="text-end">{{ $sale->balance_usd ? '$'.number_format($sale->balance_usd, 0) : '-' }}</td>
                            @else
                                <td class="text-end">{{ $sale->net_price_clp ? '$'.number_format($sale->net_price_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $sale->net_price_usd ? '$'.number_format($sale->net_price_usd, 0) : '-' }}</td>
                                <td class="text-end">{{ $sale->iva_clp ? '$'.number_format($sale->iva_clp, 0) : '-' }}</td>
                                <td class="text-end"><strong>{{ $sale->gross_total ? '$'.number_format($sale->gross_total, 0) : '-' }}</strong></td>
                            @endif
                            <td class="text-center">
                                @if($sale->paid)
                                    <span class="badge bg-success">Si</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            @if($company !== 'comercializadora')
                                <td class="text-center">{{ $sale->payment_term_days ?: '-' }}</td>
                                <td>{{ $sale->payment_date ? $sale->payment_date->format('d-m-Y') : '-' }}</td>
                            @endif
                            <td><span class="badge bg-{{ $sale->status === 'paid' ? 'success' : 'warning' }}">{{ $sale->status_display }}</span></td>
                            @if($company === 'luis_gonzalez')
                                <td>{{ $sale->bank ?: '-' }}</td>
                            @endif
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('finance.sales.edit', $sale) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.sales.destroy', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta venta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center text-muted py-4">No hay ventas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
