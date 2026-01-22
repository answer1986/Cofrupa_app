@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-building"></i> {{ $plant->name }}</h2>
                <div>
                    <a href="{{ route('processing.plants.edit', $plant->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.plants.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-4">Código:</dt>
                        <dd class="col-8">{{ $plant->code }}</dd>
                        <dt class="col-4">Nombre:</dt>
                        <dd class="col-8">{{ $plant->name }}</dd>
                        <dt class="col-4">Estado:</dt>
                        <dd class="col-8">
                            <span class="badge bg-{{ $plant->is_active ? 'success' : 'secondary' }}">
                                {{ $plant->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </dd>
                        <dt class="col-4">Dirección:</dt>
                        <dd class="col-8">{{ $plant->address ?? 'N/A' }}</dd>
                        <dt class="col-4">Teléfono:</dt>
                        <dd class="col-8">{{ $plant->phone ?? 'N/A' }}</dd>
                        <dt class="col-4">Email:</dt>
                        <dd class="col-8">{{ $plant->email ?? 'N/A' }}</dd>
                        <dt class="col-4">Contacto:</dt>
                        <dd class="col-8">{{ $plant->contact_person ?? 'N/A' }}</dd>
                        @if($plant->notes)
                            <dt class="col-4">Notas:</dt>
                            <dd class="col-8">{{ $plant->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Datos de Facturación</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        @if($plant->tax_id)
                            <dt class="col-4">RUT:</dt>
                            <dd class="col-8">{{ $plant->tax_id }}</dd>
                        @endif
                        @if($plant->bank_name)
                            <dt class="col-4">Banco:</dt>
                            <dd class="col-8">{{ $plant->bank_name }}</dd>
                        @endif
                        @if($plant->bank_account_type)
                            <dt class="col-4">Tipo Cuenta:</dt>
                            <dd class="col-8">{{ ucfirst($plant->bank_account_type) }}</dd>
                        @endif
                        @if($plant->bank_account_number)
                            <dt class="col-4">Nº Cuenta:</dt>
                            <dd class="col-8">{{ $plant->bank_account_number }}</dd>
                        @endif
                        @if(!$plant->tax_id && !$plant->bank_name)
                            <dd class="col-12 text-muted">No hay datos de facturación registrados</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Órdenes de Proceso</h5>
                </div>
                <div class="card-body">
                    @if($plant->processOrders->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plant->processOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td><span class="badge bg-info">{{ $order->status_display }}</span></td>
                                        <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No hay órdenes de proceso asociadas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

