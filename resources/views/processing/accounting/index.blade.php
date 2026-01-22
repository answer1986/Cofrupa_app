@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-calculator"></i> Módulo de Contabilidad</h2>
                <a href="{{ route('processing.accounting.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Dashboard de Totales -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3"><i class="fas fa-chart-line"></i> Resumen Financiero</h4>
        </div>
    </div>

    <!-- COSTOS -->
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="text-danger"><i class="fas fa-minus-circle"></i> COSTOS (Salidas de Dinero)</h5>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="card-title text-danger">💰 Compras a Proveedores</h6>
                    <h3 class="text-danger">{{ number_format($stats['total_compras'], 0, ',', '.') }}</h3>
                    <small class="text-muted">
                        Pendiente: <strong>{{ number_format($stats['total_compras_pendientes'], 0, ',', '.') }}</strong>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="card-title text-danger">🏭 Costo de Proceso (Plantas)</h6>
                    <h3 class="text-danger">{{ number_format($stats['total_costo_proceso'], 0, ',', '.') }}</h3>
                    <small class="text-muted">
                        Pendiente: <strong>{{ number_format($stats['total_procesos_pendientes'], 0, ',', '.') }}</strong>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="card-title text-danger">👔 Comisiones de Brokers</h6>
                    <h3 class="text-danger">{{ number_format($stats['total_comisiones_broker'], 0, ',', '.') }}</h3>
                    <small class="text-muted">1.5% - 3% de contratos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="card-title text-danger">🚢 Costos de Logística</h6>
                    <h3 class="text-danger">{{ number_format($stats['total_costos_logistica'], 0, ',', '.') }}</h3>
                    <small class="text-muted">Transporte + Naviera</small>
                </div>
            </div>
        </div>
    </div>

    <!-- INGRESOS Y MARGEN -->
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="text-success"><i class="fas fa-plus-circle"></i> INGRESOS</h5>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-success">💵 Ventas (Contratos)</h6>
                    <h3 class="text-success">{{ number_format($stats['total_ventas'], 0, ',', '.') }}</h3>
                    <small class="text-muted">Contratos activos/completados</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-dark">
                <div class="card-body">
                    <h6 class="card-title">📊 Total Costos</h6>
                    <h3 class="text-dark">{{ number_format($stats['total_costos'], 0, ',', '.') }}</h3>
                    <small class="text-muted">Suma de todos los costos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $stats['margen_bruto'] > 0 ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h6 class="card-title">💰 MARGEN BRUTO</h6>
                    <h2>{{ number_format($stats['margen_bruto'], 0, ',', '.') }}</h2>
                    <small>Ventas - Costos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $stats['porcentaje_margen'] > 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                <div class="card-body">
                    <h6 class="card-title">📈 % MARGEN</h6>
                    <h2>{{ number_format($stats['porcentaje_margen'], 2) }}%</h2>
                    <small>Rentabilidad</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Fórmula del Margen -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-calculator"></i> Cálculo del Margen:</h5>
                <p class="mb-0">
                    <strong>Margen Bruto</strong> = Ventas ({{ number_format($stats['total_ventas'], 0, ',', '.') }}) 
                    - [Compras ({{ number_format($stats['total_compras'], 0, ',', '.') }}) 
                    + Proceso ({{ number_format($stats['total_costo_proceso'], 0, ',', '.') }}) 
                    + Brokers ({{ number_format($stats['total_comisiones_broker'], 0, ',', '.') }}) 
                    + Logística ({{ number_format($stats['total_costos_logistica'], 0, ',', '.') }})]
                    = <strong>{{ number_format($stats['margen_bruto'], 0, ',', '.') }}</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Transacciones Registradas</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('processing.accounting.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tipo de Transacción</label>
                    <select name="transaction_type" class="form-select">
                        <option value="">Todas</option>
                        <option value="purchase" {{ request('transaction_type') == 'purchase' ? 'selected' : '' }}>Compra</option>
                        <option value="sale" {{ request('transaction_type') == 'sale' ? 'selected' : '' }}>Venta</option>
                        <option value="payment" {{ request('transaction_type') == 'payment' ? 'selected' : '' }}>Pago</option>
                        <option value="advance" {{ request('transaction_type') == 'advance' ? 'selected' : '' }}>Abono</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado de Pago</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Proveedor</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Transacciones -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Proveedor/Cliente</th>
                            <th>Contrato</th>
                            <th>Producto</th>
                            <th>Cantidad (kg)</th>
                            <th>Precio/kg</th>
                            <th>Total</th>
                            <th>Moneda</th>
                            <th>Abono</th>
                            <th>Restante</th>
                            <th>Estado Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                <td>
                                    @switch($transaction->transaction_type)
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
                                <td>{{ $transaction->supplier->name ?? $transaction->contract->client->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->contract->contract_number ?? 'N/A' }}</td>
                                <td>{{ $transaction->product_description ?? 'N/A' }}</td>
                                <td>{{ number_format($transaction->quantity_kg, 0, ',', '.') }}</td>
                                <td>{{ number_format($transaction->price_per_kg, 2, ',', '.') }}</td>
                                <td><strong>{{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                                <td>{{ $transaction->currency }}</td>
                                <td>{{ $transaction->advance_payment ? number_format($transaction->advance_payment, 0, ',', '.') : '-' }}</td>
                                <td>{{ $transaction->remaining_amount ? number_format($transaction->remaining_amount, 0, ',', '.') : '-' }}</td>
                                <td>
                                    @switch($transaction->payment_status)
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
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('processing.accounting.show', $transaction->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('processing.accounting.edit', $transaction->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('processing.accounting.destroy', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No hay transacciones registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection



