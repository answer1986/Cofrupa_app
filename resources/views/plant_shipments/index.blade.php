@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Despachos a Plantas</h2>
                <a href="{{ route('plant-shipments.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Despacho
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('plant-shipments.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="plant_id" class="form-label">Planta</label>
                        <select class="form-control" id="plant_id" name="plant_id">
                            <option value="">Todas</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant->id }}" {{ request('plant_id') == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="payment_status" class="form-label">Estado de Pago</label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">Todos</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>No Pagado</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de despachos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Despachos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Guía</th>
                            <th>Planta</th>
                            <th>Destino</th>
                            <th>Chofer</th>
                            <th>Patente</th>
                            <th>Kilos</th>
                            <th>Costo</th>
                            <th>Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->shipment_date->format('d/m/Y') }}</td>
                                <td><strong>{{ $shipment->guide_number }}</strong></td>
                                <td>{{ $shipment->plant->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->destination }}</td>
                                <td>{{ $shipment->driver_name }}</td>
                                <td>{{ $shipment->vehicle_plate }}</td>
                                <td>{{ number_format($shipment->total_kilos, 2) }} kg</td>
                                <td>{{ $shipment->shipment_cost ? '$' . number_format($shipment->shipment_cost, 0, ',', '.') : '-' }}</td>
                                <td>
                                    @if($shipment->payment_status == 'paid')
                                        <span class="badge bg-success">Pagado</span>
                                    @else
                                        <span class="badge bg-warning">No Pagado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('plant-shipments.show', $shipment) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('plant-shipments.edit', $shipment) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('plant-shipments.destroy', $shipment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este despacho? Se devolverá el stock a los bins.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-truck fa-2x mb-2"></i>
                                    <br>
                                    No hay despachos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shipments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $shipments->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
