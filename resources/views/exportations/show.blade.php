@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-folder-open"></i> Carpeta: {{ $exportation->export_number }}</h2>
            <div>
                <a href="{{ route('exportations.edit', $exportation->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('exportations.index') }}" class="btn btn-secondary">
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

<!-- Información del Contrato -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-contract"></i> Información del Contrato</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">N° Contrato:</dt>
                    <dd class="col-sm-7"><strong>{{ $exportation->contract->contract_number }}</strong></dd>
                    
                    <dt class="col-sm-5">Cliente:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->client->name }}</dd>
                    
                    <dt class="col-sm-5">Consignatario:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->consignee_name }}</dd>
                    
                    <dt class="col-sm-5">Puerto Destino:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->destination_port }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">Buque:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->vessel_name }}</dd>
                    
                    <dt class="col-sm-5">Booking:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->booking_number }}</dd>
                    
                    <dt class="col-sm-5">Contenedor:</dt>
                    <dd class="col-sm-7">{{ $exportation->contract->container_number }}</dd>
                    
                    <dt class="col-sm-5">Stock:</dt>
                    <dd class="col-sm-7">{{ number_format($exportation->contract->stock_committed, 2) }} kg</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Gestión Documental -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Gestión Documental - Carpeta: {{ $exportation->export_number }}</h5>
    </div>
    <div class="card-body">
        
        <!-- Sección de Generación de Documentos -->
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-file-export"></i> Generar Documentos PDF</h6>
                <p class="text-muted small">Los documentos se generan desde el contrato {{ $exportation->contract->contract_number }} y se guardan automáticamente en la carpeta.</p>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-ship fa-3x text-info mb-3"></i>
                        <h6>Bill of Lading</h6>
                        <p class="small text-muted">Conocimiento de embarque marítimo</p>
                        <a href="{{ route('contracts.generate.bill-of-lading', $exportation->contract_id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-file-invoice-dollar fa-3x text-primary mb-3"></i>
                        <h6>Commercial Invoice</h6>
                        <p class="small text-muted">Factura comercial</p>
                        <a href="{{ route('contracts.generate.invoice', $exportation->contract_id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                        <h6>Packing List</h6>
                        <p class="small text-muted">Lista de empaque</p>
                        <a href="{{ route('contracts.generate.packing-list', $exportation->contract_id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate fa-3x text-warning mb-3"></i>
                        <h6>Certificate of Origin</h6>
                        <p class="small text-muted">Certificado de origen</p>
                        <a href="{{ route('contracts.generate.certificate-origin', $exportation->contract_id) }}" class="btn btn-warning btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 border-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-leaf fa-3x text-danger mb-3"></i>
                        <h6>Phytosanitary Certificate</h6>
                        <p class="small text-muted">Certificado fitosanitario</p>
                        <a href="{{ route('contracts.generate.phytosanitary', $exportation->contract_id) }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 border-secondary">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x text-secondary mb-3"></i>
                        <h6>Quality Certificate</h6>
                        <p class="small text-muted">Certificado de calidad</p>
                        <a href="{{ route('contracts.generate.quality', $exportation->contract_id) }}" class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Documentos Almacenados -->
        <div class="row">
            <div class="col-12">
                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-folder"></i> Documentos en la Carpeta</h6>
            </div>
            
            @php
                $documents = $exportation->contract->documents()->orderBy('created_at', 'desc')->get();
            @endphp

            @if($documents->count() > 0)
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo de Documento</th>
                                    <th>Nombre del Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Subido</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $doc->document_type_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            {{ $doc->document_name }}
                                        </td>
                                        <td>{{ $doc->file_size_formatted }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $doc->created_at->format('d/m/Y H:i') }}<br>
                                                por {{ $doc->uploader->name ?? 'Sistema' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('contract-documents.download', $doc->id) }}" class="btn btn-sm btn-primary" title="Descargar">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#sendEmailModal{{ $doc->id }}" title="Enviar por Email">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                                <form action="{{ route('contract-documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este documento?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal para enviar email -->
                                    <div class="modal fade" id="sendEmailModal{{ $doc->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Enviar Documento por Email</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('exportations.send-document', $exportation->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="document_id" value="{{ $doc->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="recipient_name{{ $doc->id }}" class="form-label">Nombre del Destinatario</label>
                                                            <input type="text" class="form-control" id="recipient_name{{ $doc->id }}" name="recipient_name" value="{{ $exportation->contract->consignee_name }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="recipient_email{{ $doc->id }}" class="form-label">Email del Destinatario *</label>
                                                            <input type="email" class="form-control" id="recipient_email{{ $doc->id }}" name="recipient_email" value="{{ $exportation->contract->contact_email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="message{{ $doc->id }}" class="form-label">Mensaje (opcional)</label>
                                                            <textarea class="form-control" id="message{{ $doc->id }}" name="message" rows="3" placeholder="Mensaje adicional para el destinatario"></textarea>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>Documento a enviar:</strong> {{ $doc->document_name }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-paper-plane"></i> Enviar Email
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay documentos en esta carpeta aún. Genera los documentos usando los botones de arriba.
                    </div>
                </div>
            @endif
        </div>

        <hr>

        <!-- Subir Documento Manual -->
        <div class="row">
            <div class="col-12">
                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-upload"></i> Subir Documento Adicional</h6>
            </div>
            <div class="col-12">
                <form action="{{ route('exportations.upload-document', $exportation->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="document_type" class="form-label">Tipo de Documento *</label>
                            <select class="form-select" name="document_type" id="document_type" required>
                                <option value="">Seleccione...</option>
                                <option value="bill_of_lading">Bill of Lading</option>
                                <option value="invoice">Commercial Invoice</option>
                                <option value="packing_list">Packing List</option>
                                <option value="certificate_origin">Certificate of Origin</option>
                                <option value="phytosanitary">Phytosanitary Certificate</option>
                                <option value="calidad">Quality Certificate</option>
                                <option value="transport">Documento de Transporte</option>
                                <option value="naviera">Documento de Naviera</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="file" class="form-label">Archivo *</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                            <small class="form-text text-muted">Máximo 20MB</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control" name="notes" id="notes" rows="2" placeholder="Notas adicionales sobre el documento"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Subir Documento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@endsection
