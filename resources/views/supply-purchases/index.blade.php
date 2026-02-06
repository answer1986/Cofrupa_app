@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-shopping-bag"></i> Compras de Insumos</h2>
                <a href="{{ route('supply-purchases.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nueva Compra de Insumos
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

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('supply-purchases.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Proveedor</label>
                    <input type="text" name="supplier_name" class="form-control form-control-sm" value="{{ request('supplier_name') }}" placeholder="Nombre del proveedor">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Comprador</label>
                    <select name="buyer" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Cofrupa" {{ request('buyer') == 'Cofrupa' ? 'selected' : '' }}>Cofrupa</option>
                        <option value="LG" {{ request('buyer') == 'LG' ? 'selected' : '' }}>LG</option>
                        <option value="Comercializadora" {{ request('buyer') == 'Comercializadora' ? 'selected' : '' }}>Comercializadora</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado Pago</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span class="text-muted">
                @if($purchases->total() > 0)
                    Mostrando {{ $purchases->firstItem() }} a {{ $purchases->lastItem() }} de {{ $purchases->total() }} compras
                @else
                    No hay compras registradas
                @endif
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>N° Factura</th>
                            <th>Comprador</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Pagado</th>
                            <th>Adeudado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : '-' }}</td>
                                <td>{{ $purchase->supplier_name ?? '-' }}</td>
                                <td>{{ $purchase->invoice_number ?: '-' }}</td>
                                <td><span class="badge bg-info">{{ $purchase->buyer ?? '-' }}</span></td>
                                <td>{{ $purchase->items->count() }} insumo(s)</td>
                                <td class="text-end">${{ number_format($purchase->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">${{ number_format($purchase->amount_paid ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end"><strong>${{ number_format($purchase->amount_owed ?? 0, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $purchase->payment_status === 'paid' ? 'success' : ($purchase->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ $purchase->status_display }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ url('supply-purchases/' . $purchase->id) }}" class="btn btn-outline-info" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ url('supply-purchases/' . $purchase->id . '/edit') }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ url('supply-purchases/' . $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta compra de insumos?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No hay compras de insumos registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($purchases->hasPages())
        <div class="mt-3">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection
