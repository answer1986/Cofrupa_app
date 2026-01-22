@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-alt"></i> Crear Nuevo Documento</h2>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> Información del Documento</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="shipment_id" class="form-label">Despacho *</label>
                        <select class="form-select @error('shipment_id') is-invalid @enderror" id="shipment_id" name="shipment_id" required>
                            <option value="">Seleccione un despacho</option>
                            @foreach($shipments as $shipment)
                                <option value="{{ $shipment->id }}" {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                    {{ $shipment->shipment_number }} - {{ $shipment->contract->client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="document_type" class="form-label">Tipo de Documento *</label>
                        <select class="form-select @error('document_type') is-invalid @enderror" id="document_type" name="document_type" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="export_guide_plant" {{ old('document_type') == 'export_guide_plant' ? 'selected' : '' }}>Guía de Exportación (Planta)</option>
                            <option value="export_guide_transport" {{ old('document_type') == 'export_guide_transport' ? 'selected' : '' }}>Guía de Exportación (Transporte)</option>
                            <option value="customs_loading" {{ old('document_type') == 'customs_loading' ? 'selected' : '' }}>Documentos de Carga (Aduana)</option>
                            <option value="dvl_matrix" {{ old('document_type') == 'dvl_matrix' ? 'selected' : '' }}>Matriz DVL (Embarque)</option>
                            <option value="master_document" {{ old('document_type') == 'master_document' ? 'selected' : '' }}>Documento Maestro</option>
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="recipient" class="form-label">Destinatario *</label>
                        <select class="form-select @error('recipient') is-invalid @enderror" id="recipient" name="recipient" required>
                            <option value="">Seleccione un destinatario</option>
                            <option value="plant" {{ old('recipient') == 'plant' ? 'selected' : '' }}>Planta</option>
                            <option value="customs" {{ old('recipient') == 'customs' ? 'selected' : '' }}>Aduana</option>
                            <option value="transport" {{ old('recipient') == 'transport' ? 'selected' : '' }}>Transporte</option>
                            <option value="embarkation" {{ old('recipient') == 'embarkation' ? 'selected' : '' }}>Embarque</option>
                        </select>
                        @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="recipient_company" class="form-label">Empresa Destinataria</label>
                        <select class="form-select @error('recipient_company') is-invalid @enderror" id="recipient_company" name="recipient_company">
                            <option value="">Seleccione</option>
                            <option value="SPS" {{ old('recipient_company') == 'SPS' ? 'selected' : '' }}>SPS</option>
                            <option value="DUS" {{ old('recipient_company') == 'DUS' ? 'selected' : '' }}>DUS</option>
                        </select>
                        <small class="form-text text-muted">Aplicable para planta, aduana y transporte</small>
                        @error('recipient_company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Documento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection




