@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-qrcode"></i> Bins Procesados</h2>
            <div>
                <a href="{{ route('processed_bins.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus"></i> Nuevo Procesamiento
                </a>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Compras
                </a>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Bins Procesados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Fecha Ingreso</th>
                                <th><i class="fas fa-hashtag"></i> Bin</th>
                                <th><i class="fas fa-truck"></i> Proveedor</th>
                                <th><i class="fas fa-weight"></i> Peso (kg)</th>
                                <th><i class="fas fa-tag"></i> Calibre</th>
                                <th><i class="fas fa-map-marker"></i> Destino</th>
                                <th><i class="fas fa-shipping-fast"></i> Estado</th>
                                <th><i class="fas fa-qrcode"></i> QR</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($processedBins as $bin)
                            <tr>
                                <td>{{ $bin->entry_date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $bin->display_bin_number }}</strong>
                                    @if($bin->purchase && $bin->purchase->id)
                                        <br><small class="text-muted">Compra #{{ $bin->purchase->id }}</small>
                                    @else
                                        <br><small class="text-warning">Independiente</small>
                                    @endif
                                </td>
                                <td>{{ $bin->supplier->name }}</td>
                                <td>{{ number_format($bin->current_weight, 2) }}</td>
                                <td>{{ $bin->calibre_display }}</td>
                                <td>{{ $bin->destination ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $bin->status === 'processed' ? 'primary' : ($bin->status === 'shipped' ? 'warning' : 'success') }}">
                                        {{ $bin->status_display }}
                                    </span>
                                </td>
                                <td>
                                    @if($bin->qr_code)
                                        <i class="fas fa-check text-success"></i> Generado
                                    @else
                                        <i class="fas fa-times text-danger"></i> No generado
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('processed_bins.show', $bin) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        @if($bin->status !== 'delivered')
                                        <button class="btn btn-sm btn-warning" title="Actualizar estado" onclick="editStatus({{ $bin->id }}, '{{ $bin->status }}')">
                                            <i class="fas fa-edit"></i> Estado
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-qrcode fa-2x mb-2"></i>
                                    <br>
                                    No hay bins procesados aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($processedBins->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $processedBins->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $processedBins->total() }}</h3>
                <p class="text-muted mb-0">Total Bins Procesados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ number_format($processedBins->sum('current_weight'), 2) }}</h3>
                <p class="text-muted mb-0">Total Peso (kg)</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $processedBins->where('status', 'processed')->count() }}</h3>
                <p class="text-muted mb-0">Pendientes de Envío</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $processedBins->where('status', 'delivered')->count() }}</h3>
                <p class="text-muted mb-0">Entregados</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Estado del Bin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="processed">Procesado</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exit_date" class="form-label">Fecha de Salida</label>
                        <input type="date" class="form-control" id="exit_date" name="exit_date">
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="destination" name="destination">
                    </div>
                    <div class="mb-3">
                        <label for="guide_number" class="form-label">N° Guía</label>
                        <input type="text" class="form-control" id="guide_number" name="guide_number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editStatus(binId, currentStatus) {
    document.getElementById('editStatusForm').action = `/processed_bins/${binId}`;
    document.getElementById('status').value = currentStatus;
    new bootstrap.Modal(document.getElementById('editStatusModal')).show();
}
</script>
@endsection