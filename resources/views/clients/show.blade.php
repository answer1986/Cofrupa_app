@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user"></i> {{ $client->name }}</h2>
                <div>
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">
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
                        <dd class="col-8">{{ $client->name }}</dd>
                        <dt class="col-4">Tipo:</dt>
                        <dd class="col-8">
                            <span class="badge bg-{{ $client->type === 'constant' ? 'success' : 'info' }}">
                                {{ $client->type_display }}
                            </span>
                        </dd>
                        @if($client->email)
                            <dt class="col-4">Email:</dt>
                            <dd class="col-8">{{ $client->email }}</dd>
                        @endif
                        @if($client->phone)
                            <dt class="col-4">Teléfono:</dt>
                            <dd class="col-8">{{ $client->phone }}</dd>
                        @endif
                        @if($client->customs_agency)
                            <dt class="col-4">Agencia de Aduana:</dt>
                            <dd class="col-8">{{ $client->customs_agency }}</dd>
                        @endif
                        @if($client->address)
                            <dt class="col-4">Dirección:</dt>
                            <dd class="col-8">{{ $client->address }}</dd>
                        @endif
                        @if($client->notes)
                            <dt class="col-4">Notas:</dt>
                            <dd class="col-8">{{ $client->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Estadísticas</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-6">Contratos:</dt>
                        <dd class="col-6">{{ $client->contracts->count() }}</dd>
                        <dt class="col-6">Conversaciones:</dt>
                        <dd class="col-6">{{ $client->conversations->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Contratos -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-file-contract"></i> Contratos</h5>
        </div>
        <div class="card-body">
            @if($client->contracts->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Broker</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->contracts as $contract)
                            <tr>
                                <td>{{ $contract->contract_number }}</td>
                                <td>{{ $contract->broker->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'completed' ? 'info' : 'secondary') }}">
                                        {{ $contract->status_display }}
                                    </span>
                                </td>
                                <td>{{ $contract->contract_date ? \Carbon\Carbon::parse($contract->contract_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No hay contratos asociados</p>
            @endif
        </div>
    </div>

    <!-- Conversaciones -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-comments"></i> Conversaciones</h5>
        </div>
        <div class="card-body">
            @if($client->conversations->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Asunto</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->conversations as $conversation)
                            <tr>
                                <td>{{ $conversation->conversation_date ? \Carbon\Carbon::parse($conversation->conversation_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $conversation->subject ?? 'Sin asunto' }}</td>
                                <td>{{ $conversation->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $conversation->status_display }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewConversation({{ $conversation->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No hay conversaciones registradas</p>
            @endif
        </div>
    </div>
</div>
@endsection



