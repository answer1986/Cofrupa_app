@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-eye"></i> Orden de Proceso: {{ $order->order_number }}</h2>
                <div>
                    @if($order->plant)
                        <a href="{{ route('processing.orders.preview-pdf', $order->id) }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-file-pdf"></i> Ver PDF
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sendOrderModal">
                            <i class="fas fa-paper-plane"></i> Enviar a Planta
                        </button>
                    @endif
                    <a href="{{ route('processing.orders.edit', $order->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">N° Orden:</th>
                            <td><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Planta:</th>
                            <td><strong>{{ $order->plant->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Proveedor:</th>
                            <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contrato:</th>
                            <td>{{ $order->contract->contract_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CSG Code:</th>
                            <td>{{ $order->csg_code ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'in_progress' ? 'info' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                    {{ $order->status_display }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tiempos y Progreso</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Fecha Orden:</th>
                            <td>{{ $order->order_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tiempo Producción:</th>
                            <td>{{ $order->production_days ?? 'N/A' }} días</td>
                        </tr>
                        <tr>
                            <th>Término Esperado:</th>
                            <td>
                                @if($order->completion_week && $order->completion_year)
                                    Semana {{ $order->completion_week }}, {{ $order->completion_year }}
                                    @if($order->expected_completion_date)
                                        <span class="text-muted">({{ $order->expected_completion_date->format('d/m/Y') }})</span>
                                    @endif
                                @else
                                    {{ $order->expected_completion_date ? $order->expected_completion_date->format('d/m/Y') : 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha Término Real:</th>
                            <td>{{ $order->actual_completion_date ? $order->actual_completion_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Progreso:</th>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $order->progress_percentage == 100 ? 'bg-success' : ($order->progress_percentage >= 50 ? 'bg-info' : 'bg-warning') }}" 
                                         role="progressbar" 
                                         style="width: {{ $order->progress_percentage }}%"
                                         aria-valuenow="{{ $order->progress_percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $order->progress_percentage }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Detalles del Producto</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Materia Prima:</th>
                                    <td>{{ $order->raw_material ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Producto:</th>
                                    <td>{{ $order->product ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>{{ $order->type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Calibre:</th>
                                    <td>{{ $order->caliber ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Cantidad:</th>
                                    <td>
                                        @if($order->quantity)
                                            {{ number_format($order->quantity, 3, ',', '.') }} KILOS
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kilos Enviados:</th>
                                    <td>
                                        @if($order->kilos_sent)
                                            {{ number_format($order->kilos_sent, 3, ',', '.') }} KILOS
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kilos Producidos:</th>
                                    <td>
                                        @if($order->kilos_produced)
                                            {{ number_format($order->kilos_produced, 3, ',', '.') }} KILOS
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Calidad:</th>
                                    <td>{{ $order->quality ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Etiquetado:</th>
                                    <td>{{ $order->labeling ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Envases:</th>
                                    <td>{{ $order->packaging ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Sorbato de potasio:</th>
                                    <td>{{ $order->potassium_sorbate ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Humedad:</th>
                                    <td>{{ $order->humidity ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>% de Carozo:</th>
                                    <td>{{ $order->stone_percentage ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Aceite:</th>
                                    <td>{{ $order->oil ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Daños:</th>
                                    <td>{{ $order->damage ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Impresión Planta:</th>
                                    <td>{{ $order->plant_print ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Destino:</th>
                                    <td>{{ $order->destination ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de carga:</th>
                                    <td>{{ $order->loading_date ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SAG:</th>
                                    <td>
                                        @if($order->sag)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Descripción (adicional):</th>
                                    <td>{{ $order->product_description ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->supplies->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos a enviar</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Insumo</th>
                                <th class="text-end">Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->supplies as $s)
                                <tr>
                                    <td>{{ $s->name }}</td>
                                    <td class="text-end">{{ number_format($s->quantity, 2) }}</td>
                                    <td>{{ $s->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Facturas de Retorno</h5>
                </div>
                <div class="card-body">
                    @if($order->invoices->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>N° Factura</th>
                                    <th>Monto</th>
                                    <th>Moneda</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>{{ $invoice->currency }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->is_paid ? 'success' : 'warning' }}">
                                                {{ $invoice->is_paid ? 'Pagado' : 'Pendiente' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">No hay facturas registradas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($order->notes)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Notas</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($order->plant)
    <!-- Modal para enviar orden a planta -->
    <div class="modal fade" id="sendOrderModal" tabindex="-1" aria-labelledby="sendOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="sendOrderModalLabel">
                        <i class="fas fa-paper-plane"></i> Enviar Orden a Planta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('processing.orders.send-to-plant', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Seleccionar Contacto de la Planta</label>
                            <select class="form-control" id="contact_email" name="contact_email" required>
                                <option value="">Seleccione un contacto...</option>
                                @foreach($order->plant->contacts as $contact)
                                    @if($contact->email)
                                        <option value="{{ $contact->email }}">
                                            {{ $contact->contact_person ?? 'Sin nombre' }} - {{ $contact->email }}
                                            @if($contact->phone)
                                                ({{ $contact->phone }})
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">O ingrese un email manualmente abajo</small>
                        </div>

                        <div class="mb-3">
                            <label for="manual_email" class="form-label">O Ingresar Email Manualmente</label>
                            <input type="email" class="form-control" id="manual_email" name="manual_email" placeholder="correo@planta.com">
                            <small class="text-muted">Si ingresa un email aquí, se usará este en lugar del seleccionado arriba</small>
                        </div>

                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Nombre del Contacto (opcional)</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Nombre del destinatario">
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Se enviará un correo con la orden de proceso adjunta en formato PDF.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="sendEmailBtn">
                            <i class="fas fa-paper-plane"></i> Enviar Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactSelect = document.getElementById('contact_email');
        const manualEmail = document.getElementById('manual_email');
        const form = document.querySelector('#sendOrderModal form');

        // Cuando se envía el formulario, usar el email manual si está lleno, sino el del select
        form.addEventListener('submit', function(e) {
            if (manualEmail.value.trim()) {
                // Si hay email manual, cambiar el valor del select
                contactSelect.value = manualEmail.value.trim();
            } else if (!contactSelect.value) {
                e.preventDefault();
                alert('Por favor, seleccione un contacto o ingrese un email manualmente');
                return false;
            }
        });

        // Limpiar email manual cuando se selecciona un contacto
        contactSelect.addEventListener('change', function() {
            if (this.value) {
                manualEmail.value = '';
            }
        });
    });
    </script>
    @endif
</div>
@endsection



