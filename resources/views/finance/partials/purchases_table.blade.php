<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0" style="font-size: 0.85rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        @if($company === 'cofrupa')
                            <th>Factura Compra</th>
                            <th>Proveedor</th>
                            <th>Producto/Calibre</th>
                            <th>Tipo</th>
                            <th>Kilos</th>
                            <th>T/C</th>
                            <th>P. Unit. Neto ($)</th>
                            <th>P. Unit. Neto (U$)</th>
                            <th>Total Neto $</th>
                            <th>Total Neto US$</th>
                            <th>IVA</th>
                            <th>Total US$</th>
                            <th>Total (Pesos)</th>
                            <th>Prom Kilo</th>
                            <th>Estado</th>
                        @elseif($company === 'luis_gonzalez')
                            <th>Productor</th>
                            <th>Tipo</th>
                            <th>Kilos</th>
                            <th>P. Unit. Neto</th>
                            <th>P. Total Neto</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Comisión ($/Kilo)</th>
                            <th>Total Comisión</th>
                            <th>Flete ($/Kilo)</th>
                            <th>Total Flete</th>
                            <th>Otros ($)</th>
                            <th>Total ($)</th>
                            <th>Prom Kilo</th>
                            <th>Estado</th>
                        @else
                            <th>Productor</th>
                            <th>Tipo</th>
                            <th>Kilos</th>
                            <th>Calibre</th>
                            <th>P. Unit. Neto ($)</th>
                            <th>P. Unit. US$</th>
                            <th>Total Neto</th>
                            <th>Total US$</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Comisión ($/Kilo)</th>
                            <th>Total Comisión</th>
                            <th>Prom Kilo</th>
                            <th>Estado</th>
                        @endif
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->purchase_date->format('d-m-Y') }}</td>
                            @if($company === 'cofrupa')
                                <td>{{ $purchase->invoice_number ?: '-' }}</td>
                                <td>{{ $purchase->supplier_name }}</td>
                                <td>{{ $purchase->product_caliber ?: '-' }}</td>
                                <td>{{ $purchase->type ?: '-' }}</td>
                                <td class="text-end">{{ number_format($purchase->kilos, 2) }}</td>
                                <td>-</td>
                                <td class="text-end">{{ $purchase->unit_price_clp ? '$'.number_format($purchase->unit_price_clp, 2) : '-' }}</td>
                                <td class="text-end">{{ $purchase->unit_price_usd ? '$'.number_format($purchase->unit_price_usd, 2) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_net_clp ? '$'.number_format($purchase->total_net_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_net_usd ? '$'.number_format($purchase->total_net_usd, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->iva ? '$'.number_format($purchase->iva, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_usd ? '$'.number_format($purchase->total_usd, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_clp ? '$'.number_format($purchase->total_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->average_per_kilo ? '$'.number_format($purchase->average_per_kilo, 0) : '-' }}</td>
                                <td><span class="badge bg-{{ $purchase->status === 'paid' ? 'success' : 'warning' }}">{{ $purchase->status_display }}</span></td>
                            @elseif($company === 'luis_gonzalez')
                                <td>{{ $purchase->supplier_name }}</td>
                                <td>{{ $purchase->type ?: '-' }}</td>
                                <td class="text-end">{{ number_format($purchase->kilos, 2) }}</td>
                                <td class="text-end">{{ $purchase->unit_price_clp ? '$'.number_format($purchase->unit_price_clp, 2) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_net_clp ? '$'.number_format($purchase->total_net_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->iva ? '$'.number_format($purchase->iva, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_clp ? '$'.number_format($purchase->total_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->commission_per_kilo ? '$'.number_format($purchase->commission_per_kilo, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_commission ? '$'.number_format($purchase->total_commission, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->freight_per_kilo ? '$'.number_format($purchase->freight_per_kilo, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_freight ? '$'.number_format($purchase->total_freight, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->other_costs ? '$'.number_format($purchase->other_costs, 0) : '-' }}</td>
                                <td class="text-end"><strong>{{ $purchase->final_total ? '$'.number_format($purchase->final_total, 0) : '-' }}</strong></td>
                                <td class="text-end">{{ $purchase->average_per_kilo ? '$'.number_format($purchase->average_per_kilo, 0) : '-' }}</td>
                                <td><span class="badge bg-{{ $purchase->status === 'paid' ? 'success' : 'warning' }}">{{ $purchase->status_display }}</span></td>
                            @else
                                <td>{{ $purchase->supplier_name }}</td>
                                <td>{{ $purchase->type ?: '-' }}</td>
                                <td class="text-end">{{ number_format($purchase->kilos, 2) }}</td>
                                <td>{{ $purchase->product_caliber ?: '-' }}</td>
                                <td class="text-end">{{ $purchase->unit_price_clp ? '$'.number_format($purchase->unit_price_clp, 2) : '-' }}</td>
                                <td class="text-end">{{ $purchase->unit_price_usd ? '$'.number_format($purchase->unit_price_usd, 2) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_net_clp ? '$'.number_format($purchase->total_net_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_net_usd ? '$'.number_format($purchase->total_net_usd, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->iva ? '$'.number_format($purchase->iva, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_clp ? '$'.number_format($purchase->total_clp, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->commission_per_kilo ? '$'.number_format($purchase->commission_per_kilo, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->total_commission ? '$'.number_format($purchase->total_commission, 0) : '-' }}</td>
                                <td class="text-end">{{ $purchase->average_per_kilo ? '$'.number_format($purchase->average_per_kilo, 0) : '-' }}</td>
                                <td><span class="badge bg-{{ $purchase->status === 'paid' ? 'success' : 'warning' }}">{{ $purchase->status_display }}</span></td>
                            @endif
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('finance.purchases.edit', $purchase) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.purchases.destroy', $purchase) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta compra?')">
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
                            <td colspan="20" class="text-center text-muted py-4">No hay compras registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
