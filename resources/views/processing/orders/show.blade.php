@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-eye"></i> Orden de Proceso: {{ $order->order_number }}</h2>
                <div>
                    <a href="{{ route('processing.orders.edit', $order->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">N° Orden:</th>
                            <td><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Planta:</th>
                            <td><strong>{{ $order->plant->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Proveedor:</th>
                            <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contrato:</th>
                            <td>{{ $order->contract->contract_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CSG Code:</th>
                            <td>{{ $order->csg_code ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'in_progress' ? 'info' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                    {{ $order->status_display }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tiempos y Progreso</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Fecha Orden:</th>
                            <td>{{ $order->order_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tiempo Producción:</th>
                            <td>{{ $order->production_days ?? 'N/A' }} días</td>
                        </tr>
                        <tr>
                            <th>Fecha Término Esperada:</th>
                            <td>{{ $order->expected_completion_date ? $order->expected_completion_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Término Real:</th>
                            <td>{{ $order->actual_completion_date ? $order->actual_completion_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Progreso:</th>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $order->progress_percentage == 100 ? 'bg-success' : ($order->progress_percentage >= 50 ? 'bg-info' : 'bg-warning') }}" 
                                         role="progressbar" 
                                         style="width: {{ $order->progress_percentage }}%"
                                         aria-valuenow="{{ $order->progress_percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $order->progress_percentage }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Detalles del Producto</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Descripción:</th>
                            <td>{{ $order->product_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad:</th>
                            <td>
                                @if($order->quantity)
                                    {{ number_format($order->quantity, 0, ',', '.') }} {{ $order->unit }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Facturas de Retorno</h5>
                </div>
                <div class="card-body">
                    @if($order->invoices->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>N° Factura</th>
                                    <th>Monto</th>
                                    <th>Moneda</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>{{ $invoice->currency }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->is_paid ? 'success' : 'warning' }}">
                                                {{ $invoice->is_paid ? 'Pagado' : 'Pendiente' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">No hay facturas registradas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($order->notes)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Notas</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection



