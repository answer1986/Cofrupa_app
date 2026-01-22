@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shipping-fast"></i> Detalles del Despacho #{{ $shipment->shipment_number }}</h2>
            <div>
                <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Número de Despacho:</dt>
                    <dd class="col-sm-8"><strong>{{ $shipment->shipment_number }}</strong></dd>

                    <dt class="col-sm-4">Contrato:</dt>
                    <dd class="col-sm-8">#{{ $shipment->contract->id }} - {{ $shipment->contract->client->name }}</dd>

                    <dt class="col-sm-4">Naviera:</dt>
                    <dd class="col-sm-8">{{ $shipment->shippingLine->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Fecha Programada:</dt>
                    <dd class="col-sm-8">{{ $shipment->scheduled_date->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Estado:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $shipment->status === 'completed' ? 'success' : ($shipment->status === 'cancelled' ? 'danger' : 'info') }}">
                            {{ $shipment->status_display }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Información de Transporte</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Empresa de Transporte:</dt>
                    <dd class="col-sm-8">{{ $shipment->transport_company ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Contacto:</dt>
                    <dd class="col-sm-8">{{ $shipment->transport_contact ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Teléfono:</dt>
                    <dd class="col-sm-8">{{ $shipment->transport_phone ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8">{{ $shipment->transport_email ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">N° Solicitud:</dt>
                    <dd class="col-sm-8">{{ $shipment->transport_request_number ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Control de Tiempos</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Recogida en Planta (Programada):</dt>
                    <dd class="col-sm-8">
                        @if($shipment->plant_pickup_scheduled)
                            {{ $shipment->plant_pickup_scheduled->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Carga en Aduana (Programada):</dt>
                    <dd class="col-sm-8">
                        @if($shipment->customs_loading_scheduled)
                            {{ $shipment->customs_loading_scheduled->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Salida de Transporte (Programada):</dt>
                    <dd class="col-sm-8">
                        @if($shipment->transport_departure_scheduled)
                            {{ $shipment->transport_departure_scheduled->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Llegada al Puerto (Programada):</dt>
                    <dd class="col-sm-8">
                        @if($shipment->port_arrival_scheduled)
                            {{ $shipment->port_arrival_scheduled->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        @if($shipment->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p>{{ $shipment->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-envelope"></i> Enviar Correo a Empresa de Transporte</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form action="{{ route('shipments.send-email', $shipment->id) }}" method="POST" id="emailForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email_transport_email" class="form-label">Email de Destino</label>
                        <input type="email" class="form-control" 
                               id="email_transport_email" 
                               name="transport_email" 
                               value="{{ old('transport_email', $shipment->transport_email) }}" 
                               required
                               placeholder="transporte@empresa.com">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Enviar Correo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



