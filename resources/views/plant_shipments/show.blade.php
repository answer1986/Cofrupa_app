@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Detalle del Despacho</h2>
                <a href="{{ route('plant-shipments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información del Despacho</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Número de Guía:</th>
                            <td><strong>{{ $plantShipment->guide_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Fecha de Despacho:</th>
                            <td>{{ $plantShipment->shipment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Planta Destino:</th>
                            <td>{{ $plantShipment->plant->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Lugar/Destino:</th>
                            <td>{{ $plantShipment->destination }}</td>
                        </tr>
                        <tr>
                            <th>Orden de Proceso:</th>
                            <td>{{ $plantShipment->processOrder ? '#' . $plantShipment->processOrder->order_number : 'Sin orden' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Transporte y Pago</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Chofer:</th>
                            <td>{{ $plantShipment->driver_name }}</td>
                        </tr>
                        <tr>
                            <th>Patente:</th>
                            <td><strong>{{ $plantShipment->vehicle_plate }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tipo de Bins:</th>
                            <td>{{ $plantShipment->bin_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Kilos:</th>
                            <td><strong>{{ number_format($plantShipment->total_kilos, 2) }} kg</strong></td>
                        </tr>
                        <tr>
                            <th>Costo:</th>
                            <td>{{ $plantShipment->shipment_cost ? '$' . number_format($plantShipment->shipment_cost, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Estado de Pago:</th>
                            <td>
                                @if($plantShipment->payment_status == 'paid')
                                    <span class="badge bg-success">Pagado</span>
                                @else
                                    <span class="badge bg-warning">No Pagado</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($plantShipment->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Notas</h5>
            </div>
            <div class="card-body">
                {{ $plantShipment->notes }}
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Bins Despachados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número de Bin</th>
                            <th>Tarja</th>
                            <th>Calibre</th>
                            <th>Kilos Enviados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plantShipment->bins as $bin)
                            <tr>
                                <td>{{ $bin->current_bin_number }}</td>
                                <td>{{ $bin->tarja_number ?? '-' }}</td>
                                <td>{{ $bin->calibre ?? '-' }}</td>
                                <td><strong>{{ number_format($bin->pivot->kilos_sent, 2) }} kg</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>{{ number_format($plantShipment->total_kilos, 2) }} kg</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('plant-shipments.edit', $plantShipment) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('plant-shipments.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection
