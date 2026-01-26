@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tools"></i> Mantenciones de Máquinas</h2>
                <div>
                    <a href="{{ route('processing.machines.index') }}" class="btn btn-secondary">
                        <i class="fas fa-cog"></i> Gestionar Máquinas
                    </a>
                    <a href="{{ route('processing.maintenances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Registrar Mantención
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

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('processing.maintenances.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="machine_id">Máquina</label>
                    <select name="machine_id" id="machine_id" class="form-control">
                        <option value="">Todas las máquinas</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
                                {{ $machine->name }} ({{ $machine->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="maintenance_type">Tipo</label>
                    <select name="maintenance_type" id="maintenance_type" class="form-control">
                        <option value="">Todos los tipos</option>
                        <option value="preventive" {{ request('maintenance_type') == 'preventive' ? 'selected' : '' }}>Preventiva</option>
                        <option value="corrective" {{ request('maintenance_type') == 'corrective' ? 'selected' : '' }}>Correctiva</option>
                        <option value="predictive" {{ request('maintenance_type') == 'predictive' ? 'selected' : '' }}>Predictiva</option>
                        <option value="emergency" {{ request('maintenance_type') == 'emergency' ? 'selected' : '' }}>Emergencia</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="periodicity">Periodicidad</label>
                    <select name="periodicity" id="periodicity" class="form-control">
                        <option value="">Todas</option>
                        <option value="daily" {{ request('periodicity') == 'daily' ? 'selected' : '' }}>Diaria</option>
                        <option value="weekly" {{ request('periodicity') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                        <option value="monthly" {{ request('periodicity') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                        <option value="quarterly" {{ request('periodicity') == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                        <option value="biannual" {{ request('periodicity') == 'biannual' ? 'selected' : '' }}>Semestral</option>
                        <option value="annual" {{ request('periodicity') == 'annual' ? 'selected' : '' }}>Anual</option>
                        <option value="as_needed" {{ request('periodicity') == 'as_needed' ? 'selected' : '' }}>Según necesidad</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de mantenciones -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha Mantención</th>
                            <th>Máquina</th>
                            <th>Tipo</th>
                            <th>Periodicidad</th>
                            <th>Próxima Mantención</th>
                            <th>Técnico</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $maintenance->machine->name }}</strong><br>
                                    <small class="text-muted">{{ $maintenance->machine->code }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $maintenance->maintenance_type_display }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $maintenance->periodicity_display }}
                                    </span>
                                </td>
                                <td>
                                    @if($maintenance->next_maintenance_date)
                                        @if($maintenance->isOverdue())
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $maintenance->next_maintenance_date->format('d/m/Y') }}
                                            </span>
                                        @elseif($maintenance->next_maintenance_date->diffInDays(now()) <= 7)
                                            <span class="text-warning">
                                                <i class="fas fa-clock"></i> {{ $maintenance->next_maintenance_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            {{ $maintenance->next_maintenance_date->format('d/m/Y') }}
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $maintenance->technician ?? 'N/A' }}</td>
                                <td>
                                    @if($maintenance->cost)
                                        ${{ number_format($maintenance->cost, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($maintenance->next_maintenance_date)
                                        @if($maintenance->isOverdue())
                                            <span class="badge bg-danger">Vencida</span>
                                        @elseif($maintenance->next_maintenance_date->diffInDays(now()) <= 7)
                                            <span class="badge bg-warning">Próxima</span>
                                        @else
                                            <span class="badge bg-success">Al día</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Sin programar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('processing.maintenances.edit', $maintenance->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('processing.maintenances.destroy', $maintenance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta mantención?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @if($maintenance->description || $maintenance->observations)
                                <tr class="table-light">
                                    <td colspan="9">
                                        @if($maintenance->description)
                                            <strong>Descripción:</strong> {{ $maintenance->description }}<br>
                                        @endif
                                        @if($maintenance->observations)
                                            <strong>Observaciones:</strong> {{ $maintenance->observations }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No se encontraron mantenciones registradas</p>
                                    <a href="{{ route('processing.maintenances.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i> Registrar Primera Mantención
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($maintenances->hasPages())
                <div class="mt-4">
                    {{ $maintenances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
