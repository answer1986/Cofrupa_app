@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-shipping-fast"></i> Proceso de Despacho y Logística</h2>
                <a href="{{ route('shipments.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Despacho
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
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Despachos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Despacho</th>
                                    <th>Contrato</th>
                                    <th>Cliente</th>
                                    <th>Naviera</th>
                                    <th>Fecha Programada</th>
                                    <th>Recoge en Planta</th>
                                    <th>Carga Aduana</th>
                                    <th>Transporte</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shipments as $shipment)
                                    <tr>
                                        <td><strong>{{ $shipment->shipment_number }}</strong></td>
                                        <td>#{{ $shipment->contract->id }}</td>
                                        <td>{{ $shipment->contract->client->name }}</td>
                                        <td>{{ $shipment->shippingLine->name ?? 'N/A' }}</td>
                                        <td>{{ $shipment->scheduled_date->format('d/m/Y') }}</td>
                                        <td>{{ $shipment->plant_pickup_company ?? 'N/A' }}</td>
                                        <td>{{ $shipment->customs_loading_company ?? 'N/A' }}</td>
                                        <td>{{ $shipment->transport_company ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $shipment->status === 'completed' ? 'success' : ($shipment->status === 'cancelled' ? 'danger' : 'info') }}">
                                                {{ $shipment->status_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No hay despachos registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $shipments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




