@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-recycle"></i> Gestión de Descartes de Producción</h2>
                <a href="{{ route('discards.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Descarte
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0">Pendiente Recuperar</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_pending_kg'], 0, ',', '.') }} kg</h3>
                        </div>
                        <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0">Recuperado</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_recovered_kg'], 0, ',', '.') }} kg</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0">Desechado</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_disposed_kg'], 0, ',', '.') }} kg</h3>
                        </div>
                        <i class="fas fa-trash-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0">Valor Estimado</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_discard_value'], 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('discards.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="discard_status" class="form-label">Estado</label>
                    <select name="discard_status" id="discard_status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('discard_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="recovered" {{ request('discard_status') == 'recovered' ? 'selected' : '' }}>Recuperado</option>
                        <option value="disposed" {{ request('discard_status') == 'disposed' ? 'selected' : '' }}>Desechado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="plant_id" class="form-label">Planta</label>
                    <select name="plant_id" id="plant_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($plants as $plant)
                            <option value="{{ $plant->id }}" {{ request('plant_id') == $plant->id ? 'selected' : '' }}>
                                {{ $plant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Desde</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Hasta</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filtrar</button>
                    <a href="{{ route('discards.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Recuperación Masiva -->
    <form id="bulkRecoverForm" method="POST" action="{{ route('discards.bulk-recover') }}">
        @csrf
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Descartes</h5>
                <button type="button" class="btn btn-success btn-sm" onclick="showBulkRecoverModal()">
                    <i class="fas fa-recycle"></i> Recuperar Seleccionados
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Orden #</th>
                                <th>Planta</th>
                                <th>Producto</th>
                                <th>Calibre</th>
                                <th>Fecha</th>
                                <th>Cantidad Orden</th>
                                <th>Producido</th>
                                <th>Descarte (kg)</th>
                                <th>% Descarte</th>
                                <th>Estado</th>
                                <th>Razón</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($discards as $discard)
                                <tr>
                                    <td>
                                        @if($discard->discard_status === 'pending')
                                            <input type="checkbox" name="discard_ids[]" value="{{ $discard->id }}" class="discard-checkbox">
                                        @endif
                                    </td>
                                    <td><strong>{{ $discard->order_number }}</strong></td>
                                    <td>{{ $discard->plant->name ?? 'N/A' }}</td>
                                    <td>{{ $discard->product }}</td>
                                    <td><span class="badge bg-secondary">{{ $discard->output_caliber }}</span></td>
                                    <td>{{ $discard->completion_date ? $discard->completion_date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ number_format($discard->order_quantity_kg, 0, ',', '.') }} kg</td>
                                    <td>{{ number_format($discard->produced_kilos, 0, ',', '.') }} kg</td>
                                    <td><strong class="text-danger">{{ number_format($discard->discard_kg, 2, ',', '.') }} kg</strong></td>
                                    <td>
                                        <span class="badge {{ $discard->discard_percentage > 10 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                            {{ number_format($discard->discard_percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        @switch($discard->discard_status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                                @break
                                            @case('recovered')
                                                <span class="badge bg-success">Recuperado</span>
                                                @break
                                            @case('disposed')
                                                <span class="badge bg-danger">Desechado</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td><small>{{ Str::limit($discard->discard_reason, 30) }}</small></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('discards.show', $discard->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($discard->discard_status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="showRecoverModal({{ $discard->id }})" title="Recuperar">
                                                    <i class="fas fa-recycle"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="showDisposeModal({{ $discard->id }})" title="Desechar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center">No hay descartes registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $discards->links() }}
            </div>
        </div>
    </form>
</div>

<!-- Modal para Recuperar Individual -->
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

<!-- Modal para Recuperación Masiva -->
<div class="modal fade" id="bulkRecoverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-recycle"></i> Recuperación Masiva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bulk_recovery_location" class="form-label">Ubicación de Almacenamiento *</label>
                    <input type="text" class="form-control" id="bulk_recovery_location" name="recovery_location" form="bulkRecoverForm" required placeholder="Ej: Bodega A - Estante 5">
                </div>
                <div class="mb-3">
                    <label for="bulk_recovery_notes" class="form-label">Notas de Recuperación</label>
                    <textarea class="form-control" id="bulk_recovery_notes" name="recovery_notes" form="bulkRecoverForm" rows="3" placeholder="Observaciones generales"></textarea>
                </div>
                <p id="selectedCount" class="text-muted"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="document.getElementById('bulkRecoverForm').submit()">
                    <i class="fas fa-check"></i> Recuperar Seleccionados
                </button>
            </div>
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

function showBulkRecoverModal() {
    const checkboxes = document.querySelectorAll('.discard-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Por favor, seleccione al menos un descarte para recuperar');
        return;
    }
    document.getElementById('selectedCount').textContent = `${checkboxes.length} descarte(s) seleccionado(s)`;
    new bootstrap.Modal(document.getElementById('bulkRecoverModal')).show();
}

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.discard-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
@endsection

