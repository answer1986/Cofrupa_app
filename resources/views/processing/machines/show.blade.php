@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-cog"></i> {{ $machine->name }}</h2>
                <div>
                    <a href="{{ route('processing.machines.edit', $machine) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.machines.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-4">Código:</dt>
                        <dd class="col-8">{{ $machine->code }}</dd>
                        <dt class="col-4">Nombre:</dt>
                        <dd class="col-8">{{ $machine->name }}</dd>
                        <dt class="col-4">Tipo:</dt>
                        <dd class="col-8">{{ $machine->type ?? 'N/A' }}</dd>
                        <dt class="col-4">Marca:</dt>
                        <dd class="col-8">{{ $machine->brand ?? 'N/A' }}</dd>
                        <dt class="col-4">Modelo:</dt>
                        <dd class="col-8">{{ $machine->model ?? 'N/A' }}</dd>
                        <dt class="col-4">Nº Serie:</dt>
                        <dd class="col-8">{{ $machine->serial_number ?? 'N/A' }}</dd>
                        <dt class="col-4">Estado:</dt>
                        <dd class="col-8">
                            <span class="badge bg-{{ $machine->status == 'active' ? 'success' : ($machine->status == 'maintenance' ? 'warning' : 'secondary') }}">
                                {{ $machine->status_display }}
                            </span>
                        </dd>
                        <dt class="col-4">Fecha compra:</dt>
                        <dd class="col-8">{{ $machine->purchase_date ? $machine->purchase_date->format('d/m/Y') : 'N/A' }}</dd>
                        @if($machine->plant)
                            <dt class="col-4">Planta:</dt>
                            <dd class="col-8">
                                <a href="{{ route('processing.plants.show', $machine->plant) }}">{{ $machine->plant->name }}</a>
                            </dd>
                        @endif
                        @if($machine->notes)
                            <dt class="col-4">Notas:</dt>
                            <dd class="col-8">{{ $machine->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-wrench"></i> Mantenciones</h5>
                </div>
                <div class="card-body">
                    @if($machine->last_maintenance)
                        <p><strong>Última mantención:</strong> {{ $machine->last_maintenance->maintenance_date->format('d/m/Y') }} ({{ $machine->last_maintenance->maintenance_type_display }})</p>
                    @else
                        <p class="text-muted">Sin registros de mantención</p>
                    @endif
                    @if($machine->next_maintenance)
                        <p><strong>Próxima mantención:</strong> {{ $machine->next_maintenance->next_maintenance_date->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted">Sin próxima mantención programada</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Mantenciones</h5>
                    <a href="{{ route('processing.maintenances.create', ['machine_id' => $machine->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nueva Mantención
                    </a>
                </div>
                <div class="card-body">
                    @if($machine->maintenances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Periodicidad</th>
                                        <th>Próxima fecha</th>
                                        <th>Técnico</th>
                                        <th>Registrado por</th>
                                        <th>Costo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($machine->maintenances->sortByDesc('maintenance_date') as $m)
                                        <tr>
                                            <td>{{ $m->maintenance_date->format('d/m/Y') }}</td>
                                            <td>{{ $m->maintenance_type_display }}</td>
                                            <td>{{ $m->periodicity_display }}</td>
                                            <td>
                                                @if($m->next_maintenance_date)
                                                    @if($m->isOverdue())
                                                        <span class="text-danger">{{ $m->next_maintenance_date->format('d/m/Y') }} (vencida)</span>
                                                    @else
                                                        {{ $m->next_maintenance_date->format('d/m/Y') }}
                                                    @endif
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $m->technician ?? '—' }}</td>
                                            <td>{{ $m->user->name ?? '—' }}</td>
                                            <td>{{ $m->cost ? number_format($m->cost, 0, ',', '.') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No hay mantenciones registradas para esta máquina.</p>
                        <a href="{{ route('processing.maintenances.create', ['machine_id' => $machine->id]) }}" class="btn btn-sm btn-success mt-2">
                            <i class="fas fa-plus"></i> Registrar primera mantención
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
