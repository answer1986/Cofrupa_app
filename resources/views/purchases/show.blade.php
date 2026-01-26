@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shopping-cart"></i> Detalles de Compra</h2>
            <div>
                <a href="{{ route('bin_processing.create', ['purchase_id' => $purchase->id]) }}" class="btn btn-success me-2">
                    <i class="fas fa-qrcode"></i> Procesar Bins
                </a>
                <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Compra</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Comprador:</strong> 
                            <span class="badge bg-primary">{{ $purchase->buyer ?? 'Cofrupa' }}</span>
                        </p>
                        <p><strong>Fecha de Compra:</strong> {{ $purchase->purchase_date->format('d/m/Y') }}</p>
                        <p><strong>Orden de Compra:</strong> {{ $purchase->purchase_order ?: 'N/A' }}</p>
                        <p><strong>Proveedor:</strong> {{ $purchase->supplier->name }}</p>
                        <p><strong>Ubicación:</strong> {{ $purchase->supplier->location }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Peso Comprado:</strong> {{ number_format($purchase->weight_purchased, 2) }} kg</p>
                        <p><strong>Calibre:</strong> {{ $purchase->calibre_display }}</p>
                        <p><strong>Unidades por Libra:</strong> {{ $purchase->units_per_pound }}</p>
                        <p><strong>Precio por Unidad:</strong> ${{ number_format($purchase->unit_price, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Estado Financiero</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Calculado:</strong> ${{ number_format($purchase->calculated_total_amount, 2) }}</p>
                <p><strong>Monto Pagado:</strong> ${{ number_format($purchase->amount_paid, 2) }}</p>
                <p><strong>Monto Pendiente:</strong> ${{ number_format($purchase->amount_owed, 2) }}</p>
                <p><strong>Estado de Pago:</strong>
                    <span class="badge bg-{{ $purchase->payment_status === 'paid' ? 'success' : ($purchase->payment_status === 'partial' ? 'warning' : 'danger') }}">
                        {{ $purchase->payment_status === 'paid' ? 'Pagado' : ($purchase->payment_status === 'partial' ? 'Parcial' : 'Pendiente') }}
                    </span>
                </p>
                @if($purchase->payment_due_date)
                    <p><strong>Fecha Límite:</strong> {{ $purchase->payment_due_date->format('d/m/Y') }}</p>
                    <p><strong>Estado de Vencimiento:</strong>
                        <span class="badge bg-{{ $purchase->payment_urgency_color }}">
                            {{ $purchase->payment_urgency_text }}
                        </span>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Bins Asignados</h5>
            </div>
            <div class="card-body">
                @if($purchase->bins->count() > 0)
                    <div class="row">
                        @foreach($purchase->bins as $bin)
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title">{{ $bin->bin_number }}</h6>
                                            <p class="card-text mb-1">
                                                <strong>Peso Bin (Tara):</strong> {{ number_format($bin->weight_capacity, 2) }} kg<br>
                                                <strong>Peso Total Compras:</strong> {{ number_format($bin->total_purchase_weight, 2) }} kg<br>
                                                <strong>Estado:</strong>
                                                <span class="badge bg-{{ $bin->status === 'available' ? 'success' : ($bin->status === 'in_use' ? 'warning' : 'secondary') }}">
                                                    {{ $bin->status === 'available' ? 'Disponible' : ($bin->status === 'in_use' ? 'En Uso' : 'Mantenimiento') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                Peso asignado:<br>
                                                <strong>{{ number_format($purchase->weight_purchased / $purchase->bins->count(), 2) }} kg</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p>No hay bins asignados a esta compra</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($purchase->notes)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $purchase->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Creado: {{ $purchase->created_at->format('d/m/Y H:i') }} |
                        Última actualización: {{ $purchase->updated_at->format('d/m/Y H:i') }}
                    </small>
                    <div>
                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta compra? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Eliminar Compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection