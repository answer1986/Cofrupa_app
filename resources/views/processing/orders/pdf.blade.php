<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Proceso - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0;
            color: #555;
            font-size: 18px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info td {
            padding: 5px;
            vertical-align: top;
        }
        .order-info td:first-child {
            font-weight: bold;
            width: 30%;
            color: #555;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .section-content {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .section-content table {
            width: 100%;
            border-collapse: collapse;
        }
        .section-content td {
            padding: 6px;
            border-bottom: 1px solid #eee;
        }
        .section-content td:first-child {
            font-weight: bold;
            width: 35%;
            color: #555;
        }
        .two-columns {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #2c3e50;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COFRUPA Export SPA</h1>
        <h2>Orden de Proceso</h2>
        <p><strong>N° Orden: {{ $order->order_number }}</strong></p>
    </div>

    <div class="order-info">
        <table>
            <tr>
                <td>Planta:</td>
                <td><strong>{{ $order->plant->name }}</strong></td>
                <td>Fecha de Orden:</td>
                <td><strong>{{ $order->order_date->format('d/m/Y') }}</strong></td>
            </tr>
            @if($order->supplier)
            <tr>
                <td>Proveedor:</td>
                <td>{{ $order->supplier->name }}</td>
                <td>Estado:</td>
                <td>{{ $order->status_display }}</td>
            </tr>
            @endif
            @if($order->csg_code)
            <tr>
                <td>CSG Code:</td>
                <td>{{ $order->csg_code }}</td>
                <td>Progreso:</td>
                <td>{{ $order->progress_percentage }}%</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detalles del Producto</div>
        <div class="section-content">
            <div class="two-columns">
                <div class="column">
                    <table>
                        @if($order->raw_material)
                        <tr>
                            <td>Materia Prima:</td>
                            <td>{{ $order->raw_material }}</td>
                        </tr>
                        @endif
                        @if($order->product)
                        <tr>
                            <td>Producto:</td>
                            <td>{{ $order->product }}</td>
                        </tr>
                        @endif
                        @if($order->type)
                        <tr>
                            <td>Tipo:</td>
                            <td>{{ $order->type }}</td>
                        </tr>
                        @endif
                        @if($order->caliber)
                        <tr>
                            <td>Calibre:</td>
                            <td>{{ $order->caliber }}</td>
                        </tr>
                        @endif
                        @if($order->quantity)
                        <tr>
                            <td>Cantidad:</td>
                            <td><strong>{{ number_format($order->quantity, 3, ',', '.') }} KILOS</strong></td>
                        </tr>
                        @endif
                        @if($order->quality)
                        <tr>
                            <td>Calidad:</td>
                            <td>{{ $order->quality }}</td>
                        </tr>
                        @endif
                        @if($order->labeling || $order->labeling_attachment)
                        <tr>
                            <td>Etiquetado:</td>
                            <td>
                                @if($order->labeling){{ $order->labeling }}@endif
                                @if($order->labeling_attachment)
                                    @php
                                        $ext = strtolower(pathinfo($order->labeling_attachment, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        $fullPath = $isImage ? storage_path('app/public/' . $order->labeling_attachment) : null;
                                    @endphp
                                    @if($isImage && $fullPath && file_exists($fullPath))
                                        <br><img src="{{ $fullPath }}" alt="Adjunto etiquetado" style="max-width: 280px; max-height: 180px; margin-top: 6px;" />
                                    @elseif($order->labeling_attachment)
                                        <br><em>Adjunto: {{ basename($order->labeling_attachment) }}</em>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if($order->packaging)
                        <tr>
                            <td>Envases:</td>
                            <td>{{ $order->packaging }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="column">
                    <table>
                        @if($order->potassium_sorbate)
                        <tr>
                            <td>Sorbato de potasio:</td>
                            <td>{{ $order->potassium_sorbate }}</td>
                        </tr>
                        @endif
                        @if($order->humidity)
                        <tr>
                            <td>Humedad:</td>
                            <td>{{ $order->humidity }}</td>
                        </tr>
                        @endif
                        @if($order->stone_percentage)
                        <tr>
                            <td>% de Carozo:</td>
                            <td>{{ $order->stone_percentage }}</td>
                        </tr>
                        @endif
                        @if($order->oil)
                        <tr>
                            <td>Aceite:</td>
                            <td>{{ $order->oil }}</td>
                        </tr>
                        @endif
                        @if($order->damage)
                        <tr>
                            <td>Daños:</td>
                            <td>{{ $order->damage }}</td>
                        </tr>
                        @endif
                        @if($order->plant_print)
                        <tr>
                            <td>Impresión Planta:</td>
                            <td>{{ $order->plant_print }}</td>
                        </tr>
                        @endif
                        @if($order->destination)
                        <tr>
                            <td>Destino:</td>
                            <td>{{ $order->destination }}</td>
                        </tr>
                        @endif
                        @if($order->loading_date)
                        <tr>
                            <td>Fecha de carga:</td>
                            <td>{{ $order->loading_date }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>SAG:</td>
                            <td>{{ $order->sag ? 'Sí' : 'No' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($order->notes)
    <div class="section">
        <div class="section-title">Notas Adicionales</div>
        <div class="section-content">
            <p>{{ $order->notes }}</p>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>COFRUPA Export SPA</strong></p>
        <p>Cam Lo Mackenna PC 7-A, Buin | Teléfono: +56992395293</p>
        <p>Este documento fue generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
