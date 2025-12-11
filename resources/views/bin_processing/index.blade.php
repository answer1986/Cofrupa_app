@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-balance-scale"></i> Bins Procesados</h2>
            <a href="{{ route('bin_processing.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Procesamiento
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Bins Procesados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Fecha Procesamiento</th>
                                <th><i class="fas fa-hashtag"></i> Bin Resultante</th>
                                <th><i class="fas fa-weight"></i> Peso Total (kg)</th>
                                <th><i class="fas fa-tag"></i> Calibre</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-qrcode"></i> QR</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($processedBins as $bin)
                            <tr>
                                <td>{{ $bin->processing_date ? $bin->processing_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <strong>{{ $bin->current_bin_number }}</strong>
                                </td>
                                <td>{{ number_format($bin->current_weight, 2) }}</td>
                                <td>{{ $bin->current_calibre_display }}</td>
                                <td>
                                    <span class="badge bg-success">
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
                                    <a href="{{ route('bin_processing.show', $bin) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-balance-scale fa-2x mb-2"></i>
                                    <br>
                                    No hay bins procesados aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($processedBins->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $processedBins->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection