@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-boxes"></i> Detalles del Bin: {{ $bin->bin_number }}</h2>
            <div>
                <a href="{{ route('bins.edit', $bin) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('bins.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-hashtag"></i> Número:</strong> {{ $bin->bin_number }}</p>
                        <p><strong><i class="fas fa-cubes"></i> Tipo:</strong>
                            <span class="badge bg-{{ $bin->type === 'wood' ? 'success' : 'info' }}">
                                {{ $bin->type_display }}
                            </span>
                        </p>
                        <p><strong><i class="fas fa-weight"></i> Capacidad:</strong> {{ number_format($bin->weight_capacity, 2) }} kg</p>
                        <p><strong><i class="fas fa-weight-hanging"></i> Peso Actual:</strong> {{ number_format($bin->current_weight, 2) }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-info-circle"></i> Estado:</strong>
                            <span class="badge bg-{{ $bin->status === 'available' ? 'success' : ($bin->status === 'in_use' ? 'primary' : ($bin->status === 'damaged' ? 'danger' : 'warning')) }}">
                                {{ $bin->status_display }}
                            </span>
                        </p>
                        @if($bin->supplier)
                            <p><strong><i class="fas fa-truck"></i> Proveedor:</strong>
                                <a href="{{ route('suppliers.show', $bin->supplier) }}" class="text-decoration-none">
                                    {{ $bin->supplier->name }}
                                </a>
                            </p>
                        @else
                            <p><strong><i class="fas fa-truck"></i> Proveedor:</strong> <span class="text-muted">No asignado</span></p>
                        @endif
                        @if($bin->is_overdue)
                            <p><strong><i class="fas fa-exclamation-triangle text-danger"></i> Estado:</strong>
                                <span class="text-danger">Vencido ({{ $bin->days_since_delivery }} días)</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery/Return Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Información de Entrega</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-plus"></i> Fecha de Entrega:</strong>
                            @if($bin->delivery_date)
                                {{ $bin->delivery_date->format('d/m/Y') }}
                                @if($bin->days_since_delivery)
                                    <br><small class="text-muted">Hace {{ $bin->days_since_delivery }} días</small>
                                @endif
                            @else
                                <span class="text-muted">No entregado</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-check"></i> Fecha de Devolución:</strong>
                            @if($bin->return_date)
                                {{ $bin->return_date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">No devuelto</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($bin->supplier_id && !$bin->return_date)
                <div class="mt-3">
                    <form action="{{ route('bins.return', $bin) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('¿Marcar este bin como devuelto del proveedor?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-undo"></i> Marcar como Devuelto
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Damage Information -->
        @if($bin->damage_description || $bin->status === 'damaged')
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Información de Daños</h5>
            </div>
            <div class="card-body">
                @if($bin->damage_description)
                    <p><strong>Descripción:</strong> {{ $bin->damage_description }}</p>
                @endif
                @if($bin->status === 'damaged')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Este bin está marcado como dañado y requiere mantenimiento.
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($bin->notes)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $bin->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Photo -->
        @if($bin->photo_path)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-camera"></i> Foto del Bin</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $bin->photo_path) }}" alt="Foto del bin" class="img-fluid rounded">
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-camera"></i> Foto del Bin</h5>
            </div>
            <div class="card-body text-center">
                <div class="text-muted">
                    <i class="fas fa-camera fa-3x mb-2"></i>
                    <p>No hay foto disponible</p>
                    <a href="{{ route('bins.edit', $bin) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Agregar Foto
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                @if(!$bin->supplier_id)
                <form action="{{ route('bins.assign', $bin) }}" method="POST" class="mb-2">
                    @csrf
                    <div class="mb-2">
                        <label for="assign_supplier_id" class="form-label">Asignar a Proveedor:</label>
                        <select class="form-select form-select-sm" id="assign_supplier_id" name="supplier_id" required>
                            <option value="">Seleccionar proveedor</option>
                            @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="assign_delivery_date" class="form-label">Fecha de Entrega:</label>
                        <input type="date" class="form-control form-control-sm" id="assign_delivery_date" name="delivery_date" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-hand-holding"></i> Asignar Bin
                    </button>
                </form>
                @endif

                @if($bin->current_weight == 0 && !$bin->supplier_id)
                <form action="{{ route('bins.destroy', $bin) }}" method="POST"
                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este bin?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Bin
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection