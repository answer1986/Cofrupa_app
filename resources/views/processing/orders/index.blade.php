@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-clipboard-list"></i> Envío de Órdenes</h2>
                <a href="{{ route('processing.orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Orden
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

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('processing.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Planta</label>
                    <select name="plant_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Plant::where('is_active', true)->get() as $plant)
                            <option value="{{ $plant->id }}" {{ request('plant_id') == $plant->id ? 'selected' : '' }}>
                                {{ $plant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Órdenes -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>N° Orden</th>
                            <th>Planta</th>
                            <th>Proveedor</th>
                            <th>CSG Code</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Días Producción</th>
                            <th>Fecha Orden</th>
                            <th>Fecha Término Esperada</th>
                            <th>Fecha Término Real</th>
                            <th>Progreso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->plant->name }}</td>
                                <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $order->csg_code ?? 'N/A' }}</td>
                                <td>{{ $order->product_description ?? 'N/A' }}</td>
                                <td>
                                    @if($order->quantity)
                                        {{ number_format($order->quantity, 0, ',', '.') }} {{ $order->unit }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $order->production_days ?? 'N/A' }}</td>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($order->expected_completion_date)
                                        {{ $order->expected_completion_date->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($order->actual_completion_date)
                                        {{ $order->actual_completion_date->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $order->progress_percentage == 100 ? 'bg-success' : ($order->progress_percentage >= 50 ? 'bg-info' : 'bg-warning') }}" 
                                             role="progressbar" 
                                             style="width: {{ $order->progress_percentage }}%"
                                             aria-valuenow="{{ $order->progress_percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $order->progress_percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'in_progress' ? 'info' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ $order->status_display }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('processing.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('processing.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('processing.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta orden?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No hay órdenes de proceso registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection



