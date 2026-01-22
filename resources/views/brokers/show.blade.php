@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-tie"></i> Detalles del Broker</h2>
            <div>
                <a href="{{ route('brokers.edit', $broker->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('brokers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">ID:</dt>
                    <dd class="col-sm-9">{{ $broker->id }}</dd>

                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9"><strong>{{ $broker->name }}</strong></dd>

                    <dt class="col-sm-3">Comisión:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-info fs-6">{{ number_format($broker->commission_percentage, 2) }}%</span>
                    </dd>

                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9">{{ $broker->email ?? '-' }}</dd>

                    <dt class="col-sm-3">Teléfono:</dt>
                    <dd class="col-sm-9">{{ $broker->phone ?? '-' }}</dd>

                    <dt class="col-sm-3">Dirección:</dt>
                    <dd class="col-sm-9">{{ $broker->address ?? '-' }}</dd>

                    @if($broker->notes)
                        <dt class="col-sm-3">Notas:</dt>
                        <dd class="col-sm-9">{{ $broker->notes }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Contratos Asociados:</strong>
                    <span class="badge bg-primary fs-6">{{ $broker->contracts->count() ?? 0 }}</span>
                </div>
                <div class="mb-3">
                    <strong>Pagos Registrados:</strong>
                    <span class="badge bg-success fs-6">{{ $broker->payments->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if($broker->contracts->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Contratos Asociados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Stock Comprometido</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($broker->contracts as $contract)
                                <tr>
                                    <td>{{ $contract->id }}</td>
                                    <td>{{ $contract->client->name ?? '-' }}</td>
                                    <td>{{ number_format($contract->stock_committed, 2) }} kg</td>
                                    <td>${{ number_format($contract->price, 2) }}</td>
                                    <td>
                                        @if($contract->status === 'active')
                                            <span class="badge bg-success">Activo</span>
                                        @elseif($contract->status === 'completed')
                                            <span class="badge bg-info">Completado</span>
                                        @elseif($contract->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelado</span>
                                        @else
                                            <span class="badge bg-secondary">Borrador</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($broker->payments->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Pagos Registrados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Contrato</th>
                                <th>Tipo de Documento</th>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($broker->payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>
                                        @if($payment->contract)
                                            Contrato #{{ $payment->contract->id }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->document_type === 'original')
                                            <span class="badge bg-primary">Original</span>
                                        @else
                                            <span class="badge bg-success">Release</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection



