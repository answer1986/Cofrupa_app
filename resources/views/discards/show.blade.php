@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-recycle"></i> Detalle de Descarte - Orden #{{ $discard->order_number }}</h2>
                <a href="{{ route('discards.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información de la Orden de Producción</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Número de Orden:</th>
                            <td><strong>{{ $discard->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Planta:</th>
                            <td>{{ $discard->plant->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td>{{ $discard->product }}</td>
                        </tr>
                        <tr>
                            <th>Calibre Salida:</th>
                            <td><span class="badge bg-secondary">{{ $discard->output_caliber }}</span></td>
                        </tr>
                        <tr>
                            <th>Booking:</th>
                            <td>{{ $discard->booking_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Buque:</th>
                            <td>{{ $discard->vessel ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contrato:</th>
                            <td>
                                @if($discard->contract)
                                    <a href="{{ route('contracts.show', $discard->contract->id) }}">
                                        {{ $discard->contract->contract_number }} - {{ $discard->contract->client->name ?? 'N/A' }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Información del Descarte</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Cantidad Descarte:</th>
                            <td><h4 class="text-danger mb-0">{{ number_format($discard->discard_kg, 2, ',', '.') }} kg</h4></td>
                        </tr>
                        <tr>
                            <th>% del Total:</th>
                            <td>
                                <span class="badge {{ $discard->discard_percentage > 10 ? 'bg-danger' : 'bg-warning text-dark' }} fs-6">
                                    {{ number_format($discard->discard_percentage, 2) }}%
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @switch($discard->discard_status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark fs-6">Pendiente Recuperación</span>
                                        @break
                                    @case('recovered')
                                        <span class="badge bg-success fs-6">Recuperado</span>
                                        @break
                                    @case('disposed')
                                        <span class="badge bg-danger fs-6">Desechado</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Razón del Descarte:</th>
                            <td>{{ $discard->discard_reason ?? 'N/A' }}</td>
                        </tr>
                        @if($discard->discard_recovery_date)
                            <tr>
                                <th>Fecha de Recuperación/Desecho:</th>
                                <td>{{ $discard->discard_recovery_date->format('d/m/Y') }}</td>
                            </tr>
                        @endif
                        @if($discard->discard_notes)
                            <tr>
                                <th>Notas:</th>
                                <td>{{ $discard->discard_notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Resumen de Producción</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <h6 class="text-muted">Cantidad Ordenada</h6>
                                <h3>{{ number_format($discard->order_quantity_kg, 0, ',', '.') }} kg</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border-start">
                                <h6 class="text-muted">Producido</h6>
                                <h3 class="text-success">{{ number_format($discard->produced_kilos, 0, ',', '.') }} kg</h3>
                                <small class="text-muted">{{ number_format($discard->efficiency_percentage, 1) }}% eficiencia</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border-start">
                                <h6 class="text-muted">Descarte</h6>
                                <h3 class="text-danger">{{ number_format($discard->discard_kg, 0, ',', '.') }} kg</h3>
                                <small class="text-muted">{{ number_format($discard->discard_percentage, 1) }}% pérdida</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border-start">
                                <h6 class="text-muted">Total Contabilizado</h6>
                                <h3>{{ number_format($discard->produced_kilos + $discard->discard_kg, 0, ',', '.') }} kg</h3>
                                <small class="text-muted">
                                    {{ number_format((($discard->produced_kilos + $discard->discard_kg) / $discard->order_quantity_kg) * 100, 1) }}%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Fechas y Tiempos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Fecha de Entrada:</th>
                                    <td>{{ $discard->entry_date ? $discard->entry_date->format('d/m/Y') : 'N/A' }} {{ $discard->entry_time }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Término:</th>
                                    <td>{{ $discard->completion_date ? $discard->completion_date->format('d/m/Y') : 'N/A' }} {{ $discard->completion_time }}</td>
                                </tr>
                                <tr>
                                    <th>Día de la Semana:</th>
                                    <td><span class="badge bg-secondary">{{ ucfirst($discard->day_of_week ?? 'N/A') }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Horas Estimadas:</th>
                                    <td>{{ $discard->estimated_hours ?? 'N/A' }} hrs</td>
                                </tr>
                                <tr>
                                    <th>Horas Reales:</th>
                                    <td>{{ $discard->actual_hours ?? 'N/A' }} hrs</td>
                                </tr>
                                <tr>
                                    <th>Atraso:</th>
                                    <td>
                                        @if($discard->has_delay)
                                            <span class="badge bg-danger">{{ $discard->delay_hours }} hrs</span>
                                            @if($discard->delay_reason)
                                                <br><small>{{ $discard->delay_reason }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-success">Sin atraso</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($discard->discard_status === 'pending' && $discard->discard_kg > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5><i class="fas fa-exclamation-triangle text-warning"></i> Acciones Disponibles</h5>
                        <p>Este descarte está pendiente de recuperación. Puede:</p>
                        <button type="button" class="btn btn-success" onclick="showRecoverModal({{ $discard->id }})">
                            <i class="fas fa-recycle"></i> Recuperar y Devolver al Stock
                        </button>
                        <button type="button" class="btn btn-danger" onclick="showDisposeModal({{ $discard->id }})">
                            <i class="fas fa-trash"></i> Marcar como Desechado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal para Recuperar -->
<div class="modal fade" id="recoverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="recoverForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-recycle"></i> Recuperar Descarte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recovery_location" class="form-label">Ubicación de Almacenamiento *</label>
                        <input type="text" class="form-control" id="recovery_location" name="recovery_location" required placeholder="Ej: Bodega A - Estante 5">
                    </div>
                    <div class="mb-3">
                        <label for="recovery_notes" class="form-label">Notas de Recuperación</label>
                        <textarea class="form-control" id="recovery_notes" name="recovery_notes" rows="3" placeholder="Observaciones sobre el estado del material recuperado"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Recuperar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Desechar -->
<div class="modal fade" id="disposeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="disposeForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash"></i> Desechar Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Esta acción marcará el material como desechado y no podrá ser recuperado.
                    </div>
                    <div class="mb-3">
                        <label for="dispose_reason" class="form-label">Razón del Desecho *</label>
                        <textarea class="form-control" id="dispose_reason" name="dispose_reason" rows="3" required placeholder="Explique por qué este material no puede ser recuperado"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Confirmar Desecho</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRecoverModal(discardId) {
    const form = document.getElementById('recoverForm');
    form.action = `/discards/${discardId}/recover`;
    new bootstrap.Modal(document.getElementById('recoverModal')).show();
}

function showDisposeModal(discardId) {
    const form = document.getElementById('disposeForm');
    form.action = `/discards/${discardId}/dispose`;
    new bootstrap.Modal(document.getElementById('disposeModal')).show();
}
</script>
@endsection

