@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-cog"></i> Máquinas Internas</h2>
                <a href="{{ route('processing.machines.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Máquina
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

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado</th>
                        <th>Última Mantención</th>
                        <th>Próxima Mantención</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($machines as $machine)
                        <tr>
                            <td>{{ $machine->code }}</td>
                            <td><strong>{{ $machine->name }}</strong></td>
                            <td>{{ $machine->type ?? 'N/A' }}</td>
                            <td>{{ $machine->brand ?? 'N/A' }}</td>
                            <td>{{ $machine->model ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $machine->status == 'active' ? 'success' : ($machine->status == 'maintenance' ? 'warning' : 'secondary') }}">
                                    {{ $machine->status_display }}
                                </span>
                            </td>
                            <td>
                                @if($machine->last_maintenance)
                                    {{ $machine->last_maintenance->maintenance_date->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($machine->next_maintenance)
                                    @if($machine->next_maintenance->isOverdue())
                                        <span class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $machine->next_maintenance->next_maintenance_date->format('d/m/Y') }}
                                        </span>
                                    @else
                                        {{ $machine->next_maintenance->next_maintenance_date->format('d/m/Y') }}
                                    @endif
                                @else
                                    <span class="text-muted">Sin programar</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('processing.machines.show', $machine->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('processing.machines.edit', $machine->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('processing.machines.destroy', $machine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta máquina?');">
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
                            <td colspan="9" class="text-center">No hay máquinas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
