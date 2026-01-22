<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instrucciones de Despacho - {{ $shipment->shipment_number }}</title>
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
        <h2>Instrucciones de Despacho</h2>
        <p>Número de Despacho: <strong>{{ $shipment->shipment_number }}</strong></p>
    </div>

    <div class="content">
        <div class="section">
            <h3>Información del Contrato</h3>
            <div class="info-row">
                <div class="info-label">Cliente:</div>
                <div class="info-value">{{ $shipment->contract->client->name }}</div>
            </div>
            @if($shipment->contract->contract_number)
            <div class="info-row">
                <div class="info-label">Número de Contrato:</div>
                <div class="info-value">{{ $shipment->contract->contract_number }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Stock Comprometido:</div>
                <div class="info-value">{{ number_format($shipment->contract->stock_committed, 2) }} kg</div>
            </div>
        </div>

        <div class="section">
            <h3>Información de Transporte</h3>
            @if($shipment->transport_company)
            <div class="info-row">
                <div class="info-label">Empresa de Transporte:</div>
                <div class="info-value"><strong>{{ $shipment->transport_company }}</strong></div>
            </div>
            @endif
            @if($shipment->transport_contact)
            <div class="info-row">
                <div class="info-label">Contacto:</div>
                <div class="info-value">{{ $shipment->transport_contact }}</div>
            </div>
            @endif
            @if($shipment->transport_phone)
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $shipment->transport_phone }}</div>
            </div>
            @endif
            @if($shipment->transport_request_number)
            <div class="info-row">
                <div class="info-label">N° Solicitud:</div>
                <div class="info-value">{{ $shipment->transport_request_number }}</div>
            </div>
            @endif
        </div>

        <div class="section">
            <h3>Asignaciones</h3>
            @if($shipment->plant_pickup_company)
            <div class="info-row">
                <div class="info-label">Recoge en Planta:</div>
                <div class="info-value">{{ $shipment->plant_pickup_company }}</div>
            </div>
            @endif
            @if($shipment->customs_loading_company)
            <div class="info-row">
                <div class="info-label">Carga para Aduana:</div>
                <div class="info-value">{{ $shipment->customs_loading_company }}</div>
            </div>
            @endif
        </div>

        <div class="section">
            <h3>Control de Tiempos</h3>
            @if($shipment->plant_pickup_scheduled)
            <div class="info-row">
                <div class="info-label">Recogida en Planta (Programada):</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($shipment->plant_pickup_scheduled)->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($shipment->customs_loading_scheduled)
            <div class="info-row">
                <div class="info-label">Carga en Aduana (Programada):</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($shipment->customs_loading_scheduled)->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($shipment->transport_departure_scheduled)
            <div class="info-row">
                <div class="info-label">Salida de Transporte (Programada):</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($shipment->transport_departure_scheduled)->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($shipment->port_arrival_scheduled)
            <div class="info-row">
                <div class="info-label">Llegada al Puerto (Programada):</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($shipment->port_arrival_scheduled)->format('d/m/Y H:i') }}</div>
            </div>
            @endif
        </div>

        @if($shipment->shippingLine)
        <div class="section">
            <h3>Información de Naviera</h3>
            <div class="info-row">
                <div class="info-label">Naviera:</div>
                <div class="info-value">{{ $shipment->shippingLine->name }}</div>
            </div>
            @if($shipment->shippingLine->code)
            <div class="info-row">
                <div class="info-label">Código:</div>
                <div class="info-value">{{ $shipment->shippingLine->code }}</div>
            </div>
            @endif
        </div>
        @endif

        @if($shipment->notes)
        <div class="section">
            <h3>Notas Adicionales</h3>
            <p>{{ $shipment->notes }}</p>
        </div>
        @endif

        <div class="highlight">
            <strong>Importante:</strong> Por favor, confirme la recepción de este correo y la disponibilidad para realizar el transporte según las fechas programadas.
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



