@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-building"></i> Mantenedor de Plantas</h2>
                <a href="{{ route('processing.plants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Planta
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
                        <th>Contacto</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plants as $plant)
                        <tr>
                            <td>{{ $plant->code }}</td>
                            <td>{{ $plant->name }}</td>
                            <td>{{ $plant->contact_person ?? 'N/A' }}</td>
                            <td>{{ $plant->email ?? 'N/A' }}</td>
                            <td>{{ $plant->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $plant->is_active ? 'success' : 'secondary' }}">
                                    {{ $plant->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('processing.plants.show', $plant->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('processing.plants.edit', $plant->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('processing.plants.destroy', $plant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta planta?');">
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
                            <td colspan="7" class="text-center">No hay plantas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



