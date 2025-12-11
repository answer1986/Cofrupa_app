@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock"></i> Seguimiento de Pagos</h2>
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

<!-- Filter Controls -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Vencidos</option>
                            <option value="urgent" {{ request('status') == 'urgent' ? 'selected' : '' }}>Urgentes (≤3 días)</option>
                            <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>Advertencia (≤7 días)</option>
                            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="supplier_id" class="form-label">Proveedor</label>
                        <select name="supplier_id" id="supplier_id" class="form-select">
                            <option value="">Todos los proveedores</option>
                            @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="min_amount" class="form-label">Monto Mínimo</label>
                        <input type="number" step="0.01" name="min_amount" id="min_amount"
                               class="form-control" value="{{ request('min_amount') }}"
                               placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label for="max_amount" class="form-label">Monto Máximo</label>
                        <input type="number" step="0.01" name="max_amount" id="max_amount"
                               class="form-control" value="{{ request('max_amount') }}"
                               placeholder="0.00">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('reports.payments') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times"></i> Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Pagos Pendientes
                    <span class="badge bg-primary ms-2">{{ $purchases->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($purchases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Fecha Compra</th>
                                    <th>Orden</th>
                                    <th>Calibre</th>
                                    <th>Peso</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Pendiente</th>
                                    <th>Fecha Límite</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr class="{{ $purchase->is_overdue ? 'table-danger' : ($purchase->payment_urgency == 'urgent' ? 'table-warning' : '') }}">
                                    <td>
                                        <strong>{{ $purchase->supplier->name }}</strong>
                                        <br><small class="text-muted">{{ $purchase->supplier->location }}</small>
                                    </td>
                                    <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($purchase->purchase_order)
                                            {{ $purchase->purchase_order }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $purchase->calibre_display }}</td>
                                    <td>{{ number_format($purchase->weight_purchased, 2) }} kg</td>
                                    <td>${{ number_format($purchase->calculated_total_amount, 2) }}</td>
                                    <td class="text-success">${{ number_format($purchase->amount_paid, 2) }}</td>
                                    <td class="text-danger">
                                        <strong>${{ number_format($purchase->amount_owed, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($purchase->payment_due_date)
                                            {{ $purchase->payment_due_date->format('d/m/Y') }}
                                            @if($purchase->days_until_due !== null)
                                                <br><small class="{{ $purchase->is_overdue ? 'text-danger' : 'text-muted' }}">
                                                    {{ $purchase->payment_urgency_text }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">Sin fecha</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $purchase->payment_urgency_color }}">
                                            @if($purchase->is_overdue)
                                                Vencido
                                            @elseif($purchase->payment_urgency == 'urgent')
                                                Urgente
                                            @elseif($purchase->payment_urgency == 'warning')
                                                Advertencia
                                            @else
                                                Normal
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('suppliers.show', $purchase->supplier) }}" class="btn btn-sm btn-secondary" title="Ver Proveedor">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="tel:{{ $purchase->supplier->phone }}" class="btn btn-sm btn-success" title="Llamar">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $purchases->appends(request()->query())->links() }}
                    </div>

                    <!-- Summary -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-center border-primary">
                                <div class="card-body">
                                    <h5 class="text-primary">{{ $purchases->count() }}</h5>
                                    <p class="text-muted mb-0">Pagos Mostrados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-danger">
                                <div class="card-body">
                                    <h5 class="text-danger">
                                        ${{ number_format($purchases->sum('amount_owed'), 2) }}
                                    </h5>
                                    <p class="text-muted mb-0">Total Pendiente</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-warning">
                                <div class="card-body">
                                    <h5 class="text-warning">
                                        {{ $purchases->where('is_overdue', true)->count() }}
                                    </h5>
                                    <p class="text-muted mb-0">Pagos Vencidos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-info">
                                <div class="card-body">
                                    <h5 class="text-info">
                                        {{ $purchases->where('payment_urgency', 'urgent')->count() }}
                                    </h5>
                                    <p class="text-muted mb-0">Urgentes (≤3 días)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4>No hay pagos pendientes</h4>
                        <p>Todos los pagos están al día.</p>
                        <a href="{{ route('reports.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);

// Add some visual enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Highlight overdue rows more prominently
    const overdueRows = document.querySelectorAll('.table-danger');
    overdueRows.forEach(row => {
        row.style.animation = 'blink 2s infinite';
    });
});

const style = document.createElement('style');
style.textContent = `
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.7; }
    }
`;
document.head.appendChild(style);
</script>
@endsection