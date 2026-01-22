@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-contract"></i> Detalles del Contrato #{{ $contract->id }}</h2>
            <div>
                <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Número de Contrato:</dt>
                    <dd class="col-sm-8"><strong>{{ $contract->contract_number ?? 'N/A' }}</strong></dd>

                    <dt class="col-sm-4">Fecha del Contrato:</dt>
                    <dd class="col-sm-8">
                        @if($contract->contract_date)
                            {{ \Carbon\Carbon::parse($contract->contract_date)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Cliente:</dt>
                    <dd class="col-sm-8">{{ $contract->client->name }}</dd>

                    <dt class="col-sm-4">Broker:</dt>
                    <dd class="col-sm-8">{{ $contract->broker->name ?? 'Sin broker' }}</dd>

                    <dt class="col-sm-4">Stock Comprometido:</dt>
                    <dd class="col-sm-8">{{ number_format($contract->stock_committed, 2) }} kg</dd>

                    <dt class="col-sm-4">Precio por kg:</dt>
                    <dd class="col-sm-8">${{ number_format($contract->price, 2) }}</dd>

                    <dt class="col-sm-4">Valor Total:</dt>
                    <dd class="col-sm-8"><strong class="text-success">${{ number_format($contract->total_value, 2) }}</strong></dd>

                    @if($contract->broker_commission_percentage)
                        <dt class="col-sm-4">Comisión Broker:</dt>
                        <dd class="col-sm-8">
                            {{ number_format($contract->broker_commission_percentage, 2) }}%
                            <span class="text-muted">(${{ number_format($contract->broker_commission, 2) }})</span>
                        </dd>
                    @endif

                    <dt class="col-sm-4">Banco de Destino:</dt>
                    <dd class="col-sm-8">{{ $contract->destination_bank ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Puerto de Destino:</dt>
                    <dd class="col-sm-8">{{ $contract->destination_port ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Estado:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'completed' ? 'info' : ($contract->status === 'cancelled' ? 'danger' : 'secondary')) }} fs-6">
                            {{ $contract->status_display }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Consignatario -->
        @if($contract->consignee_name)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-tag"></i> Consignatario (Consignee)</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nombre:</dt>
                    <dd class="col-sm-8">{{ $contract->consignee_name }}</dd>

                    @if($contract->consignee_address)
                        <dt class="col-sm-4">Dirección (Inglés):</dt>
                        <dd class="col-sm-8">{{ $contract->consignee_address }}</dd>
                    @endif

                    @if($contract->consignee_chinese_address)
                        <dt class="col-sm-4">Dirección (Chino):</dt>
                        <dd class="col-sm-8">{{ $contract->consignee_chinese_address }}</dd>
                    @endif

                    @if($contract->consignee_tax_id)
                        <dt class="col-sm-4">TAX ID / USCI:</dt>
                        <dd class="col-sm-8">{{ $contract->consignee_tax_id }}</dd>
                    @endif

                    @if($contract->consignee_phone)
                        <dt class="col-sm-4">Teléfono:</dt>
                        <dd class="col-sm-8">{{ $contract->consignee_phone }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Dirección de Notificación -->
        @if($contract->notify_name)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bell"></i> Dirección de Notificación (Notify Address)</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nombre:</dt>
                    <dd class="col-sm-8">{{ $contract->notify_name }}</dd>

                    @if($contract->notify_address)
                        <dt class="col-sm-4">Dirección (Inglés):</dt>
                        <dd class="col-sm-8">{{ $contract->notify_address }}</dd>
                    @endif

                    @if($contract->notify_chinese_address)
                        <dt class="col-sm-4">Dirección (Chino):</dt>
                        <dd class="col-sm-8">{{ $contract->notify_chinese_address }}</dd>
                    @endif

                    @if($contract->notify_tax_id)
                        <dt class="col-sm-4">TAX ID / USCI:</dt>
                        <dd class="col-sm-8">{{ $contract->notify_tax_id }}</dd>
                    @endif

                    @if($contract->notify_phone)
                        <dt class="col-sm-4">Teléfono:</dt>
                        <dd class="col-sm-8">{{ $contract->notify_phone }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Personas de Contacto -->
        @if($contract->contact_person_1_name || $contract->contact_person_2_name || $contract->contact_email)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users"></i> Personas de Contacto</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    @if($contract->contact_person_1_name)
                        <dt class="col-sm-4">Contacto 1:</dt>
                        <dd class="col-sm-8">
                            {{ $contract->contact_person_1_name }}
                            @if($contract->contact_person_1_phone)
                                <br><small class="text-muted">{{ $contract->contact_person_1_phone }}</small>
                            @endif
                        </dd>
                    @endif

                    @if($contract->contact_person_2_name)
                        <dt class="col-sm-4">Contacto 2:</dt>
                        <dd class="col-sm-8">
                            {{ $contract->contact_person_2_name }}
                            @if($contract->contact_person_2_phone)
                                <br><small class="text-muted">{{ $contract->contact_person_2_phone }}</small>
                            @endif
                        </dd>
                    @endif

                    @if($contract->contact_email)
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">{{ $contract->contact_email }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Vendedor -->
        @if($contract->seller_name)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building"></i> Vendedor (Seller)</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nombre:</dt>
                    <dd class="col-sm-8">{{ $contract->seller_name }}</dd>

                    @if($contract->seller_address)
                        <dt class="col-sm-4">Dirección:</dt>
                        <dd class="col-sm-8">{{ $contract->seller_address }}</dd>
                    @endif

                    @if($contract->seller_phone)
                        <dt class="col-sm-4">Teléfono:</dt>
                        <dd class="col-sm-8">{{ $contract->seller_phone }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Información del Producto -->
        @if($contract->product_description || $contract->quality_specification || $contract->crop_year || $contract->packing || $contract->label_info)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box"></i> Información del Producto</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    @if($contract->product_description)
                        <dt class="col-sm-4">Descripción:</dt>
                        <dd class="col-sm-8">{{ $contract->product_description }}</dd>
                    @endif

                    @if($contract->quality_specification)
                        <dt class="col-sm-4">Especificación de Calidad:</dt>
                        <dd class="col-sm-8">{{ $contract->quality_specification }}</dd>
                    @endif

                    @if($contract->crop_year)
                        <dt class="col-sm-4">Año de Cosecha:</dt>
                        <dd class="col-sm-8">{{ $contract->crop_year }}</dd>
                    @endif

                    @if($contract->packing)
                        <dt class="col-sm-4">Empaque:</dt>
                        <dd class="col-sm-8">{{ $contract->packing }}</dd>
                    @endif

                    @if($contract->label_info)
                        <dt class="col-sm-4">Información de Etiqueta:</dt>
                        <dd class="col-sm-8">{{ $contract->label_info }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Términos Comerciales -->
        @if($contract->incoterm || $contract->payment_terms || $contract->required_documents)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-handshake"></i> Términos Comerciales</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    @if($contract->incoterm)
                        <dt class="col-sm-4">Incoterm:</dt>
                        <dd class="col-sm-8">{{ $contract->incoterm }}</dd>
                    @endif

                    @if($contract->payment_terms)
                        <dt class="col-sm-4">Términos de Pago:</dt>
                        <dd class="col-sm-8">{{ $contract->payment_terms }}</dd>
                    @endif

                    @if($contract->required_documents)
                        <dt class="col-sm-4">Documentos Requeridos:</dt>
                        <dd class="col-sm-8">{{ $contract->required_documents }}</dd>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Información de Envío/Shipping -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Información de Envío</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Producto/Tipo de Tarifa:</dt>
                    <dd class="col-sm-8">{{ $contract->product_type ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Número de Booking:</dt>
                    <dd class="col-sm-8">{{ $contract->booking_number ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Nombre del Buque:</dt>
                    <dd class="col-sm-8">{{ $contract->vessel_name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">ETD (Salida Estimada):</dt>
                    <dd class="col-sm-8">
                        @if($contract->etd_date)
                            {{ \Carbon\Carbon::parse($contract->etd_date)->format('d/m/Y') }}
                            @if($contract->etd_week)
                                <span class="badge bg-info">Sem. {{ $contract->etd_week }}</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">ETA (Llegada Estimada):</dt>
                    <dd class="col-sm-8">
                        @if($contract->eta_date)
                            {{ \Carbon\Carbon::parse($contract->eta_date)->format('d/m/Y') }}
                            @if($contract->eta_week)
                                <span class="badge bg-info">Sem. {{ $contract->eta_week }}</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Número de Contenedor:</dt>
                    <dd class="col-sm-8">{{ $contract->container_number ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Semanas de Tránsito:</dt>
                    <dd class="col-sm-8">{{ $contract->transit_weeks ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Monto de Flete:</dt>
                    <dd class="col-sm-8">
                        @if($contract->freight_amount)
                            ${{ number_format($contract->freight_amount, 2) }}
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-4">Estado del Pago:</dt>
                    <dd class="col-sm-8">
                        @if($contract->payment_status === 'paid')
                            <span class="badge bg-success">Pagado</span>
                        @elseif($contract->payment_status === 'partial')
                            <span class="badge bg-warning">Parcial</span>
                        @else
                            <span class="badge bg-secondary">Pendiente</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        @if($contract->contract_variations)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Variaciones del Contrato</h5>
                </div>
                <div class="card-body">
                    <p>{{ $contract->contract_variations }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Historial de Modificaciones -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Modificaciones</h5>
            </div>
            <div class="card-body">
                @if($contract->modifications->count() > 0)
                    <div class="list-group">
                        @foreach($contract->modifications->take(10) as $modification)
                            <div class="list-group-item">
                                <small class="text-muted">{{ $modification->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-1"><strong>{{ $modification->field_changed }}</strong></p>
                                @if($modification->old_value)
                                    <small class="text-danger">Antes: {{ $modification->old_value }}</small><br>
                                @endif
                                @if($modification->new_value)
                                    <small class="text-success">Después: {{ $modification->new_value }}</small>
                                @endif
                                @if($modification->notes)
                                    <p class="mb-0 mt-1"><small>{{ $modification->notes }}</small></p>
                                @endif
                                <small class="text-muted">Por: {{ $modification->user->name ?? 'Sistema' }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No hay modificaciones registradas</p>
                @endif
            </div>
        </div>

        <!-- Pagos al Broker -->
        @if($contract->brokerPayments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Pagos al Broker</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($contract->brokerPayments as $payment)
                            <div class="list-group-item">
                                <strong>${{ number_format($payment->amount, 2) }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $payment->document_type === 'original' ? 'Original' : 'Release' }}
                                    @if($payment->payment_date)
                                        - {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
