@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-building"></i> {{ $agency->name }}</h2>
                <div>
                    <a href="{{ route('customs-agencies.edit', $agency->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-4">Nombre:</dt>
                        <dd class="col-8">{{ $agency->name }}</dd>
                        @if($agency->code)
                            <dt class="col-4">Código:</dt>
                            <dd class="col-8"><span class="badge bg-info">{{ $agency->code }}</span></dd>
                        @endif
                        @if($agency->address)
                            <dt class="col-4">Dirección:</dt>
                            <dd class="col-8">{{ $agency->address }}</dd>
                        @endif
                        <dt class="col-4">Estado:</dt>
                        <dd class="col-8">
                            @if($agency->is_active)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </dd>
                        @if($agency->notes)
                            <dt class="col-4">Notas:</dt>
                            <dd class="col-8">{{ $agency->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Datos de Facturación</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        @if($agency->tax_id)
                            <dt class="col-5">RUT / Tax ID:</dt>
                            <dd class="col-7">{{ $agency->tax_id }}</dd>
                        @endif
                        @if($agency->bank_name)
                            <dt class="col-5">Banco:</dt>
                            <dd class="col-7">{{ $agency->bank_name }}</dd>
                        @endif
                        @if($agency->bank_account_type)
                            <dt class="col-5">Tipo de Cuenta:</dt>
                            <dd class="col-7">{{ ucfirst($agency->bank_account_type) }}</dd>
                        @endif
                        @if($agency->bank_account_number)
                            <dt class="col-5">Número de Cuenta:</dt>
                            <dd class="col-7">{{ $agency->bank_account_number }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Contactos -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-users"></i> Contactos ({{ $agency->contacts->count() }})</h5>
        </div>
        <div class="card-body">
            @if($agency->contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Persona de Contacto</th>
                                <th>Cargo/Posición</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agency->contacts as $contact)
                                <tr>
                                    <td>{{ $contact->contact_person ?? 'N/A' }}</td>
                                    <td>{{ $contact->position ?? 'N/A' }}</td>
                                    <td>{{ $contact->phone ?? 'N/A' }}</td>
                                    <td>{{ $contact->email ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No hay contactos registrados</p>
            @endif
        </div>
    </div>
</div>
@endsection
