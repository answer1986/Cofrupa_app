@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice"></i> Instructivo de Embarque</h2>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-folder-open"></i> Ver Carpetas
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona un contrato para generar el Instructivo de Embarque</strong><br>
        <small>Documento con instrucciones detalladas para la naviera sobre el embarque y los contenedores.</small>
    </div>

    <div class="row">
        @forelse($contracts as $contract)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">{{ $contract->contract_number }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5">Cliente:</dt>
                            <dd class="col-7">{{ $contract->client->name }}</dd>
                            
                            <dt class="col-5">Consignatario:</dt>
                            <dd class="col-7">{{ Str::limit($contract->consignee_name ?? 'N/A', 25) }}</dd>
                            
                            <dt class="col-5">Producto:</dt>
                            <dd class="col-7">{{ Str::limit($contract->product_description ?? 'N/A', 30) }}</dd>
                            
                            <dt class="col-5">Buque:</dt>
                            <dd class="col-7">{{ $contract->vessel_name ?? 'N/A' }}</dd>
                            
                            <dt class="col-5">Estado:</dt>
                            <dd class="col-7">
                                <span class="badge bg-{{ $contract->status === 'active' ? 'success' : 'info' }}">
                                    {{ $contract->status_display }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('documents.shipping-instructions.edit', $contract->id) }}" class="btn btn-info w-100">
                            <i class="fas fa-edit"></i> Generar Instructivo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No hay contratos activos.
                    <a href="{{ route('contracts.create') }}" class="alert-link">Crear un contrato primero</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection



