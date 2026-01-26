<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Proceso - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-left: 4px solid #3498db;
        }
        .section h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
            color: #555;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            background-color: #34495e;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            margin-top: 20px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COFRUPA Export SPA</h1>
        <h2>Orden de Proceso</h2>
        <p>Número de Orden: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div class="content">
        <div class="section">
            <h3>Información de la Orden</h3>
            <div class="info-row">
                <div class="info-label">Planta:</div>
                <div class="info-value"><strong>{{ $order->plant->name }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Orden:</div>
                <div class="info-value">{{ $order->order_date->format('d/m/Y') }}</div>
            </div>
            @if($order->supplier)
            <div class="info-row">
                <div class="info-label">Proveedor:</div>
                <div class="info-value">{{ $order->supplier->name }}</div>
            </div>
            @endif
            @if($order->product)
            <div class="info-row">
                <div class="info-label">Producto:</div>
                <div class="info-value">{{ $order->product }}</div>
            </div>
            @endif
            @if($order->quantity)
            <div class="info-row">
                <div class="info-label">Cantidad:</div>
                <div class="info-value"><strong>{{ number_format($order->quantity, 3, ',', '.') }} KILOS</strong></div>
            </div>
            @endif
            @if($order->expected_completion_date)
            <div class="info-row">
                <div class="info-label">Fecha Término Esperada:</div>
                <div class="info-value"><strong>{{ $order->expected_completion_date->format('d/m/Y') }}</strong></div>
            </div>
            @endif
        </div>

        <div class="highlight">
            <strong>Estimado/a:</strong><br>
            Se adjunta la orden de proceso {{ $order->order_number }} para su revisión y procesamiento.
            Por favor, confirme la recepción de esta orden y la disponibilidad para procesar según las especificaciones indicadas.
        </div>
    </div>

    <div class="footer">
        <p>COFRUPA Export SPA</p>
        <p>Cam Lo Mackenna PC 7-A, Buin</p>
        <p>Teléfono: +56992395293</p>
        <p>Este es un correo automático, por favor no responder a este mensaje.</p>
    </div>
</body>
</html>
