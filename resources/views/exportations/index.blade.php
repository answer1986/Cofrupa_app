@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-folder-open"></i> Carpetas de Exportación por Contrato</h2>
                <a href="{{ route('exportations.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nueva Exportación
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> <strong>Carpetas Organizadas por Contrato</strong><br>
        <small>Cada contrato tiene una carpeta con todos sus documentos asociados. Click en "Ver Carpeta" para acceder a los documentos.</small>
    </div>

    <!-- Agrupar por contratos -->
    @php
        $contractGroups = \App\Models\Contract::with(['documents', 'client'])
            ->whereIn('status', ['active', 'completed'])
            ->get()
            ->map(function($contract) {
                return [
                    'contract' => $contract,
                    'documents_count' => $contract->documents()->count(),
                    'exportations' => \App\Models\Exportation::where('contract_id', $contract->id)->get()
                ];
            });
    @endphp

    <div class="row">
        @forelse($contractGroups as $group)
            @php $contract = $group['contract']; @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-folder"></i> {{ $contract->contract_number }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5">Cliente:</dt>
                            <dd class="col-7">{{ $contract->client->name }}</dd>
                            
                            <dt class="col-5">Consignatario:</dt>
                            <dd class="col-7">{{ Str::limit($contract->consignee_name ?? 'N/A', 20) }}</dd>
                            
                            <dt class="col-5">Buque:</dt>
                            <dd class="col-7">{{ $contract->vessel_name ?? 'N/A' }}</dd>
                            
                            <dt class="col-5">Contenedor:</dt>
                            <dd class="col-7">{{ $contract->container_number ?? 'N/A' }}</dd>
                            
                            <dt class="col-5">Documentos:</dt>
                            <dd class="col-7">
                                <span class="badge bg-success">{{ $group['documents_count'] }} PDFs</span>
                            </dd>
                            
                            <dt class="col-5">Estado:</dt>
                            <dd class="col-7">
                                <span class="badge bg-{{ $contract->status === 'active' ? 'success' : 'info' }}">
                                    {{ $contract->status_display }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2">
                            @if($group['exportations']->count() > 0)
                                <a href="{{ route('exportations.show', $group['exportations']->first()->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-folder-open"></i> Ver Carpeta Completa
                                </a>
                            @else
                                <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-medical"></i> Generar Documentos
                                </a>
                            @endif
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('documents.quality-certificate.edit', $contract->id) }}" class="btn btn-outline-secondary" title="Certificado de Calidad">
                                    <i class="fas fa-certificate"></i>
                                </a>
                                <a href="{{ route('documents.shipping-instructions.edit', $contract->id) }}" class="btn btn-outline-secondary" title="Inst. Embarque">
                                    <i class="fas fa-ship"></i>
                                </a>
                                <a href="{{ route('documents.transport-instructions.edit', $contract->id) }}" class="btn btn-outline-secondary" title="Inst. Transporte">
                                    <i class="fas fa-truck"></i>
                                </a>
                            </div>
                        </div>
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
