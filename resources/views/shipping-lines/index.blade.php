@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-ship"></i> Gestión de Navieras</h2>
            <a href="{{ route('shipping-lines.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Naviera
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Navieras</h5>
            </div>
            <div class="card-body">
                @if($shippingLines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Contacto</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Despachos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shippingLines as $line)
                                    <tr>
                                        <td>{{ $line->id }}</td>
                                        <td><strong>{{ $line->name }}</strong></td>
                                        <td><span class="badge bg-info">{{ $line->code ?? '-' }}</span></td>
                                        <td>{{ $line->contact_name ?? '-' }}</td>
                                        <td>{{ $line->contact_email ?? '-' }}</td>
                                        <td>{{ $line->contact_phone ?? '-' }}</td>
                                        <td>
                                            @if($line->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $line->shipments->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('shipping-lines.show', $line->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('shipping-lines.edit', $line->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('shipping-lines.destroy', $line->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta naviera?');">
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
                        {{ $shippingLines->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No hay navieras registradas. <a href="{{ route('shipping-lines.create') }}">Crear la primera</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



