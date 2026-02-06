@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-money-check-alt"></i> Registro de Pagos</h2>
                <a href="{{ route('finance.payments.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Registrar Pago
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

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Total Pagado</h6>
                    <h3 class="mb-0">${{ number_format($totalPaid, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Pendiente</h6>
                    <h3 class="mb-0">${{ number_format($totalPending, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Por Método de Pago</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($paymentsByMethod as $pm)
                            <span class="badge bg-secondary">
                                {{ ucfirst($pm->payment_method) }}: ${{ number_format($pm->total, 0, ',', '.') }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.payments.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Empresa</label>
                    <select name="company" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="cofrupa" {{ request('company') == 'cofrupa' ? 'selected' : '' }}>Cofrupa</option>
                        <option value="luis_gonzalez" {{ request('company') == 'luis_gonzalez' ? 'selected' : '' }}>Luis Gonzalez</option>
                        <option value="comercializadora" {{ request('company') == 'comercializadora' ? 'selected' : '' }}>Comercializadora</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Método</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="transferencia" {{ request('payment_method') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="efectivo" {{ request('payment_method') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ request('payment_method') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="otro" {{ request('payment_method') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="completado" {{ request('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="rechazado" {{ request('status') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        <option value="anulado" {{ request('status') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="payment_type" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="compra" {{ request('payment_type') == 'compra' ? 'selected' : '' }}>Compra</option>
                        <option value="venta" {{ request('payment_type') == 'venta' ? 'selected' : '' }}>Venta</option>
                        <option value="gasto" {{ request('payment_type') == 'gasto' ? 'selected' : '' }}>Gasto</option>
                        <option value="otro" {{ request('payment_type') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Pagos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Pagos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Empresa</th>
                            <th>Método</th>
                            <th>Referencia</th>
                            <th>Beneficiario</th>
                            <th>Monto</th>
                            <th>Factura</th>
                            <th>OC</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td><span class="badge bg-info">{{ $payment->company_display }}</span></td>
                            <td><i class="fas fa-{{ $payment->payment_method == 'cheque' ? 'money-check' : ($payment->payment_method == 'transferencia' ? 'exchange-alt' : 'money-bill-wave') }}"></i> {{ $payment->payment_method_display }}</td>
                            <td><code>{{ $payment->reference_number ?? '—' }}</code></td>
                            <td>{{ $payment->payee_name ?? '—' }}</td>
                            <td class="text-end"><strong>{{ $payment->currency }} {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                            <td>{{ $payment->invoice_number ?? '—' }}</td>
                            <td>{{ $payment->purchase_order ?? '—' }}</td>
                            <td><span class="badge bg-secondary">{{ $payment->payment_type_display }}</span></td>
                            <td>
                                <span class="badge bg-{{ $payment->status == 'completado' ? 'success' : ($payment->status == 'pendiente' ? 'warning' : 'danger') }}">
                                    {{ $payment->status_display }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('finance.payments.edit', $payment) }}" class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este pago?');">
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
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                                No hay pagos registrados con los filtros seleccionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
