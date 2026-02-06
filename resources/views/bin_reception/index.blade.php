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

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('bin_reception.index') }}" class="row g-3">
            <div class="col-md-2">
                <label for="vehicle_plate" class="form-label">Camión (Placa)</label>
                <input type="text" 
                       class="form-control form-control-sm" 
                       id="vehicle_plate" 
                       name="vehicle_plate" 
                       value="{{ request('vehicle_plate') }}"
                       placeholder="Ej: ABC123">
            </div>
            <div class="col-md-2">
                <label for="guide_number" class="form-label">N° Guía</label>
                <input type="text" 
                       class="form-control form-control-sm" 
                       id="guide_number" 
                       name="guide_number" 
                       value="{{ request('guide_number') }}"
                       placeholder="Número de guía">
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Fecha Desde</label>
                <input type="date" 
                       class="form-control form-control-sm" 
                       id="date_from" 
                       name="date_from" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Fecha Hasta</label>
                <input type="date" 
                       class="form-control form-control-sm" 
                       id="date_to" 
                       name="date_to" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label for="supplier_id" class="form-label">Proveedor</label>
                <select class="form-select form-select-sm" id="supplier_id" name="supplier_id">
                    <option value="">Todos los proveedores</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
            @if(request()->hasAny(['vehicle_plate', 'guide_number', 'date_from', 'date_to', 'supplier_id']))
            <div class="col-12">
                <a href="{{ route('bin_reception.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times"></i> Limpiar Filtros
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

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
                                <th><i class="fas fa-car"></i> Camión (Placa)</th>
                                <th><i class="fas fa-file-alt"></i> N° Guía</th>
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
                                <td>
                                    @if($bin->vehicle_plate)
                                        <span class="badge bg-secondary">{{ $bin->vehicle_plate }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bin->guide_number)
                                        <span class="badge bg-info text-dark">{{ $bin->guide_number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
                                <td colspan="10" class="text-center text-muted py-4">
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