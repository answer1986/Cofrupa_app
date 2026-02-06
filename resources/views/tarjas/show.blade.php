<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarja - {{ $processedBin->tarja_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .tarja-container { 
                page-break-inside: avoid;
                border: 2px solid #000;
                padding: 15px;
            }
        }
        .tarja-container {
            max-width: 800px;
            margin: 20px auto;
            border: 2px solid #000;
            padding: 20px;
            background: white;
        }
        .tarja-header {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo-container img {
            max-height: 80px;
        }
        .tarja-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .tarja-number {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .tarja-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .tarja-field {
            border: 1px solid #ccc;
            padding: 10px;
            background: #f9f9f9;
        }
        .tarja-label {
            font-weight: bold;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .tarja-value {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .tarja-weight {
            grid-column: 1 / -1;
            background: #fff3cd;
            border: 2px solid #ffc107;
        }
        .tarja-weight .tarja-value {
            font-size: 32px;
            color: #d32f2f;
        }
        .tarja-stars {
            font-size: 24px;
            color: #ffc107;
        }
        .tarja-qr {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            border-top: 2px solid #000;
        }
        .tarja-qr img {
            max-width: 200px;
            border: 2px solid #000;
            padding: 10px;
            background: white;
        }
        .tarja-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print mb-3">
            <a href="{{ route('bin_reception.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimir Tarja
            </button>
        </div>
        
        <div class="tarja-container">
            <div class="tarja-header">
                <div class="logo-container">
                    <img src="{{ asset('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa Logo">
                </div>
                <div class="tarja-title">TARJA DE CALIBRADO</div>
                <div class="tarja-number">{{ $processedBin->tarja_number }}</div>
            </div>
            
            <div class="tarja-grid">
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-tag"></i> Nro. de Tarja</div>
                    <div class="tarja-value">{{ $processedBin->tarja_number }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-box"></i> Lote</div>
                    <div class="tarja-value">{{ $processedBin->lote ?: 'N/A' }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-star"></i> Daño</div>
                    <div class="tarja-value">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="tarja-stars">{{ str_repeat('★', $processedBin->trash_level_stars) }}{{ str_repeat('☆', 4 - $processedBin->trash_level_stars) }}</span>
                                <br>
                                <small>({{ ucfirst($processedBin->trash_level_display) }})</small>
                            </div>
                            <div class="text-end">
                                <strong class="h5 mb-0">{{ $processedBin->damage_percentage ? number_format($processedBin->damage_percentage, 2) . '%' : 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">Daño</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-barcode"></i> CSG</div>
                    <div class="tarja-value">{{ $processedBin->supplier->csg_code ?? 'N/A' }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-ruler"></i> Calibre</div>
                    <div class="tarja-value">{{ $processedBin->original_calibre }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-hashtag"></i> Unidades x Libra Promedio</div>
                    <div class="tarja-value">{{ $processedBin->unidades_per_pound_avg ? number_format($processedBin->unidades_per_pound_avg, 2) : 'N/A' }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-tint"></i> Humedad</div>
                    <div class="tarja-value">{{ $processedBin->humidity ? number_format($processedBin->humidity, 2) . '%' : 'N/A' }}</div>
                </div>
                
                <div class="tarja-field">
                    <div class="tarja-label"><i class="fas fa-id-card"></i> Código Interno Proveedor</div>
                    <div class="tarja-value">{{ $processedBin->supplier->internal_code ?? 'N/A' }}</div>
                </div>
                
                <div class="tarja-field tarja-weight">
                    <div class="tarja-label"><i class="fas fa-weight"></i> PESO NETO FRUTA</div>
                    <div class="tarja-value">{{ number_format($processedBin->net_fruit_weight ?? $processedBin->original_weight, 2) }} kg</div>
                </div>
            </div>
            
            <div class="tarja-qr">
                <div style="margin-bottom: 10px; font-weight: bold;">CÓDIGO QR INTERNO</div>
                @if($processedBin->qr_code)
                    @if(str_ends_with($processedBin->qr_code, '.svg'))
                        <div style="display: inline-block; border: 2px solid #000; padding: 10px; background: white;">
                            {!! file_get_contents(storage_path('app/public/' . $processedBin->qr_code)) !!}
                        </div>
                    @else
                        <img src="{{ asset('storage/' . $processedBin->qr_code) }}" alt="QR Code">
                    @endif
                @else
                    <div class="alert alert-warning">QR Code no generado</div>
                @endif
                <div class="tarja-footer">
                    <small>Este código QR es de uso interno y contiene información encriptada</small>
                </div>
            </div>
            
            <div class="tarja-footer">
                <div><strong>Fecha de Recepción:</strong> {{ $processedBin->entry_date->format('d/m/Y') }}</div>
                <div><strong>Patente:</strong> {{ $processedBin->vehicle_plate ?? 'N/A' }}</div>
                <div><strong>Bins:</strong> {{ $processedBin->original_bin_number }}</div>
            </div>
        </div>
    </div>
</body>
</html>

