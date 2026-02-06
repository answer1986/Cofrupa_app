@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-truck-loading"></i> Registrar Nuevo Flete</h2>
                <a href="{{ route('ventas.fletes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ventas.fletes.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Información del Flete</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="freight_type" class="form-label">Tipo de Flete <span class="text-danger">*</span></label>
                                <select class="form-select @error('freight_type') is-invalid @enderror" id="freight_type" name="freight_type" required>
                                    <option value="">Seleccione...</option>
                                    <option value="reception" {{ old('freight_type') === 'reception' ? 'selected' : '' }}>Recepción de Fruta (a la planta)</option>
                                    <option value="to_processing" {{ old('freight_type') === 'to_processing' ? 'selected' : '' }}>Envío a Planta de Procesamiento</option>
                                    <option value="to_port" {{ old('freight_type') === 'to_port' ? 'selected' : '' }}>Envío a Puerto</option>
                                    <option value="supply_purchase" {{ old('freight_type') === 'supply_purchase' ? 'selected' : '' }}>Compra de Insumos</option>
                                    <option value="other" {{ old('freight_type') === 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('freight_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="freight_date" class="form-label">Fecha del Flete <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('freight_date') is-invalid @enderror" id="freight_date" name="freight_date" value="{{ old('freight_date', date('Y-m-d')) }}" required>
                                @error('freight_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origin" class="form-label">Origen</label>
                                <input type="text" class="form-control" id="origin" name="origin" value="{{ old('origin') }}" placeholder="Ej: Molina, Huerto Los Robles">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destination" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}" placeholder="Ej: Planta Curicó, Puerto San Antonio">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="driver_name" class="form-label">Chofer</label>
                                <input type="text" class="form-control" id="driver_name" name="driver_name" value="{{ old('driver_name') }}" placeholder="Nombre del chofer">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vehicle_plate" class="form-label">Patente</label>
                                <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" value="{{ old('vehicle_plate') }}" placeholder="Ej: ABCD12">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="guide_number" class="form-label">N° Guía</label>
                                <input type="text" class="form-control" id="guide_number" name="guide_number" value="{{ old('guide_number') }}" placeholder="Número de guía">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="logistics_company_id" class="form-label">Empresa de Transporte</label>
                                <select class="form-select" id="logistics_company_id" name="logistics_company_id">
                                    <option value="">Seleccione...</option>
                                    @foreach($logisticsCompanies as $company)
                                        <option value="{{ $company->id }}" {{ old('logistics_company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kilos" class="form-label">Kilos Transportados</label>
                                <input type="number" step="0.01" class="form-control" id="kilos" name="kilos" value="{{ old('kilos') }}" min="0" placeholder="0.00">
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
                            <div class="col-md-6 mb-3">
                                <label for="freight_cost" class="form-label">Costo del Flete <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('freight_cost') is-invalid @enderror" id="freight_cost" name="freight_cost" value="{{ old('freight_cost') }}" required min="0" placeholder="0.00">
                                @error('freight_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">Estado de Pago <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                    <option value="pending" {{ old('payment_status', 'pending') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Pagado</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Referencias (Opcional)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Vincule este flete con una operación específica si corresponde.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_id" class="form-label">Compra de Fruta</label>
                                <select class="form-select" id="purchase_id" name="purchase_id">
                                    <option value="">Sin vincular</option>
                                    @foreach($purchases as $p)
                                        <option value="{{ $p->id }}" {{ old('purchase_id') == $p->id ? 'selected' : '' }}>
                                            #{{ $p->id }} - {{ $p->supplier->name ?? 'N/A' }} - {{ $p->purchase_date->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="process_order_id" class="form-label">Orden de Proceso</label>
                                <select class="form-select" id="process_order_id" name="process_order_id">
                                    <option value="">Sin vincular</option>
                                    @foreach($processOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('process_order_id') == $order->id ? 'selected' : '' }}>
                                            #{{ $order->order_number }} - {{ $order->plant->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="plant_shipment_id" class="form-label">Despacho a Planta</label>
                                <select class="form-select" id="plant_shipment_id" name="plant_shipment_id">
                                    <option value="">Sin vincular</option>
                                    @foreach($plantShipments as $ps)
                                        <option value="{{ $ps->id }}" {{ old('plant_shipment_id') == $ps->id ? 'selected' : '' }}>
                                            Guía {{ $ps->guide_number }} - {{ $ps->plant->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="supply_purchase_id" class="form-label">Compra de Insumos</label>
                                <select class="form-select" id="supply_purchase_id" name="supply_purchase_id">
                                    <option value="">Sin vincular</option>
                                    @foreach($supplyPurchases as $sp)
                                        <option value="{{ $sp->id }}" {{ old('supply_purchase_id') == $sp->id ? 'selected' : '' }}>
                                            #{{ $sp->id }} - {{ $sp->supplier_name }} - {{ $sp->purchase_date->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Observaciones adicionales">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Ayuda</h5>
                    </div>
                    <div class="card-body">
                        <h6>Tipos de Flete:</h6>
                        <ul class="small">
                            <li><strong>Recepción:</strong> Traer fruta desde huerto/proveedor a la planta.</li>
                            <li><strong>A Procesamiento:</strong> Llevar fruta a planta de procesamiento.</li>
                            <li><strong>A Puerto:</strong> Transportar producto al puerto de embarque.</li>
                            <li><strong>Compra Insumos:</strong> Fletes para traer insumos.</li>
                            <li><strong>Otro:</strong> Cualquier otro flete de la operación.</li>
                        </ul>
                    </div>
                    <div class="card-body border-top">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-save"></i> Guardar Flete
                        </button>
                        <a href="{{ route('ventas.fletes.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
