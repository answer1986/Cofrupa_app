@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-contract"></i> Gestión de Contratos</h2>
                <a href="{{ route('contracts.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Contrato
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
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Contratos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Broker</th>
                                    <th>Stock (kg)</th>
                                    <th>Precio</th>
                                    <th>Valor Total</th>
                                    <th>Comisión Broker</th>
                                    <th>Banco</th>
                                    <th>Puerto</th>
                                    <th>Estado</th>
                                    <th>Modificaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr>
                                        <td>#{{ $contract->id }}</td>
                                        <td>{{ $contract->client->name }}</td>
                                        <td>{{ $contract->broker->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($contract->stock_committed, 2) }}</td>
                                        <td>${{ number_format($contract->price, 2) }}</td>
                                        <td><strong>${{ number_format($contract->total_value, 2) }}</strong></td>
                                        <td>
                                            @if($contract->broker_commission_percentage)
                                                {{ number_format($contract->broker_commission_percentage, 2) }}%
                                                <br><small>(${{ number_format($contract->broker_commission, 2) }})</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $contract->destination_bank ?? 'N/A' }}</td>
                                        <td>{{ $contract->destination_port ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'completed' ? 'info' : ($contract->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                                {{ $contract->status_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $contract->modifications->count() }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No hay contratos registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
