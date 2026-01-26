@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-search"></i> Trazabilidad del Bin</h2>
            <a href="{{ route('bin_processing.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- Información del Bin Actual -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-box"></i> Bin Actual</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Número de Bin:</strong> {{ $traceabilityInfo['current_bin']['bin_number'] }}</p>
                        <p><strong>Número de Tarja:</strong> {{ $traceabilityInfo['current_bin']['tarja_number'] ?? 'N/A' }}</p>
                        <p><strong>Peso:</strong> {{ number_format($traceabilityInfo['current_bin']['weight'], 2) }} kg</p>
                        <p><strong>Calibre:</strong> {{ $traceabilityInfo['current_bin']['calibre'] ?? 'N/A' }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-success">{{ $traceabilityInfo['current_bin']['status'] }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Proveedor Actual:</strong> 
                            @if($traceabilityInfo['current_bin']['supplier'])
                                {{ $traceabilityInfo['current_bin']['supplier']['name'] }}
                                @if($traceabilityInfo['current_bin']['supplier']['internal_code'])
                                    ({{ $traceabilityInfo['current_bin']['supplier']['internal_code'] }})
                                @endif
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proveedor Original -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user-tie"></i> Proveedor Original (Origen de la Fruta)</h5>
            </div>
            <div class="card-body">
                @if($traceabilityInfo['original_supplier'])
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Nombre:</strong> {{ $traceabilityInfo['original_supplier']['name'] }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Código Interno:</strong> 
                                {{ $traceabilityInfo['original_supplier']['internal_code'] ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Código CSG:</strong> 
                                {{ $traceabilityInfo['original_supplier']['csg_code'] ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Este es el proveedor original</strong> del cual se compró la fruta que está en este bin.
                        @if($traceabilityInfo['current_bin']['supplier']['id'] != $traceabilityInfo['original_supplier']['id'])
                            <br><small>Nota: Este bin fue mezclado. El proveedor actual puede ser diferente del original.</small>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        No se pudo determinar el proveedor original.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bins Fuente (si fue mezclado) -->
@if($traceabilityInfo['source_bins_count'] > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Bins Fuente ({{ $traceabilityInfo['source_bins_count'] }})</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Este bin fue creado mezclando los siguientes bins:</p>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Bin/Tarja</th>
                                <th>Peso (kg)</th>
                                <th>Proveedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traceabilityInfo['source_bins'] as $sourceBin)
                            <tr>
                                <td>
                                    <strong>{{ $sourceBin['bin_number'] }}</strong>
                                    @if($sourceBin['tarja_number'])
                                        <br><small class="text-muted">Tarja: {{ $sourceBin['tarja_number'] }}</small>
                                    @endif
                                </td>
                                <td>{{ number_format($sourceBin['weight'], 2) }}</td>
                                <td>{{ $sourceBin['supplier'] ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Todos los Proveedores Involucrados -->
@if(count($traceabilityInfo['all_suppliers']) > 1)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users"></i> Todos los Proveedores en la Cadena</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Proveedores involucrados en la cadena de trazabilidad:</p>
                <ul class="list-group">
                    @foreach($traceabilityInfo['all_suppliers'] as $supplier)
                    <li class="list-group-item">
                        <strong>{{ $supplier['name'] }}</strong>
                        @if($supplier['internal_code'])
                            <span class="badge bg-secondary">{{ $supplier['internal_code'] }}</span>
                        @endif
                        @if($supplier['id'] == $traceabilityInfo['original_supplier']['id'])
                            <span class="badge bg-success">Original</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Órdenes de Procesamiento -->
@if($traceabilityInfo['process_orders_count'] > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-industry"></i> Órdenes de Procesamiento ({{ $traceabilityInfo['process_orders_count'] }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Número de Orden</th>
                                <th>Planta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traceabilityInfo['process_orders'] as $order)
                            <tr>
                                <td><strong>{{ $order['order_number'] }}</strong></td>
                                <td>{{ $order['plant'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $order['status'] === 'completed' ? 'success' : ($order['status'] === 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Resumen -->
<div class="row">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Resumen de Trazabilidad</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-primary">{{ $traceabilityInfo['source_bins_count'] }}</h3>
                            <p class="text-muted mb-0">Bins Fuente</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-success">{{ count($traceabilityInfo['all_suppliers']) }}</h3>
                            <p class="text-muted mb-0">Proveedores</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-warning">{{ $traceabilityInfo['process_orders_count'] }}</h3>
                            <p class="text-muted mb-0">Órdenes de Proceso</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-info">{{ number_format($traceabilityInfo['current_bin']['weight'], 2) }}</h3>
                            <p class="text-muted mb-0">Peso Total (kg)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
