@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck"></i> Detalles de la Empresa Logística</h2>
            <div>
                <a href="{{ route('logistics-companies.edit', $logisticsCompany->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('logistics-companies.index') }}" class="btn btn-secondary">
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
                    <dd class="col-sm-9">{{ $logisticsCompany->id }}</dd>

                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9"><strong>{{ $logisticsCompany->name }}</strong></dd>

                    <dt class="col-sm-3">Código:</dt>
                    <dd class="col-sm-9"><span class="badge bg-info">{{ $logisticsCompany->code ?? '-' }}</span></dd>

                    <dt class="col-sm-3">Contacto:</dt>
                    <dd class="col-sm-9">{{ $logisticsCompany->contact_name ?? '-' }}</dd>

                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9">{{ $logisticsCompany->contact_email ?? '-' }}</dd>

                    <dt class="col-sm-3">Teléfono:</dt>
                    <dd class="col-sm-9">{{ $logisticsCompany->contact_phone ?? '-' }}</dd>

                    <dt class="col-sm-3">Dirección:</dt>
                    <dd class="col-sm-9">{{ $logisticsCompany->address ?? '-' }}</dd>

                    <dt class="col-sm-3">Estado:</dt>
                    <dd class="col-sm-9">
                        @if($logisticsCompany->is_active)
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </dd>

                    @if($logisticsCompany->notes)
                        <dt class="col-sm-3">Notas:</dt>
                        <dd class="col-sm-9">{{ $logisticsCompany->notes }}</dd>
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
                    <strong>Despachos Asociados:</strong>
                    <span class="badge bg-primary fs-6">{{ $logisticsCompany->shipments->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if($logisticsCompany->shipments->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Despachos Asociados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>N° Despacho</th>
                                <th>Contrato</th>
                                <th>Cliente</th>
                                <th>Fecha Programada</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logisticsCompany->shipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->shipment_number }}</td>
                                    <td>Contrato #{{ $shipment->contract->id ?? '-' }}</td>
                                    <td>{{ $shipment->contract->client->name ?? '-' }}</td>
                                    <td>{{ $shipment->scheduled_date ? \Carbon\Carbon::parse($shipment->scheduled_date)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($shipment->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-sm btn-info">
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
@endsection



