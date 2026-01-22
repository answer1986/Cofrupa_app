@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-tie"></i> Gestión de Brokers</h2>
            <a href="{{ route('brokers.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Broker
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Brokers</h5>
            </div>
            <div class="card-body">
                @if($brokers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Comisión (%)</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Contratos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brokers as $broker)
                                    <tr>
                                        <td>{{ $broker->id }}</td>
                                        <td><strong>{{ $broker->name }}</strong></td>
                                        <td><span class="badge bg-info">{{ number_format($broker->commission_percentage, 2) }}%</span></td>
                                        <td>{{ $broker->email ?? '-' }}</td>
                                        <td>{{ $broker->phone ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $broker->contracts->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('brokers.show', $broker->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('brokers.edit', $broker->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('brokers.destroy', $broker->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este broker?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $brokers->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No hay brokers registrados. <a href="{{ route('brokers.create') }}">Crear el primero</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

