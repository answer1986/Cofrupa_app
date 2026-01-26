@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-truck-loading"></i> Recepción de Productos</h2>
            <a href="{{ route('bin_reception.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Recepción
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Bins Recibidos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Fecha Recepción</th>
                                <th><i class="fas fa-hashtag"></i> Bin</th>
                                <th><i class="fas fa-truck"></i> Proveedor</th>
                                <th><i class="fas fa-weight"></i> Peso (kg)</th>
                                <th><i class="fas fa-tag"></i> Calibre</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-qrcode"></i> QR</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receivedBins as $bin)
                            <tr>
                                <td>{{ $bin->entry_date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $bin->current_bin_number }}</strong>
                                </td>
                                <td>{{ $bin->supplier->name }}</td>
                                <td>{{ number_format($bin->current_weight, 2) }}</td>
                                <td>{{ $bin->current_calibre_display }}</td>
                                <td>
                                    <span class="badge bg-{{ $bin->status === 'received' ? 'info' : ($bin->status === 'processed' ? 'success' : 'secondary') }}">
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
                                    <a href="{{ route('bin_reception.show', $bin) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-truck-loading fa-2x mb-2"></i>
                                    <br>
                                    No hay bins recibidos aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($receivedBins->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $receivedBins->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection