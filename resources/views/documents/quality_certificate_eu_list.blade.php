@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-certificate"></i> Certificado de Calidad - Unión Europea</h2>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-folder-open"></i> Ver Carpetas
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Selecciona un contrato para generar el Certificado de Calidad (EU)</strong><br>
        <small>Formato para cumplimiento con regulaciones de la Unión Europea. Podrás editar los datos antes de generar el PDF final.</small>
    </div>

    <div class="row">
        @forelse($contracts as $contract)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
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
                            
                            <dt class="col-5">Destino:</dt>
                            <dd class="col-7">{{ Str::limit($contract->destination_port ?? 'N/A', 25) }}</dd>
                            
                            <dt class="col-5">Estado:</dt>
                            <dd class="col-7">
                                <span class="badge bg-{{ $contract->status === 'active' ? 'success' : 'info' }}">
                                    {{ $contract->status_display }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('documents.quality-certificate-eu.edit', $contract->id) }}" class="btn btn-success w-100">
                            <i class="fas fa-edit"></i> Generar Certificado EU
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



