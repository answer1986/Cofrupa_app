@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-shopping-bag"></i> Detalle de Compra de Insumos #{{ $supplyPurchase->id }}</h2>
                <div>
                    <a href="{{ route('supply-purchases.edit', $supplyPurchase) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('supply-purchases.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Información de la Compra</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Fecha:</strong><br>
                            {{ $supplyPurchase->purchase_date ? $supplyPurchase->purchase_date->format('d-m-Y') : '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Proveedor:</strong><br>
                            {{ $supplyPurchase->supplier_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>N° Factura:</strong><br>
                            {{ $supplyPurchase->invoice_number ?: '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Comprador:</strong><br>
                            <span class="badge bg-info">{{ $supplyPurchase->buyer }}</span>
                        </div>
                    </div>
                    @if($supplyPurchase->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Notas:</strong><br>
                            {{ $supplyPurchase->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Insumos Comprados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Insumo</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplyPurchase->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->name }}</strong></td>
                                        <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td class="text-end">${{ number_format($item->unit_price ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end"><strong>${{ number_format($item->total ?? 0, 0, ',', '.') }}</strong></td>
                                        <td>{{ $item->notes ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th class="text-end">${{ number_format($supplyPurchase->total_amount, 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Resumen de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Compra:</strong><br>
                        <h4 class="mb-0">${{ number_format($supplyPurchase->total_amount, 0, ',', '.') }}</h4>
                    </div>
                    <div class="mb-3">
                        <strong>Monto Pagado:</strong><br>
                        <h5 class="mb-0 text-success">${{ number_format($supplyPurchase->amount_paid, 0, ',', '.') }}</h5>
                    </div>
                    <div class="mb-3">
                        <strong>Monto Adeudado:</strong><br>
                        <h5 class="mb-0 text-danger">${{ number_format($supplyPurchase->amount_owed, 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div>
                        <strong>Estado:</strong><br>
                        <span class="badge bg-{{ $supplyPurchase->payment_status === 'paid' ? 'success' : ($supplyPurchase->payment_status === 'partial' ? 'warning' : 'danger') }} badge-lg">
                            {{ $supplyPurchase->status_display }}
                        </span>
                    </div>
                    @if($supplyPurchase->payment_due_date)
                    <div class="mt-3">
                        <strong>Fecha Vencimiento:</strong><br>
                        {{ $supplyPurchase->payment_due_date->format('d-m-Y') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
