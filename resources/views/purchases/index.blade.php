@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shopping-cart"></i> Gestión de Compras</h2>
            <a href="{{ route('purchases.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Compra
            </a>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Compras</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Fecha</th>
                                <th><i class="fas fa-file-invoice"></i> Orden</th>
                                <th><i class="fas fa-truck"></i> Proveedor</th>
                                <th><i class="fas fa-boxes"></i> Bins</th>
                                <th><i class="fas fa-weight"></i> Peso (kg)</th>
                                <th><i class="fas fa-tag"></i> Calibre</th>
                                <th><i class="fas fa-dollar-sign"></i> Total</th>
                                <th><i class="fas fa-credit-card"></i> Estado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                <td>{{ $purchase->purchase_order ?: 'N/A' }}</td>
                                <td>
                                    <strong>{{ $purchase->supplier->name }}</strong>
                                </td>
                                <td>{{ $purchase->bins_display }}</td>
                                <td>{{ number_format($purchase->weight_purchased, 2) }}</td>
                                <td>{{ $purchase->calibre_display }}</td>
                                <td>${{ number_format($purchase->calculated_total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $purchase->payment_status === 'paid' ? 'success' : ($purchase->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ $purchase->payment_status === 'paid' ? 'Pagado' : ($purchase->payment_status === 'partial' ? 'Parcial' : 'Pendiente') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning" title="Editar compra">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta compra?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar compra">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <br>
                                    No hay compras registradas aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($purchases->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $purchases->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $purchases->total() }}</h3>
                <p class="text-muted mb-0">Total Compras</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ number_format($purchases->sum('weight_purchased'), 2) }}</h3>
                <p class="text-muted mb-0">Total Peso (kg)</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">${{ number_format($purchases->sum('amount_paid'), 2) }}</h3>
                <p class="text-muted mb-0">Total Pagado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">${{ number_format($purchases->sum('amount_owed'), 2) }}</h3>
                <p class="text-muted mb-0">Total Pendiente</p>
            </div>
        </div>
    </div>
</div>
@endsection