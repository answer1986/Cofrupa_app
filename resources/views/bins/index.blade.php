@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-boxes"></i> Gestión de Bins</h2>
            <a href="{{ route('bins.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Bin
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Bins</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-hashtag"></i> Número</th>
                                <th><i class="fas fa-cubes"></i> Tipo</th>
                                <th><i class="fas fa-user-tag"></i> Propiedad</th>
                                <th><i class="fas fa-weight"></i> Peso Bin (Tara)</th>
                                <th><i class="fas fa-weight-hanging"></i> Peso Total Compras</th>
                                <th><i class="fas fa-truck"></i> Proveedor</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-calendar"></i> Entrega</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bins as $bin)
                            <tr class="{{ $bin->is_overdue ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $bin->bin_number }}</strong>
                                    @if($bin->photo_path)
                                        <br><small><i class="fas fa-camera text-muted"></i> Foto</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $bin->type === 'wood' ? 'success' : 'info' }}">
                                        {{ $bin->type_display }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        if ($bin->ownership_type === 'internal') {
                                            $badgeColor = 'primary';
                                        } elseif ($bin->ownership_type === 'supplier') {
                                            $badgeColor = 'secondary';
                                        } elseif ($bin->ownership_type === 'field') {
                                            $badgeColor = 'info';
                                        } else {
                                            $badgeColor = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ $bin->ownership_type_display }}
                                    </span>
                                </td>
                                <td>{{ number_format($bin->weight_capacity, 2) }} kg</td>
                                <td>
                                    <strong>{{ number_format($bin->total_purchase_weight, 2) }} kg</strong>
                                    @if($bin->purchases()->count() > 0)
                                        <br><small class="text-muted">{{ $bin->purchases()->count() }} compra(s)</small>
                                    @endif
                                </td>
                                <td>
                                    @if($bin->supplier)
                                        <strong>{{ $bin->supplier->name }}</strong>
                                        @if($bin->is_overdue)
                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Vencido</small>
                                        @endif
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $bin->status === 'available' ? 'success' : ($bin->status === 'in_use' ? 'primary' : ($bin->status === 'damaged' ? 'danger' : 'warning')) }}">
                                        {{ $bin->status_display }}
                                    </span>
                                    @if($bin->damage_description)
                                        <br><small class="text-danger" title="{{ $bin->damage_description }}">
                                            <i class="fas fa-exclamation-circle"></i> Dañado
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($bin->delivery_date)
                                        {{ $bin->delivery_date->format('d/m/Y') }}
                                        @if($bin->days_since_delivery)
                                            <br><small class="text-muted">{{ $bin->days_since_delivery }} días</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('bins.show', $bin) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('bins.edit', $bin) }}" class="btn btn-sm btn-warning" title="Editar bin">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        @if($bin->supplier_id && !$bin->return_date)
                                        <form action="{{ route('bins.return', $bin) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Marcar este bin como devuelto del proveedor?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="Marcar como devuelto">
                                                <i class="fas fa-undo"></i> Devolver
                                            </button>
                                        </form>
                                        @endif
                                        @if(!$bin->supplier_id && $bin->total_purchase_weight == 0)
                                        <form action="{{ route('bins.destroy', $bin) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar este bin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar bin">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-boxes fa-2x mb-2"></i>
                                    <br>
                                    No hay bins registrados aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bins->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bins->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $bins->total() }}</h3>
                <p class="text-muted mb-0">Total Bins</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $bins->where('status', 'available')->count() }}</h3>
                <p class="text-muted mb-0">Disponibles</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $bins->where('status', 'in_use')->count() }}</h3>
                <p class="text-muted mb-0">En Uso</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $bins->where('status', 'maintenance')->count() }}</h3>
                <p class="text-muted mb-0">Mantenimiento</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $bins->where('status', 'damaged')->count() }}</h3>
                <p class="text-muted mb-0">Dañados</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $bins->where('supplier_id', '!=', null)->count() }}</h3>
                <p class="text-muted mb-0">Asignados</p>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Bins Alert -->
@if($bins->where('is_overdue', true)->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Bins Vencidos</h5>
            <p class="mb-0">Hay {{ $bins->where('is_overdue', true)->count() }} bins que han excedido el tiempo límite de entrega (30 días).</p>
        </div>
    </div>
</div>
@endif
@endsection