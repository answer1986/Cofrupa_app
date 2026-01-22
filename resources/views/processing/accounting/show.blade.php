@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-eye"></i> Detalle de Registro Contable</h2>
                <div>
                    <a href="{{ route('processing.accounting.edit', $accounting->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.accounting.index') }}" class="btn btn-secondary">
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
                    <h5 class="mb-0">Información de la Transacción</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Tipo:</th>
                            <td>
                                @switch($accounting->transaction_type)
                                    @case('purchase')
                                        <span class="badge bg-danger">Compra</span>
                                        @break
                                    @case('sale')
                                        <span class="badge bg-success">Venta</span>
                                        @break
                                    @case('payment')
                                        <span class="badge bg-info">Pago</span>
                                        @break
                                    @case('advance')
                                        <span class="badge bg-warning text-dark">Abono</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha Transacción:</th>
                            <td>{{ $accounting->transaction_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Cierre:</th>
                            <td>{{ $accounting->closing_date ? $accounting->closing_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Proveedor:</th>
                            <td>{{ $accounting->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contrato:</th>
                            <td>{{ $accounting->contract->contract_number ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Detalles del Producto</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Producto:</th>
                            <td>{{ $accounting->product_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Calibre/Rango:</th>
                            <td>{{ $accounting->size_range ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad:</th>
                            <td>{{ number_format($accounting->quantity_kg, 0, ',', '.') }} kg</td>
                        </tr>
                        <tr>
                            <th>Precio por kg:</th>
                            <td>{{ number_format($accounting->price_per_kg, 2, ',', '.') }} {{ $accounting->currency }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Cambio:</th>
                            <td>{{ $accounting->exchange_rate ? number_format($accounting->exchange_rate, 4, ',', '.') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Información de Pago</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Monto Total:</th>
                            <td><h4 class="mb-0">{{ number_format($accounting->total_amount, 0, ',', '.') }} {{ $accounting->currency }}</h4></td>
                        </tr>
                        <tr>
                            <th>Abono/Adelanto:</th>
                            <td>{{ $accounting->advance_payment ? number_format($accounting->advance_payment, 0, ',', '.') : '0' }} {{ $accounting->currency }}</td>
                        </tr>
                        <tr>
                            <th>Monto Restante:</th>
                            <td class="text-danger"><strong>{{ number_format($accounting->remaining_amount, 0, ',', '.') }} {{ $accounting->currency }}</strong></td>
                        </tr>
                        <tr>
                            <th>Estado de Pago:</th>
                            <td>
                                @switch($accounting->payment_status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @break
                                    @case('partial')
                                        <span class="badge bg-info">Parcial</span>
                                        @break
                                    @case('paid')
                                        <span class="badge bg-success">Pagado</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Método de Pago:</th>
                            <td>{{ $accounting->payment_method ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Banco:</th>
                            <td>{{ $accounting->bank_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cuenta:</th>
                            <td>{{ $accounting->bank_account ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Límite Pago:</th>
                            <td>{{ $accounting->payment_due_date ? $accounting->payment_due_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Real Pago:</th>
                            <td>{{ $accounting->actual_payment_date ? $accounting->actual_payment_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            @if($accounting->notes)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Notas</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $accounting->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



