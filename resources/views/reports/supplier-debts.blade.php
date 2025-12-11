@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> Deudas por Proveedor</h2>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Proveedores con Deuda Pendiente</h5>
            </div>
            <div class="card-body">
                @if($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Ubicación</th>
                                    <th>Total Adeudado</th>
                                    <th>Total Pagado</th>
                                    <th>Compras Pendientes</th>
                                    <th>Próximo Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                <tr>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                        @if($supplier->business_name)
                                            <br><small class="text-muted">{{ $supplier->business_name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $supplier->location }}</td>
                                    <td class="text-danger">
                                        <strong>${{ number_format($supplier->total_debt, 2) }}</strong>
                                    </td>
                                    <td class="text-success">
                                        ${{ number_format($supplier->total_paid, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $supplier->purchases->where('amount_owed', '>', 0)->count() }} compras
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $nextDue = $supplier->purchases->where('amount_owed', '>', 0)
                                                ->whereNotNull('payment_due_date')
                                                ->sortBy('payment_due_date')
                                                ->first();
                                        @endphp
                                        @if($nextDue)
                                            <div>
                                                <span class="badge bg-{{ $nextDue->payment_urgency_color }}">
                                                    {{ $nextDue->payment_urgency_text }}
                                                </span>
                                                <br><small class="text-muted">
                                                    ${{ number_format($nextDue->amount_owed, 2) }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($supplier->has_overdue_payments)
                                            <span class="badge bg-danger">Tiene Vencidos</span>
                                        @elseif($supplier->has_upcoming_payments)
                                            <span class="badge bg-warning">Próximos Vencimientos</span>
                                        @else
                                            <span class="badge bg-success">Al Día</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="tel:{{ $supplier->phone }}" class="btn btn-sm btn-success" title="Llamar">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Expandable row with purchase details -->
                                <tr class="table-light">
                                    <td colspan="8">
                                        <div class="collapse" id="details-{{ $supplier->id }}">
                                            @if($supplier->purchases->where('amount_owed', '>', 0)->count() > 0)
                                                <strong>Compras Pendientes:</strong>
                                                <div class="row mt-2">
                                                    @foreach($supplier->purchases->where('amount_owed', '>', 0)->sortBy('payment_due_date') as $purchase)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="card border-{{ $purchase->payment_urgency_color }}">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <small class="text-muted">{{ $purchase->purchase_date->format('d/m/Y') }}</small>
                                                                        @if($purchase->purchase_order)
                                                                            <br><small><strong>{{ $purchase->purchase_order }}</strong></small>
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <strong class="text-{{ $purchase->payment_urgency_color }}">
                                                                            ${{ number_format($purchase->amount_owed, 2) }}
                                                                        </strong>
                                                                        @if($purchase->payment_due_date)
                                                                            <br><small class="badge bg-{{ $purchase->payment_urgency_color }}">
                                                                                {{ $purchase->payment_urgency_text }}
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <em class="text-muted">No hay compras pendientes</em>
                                            @endif
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mt-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#details-{{ $supplier->id }}"
                                                aria-expanded="false">
                                            <i class="fas fa-chevron-down"></i> Ver Detalles
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $suppliers->links() }}
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4>No hay proveedores con deuda pendiente</h4>
                        <p>Todos los proveedores están al día con sus pagos.</p>
                        <a href="{{ route('reports.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
@if($suppliers->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-primary">{{ $suppliers->count() }}</h4>
                <p class="text-muted mb-0">Proveedores con Deuda</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-danger">
                    ${{ number_format($suppliers->sum('total_debt'), 2) }}
                </h4>
                <p class="text-muted mb-0">Total Adeudado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">
                    ${{ number_format($suppliers->sum('total_paid'), 2) }}
                </h4>
                <p class="text-muted mb-0">Total Pagado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-warning">
                    {{ $suppliers->sum(function($supplier) { return $supplier->purchases->where('amount_owed', '>', 0)->count(); }) }}
                </h4>
                <p class="text-muted mb-0">Compras Pendientes</p>
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Auto-refresh every 10 minutes for this detailed view
setTimeout(function() {
    location.reload();
}, 600000);
</script>
@endsection