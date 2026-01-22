@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-clipboard-list"></i> Programa de Producción</h2>
                <a href="{{ route('processing.production-orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Orden de Producción
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
            <form method="GET" action="{{ route('processing.production-orders.index') }}" class="row g-3">
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
                        <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Retrasado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Con Retraso</label>
                    <select name="has_delay" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('has_delay') == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ request('has_delay') == '0' ? 'selected' : '' }}>No</option>
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

    <!-- Tabla de Producción -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" style="font-size: 11pt;">
                    <thead class="table-dark">
                        <tr>
                            <th>CONTRATO</th>
                            <th>PLANTA PROCESO</th>
                            <th>PRODUCTO</th>
                            <th>CALIBRE SALIDA</th>
                            <th>CANTIDAD (kilos)</th>
                            <th>N° ORDEN</th>
                            <th>NUMERO DE RESERVA</th>
                            <th>MOTONAVE</th>
                            <th>FECHA TERMINO</th>
                            <th>HORA TERMINO</th>
                            <th>PROGRAMA DE PRODUCCION</th>
                            <th>SOLUCION SORBATO</th>
                            <th>ATRASO</th>
                            <th>RAZON DEL ATRASO</th>
                            <th>KILOS PRODUCIDOS</th>
                            <th>KG/HORA NOMINAL</th>
                            <th>DIA</th>
                            <th>HORAS ESTIMADAS</th>
                            <th>HORAS REALES</th>
                            <th>ESTADO</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="{{ $order->has_delay ? 'table-warning' : '' }}">
                                <td>{{ $order->contract->contract_number ?? 'N/A' }}</td>
                                <td>{{ $order->plant->name }}</td>
                                <td>{{ $order->product ?? 'N/A' }}</td>
                                <td>{{ $order->output_caliber ?? 'N/A' }}</td>
                                <td>{{ number_format($order->order_quantity_kg, 0, ',', '.') }}</td>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->booking_number ?? 'N/A' }}</td>
                                <td>{{ $order->vessel ?? 'N/A' }}</td>
                                <td>{{ $order->completion_date ? $order->completion_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $order->completion_time ? \Carbon\Carbon::parse($order->completion_time)->format('H:i:s') : 'N/A' }}</td>
                                <td>{{ $order->production_program ?? 'N/A' }}</td>
                                <td>{{ $order->sorbate_solution ?? 'N/A' }}</td>
                                <td class="{{ $order->delay_hours > 0 ? 'text-danger fw-bold' : '' }}">
                                    {{ $order->delay_hours ? number_format($order->delay_hours, 2, ',', '.') : '0' }}
                                </td>
                                <td>
                                    @if($order->delay_reason)
                                        <span class="badge bg-warning text-dark" title="{{ $order->delay_reason }}">
                                            {{ Str::limit($order->delay_reason, 30) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $order->produced_kilos ? number_format($order->produced_kilos, 0, ',', '.') : 'N/A' }}</td>
                                <td>{{ $order->nominal_kg_per_hour ? number_format($order->nominal_kg_per_hour, 0, ',', '.') : 'N/A' }}</td>
                                <td>{{ $order->day_of_week ?? 'N/A' }}</td>
                                <td>{{ $order->estimated_hours ? number_format($order->estimated_hours, 2, ',', '.') : 'N/A' }}</td>
                                <td>{{ $order->actual_hours ? number_format($order->actual_hours, 2, ',', '.') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'delayed' ? 'danger' : ($order->status === 'in_progress' ? 'info' : 'secondary')) }}">
                                        {{ $order->status_display }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('processing.production-orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('processing.production-orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('processing.production-orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta orden?');">
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
                                <td colspan="21" class="text-center">No hay órdenes de producción registradas</td>
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



