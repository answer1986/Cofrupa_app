@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-alt"></i> Detalles del Documento #{{ $document->document_number }}</h2>
            <div>
                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Número de Documento:</dt>
                    <dd class="col-sm-8"><strong>{{ $document->document_number }}</strong></dd>

                    <dt class="col-sm-4">Tipo de Documento:</dt>
                    <dd class="col-sm-8">{{ $document->document_type_display }}</dd>

                    <dt class="col-sm-4">Despacho:</dt>
                    <dd class="col-sm-8">
                        <a href="{{ route('shipments.show', $document->shipment->id) }}">
                            {{ $document->shipment->shipment_number }}
                        </a>
                    </dd>

                    <dt class="col-sm-4">Contrato:</dt>
                    <dd class="col-sm-8">
                        <a href="{{ route('contracts.show', $document->shipment->contract->id) }}">
                            #{{ $document->shipment->contract->id }} - {{ $document->shipment->contract->client->name }}
                        </a>
                    </dd>

                    <dt class="col-sm-4">Destinatario:</dt>
                    <dd class="col-sm-8">{{ $document->recipient_display }}</dd>

                    @if($document->recipient_company)
                    <dt class="col-sm-4">Empresa Destinataria:</dt>
                    <dd class="col-sm-8">{{ $document->recipient_company }}</dd>
                    @endif

                    <dt class="col-sm-4">Estado:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $document->status === 'confirmed' ? 'success' : ($document->status === 'sent' ? 'info' : ($document->status === 'generated' ? 'warning' : 'secondary')) }}">
                            {{ ucfirst($document->status) }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Fechas y Archivos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Fechas y Archivos</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Fecha de Generación:</dt>
                    <dd class="col-sm-8">
                        @if($document->generated_at)
                            {{ $document->generated_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">No generado</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Fecha de Envío:</dt>
                    <dd class="col-sm-8">
                        @if($document->sent_at)
                            {{ $document->sent_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">No enviado</span>
                        @endif
                    </dd>

                    @if($document->file_path)
                    <dt class="col-sm-4">Archivo:</dt>
                    <dd class="col-sm-8">
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Descargar Documento
                        </a>
                    </dd>
                    @endif
                </dl>
            </div>
        </div>

        @if($document->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p>{{ $document->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Acciones -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Acciones</h5>
            </div>
            <div class="card-body">
                @if($document->status === 'draft')
                    <form action="{{ route('documents.generate', $document->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-file-pdf"></i> Generar Documento
                        </button>
                    </form>
                @endif

                @if($document->status === 'generated')
                    <form action="{{ route('documents.send', $document->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-paper-plane"></i> Enviar Documento
                        </button>
                    </form>
                @endif

                @if($document->file_path)
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-eye"></i> Ver Documento
                    </a>
                @endif

                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> Editar Documento
                </a>

                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este documento?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Documento
                    </button>
                </form>
            </div>
        </div>

        <!-- Información del Despacho -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Información del Despacho</h5>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Número:</dt>
                    <dd><a href="{{ route('shipments.show', $document->shipment->id) }}">{{ $document->shipment->shipment_number }}</a></dd>

                    <dt>Fecha Programada:</dt>
                    <dd>{{ $document->shipment->scheduled_date->format('d/m/Y') }}</dd>

                    <dt>Estado:</dt>
                    <dd>
                        <span class="badge bg-{{ $document->shipment->status === 'completed' ? 'success' : ($document->shipment->status === 'cancelled' ? 'danger' : 'info') }}">
                            {{ $document->shipment->status_display }}
                        </span>
                    </dd>

                    <dt>Cliente:</dt>
                    <dd>{{ $document->shipment->contract->client->name }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection



