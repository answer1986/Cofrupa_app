@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck"></i> Detalle de Flete #{{ $freight->id }}</h2>
                <div>
                    <a href="{{ route('ventas.fletes.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="{{ route('ventas.fletes.edit', $freight) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información del Flete</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tipo de Flete:</strong><br>
                            <span class="badge bg-info">{{ $freight->freight_type_display }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha:</strong><br>
                            {{ $freight->freight_date->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Origen:</strong><br>
                            {{ $freight->origin ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Destino:</strong><br>
                            {{ $freight->destination ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Chofer:</strong><br>
                            {{ $freight->driver_name ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Patente:</strong><br>
                            {{ $freight->vehicle_plate ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>N° Guía:</strong><br>
                            {{ $freight->guide_number ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Empresa de Transporte:</strong><br>
                            {{ $freight->logisticsCompany->name ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Kilos Transportados:</strong><br>
                            {{ $freight->kilos ? number_format($freight->kilos, 2) . ' kg' : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Costos y Pago</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Costo del Flete:</strong><br>
                            <h4 class="text-success">${{ number_format($freight->freight_cost, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <strong>Estado de Pago:</strong><br>
                            @if($freight->payment_status === 'paid')
                                <span class="badge bg-success fs-6">Pagado</span>
                            @else
                                <span class="badge bg-warning fs-6">Pendiente</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($freight->notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Notas</h5>
                    </div>
                    <div class="card-body">
                        {{ $freight->notes }}
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            @if($freight->purchase || $freight->processOrder || $freight->plantShipment || $freight->supplyPurchase)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-link"></i> Referencias</h5>
                    </div>
                    <div class="card-body">
                        @if($freight->purchase)
                            <div class="mb-3">
                                <strong>Compra de Fruta:</strong><br>
                                <a href="{{ route('purchases.show', $freight->purchase) }}" class="btn btn-sm btn-outline-primary">
                                    #{{ $freight->purchase->id }} - Ver Compra
                                </a>
                            </div>
                        @endif

                        @if($freight->processOrder)
                            <div class="mb-3">
                                <strong>Orden de Proceso:</strong><br>
                                <a href="{{ route('processing.orders.show', $freight->processOrder) }}" class="btn btn-sm btn-outline-primary">
                                    #{{ $freight->processOrder->order_number }} - Ver Orden
                                </a>
                            </div>
                        @endif

                        @if($freight->plantShipment)
                            <div class="mb-3">
                                <strong>Despacho a Planta:</strong><br>
                                <a href="{{ route('plant-shipments.show', $freight->plantShipment) }}" class="btn btn-sm btn-outline-primary">
                                    Guía {{ $freight->plantShipment->guide_number }} - Ver Despacho
                                </a>
                            </div>
                        @endif

                        @if($freight->supplyPurchase)
                            <div class="mb-3">
                                <strong>Compra de Insumos:</strong><br>
                                <a href="{{ route('supply-purchases.show', $freight->supplyPurchase) }}" class="btn btn-sm btn-outline-primary">
                                    #{{ $freight->supplyPurchase->id }} - Ver Compra
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <p class="small mb-2">
                        <strong>Creado:</strong><br>
                        {{ $freight->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="small mb-0">
                        <strong>Última actualización:</strong><br>
                        {{ $freight->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
