@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Gestión de Empresas Logísticas</h2>
            <a href="{{ route('logistics-companies.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Empresa Logística
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Empresas Logísticas</h5>
            </div>
            <div class="card-body">
                @if($logisticsCompanies->count() > 0)
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
                                @foreach($logisticsCompanies as $company)
                                    <tr>
                                        <td>{{ $company->id }}</td>
                                        <td><strong>{{ $company->name }}</strong></td>
                                        <td><span class="badge bg-info">{{ $company->code ?? '-' }}</span></td>
                                        <td>{{ $company->contact_name ?? '-' }}</td>
                                        <td>{{ $company->contact_email ?? '-' }}</td>
                                        <td>{{ $company->contact_phone ?? '-' }}</td>
                                        <td>
                                            @if($company->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $company->shipments->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('logistics-companies.show', $company->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('logistics-companies.edit', $company->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('logistics-companies.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta empresa logística?');">
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
                        {{ $logisticsCompanies->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No hay empresas logísticas registradas. <a href="{{ route('logistics-companies.create') }}">Crear la primera</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



