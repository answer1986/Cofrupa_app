@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-contract"></i> Editar Contrato #{{ $contract->id }}</h2>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="alert-heading mb-2"><i class="fas fa-language"></i> Traducción Automática</h5>
                    <p class="mb-0">Activa o desactiva la traducción automática de campos al inglés al guardar el contrato.</p>
                </div>
                <div class="form-check form-switch ms-3">
                    <input class="form-check-input" type="checkbox" id="autoTranslateToggle" name="auto_translate" value="1" {{ old('auto_translate', false) ? 'checked' : '' }} style="transform: scale(1.5);">
                    <label class="form-check-label" for="autoTranslateToggle">
                        <strong id="translateStatus">Desactivado</strong>
                    </label>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Información del Contrato</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('contracts.update', $contract->id) }}" method="POST" id="contractForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Cliente *</label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">Seleccione un cliente</option>
                                @forelse($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $contract->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->type_display }})
                                    </option>
                                @empty
                                    <option value="" disabled>No hay clientes registrados</option>
                                @endforelse
                            </select>
                            @if(count($clients) === 0)
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No hay clientes registrados. 
                                    <a href="{{ route('clients.create') }}" target="_blank">Crear un cliente primero</a>
                                </small>
                            @endif
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="broker_id" class="form-label">Broker</label>
                            <select class="form-select @error('broker_id') is-invalid @enderror" id="broker_id" name="broker_id">
                                <option value="">Sin broker</option>
                                @forelse($brokers as $broker)
                                    <option value="{{ $broker->id }}" data-commission="{{ $broker->commission_percentage }}" {{ old('broker_id', $contract->broker_id) == $broker->id ? 'selected' : '' }}>
                                        {{ $broker->name }} ({{ number_format($broker->commission_percentage, 2) }}%)
                                    </option>
                                @empty
                                    <option value="" disabled>No hay brokers registrados</option>
                                @endforelse
                            </select>
                            @if(count($brokers) === 0)
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No hay brokers registrados. 
                                    <a href="{{ route('brokers.create') }}" target="_blank">Crear un broker primero</a>
                                </small>
                            @endif
                            @error('broker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_committed" class="form-label">Stock Comprometido (kg) *</label>
                            <input type="number" step="0.01" class="form-control @error('stock_committed') is-invalid @enderror"
                                   id="stock_committed" name="stock_committed" value="{{ old('stock_committed', $contract->stock_committed) }}" required>
                            @error('stock_committed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Precio por kg *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price', $contract->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="broker_commission_percentage" class="form-label">Porcentaje de Comisión del Broker (%)</label>
                            <input type="number" step="0.01" min="1.5" max="3.0" class="form-control @error('broker_commission_percentage') is-invalid @enderror"
                                   id="broker_commission_percentage" name="broker_commission_percentage" value="{{ old('broker_commission_percentage', $contract->broker_commission_percentage) }}">
                            <small class="form-text text-muted">Rango: 1.5% - 3.0%</small>
                            @error('broker_commission_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Estado *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', $contract->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                                <option value="active" {{ old('status', $contract->status) === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="completed" {{ old('status', $contract->status) === 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelled" {{ old('status', $contract->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="destination_bank" class="form-label">Banco de Destino</label>
                            <input type="text" class="form-control @error('destination_bank') is-invalid @enderror"
                                   id="destination_bank" name="destination_bank" value="{{ old('destination_bank', $contract->destination_bank) }}">
                            @error('destination_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="destination_port" class="form-label">Puerto de Destino</label>
                            <input type="text" class="form-control @error('destination_port') is-invalid @enderror"
                                   id="destination_port" name="destination_port" value="{{ old('destination_port', $contract->destination_port) }}">
                            @error('destination_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contract_variations" class="form-label">Variaciones Específicas del Contrato</label>
                        <textarea class="form-control @error('contract_variations') is-invalid @enderror"
                                  id="contract_variations" name="contract_variations" rows="4">{{ old('contract_variations', $contract->contract_variations) }}</textarea>
                        @error('contract_variations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-file-contract"></i> Información del Contrato</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_number" class="form-label">Número de Contrato</label>
                            <input type="text" class="form-control @error('contract_number') is-invalid @enderror"
                                   id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" placeholder="Ej: 0909-25">
                            @error('contract_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contract_date" class="form-label">Fecha del Contrato</label>
                            <input type="date" class="form-control @error('contract_date') is-invalid @enderror"
                                   id="contract_date" name="contract_date" value="{{ old('contract_date', $contract->contract_date ? $contract->contract_date->format('Y-m-d') : '') }}">
                            @error('contract_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-user-tag"></i> Consignatario (Consignee)</h5>

                    <div class="mb-3">
                        <label for="consignee_name" class="form-label">Nombre del Consignatario</label>
                        <input type="text" class="form-control @error('consignee_name') is-invalid @enderror"
                               id="consignee_name" name="consignee_name" value="{{ old('consignee_name', $contract->consignee_name) }}">
                        @error('consignee_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="consignee_address" class="form-label">Dirección (Inglés)</label>
                            <textarea class="form-control @error('consignee_address') is-invalid @enderror"
                                      id="consignee_address" name="consignee_address" rows="2">{{ old('consignee_address', $contract->consignee_address) }}</textarea>
                            @error('consignee_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="consignee_chinese_address" class="form-label">Dirección (Chino)</label>
                            <textarea class="form-control @error('consignee_chinese_address') is-invalid @enderror"
                                      id="consignee_chinese_address" name="consignee_chinese_address" rows="2">{{ old('consignee_chinese_address', $contract->consignee_chinese_address) }}</textarea>
                            @error('consignee_chinese_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="consignee_tax_id" class="form-label">TAX ID / USCI</label>
                            <input type="text" class="form-control @error('consignee_tax_id') is-invalid @enderror"
                                   id="consignee_tax_id" name="consignee_tax_id" value="{{ old('consignee_tax_id', $contract->consignee_tax_id) }}">
                            @error('consignee_tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="consignee_phone" class="form-label">Teléfono Consignatario</label>
                            <input type="text" class="form-control @error('consignee_phone') is-invalid @enderror"
                                   id="consignee_phone" name="consignee_phone" value="{{ old('consignee_phone', $contract->consignee_phone) }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+861234567890"
                                   maxlength="20">
                            @error('consignee_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-bell"></i> Dirección de Notificación (Notify Address)</h5>

                    <div class="mb-3">
                        <label for="notify_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('notify_name') is-invalid @enderror"
                               id="notify_name" name="notify_name" value="{{ old('notify_name', $contract->notify_name) }}">
                        @error('notify_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="notify_address" class="form-label">Dirección (Inglés)</label>
                            <textarea class="form-control @error('notify_address') is-invalid @enderror"
                                      id="notify_address" name="notify_address" rows="2">{{ old('notify_address', $contract->notify_address) }}</textarea>
                            @error('notify_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="notify_chinese_address" class="form-label">Dirección (Chino)</label>
                            <textarea class="form-control @error('notify_chinese_address') is-invalid @enderror"
                                      id="notify_chinese_address" name="notify_chinese_address" rows="2">{{ old('notify_chinese_address', $contract->notify_chinese_address) }}</textarea>
                            @error('notify_chinese_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="notify_tax_id" class="form-label">TAX ID / USCI</label>
                            <input type="text" class="form-control @error('notify_tax_id') is-invalid @enderror"
                                   id="notify_tax_id" name="notify_tax_id" value="{{ old('notify_tax_id', $contract->notify_tax_id) }}">
                            @error('notify_tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="notify_phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('notify_phone') is-invalid @enderror"
                                   id="notify_phone" name="notify_phone" value="{{ old('notify_phone', $contract->notify_phone) }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+861234567890"
                                   maxlength="20">
                            @error('notify_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-users"></i> Personas de Contacto</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_person_1_name" class="form-label">Contacto 1 - Nombre</label>
                            <input type="text" class="form-control @error('contact_person_1_name') is-invalid @enderror"
                                   id="contact_person_1_name" name="contact_person_1_name" value="{{ old('contact_person_1_name', $contract->contact_person_1_name) }}">
                            @error('contact_person_1_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_person_1_phone" class="form-label">Contacto 1 - Teléfono</label>
                            <input type="text" class="form-control @error('contact_person_1_phone') is-invalid @enderror"
                                   id="contact_person_1_phone" name="contact_person_1_phone" value="{{ old('contact_person_1_phone', $contract->contact_person_1_phone) }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+861234567890"
                                   maxlength="20">
                            @error('contact_person_1_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_person_2_name" class="form-label">Contacto 2 - Nombre</label>
                            <input type="text" class="form-control @error('contact_person_2_name') is-invalid @enderror"
                                   id="contact_person_2_name" name="contact_person_2_name" value="{{ old('contact_person_2_name', $contract->contact_person_2_name) }}">
                            @error('contact_person_2_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_person_2_phone" class="form-label">Contacto 2 - Teléfono</label>
                            <input type="text" class="form-control @error('contact_person_2_phone') is-invalid @enderror"
                                   id="contact_person_2_phone" name="contact_person_2_phone" value="{{ old('contact_person_2_phone', $contract->contact_person_2_phone) }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+861234567890"
                                   maxlength="20">
                            @error('contact_person_2_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Email de Contacto</label>
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                               id="contact_email" name="contact_email" value="{{ old('contact_email', $contract->contact_email) }}" autocomplete="email">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-building"></i> Vendedor (Seller)</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="seller_name" class="form-label">Nombre del Vendedor</label>
                            <input type="text" class="form-control @error('seller_name') is-invalid @enderror"
                                   id="seller_name" name="seller_name" value="{{ old('seller_name', $contract->seller_name ?? 'COFRUPA Export SPA') }}">
                            @error('seller_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="seller_phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('seller_phone') is-invalid @enderror"
                                   id="seller_phone" name="seller_phone" value="{{ old('seller_phone', $contract->seller_phone ?? '+56992395293') }}" 
                                   pattern="^\+[0-9]{11}$" 
                                   placeholder="+56912345678"
                                   maxlength="20">
                            @error('seller_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="seller_address" class="form-label">Dirección del Vendedor</label>
                        <textarea class="form-control @error('seller_address') is-invalid @enderror"
                                  id="seller_address" name="seller_address" rows="2">{{ old('seller_address', $contract->seller_address ?? 'Cam Lo Mackenna PC 7-A, Buin') }}</textarea>
                        @error('seller_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-box"></i> Información del Producto</h5>

                    <div class="mb-3">
                        <label for="product_description" class="form-label">Descripción del Producto</label>
                        <textarea class="form-control @error('product_description') is-invalid @enderror"
                                  id="product_description" name="product_description" rows="2" placeholder="Ej: Natural Condition Chilean prunes size 120/140 & 140+">{{ old('product_description', $contract->product_description) }}</textarea>
                        @error('product_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quality_specification" class="form-label">Especificación de Calidad</label>
                        <textarea class="form-control @error('quality_specification') is-invalid @enderror"
                                  id="quality_specification" name="quality_specification" rows="2" placeholder="Ej: As per attached spec / Chilean protocol">{{ old('quality_specification', $contract->quality_specification) }}</textarea>
                        @error('quality_specification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="crop_year" class="form-label">Año de Cosecha</label>
                            <input type="text" class="form-control @error('crop_year') is-invalid @enderror"
                                   id="crop_year" name="crop_year" value="{{ old('crop_year', $contract->crop_year ?? date('Y')) }}" placeholder="Ej: 2025">
                            @error('crop_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="packing" class="form-label">Empaque</label>
                            <input type="text" class="form-control @error('packing') is-invalid @enderror"
                                   id="packing" name="packing" value="{{ old('packing', $contract->packing) }}" placeholder="Ej: 25 kg bags">
                            @error('packing')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="label_info" class="form-label">Información de Etiqueta</label>
                            <input type="text" class="form-control @error('label_info') is-invalid @enderror"
                                   id="label_info" name="label_info" value="{{ old('label_info', $contract->label_info) }}" placeholder="Ej: To be provided by buyer">
                            @error('label_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-handshake"></i> Términos Comerciales</h5>

                    <div class="mb-3">
                        <label for="incoterm" class="form-label">Incoterm</label>
                        <input type="text" class="form-control @error('incoterm') is-invalid @enderror"
                               id="incoterm" name="incoterm" value="{{ old('incoterm', $contract->incoterm) }}" placeholder="Ej: CFR Main Chinese port">
                        @error('incoterm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_terms" class="form-label">Términos de Pago</label>
                        <textarea class="form-control @error('payment_terms') is-invalid @enderror"
                                  id="payment_terms" name="payment_terms" rows="3" placeholder="Ej: 20% advance payment 2 weeks before ETD, 80% balance against first presentation of full set of original doc">{{ old('payment_terms', $contract->payment_terms) }}</textarea>
                        @error('payment_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="required_documents" class="form-label">Documentos Requeridos</label>
                        <textarea class="form-control @error('required_documents') is-invalid @enderror"
                                  id="required_documents" name="required_documents" rows="3" placeholder="Ej: invoice, 3/3 OBL, pack list, cert of origin., phytosanitary">{{ old('required_documents', $contract->required_documents) }}</textarea>
                        @error('required_documents')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-shipping-fast"></i> Información de Envío</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_number" class="form-label">Número de Contrato</label>
                            <input type="text" class="form-control @error('contract_number') is-invalid @enderror"
                                   id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" placeholder="Ej: 313-2503">
                            @error('contract_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="product_type" class="form-label">Producto/Tipo de Tarifa</label>
                            <input type="text" class="form-control @error('product_type') is-invalid @enderror"
                                   id="product_type" name="product_type" value="{{ old('product_type', $contract->product_type) }}" placeholder="Ej: EX50-60">
                            @error('product_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_number" class="form-label">Número de Booking</label>
                            <input type="text" class="form-control @error('booking_number') is-invalid @enderror"
                                   id="booking_number" name="booking_number" value="{{ old('booking_number', $contract->booking_number) }}" placeholder="Ej: SNG91966724">
                            @error('booking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vessel_name" class="form-label">Nombre del Buque</label>
                            <input type="text" class="form-control @error('vessel_name') is-invalid @enderror"
                                   id="vessel_name" name="vessel_name" value="{{ old('vessel_name', $contract->vessel_name) }}" placeholder="Ej: CALLAO EXPRESS">
                            @error('vessel_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="etd_date" class="form-label">ETD (Salida Estimada)</label>
                            <input type="date" class="form-control @error('etd_date') is-invalid @enderror"
                                   id="etd_date" name="etd_date" value="{{ old('etd_date', $contract->etd_date ? $contract->etd_date->format('Y-m-d') : '') }}">
                            @error('etd_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="etd_week" class="form-label">Semana ETD</label>
                            <input type="number" class="form-control @error('etd_week') is-invalid @enderror"
                                   id="etd_week" name="etd_week" value="{{ old('etd_week', $contract->etd_week) }}" min="1" max="52" readonly>
                            <small class="form-text text-muted">Calculada automáticamente</small>
                            @error('etd_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="eta_date" class="form-label">ETA (Llegada Estimada)</label>
                            <input type="date" class="form-control @error('eta_date') is-invalid @enderror"
                                   id="eta_date" name="eta_date" value="{{ old('eta_date', $contract->eta_date ? $contract->eta_date->format('Y-m-d') : '') }}">
                            @error('eta_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="eta_week" class="form-label">Semana ETA</label>
                            <input type="number" class="form-control @error('eta_week') is-invalid @enderror"
                                   id="eta_week" name="eta_week" value="{{ old('eta_week', $contract->eta_week) }}" min="1" max="52" readonly>
                            <small class="form-text text-muted">Calculada automáticamente</small>
                            @error('eta_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="container_number" class="form-label">Número de Contenedor</label>
                            <input type="text" class="form-control @error('container_number') is-invalid @enderror"
                                   id="container_number" name="container_number" value="{{ old('container_number', $contract->container_number) }}" placeholder="Ej: cMAu2248270">
                            @error('container_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="transit_weeks" class="form-label">Semanas de Tránsito</label>
                            <input type="number" class="form-control @error('transit_weeks') is-invalid @enderror"
                                   id="transit_weeks" name="transit_weeks" value="{{ old('transit_weeks', $contract->transit_weeks) }}" min="0">
                            @error('transit_weeks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="freight_amount" class="form-label">Monto de Flete</label>
                            <input type="number" step="0.01" class="form-control @error('freight_amount') is-invalid @enderror"
                                   id="freight_amount" name="freight_amount" value="{{ old('freight_amount', $contract->freight_amount) }}" min="0">
                            @error('freight_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">Estado del Pago</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="pending" {{ old('payment_status', $contract->payment_status) === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="partial" {{ old('payment_status', $contract->payment_status) === 'partial' ? 'selected' : '' }}>Parcial</option>
                                <option value="paid" {{ old('payment_status', $contract->payment_status) === 'paid' ? 'selected' : '' }}>Pagado</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6>Resumen de Cálculos (Validación Automática)</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Valor Total:</strong> <span id="totalValue">$0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Comisión Broker:</strong> <span id="brokerCommission">$0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Neto:</strong> <span id="netValue">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar Contrato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stockInput = document.getElementById('stock_committed');
    const priceInput = document.getElementById('price');
    const brokerSelect = document.getElementById('broker_id');
    const commissionInput = document.getElementById('broker_commission_percentage');
    const etdDateInput = document.getElementById('etd_date');
    const etdWeekInput = document.getElementById('etd_week');
    const etaDateInput = document.getElementById('eta_date');
    const etaWeekInput = document.getElementById('eta_week');
    
    // Función para calcular la semana del año
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }
    
    // Calcular semana ETD automáticamente
    etdDateInput.addEventListener('change', function() {
        if (this.value) {
            const date = new Date(this.value);
            const week = getWeekNumber(date);
            etdWeekInput.value = week;
            
            // Validar que ETA no sea menor que ETD
            if (etaDateInput.value) {
                const etaDate = new Date(etaDateInput.value);
                if (etaDate < date) {
                    alert('La fecha de llegada (ETA) no puede ser anterior a la fecha de salida (ETD)');
                    etaDateInput.value = '';
                    etaWeekInput.value = '';
                }
            }
            
            // Establecer fecha mínima para ETA
            const minDate = new Date(date);
            minDate.setDate(minDate.getDate() + 1);
            etaDateInput.min = minDate.toISOString().split('T')[0];
        }
    });
    
    // Calcular semana ETA automáticamente
    etaDateInput.addEventListener('change', function() {
        if (this.value) {
            const date = new Date(this.value);
            const week = getWeekNumber(date);
            etaWeekInput.value = week;
            
            // Validar que ETA no sea menor que ETD
            if (etdDateInput.value) {
                const etdDate = new Date(etdDateInput.value);
                if (date < etdDate) {
                    alert('La fecha de llegada (ETA) no puede ser anterior a la fecha de salida (ETD)');
                    this.value = '';
                    etaWeekInput.value = '';
                    return;
                }
            }
        }
    });
    
    // Validación en tiempo real
    etaDateInput.addEventListener('input', function() {
        if (this.value && etdDateInput.value) {
            const etaDate = new Date(this.value);
            const etdDate = new Date(etdDateInput.value);
            if (etaDate < etdDate) {
                this.setCustomValidity('La fecha de llegada debe ser igual o posterior a la fecha de salida');
            } else {
                this.setCustomValidity('');
            }
        }
    });
    
    function calculateTotals() {
        const stock = parseFloat(stockInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const totalValue = stock * price;
        
        let commission = 0;
        if (brokerSelect.value) {
            const selectedOption = brokerSelect.options[brokerSelect.selectedIndex];
            const brokerCommission = parseFloat(selectedOption.dataset.commission) || 0;
            const commissionPercent = parseFloat(commissionInput.value) || brokerCommission;
            commission = (totalValue * commissionPercent) / 100;
        }
        
        document.getElementById('totalValue').textContent = '$' + totalValue.toFixed(2);
        document.getElementById('brokerCommission').textContent = '$' + commission.toFixed(2);
        document.getElementById('netValue').textContent = '$' + (totalValue - commission).toFixed(2);
    }
    
    stockInput.addEventListener('input', calculateTotals);
    priceInput.addEventListener('input', calculateTotals);
    brokerSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            commissionInput.value = selectedOption.dataset.commission || '';
        } else {
            commissionInput.value = '';
        }
        calculateTotals();
    });
    commissionInput.addEventListener('input', calculateTotals);
    
    calculateTotals();

    // Toggle de traducción automática
    const autoTranslateToggle = document.getElementById('autoTranslateToggle');
    const translateStatus = document.getElementById('translateStatus');
    
    if (autoTranslateToggle) {
        autoTranslateToggle.addEventListener('change', function() {
            if (this.checked) {
                translateStatus.textContent = 'Activado';
                translateStatus.classList.remove('text-danger');
                translateStatus.classList.add('text-success');
            } else {
                translateStatus.textContent = 'Desactivado';
                translateStatus.classList.remove('text-success');
                translateStatus.classList.add('text-danger');
            }
        });

        // Inicializar estado visual
        if (autoTranslateToggle.checked) {
            translateStatus.classList.add('text-success');
            translateStatus.textContent = 'Activado';
        } else {
            translateStatus.classList.add('text-danger');
            translateStatus.textContent = 'Desactivado';
        }
    }
});
</script>

@endsection
