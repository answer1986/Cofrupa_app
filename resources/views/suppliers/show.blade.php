@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Detalles del Proveedor</h2>
            <div>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nombre del Proveedor</label>
                            <p class="h5">{{ $supplier->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Razón Social</label>
                            <p class="h5">{{ $supplier->business_name ?: 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Ubicación</label>
                            <p class="h5">{{ $supplier->location }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Teléfono</label>
                            <p class="h5">{{ $supplier->phone ?: 'No especificado' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Giro Comercial</label>
                            <p class="h5">{{ $supplier->business_type ?: 'No especificado' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Fecha de Registro</label>
                            <p class="h5">{{ $supplier->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchases Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Historial de Compras ({{ $supplier->purchases->count() }})</h5>
            </div>
            <div class="card-body">
                @if($supplier->purchases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Orden de Compra</th>
                                    <th>Calibre</th>
                                    <th>Peso (kg)</th>
                                    <th>Precio Unit.</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->purchases->sortByDesc('purchase_date') as $purchase)
                                <tr>
                                    <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                    <td>{{ $purchase->purchase_order ?: 'N/A' }}</td>
                                    <td>{{ $purchase->calibre_display }}</td>
                                    <td>{{ number_format($purchase->weight_purchased, 2) }}</td>
                                    <td>${{ number_format($purchase->unit_price, 2) }}</td>
                                    <td>${{ number_format($purchase->calculated_total_amount, 2) }}</td>
                                    <td>${{ number_format($purchase->amount_paid, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $purchase->payment_status === 'paid' ? 'success' : ($purchase->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                            {{ $purchase->payment_status === 'paid' ? 'Pagado' : ($purchase->payment_status === 'partial' ? 'Parcial' : 'Pendiente') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay compras registradas para este proveedor.</p>
                @endif
            </div>
        </div>

        <!-- Bin Assignment History -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Historial de Bins Asignados ({{ $supplier->binAssignments->count() }})</h5>
            </div>
            <div class="card-body">
                @if($supplier->binAssignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Número de Bin</th>
                                <th>Tipo</th>
                                <th>Fecha de Entrega</th>
                                <th>Fecha de Devolución</th>
                                <th>Peso Entregado</th>
                                <th>Peso Devuelto</th>
                                <th>Estado</th>
                                <th>Días</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->binAssignments->sortByDesc('delivery_date') as $assignment)
                            <tr class="{{ $assignment->is_overdue ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $assignment->bin->bin_number }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $assignment->bin->type === 'wood' ? 'success' : 'info' }}">
                                        {{ $assignment->bin->type_display }}
                                    </span>
                                </td>
                                <td>{{ $assignment->delivery_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($assignment->return_date)
                                        {{ $assignment->return_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($assignment->weight_delivered, 2) }} kg</td>
                                <td>{{ number_format($assignment->weight_returned, 2) }} kg</td>
                                <td>
                                    @if($assignment->return_date)
                                        <span class="badge bg-success">Devuelto</span>
                                    @elseif($assignment->is_overdue)
                                        <span class="badge bg-danger">Vencido</span>
                                    @else
                                        <span class="badge bg-primary">En préstamo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->return_date)
                                        {{ $assignment->delivery_date->diffInDays($assignment->return_date) }}
                                    @else
                                        {{ $assignment->days_since_delivery }}
                                        @if($assignment->is_overdue)
                                            <br><small class="text-danger">!</small>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Current Bins Summary -->
                <div class="mt-3">
                    <h6><i class="fas fa-clock"></i> Bins Actualmente en Préstamo:</h6>
                    @php
                        $currentBins = $supplier->binAssignments->whereNull('return_date');
                    @endphp
                    @if($currentBins->count() > 0)
                        <div class="row">
                            @foreach($currentBins as $assignment)
                            <div class="col-md-6 mb-2">
                                <div class="card border-primary">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">{{ $assignment->bin->bin_number }}</h6>
                                        <small class="text-muted">
                                            Entregado: {{ $assignment->delivery_date->format('d/m/Y') }}<br>
                                            Hace {{ $assignment->days_since_delivery }} días
                                            @if($assignment->is_overdue)
                                                <span class="text-danger">(Vencido)</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay bins actualmente en préstamo.</p>
                    @endif
                </div>
                @else
                <p class="text-muted mb-0">No hay bins asignados a este proveedor.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Información Financiera</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Total Pagado</label>
                    <h4 class="text-success">${{ number_format($supplier->total_paid, 2) }}</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Deuda Total</label>
                    <h4 class="text-danger">${{ number_format($supplier->total_debt, 2) }}</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Saldo Pendiente</label>
                    <h4 class="{{ $supplier->pending_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($supplier->pending_amount, 2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Total de Compras</label>
                    <h4 class="text-primary">{{ $supplier->purchases->count() }}</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Peso Total Comprado</label>
                    <h4 class="text-info">{{ number_format($supplier->total_weight_purchased, 2) }} kg</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Bins Asignados</label>
                    <h4 class="text-warning">{{ $supplier->binAssignments->whereNull('return_date')->count() }}</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Total Bins Históricos</label>
                    <h4 class="text-secondary">{{ $supplier->binAssignments->count() }}</h4>
                </div>
            </div>
        </div>

        @if($supplier->binAssignments->count() === 0 && $supplier->purchases->count() === 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0 text-danger"><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Esta acción no se puede deshacer. El proveedor será eliminado permanentemente.
                </p>
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                      onsubmit="return confirm('¿Estás completamente seguro de que quieres eliminar este proveedor? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Proveedor
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection