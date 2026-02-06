@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="fas fa-truck"></i> Gestión de Fletes</h2>
                    <p class="text-muted mb-0">Costos de transporte en todas las etapas de la operación</p>
                </div>
                <div>
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Volver a Ventas
                    </a>
                    <a href="{{ route('ventas.fletes.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Flete
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Pendientes de Pago</h6>
                    <h3 class="mb-0 text-warning">${{ number_format($stats['total_pending'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Pagados</h6>
                    <h3 class="mb-0 text-success">${{ number_format($stats['total_paid'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Fletes</h6>
                    <h3 class="mb-0 text-primary">${{ number_format($stats['total_pending'] + $stats['total_paid'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.fletes.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="freight_type" class="form-label">Tipo de Flete</label>
                        <select class="form-select" id="freight_type" name="freight_type">
                            <option value="">Todos</option>
                            <option value="reception" {{ request('freight_type') === 'reception' ? 'selected' : '' }}>Recepción de Fruta</option>
                            <option value="to_processing" {{ request('freight_type') === 'to_processing' ? 'selected' : '' }}>Envío a Procesamiento</option>
                            <option value="to_port" {{ request('freight_type') === 'to_port' ? 'selected' : '' }}>Envío a Puerto</option>
                            <option value="supply_purchase" {{ request('freight_type') === 'supply_purchase' ? 'selected' : '' }}>Compra de Insumos</option>
                            <option value="other" {{ request('freight_type') === 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_status" class="form-label">Estado de Pago</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Pagado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de fletes --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Registro de Fletes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Chofer</th>
                            <th>Patente</th>
                            <th>Guía</th>
                            <th>Kilos</th>
                            <th>Costo</th>
                            <th>Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($freights as $f)
                            <tr>
                                <td>{{ $f->freight_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $f->freight_type_display }}</span>
                                </td>
                                <td>{{ $f->origin ?? '-' }}</td>
                                <td>{{ $f->destination ?? '-' }}</td>
                                <td>{{ $f->driver_name ?? '-' }}</td>
                                <td>{{ $f->vehicle_plate ?? '-' }}</td>
                                <td>{{ $f->guide_number ?? '-' }}</td>
                                <td>{{ $f->kilos ? number_format($f->kilos, 2) . ' kg' : '-' }}</td>
                                <td><strong>${{ number_format($f->freight_cost, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($f->payment_status === 'paid')
                                        <span class="badge bg-success">Pagado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ventas.fletes.show', $f) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('ventas.fletes.edit', $f) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ventas.fletes.destroy', $f) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este flete?')">
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
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-truck fa-2x mb-2"></i>
                                    <br>
                                    No hay fletes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($freights->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $freights->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Resumen por tipo de flete --}}
    @if($stats['by_type']->isNotEmpty())
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Resumen por Tipo de Flete</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $typeLabels = [
                            'reception' => 'Recepción de Fruta',
                            'to_processing' => 'Envío a Procesamiento',
                            'to_port' => 'Envío a Puerto',
                            'supply_purchase' => 'Compra de Insumos',
                            'other' => 'Otro',
                        ];
                    @endphp
                    @foreach($stats['by_type'] as $type => $total)
                        <div class="col-md-4 mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ $typeLabels[$type] ?? $type }}:</span>
                                <strong>${{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
