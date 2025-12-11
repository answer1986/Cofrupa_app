@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar"></i> Reportes y Seguimiento Financiero</h2>
            <div>
                <a href="{{ route('reports.payments') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-clock"></i> Ver Todos los Pagos
                </a>
                <a href="{{ route('reports.supplier-debts') }}" class="btn btn-outline-info">
                    <i class="fas fa-users"></i> Deudas por Proveedor
                </a>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Financial Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h3 class="text-danger">${{ number_format($totalOutstanding, 2) }}</h3>
                <p class="text-muted mb-0">Total Adeudado</p>
                <small class="text-danger">Monto total pendiente de pago</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning">${{ number_format($totalDueThisWeek, 2) }}</h3>
                <p class="text-muted mb-0">Vence Esta Semana</p>
                <small class="text-warning">Pagos próximos (7 días)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h3 class="text-danger">${{ number_format($totalOverdue, 2) }}</h3>
                <p class="text-muted mb-0">Pagos Vencidos</p>
                <small class="text-danger">¡Requiere atención inmediata!</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Overdue Payments -->
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Pagos Vencidos</h5>
            </div>
            <div class="card-body">
                @if($overduePayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Monto</th>
                                    <th>Venció</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overduePayments->take(5) as $purchase)
                                <tr>
                                    <td>
                                        <strong>{{ $purchase->supplier->name }}</strong>
                                        @if($purchase->purchase_order)
                                            <br><small class="text-muted">{{ $purchase->purchase_order }}</small>
                                        @endif
                                    </td>
                                    <td class="text-danger">
                                        <strong>${{ number_format($purchase->amount_owed, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            Hace {{ $purchase->payment_due_date->diffInDays(now()) }} días
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($overduePayments->count() > 5)
                        <div class="text-center mt-2">
                            <a href="{{ route('reports.payments') }}" class="btn btn-sm btn-outline-danger">
                                Ver todos ({{ $overduePayments->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p>No hay pagos vencidos</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Payments -->
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Próximos Pagos (7 días)</h5>
            </div>
            <div class="card-body">
                @if($upcomingPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Monto</th>
                                    <th>Vence</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingPayments as $purchase)
                                <tr>
                                    <td>
                                        <strong>{{ $purchase->supplier->name }}</strong>
                                        @if($purchase->purchase_order)
                                            <br><small class="text-muted">{{ $purchase->purchase_order }}</small>
                                        @endif
                                    </td>
                                    <td class="text-warning">
                                        <strong>${{ number_format($purchase->amount_owed, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $purchase->payment_urgency_color }}">
                                            {{ $purchase->payment_urgency_text }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                        <p>No hay pagos próximos</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Suppliers with Debt -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users"></i> Proveedores con Deuda</h5>
            </div>
            <div class="card-body">
                @if($suppliersWithDebt->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Total Adeudado</th>
                                    <th>Total Pagado</th>
                                    <th>Compras Pendientes</th>
                                    <th>Próximo Vencimiento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliersWithDebt->take(10) as $supplier)
                                <tr>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                        <br><small class="text-muted">{{ $supplier->location }}</small>
                                    </td>
                                    <td class="text-danger">
                                        <strong>${{ number_format($supplier->total_debt, 2) }}</strong>
                                    </td>
                                    <td class="text-success">
                                        ${{ number_format($supplier->total_paid, 2) }}
                                    </td>
                                    <td>
                                        {{ $supplier->purchases->where('amount_owed', '>', 0)->count() }} compras
                                    </td>
                                    <td>
                                        @php
                                            $nextDue = $supplier->purchases->where('amount_owed', '>', 0)
                                                ->whereNotNull('payment_due_date')
                                                ->sortBy('payment_due_date')
                                                ->first();
                                        @endphp
                                        @if($nextDue)
                                            <span class="badge bg-{{ $nextDue->payment_urgency_color }}">
                                                {{ $nextDue->payment_urgency_text }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($suppliersWithDebt->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('reports.supplier-debts') }}" class="btn btn-outline-primary">
                                Ver todos los proveedores ({{ $suppliersWithDebt->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p>No hay proveedores con deuda pendiente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Timer Widget -->
@if($upcomingPayments->count() > 0 || $overduePayments->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-stopwatch"></i> Próximos Vencimientos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($upcomingPayments->take(6) as $purchase)
                    <div class="col-md-4 mb-3">
                        <div class="card border-{{ $purchase->payment_urgency_color }}">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ $purchase->supplier->name }}</h6>
                                <h4 class="text-{{ $purchase->payment_urgency_color }}">
                                    ${{ number_format($purchase->amount_owed, 2) }}
                                </h4>
                                <p class="card-text">
                                    <span class="badge bg-{{ $purchase->payment_urgency_color }}">
                                        {{ $purchase->payment_urgency_text }}
                                    </span>
                                </p>
                                @if($purchase->purchase_order)
                                    <small class="text-muted">{{ $purchase->purchase_order }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Auto-refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endsection