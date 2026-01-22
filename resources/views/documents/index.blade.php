@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Generación de Documentos (CORE)</h2>
                <a href="{{ route('documents.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Documento
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Documentos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Documento</th>
                                    <th>Despacho</th>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Destinatario</th>
                                    <th>Empresa</th>
                                    <th>Estado</th>
                                    <th>Generado</th>
                                    <th>Enviado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td><strong>{{ $document->document_number }}</strong></td>
                                        <td>{{ $document->shipment->shipment_number }}</td>
                                        <td>{{ $document->shipment->contract->client->name }}</td>
                                        <td>{{ $document->document_type_display }}</td>
                                        <td>{{ $document->recipient_display }}</td>
                                        <td>{{ $document->recipient_company ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $document->status === 'confirmed' ? 'success' : ($document->status === 'sent' ? 'info' : ($document->status === 'generated' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $document->generated_at ? $document->generated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $document->sent_at ? $document->sent_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($document->status === 'draft')
                                                <form action="{{ route('documents.generate', $document->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Generar">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($document->status === 'generated')
                                                <form action="{{ route('documents.send', $document->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Enviar">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No hay documentos registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




