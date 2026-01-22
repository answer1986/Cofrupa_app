@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users"></i> Gestión de Clientes y Brokers</h2>
                <div>
                    <a href="{{ route('clients.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Cliente
                    </a>
                    <a href="{{ route('brokers.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-tie"></i> Nuevo Broker
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="managementTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients" type="button" role="tab">
                <i class="fas fa-users"></i> Clientes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brokers-tab" data-bs-toggle="tab" data-bs-target="#brokers" type="button" role="tab">
                <i class="fas fa-user-tie"></i> Brokers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="conversations-tab" data-bs-toggle="tab" data-bs-target="#conversations" type="button" role="tab">
                <i class="fas fa-comments"></i> Conversaciones
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shipping-lines-tab" data-bs-toggle="tab" data-bs-target="#shipping-lines" type="button" role="tab">
                <i class="fas fa-ship"></i> Navieras
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logistics-companies-tab" data-bs-toggle="tab" data-bs-target="#logistics-companies" type="button" role="tab">
                <i class="fas fa-truck"></i> Empresas Logísticas
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="managementTabsContent">
        <!-- Clientes Tab -->
        <div class="tab-pane fade show active" id="clients" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Clientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Conversaciones</th>
                                    <th>Contratos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                    <tr>
                                        <td>{{ $client->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $client->type === 'constant' ? 'success' : 'info' }}">
                                                {{ $client->type_display }}
                                            </span>
                                        </td>
                                        <td>{{ $client->email ?? 'N/A' }}</td>
                                        <td>{{ $client->phone ?? 'N/A' }}</td>
                                        <td>{{ $client->conversations->count() }}</td>
                                        <td>{{ $client->contracts->count() }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
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
                                        <td colspan="7" class="text-center">No hay clientes registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>

        <!-- Brokers Tab -->
        <div class="tab-pane fade" id="brokers" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Brokers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Comisión (%)</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Pagos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brokers as $broker)
                                    <tr>
                                        <td>{{ $broker->name }}</td>
                                        <td><strong>{{ number_format($broker->commission_percentage, 2) }}%</strong></td>
                                        <td>{{ $broker->email ?? 'N/A' }}</td>
                                        <td>{{ $broker->phone ?? 'N/A' }}</td>
                                        <td>{{ $broker->payments->count() }}</td>
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay brokers registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $brokers->links() }}
                </div>
            </div>
        </div>

        <!-- Conversaciones Tab -->
        <div class="tab-pane fade" id="conversations" role="tabpanel">
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createConversationModal">
                        <i class="fas fa-plus"></i> Nueva Conversación
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Seguimiento de Conversaciones</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Broker</th>
                                    <th>Usuario</th>
                                    <th>Etapa</th>
                                    <th>Notas</th>
                                    <th>Archivos</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversations as $conversation)
                                    <tr>
                                        <td>{{ $conversation->client->name }}</td>
                                        <td>{{ $conversation->broker->name ?? 'N/A' }}</td>
                                        <td>{{ $conversation->user->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $conversation->stage_display }}</span>
                                        </td>
                                        <td>{{ Str::limit($conversation->notes, 50) }}</td>
                                        <td>
                                            @if($conversation->attachments && count($conversation->attachments) > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-paperclip"></i> {{ count($conversation->attachments) }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#attachmentsModal{{ $conversation->id }}">
                                                    Ver
                                                </button>
                                            @else
                                                <span class="text-muted">Sin archivos</span>
                                            @endif
                                        </td>
                                        <td>{{ $conversation->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay conversaciones registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>

        <!-- Navieras Tab -->
        <div class="tab-pane fade" id="shipping-lines" role="tabpanel">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('shipping-lines.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Naviera
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Navieras</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
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
                                @forelse($shippingLines as $line)
                                    <tr>
                                        <td>{{ $line->name }}</td>
                                        <td><span class="badge bg-info">{{ $line->code ?? '-' }}</span></td>
                                        <td>{{ $line->contact_name ?? 'N/A' }}</td>
                                        <td>{{ $line->contact_email ?? 'N/A' }}</td>
                                        <td>{{ $line->contact_phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($line->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>{{ $line->shipments->count() }}</td>
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
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hay navieras registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $shippingLines->links() }}
                </div>
            </div>
        </div>

        <!-- Empresas Logísticas Tab -->
        <div class="tab-pane fade" id="logistics-companies" role="tabpanel">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('logistics-companies.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Empresa Logística
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Empresas Logísticas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
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
                                @forelse($logisticsCompanies as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td><span class="badge bg-info">{{ $company->code ?? '-' }}</span></td>
                                        <td>{{ $company->contact_name ?? 'N/A' }}</td>
                                        <td>{{ $company->contact_email ?? 'N/A' }}</td>
                                        <td>{{ $company->contact_phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($company->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>{{ $company->shipments->count() }}</td>
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
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hay empresas logísticas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $logisticsCompanies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Conversación -->
<div class="modal fade" id="createConversationModal" tabindex="-1" aria-labelledby="createConversationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createConversationModalLabel">
                    <i class="fas fa-comments"></i> Nueva Conversación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.conversations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Cliente *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="broker_id" class="form-label">Broker</label>
                        <select class="form-select @error('broker_id') is-invalid @enderror" id="broker_id" name="broker_id">
                            <option value="">Sin broker</option>
                            @foreach($brokers as $broker)
                                <option value="{{ $broker->id }}" {{ old('broker_id') == $broker->id ? 'selected' : '' }}>
                                    {{ $broker->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('broker_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stage" class="form-label">Etapa *</label>
                        <select class="form-select @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                            <option value="">Seleccione una etapa</option>
                            <option value="client_contact" {{ old('stage') == 'client_contact' ? 'selected' : '' }}>Contacto con Cliente</option>
                            <option value="stock_offer" {{ old('stage') == 'stock_offer' ? 'selected' : '' }}>Oferta de Stock</option>
                            <option value="negotiation" {{ old('stage') == 'negotiation' ? 'selected' : '' }}>Negociación</option>
                        </select>
                        @error('stage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas *</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" required>{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Archivos Adjuntos (Correos, Imágenes, Documentos)</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">Puede seleccionar múltiples archivos. Máx 10MB por archivo.</small>
                        @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Conversación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales para ver archivos adjuntos de conversaciones -->
@foreach($conversations as $conversation)
    @if($conversation->attachments && count($conversation->attachments) > 0)
        <div class="modal fade" id="attachmentsModal{{ $conversation->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-paperclip"></i> Archivos Adjuntos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            @foreach($conversation->attachments as $attachment)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file"></i>
                                            <strong>{{ $attachment['original_name'] }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Tipo: {{ $attachment['type'] }} | 
                                                Tamaño: {{ number_format($attachment['size'] / 1024, 2) }} KB
                                            </small>
                                        </div>
                                        <a href="{{ Storage::url($attachment['path']) }}" class="btn btn-sm btn-primary" download target="_blank">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
