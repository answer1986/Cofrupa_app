@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-clipboard-list"></i> Petición de cupos</h2>
            <p class="text-muted mb-0">Ver qué tarjas se rebajaron, kilos enviados vs devueltos, rendimiento, camión, planta, fecha y orden.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('processing.request.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Planta</label>
                    <select name="plant_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($plants as $plant)
                            <option value="{{ $plant->id }}" {{ request('plant_id') == $plant->id ? 'selected' : '' }}>{{ $plant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha desde</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha hasta</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="low_performance" value="1" id="low_performance" {{ request('low_performance') ? 'checked' : '' }}>
                        <label class="form-check-label" for="low_performance">Rendimiento bajo</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla: tarjas rebajadas, kilos enviados vs devueltos, rendimiento -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Orden</th>
                            <th>Planta</th>
                            <th>Fecha envío</th>
                            <th>Horario</th>
                            <th>Patente (camión)</th>
                            <th>Tarjas rebajadas</th>
                            <th>Kilos enviados</th>
                            <th>Kilos devueltos</th>
                            <th>Rendimiento</th>
                            <th>Calibre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $kilosSent = $order->kilos_sent ? (float) $order->kilos_sent : null;
                                $kilosProduced = $order->kilos_produced !== null ? (float) $order->kilos_produced : null;
                                $rendimiento = null;
                                if ($kilosSent && $kilosSent > 0 && $kilosProduced !== null) {
                                    $rendimiento = round(($kilosProduced / $kilosSent) * 100, 1);
                                }
                                $lowPerformance = $rendimiento !== null && $rendimiento < 70;
                                $tarjas = $order->tarjas ?? collect();
                                $tarjasCount = $tarjas->count();
                                $tarjasKilos = $tarjas->sum(fn($t) => (float) ($t->pivot->quantity_kg ?? 0));
                            @endphp
                            <tr class="{{ $lowPerformance ? 'table-warning' : '' }}">
                                <td>
                                    <a href="{{ route('processing.orders.show', $order) }}">{{ $order->order_number }}</a>
                                </td>
                                <td>{{ $order->plant->name ?? '-' }}</td>
                                <td>{{ $order->shipment_date ? $order->shipment_date->format('d/m/Y') : ($order->order_date ? $order->order_date->format('d/m/Y') : '-') }}</td>
                                <td>{{ $order->shipment_time ? \Carbon\Carbon::parse($order->shipment_time)->format('H:i') : '-' }}</td>
                                <td>{{ $order->vehicle_plate ?: '-' }}</td>
                                <td>
                                    @if($tarjasCount > 0)
                                        <span title="{{ $tarjas->pluck('tarja_number')->join(', ') }}">
                                            {{ $tarjasCount }} tarja(s) · {{ number_format($tarjasKilos, 1) }} kg
                                        </span>
                                        <br><small class="text-muted">{{ $tarjas->take(3)->pluck('tarja_number')->join(', ') }}{{ $tarjasCount > 3 ? '…' : '' }}</small>
                                    @else
                                        <span class="text-muted">Sin tarjas</span>
                                    @endif
                                </td>
                                <td>{{ $kilosSent !== null ? number_format($kilosSent, 1) : '-' }}</td>
                                <td>{{ $kilosProduced !== null ? number_format($kilosProduced, 1) : '-' }}</td>
                                <td>
                                    @if($rendimiento !== null)
                                        {{ $rendimiento }}%
                                        @if($lowPerformance)
                                            <span class="badge bg-warning text-dark">Rendimiento bajo</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $order->caliber ?: '-' }}</td>
                                <td><span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'in_progress' ? 'info' : 'secondary') }}">{{ $order->status_display }}</span></td>
                                <td>
                                    <a href="{{ route('processing.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Ver orden / rebajar tarjas">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('processing.orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary" title="Editar orden">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">No hay órdenes que coincidan con los filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('processing.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-clipboard-list"></i> Ir a Envío de Órdenes
        </a>
        <a href="{{ route('processing.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Orden
        </a>
    </div>
</div>
@endsection
