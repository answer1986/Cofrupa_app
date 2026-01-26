@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building"></i> Agencias de Aduana</h2>
                <a href="{{ route('customs-agencies.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nueva Agencia de Aduana
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Agencias de Aduana</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Dirección</th>
                            <th>Contactos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agencies as $agency)
                            <tr>
                                <td><strong>{{ $agency->name }}</strong></td>
                                <td><span class="badge bg-info">{{ $agency->code ?? '-' }}</span></td>
                                <td>{{ $agency->address ?? 'N/A' }}</td>
                                <td>{{ $agency->contacts->count() }}</td>
                                <td>
                                    @if($agency->is_active)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customs-agencies.show', $agency->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customs-agencies.edit', $agency->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customs-agencies.destroy', $agency->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta agencia de aduana?');">
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
                                <td colspan="6" class="text-center">No hay agencias de aduana registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $agencies->links() }}
        </div>
    </div>
</div>
@endsection
