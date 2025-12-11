@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Gestión de Proveedores</h2>
            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Proveedor
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Proveedores</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-building"></i> Nombre</th>
                                <th><i class="fas fa-hashtag"></i> Código Interno</th>
                                <th><i class="fas fa-barcode"></i> CSG</th>
                                <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                <th><i class="fas fa-phone"></i> Teléfono</th>
                                <th><i class="fas fa-briefcase"></i> Giro</th>
                                <th><i class="fas fa-boxes"></i> Bins</th>
                                <th><i class="fas fa-dollar-sign"></i> Deuda</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr>
                                <td>
                                    <strong>{{ $supplier->name }}</strong>
                                    @if($supplier->business_name)
                                        <br><small class="text-muted">{{ $supplier->business_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->internal_code)
                                        <span class="badge bg-primary">{{ $supplier->internal_code }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->csg_code)
                                        <span class="badge bg-secondary">{{ $supplier->csg_code }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $supplier->location }}</td>
                                <td>{{ $supplier->phone ?: 'No especificado' }}</td>
                                <td>{{ $supplier->business_type ?: 'No especificado' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $supplier->bins->count() }} bins</span>
                                </td>
                                <td>
                                    @if($supplier->pending_amount > 0)
                                        <span class="badge bg-danger">${{ number_format($supplier->pending_amount, 2) }}</span>
                                    @else
                                        <span class="badge bg-success">Al día</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning" title="Editar proveedor">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proveedor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar proveedor">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-truck fa-2x mb-2"></i>
                                    <br>
                                    No hay proveedores registrados aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($suppliers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $suppliers->links() }}
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
                <h3 class="text-primary">{{ $suppliers->total() }}</h3>
                <p class="text-muted mb-0">Total Proveedores</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $suppliers->sum(fn($s) => $s->bins->count()) }}</h3>
                <p class="text-muted mb-0">Total Bins</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">${{ number_format($suppliers->sum('total_paid'), 2) }}</h3>
                <p class="text-muted mb-0">Total Pagado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">${{ number_format($suppliers->sum('total_debt') - $suppliers->sum('total_paid'), 2) }}</h3>
                <p class="text-muted mb-0">Deuda Pendiente</p>
            </div>
        </div>
    </div>
</div>
@endsection