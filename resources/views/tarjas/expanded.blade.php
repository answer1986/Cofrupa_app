@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-info-circle"></i> Información Ampliada de Tarja</h2>
                <div>
                    <a href="{{ route('tarjas.show', $processedBin->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> Ver Tarja
                    </a>
                    <a href="{{ route('tarjas.scanner') }}" class="btn btn-secondary">
                        <i class="fas fa-qrcode"></i> Escanear Otro QR
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

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tag"></i> Información de la Tarja</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-hashtag"></i> Número de Tarja:</strong><br>
                            <span class="h4 text-primary">{{ $processedBin->tarja_number }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-box"></i> Lote:</strong><br>
                            <span class="h5">{{ $processedBin->lote ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-calendar"></i> Fecha de Recepción:</strong><br>
                            {{ $processedBin->entry_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-car"></i> Patente del Vehículo:</strong><br>
                            {{ $processedBin->vehicle_plate ?: 'N/A' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-boxes"></i> Números de Bins:</strong><br>
                            {{ $processedBin->original_bin_number }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-layer-group"></i> Tipo de Bin:</strong><br>
                            {{ $processedBin->bin_type_display }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Proveedor -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-truck"></i> Información del Proveedor</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-id-card"></i> Código Interno:</strong><br>
                            <span class="h5">{{ $processedBin->supplier->internal_code ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-barcode"></i> Código CSG:</strong><br>
                            <span class="h5">{{ $processedBin->supplier->csg_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-building"></i> Nombre del Proveedor:</strong><br>
                            {{ $processedBin->supplier->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-map-marker-alt"></i> Ubicación:</strong><br>
                            {{ $processedBin->supplier->location ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Peso y Calidad -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-weight"></i> Información de Peso y Calidad</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-weight"></i> Peso Bruto:</strong><br>
                            <span class="h5 text-success">{{ number_format($processedBin->gross_weight ?? 0, 2) }} kg</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-apple-alt"></i> Peso Neto Fruta:</strong><br>
                            <span class="h5 text-primary">{{ number_format($processedBin->net_fruit_weight ?? $processedBin->original_weight ?? 0, 2) }} kg</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-boxes"></i> Bins en el Grupo:</strong><br>
                            <span class="h5">{{ $processedBin->bins_in_group ?? 1 }}</span>
                            <small class="text-muted">
                                ({{ $processedBin->wood_bins_count ?? 0 }} madera, {{ $processedBin->plastic_bins_count ?? 0 }} plástico)
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-ruler"></i> Calibre:</strong><br>
                            <span class="h5">{{ $processedBin->original_calibre }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-hashtag"></i> Unidades x Libra Promedio:</strong><br>
                            <span class="h5">{{ $processedBin->unidades_per_pound_avg ? number_format($processedBin->unidades_per_pound_avg, 2) : 'N/A' }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong><i class="fas fa-tint"></i> Humedad:</strong><br>
                            <span class="h5">{{ $processedBin->humidity ? number_format($processedBin->humidity, 2) . '%' : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calificación de Suciedad y Daño -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Calificación de Suciedad y Daño</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!--<div class="col-md-4 mb-3">
                            <strong>Nivel de Suciedad:</strong><br>
                            <span class="badge bg-{{ $processedBin->trash_level === 'limpio' ? 'success' : ($processedBin->trash_level === 'bajo' ? 'info' : ($processedBin->trash_level === 'mediano' ? 'warning' : 'danger')) }} fs-6">
                                {{ ucfirst($processedBin->trash_level_display) }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Calificación con Estrellas:</strong><br>
                            <span class="h4 text-warning">
                                {{ str_repeat('★', $processedBin->trash_level_stars) }}{{ str_repeat('☆', 4 - $processedBin->trash_level_stars) }}
                            </span>
                        </div>-->
                        <div class="col-md-4 mb-3">
                            <strong>Porcentaje de Daño:</strong><br>
                            <span class="h4 text-danger">
                                {{ $processedBin->damage_percentage ? number_format($processedBin->damage_percentage, 2) . '%' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Adicional</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-{{ $processedBin->status === 'received' ? 'success' : ($processedBin->status === 'processed' ? 'primary' : 'secondary') }}">
                            {{ ucfirst($processedBin->status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Lote de Recepción:</strong><br>
                        {{ $processedBin->reception_batch_id ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>Fecha de Recepción:</strong><br>
                        {{ $processedBin->received_at ? $processedBin->received_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                    @if($processedBin->notes)
                    <div class="mb-3">
                        <strong>Notas:</strong><br>
                        <small>{{ $processedBin->notes }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- QR Code -->
            @if($processedBin->qr_code)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-qrcode"></i> Código QR</h5>
                </div>
                <div class="card-body text-center">
                    @if(str_ends_with($processedBin->qr_code, '.svg'))
                        <div style="display: inline-block; border: 2px solid #000; padding: 10px; background: white;">
                            {!! file_get_contents(storage_path('app/public/' . $processedBin->qr_code)) !!}
                        </div>
                    @else
                        <img src="{{ asset('storage/' . $processedBin->qr_code) }}" alt="QR Code" class="img-fluid">
                    @endif
                    <p class="mt-3 mb-0">
                        <small class="text-muted">Código QR Interno Encriptado</small>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

