@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shipping-fast"></i> Crear Nuevo Despacho</h2>
            <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Información del Despacho</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shipments.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_id" class="form-label">Contrato *</label>
                            <select class="form-select @error('contract_id') is-invalid @enderror" id="contract_id" name="contract_id" required>
                                <option value="">Seleccione un contrato</option>
                                @forelse($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                        #{{ $contract->id }} - {{ $contract->client->name }} ({{ number_format($contract->stock_committed, 2) }} kg) - {{ ucfirst($contract->status) }}
                                    </option>
                                @empty
                                    <option value="" disabled>No hay contratos disponibles</option>
                                @endforelse
                            </select>
                            @if(count($contracts) === 0)
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No hay contratos disponibles. 
                                    <a href="{{ route('contracts.create') }}" target="_blank">Crear un contrato primero</a>
                                </small>
                            @endif
                            @error('contract_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="shipping_line_id" class="form-label">Naviera</label>
                            <select class="form-select @error('shipping_line_id') is-invalid @enderror" id="shipping_line_id" name="shipping_line_id">
                                <option value="">Sin naviera asignada</option>
                                @forelse($shippingLines as $line)
                                    <option value="{{ $line->id }}" {{ old('shipping_line_id') == $line->id ? 'selected' : '' }}>
                                        {{ $line->name }} ({{ $line->code }})
                                    </option>
                                @empty
                                    <option value="" disabled>No hay navieras registradas</option>
                                @endforelse
                            </select>
                            @if(count($shippingLines) === 0)
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No hay navieras registradas. 
                                    <a href="{{ route('shipping-lines.create') }}" target="_blank">Crear una naviera primero</a>
                                </small>
                            @endif
                            @error('shipping_line_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scheduled_date" class="form-label">Fecha Programada *</label>
                            <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror"
                                   id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-truck"></i> Asignaciones</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="plant_pickup_company" class="form-label">Recoge en Planta</label>
                            <select class="form-select @error('plant_pickup_company') is-invalid @enderror" id="plant_pickup_company" name="plant_pickup_company">
                                <option value="">Seleccione</option>
                                <option value="SPS" {{ old('plant_pickup_company') == 'SPS' ? 'selected' : '' }}>SPS</option>
                                <option value="DUS" {{ old('plant_pickup_company') == 'DUS' ? 'selected' : '' }}>DUS</option>
                            </select>
                            @error('plant_pickup_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="customs_loading_company" class="form-label">Carga para Aduana</label>
                            <select class="form-select @error('customs_loading_company') is-invalid @enderror" id="customs_loading_company" name="customs_loading_company">
                                <option value="">Seleccione</option>
                                <option value="SPS" {{ old('customs_loading_company') == 'SPS' ? 'selected' : '' }}>SPS</option>
                                <option value="DUS" {{ old('customs_loading_company') == 'DUS' ? 'selected' : '' }}>DUS</option>
                            </select>
                            @error('customs_loading_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="transport_company_id" class="form-label">Empresa de Transporte</label>
                            <select class="form-select @error('transport_company_id') is-invalid @enderror" id="transport_company_id" name="transport_company_id">
                                <option value="">Seleccione una empresa</option>
                                @foreach($logisticsCompanies as $company)
                                    <option value="{{ $company->id }}" {{ old('transport_company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(count($logisticsCompanies) === 0)
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No hay empresas logísticas registradas. 
                                    <a href="{{ route('logistics-companies.create') }}" target="_blank">Crear una empresa primero</a>
                                </small>
                            @endif
                            @error('transport_company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">O ingrese manualmente:</small>
                            <input type="text" class="form-control mt-1 @error('transport_company') is-invalid @enderror"
                                   id="transport_company" name="transport_company" value="{{ old('transport_company') }}" placeholder="Empresa manual">
                            @error('transport_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transport_contact" class="form-label">Contacto Transporte</label>
                            <input type="text" class="form-control @error('transport_contact') is-invalid @enderror"
                                   id="transport_contact" name="transport_contact" value="{{ old('transport_contact') }}">
                            @error('transport_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="transport_phone" class="form-label">Teléfono Transporte</label>
                            <input type="text" class="form-control @error('transport_phone') is-invalid @enderror"
                                   id="transport_phone" name="transport_phone" value="{{ old('transport_phone') }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+56912345678"
                                   maxlength="20">
                            @error('transport_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transport_email" class="form-label">Email Transporte</label>
                            <input type="email" class="form-control @error('transport_email') is-invalid @enderror"
                                   id="transport_email" name="transport_email" value="{{ old('transport_email') }}" 
                                   autocomplete="email"
                                   placeholder="transporte@empresa.com">
                            @error('transport_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="transport_request_number" class="form-label">N° Solicitud de Transporte</label>
                        <input type="text" class="form-control @error('transport_request_number') is-invalid @enderror"
                               id="transport_request_number" name="transport_request_number" value="{{ old('transport_request_number') }}">
                        @error('transport_request_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="truck_cost" class="form-label">Costo del Camión</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('truck_cost') is-invalid @enderror"
                               id="truck_cost" name="truck_cost" value="{{ old('truck_cost') }}">
                        @error('truck_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ingrese el costo del transporte terrestre (camión)</small>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-clock"></i> Control de Tiempos</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="plant_pickup_scheduled" class="form-label">Recogida en Planta (Programada)</label>
                            <input type="datetime-local" class="form-control @error('plant_pickup_scheduled') is-invalid @enderror"
                                   id="plant_pickup_scheduled" name="plant_pickup_scheduled" value="{{ old('plant_pickup_scheduled') }}">
                            @error('plant_pickup_scheduled')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customs_loading_scheduled" class="form-label">Carga en Aduana (Programada)</label>
                            <input type="datetime-local" class="form-control @error('customs_loading_scheduled') is-invalid @enderror"
                                   id="customs_loading_scheduled" name="customs_loading_scheduled" value="{{ old('customs_loading_scheduled') }}">
                            @error('customs_loading_scheduled')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transport_departure_scheduled" class="form-label">Salida de Transporte (Programada)</label>
                            <input type="datetime-local" class="form-control @error('transport_departure_scheduled') is-invalid @enderror"
                                   id="transport_departure_scheduled" name="transport_departure_scheduled" value="{{ old('transport_departure_scheduled') }}">
                            @error('transport_departure_scheduled')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="port_arrival_scheduled" class="form-label">Llegada al Puerto (Programada)</label>
                            <input type="datetime-local" class="form-control @error('port_arrival_scheduled') is-invalid @enderror"
                                   id="port_arrival_scheduled" name="port_arrival_scheduled" value="{{ old('port_arrival_scheduled') }}">
                            @error('port_arrival_scheduled')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Despacho</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


    