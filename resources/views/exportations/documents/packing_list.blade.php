<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Packing List - {{ $exportation->export_number }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 16pt; font-weight: bold; color: #8B0000; }
        .title { text-align: center; font-size: 18pt; font-weight: bold; margin: 20px 0; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .total-row { background-color: #e0e0e0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">COFRUPA EXPORT - PREMIUM PRUNES FROM CHILE</div>
        <div>CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE Tel: +569 7794 9575</div>
        <div>Email: benjamin.prieto@patagoniannut.cl</div>
    </div>

    <div class="title">PACKING LIST</div>

    <table>
        <tr>
            <td width="50%">
                <strong>SHIPPER / Expedidor:</strong><br>
                COFRUPA EXPORT SPA<br>
                RUT: {{ $contract->seller_tax_id ?? '76.505.934-8' }}<br>
                {{ $contract->seller_address ?? 'CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE' }}
            </td>
            <td>
                <strong>Packing List Nr:</strong> {{ $contract->contract_number }}<br>
                <strong>Date:</strong> {{ now()->format('d/m/Y') }}<br>
                <strong>Invoice Nr:</strong> {{ $contract->contract_number }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                <strong>CONSIGNEE / Consignatario:</strong><br>
                {{ $contract->consignee_name }}<br>
                {{ $contract->consignee_address }}<br>
                TAX ID: {{ $contract->consignee_tax_id }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><strong>Vessel Name:</strong> {{ $contract->vessel_name }}</td>
            <td><strong>Booking Nr:</strong> {{ $contract->booking_number }}</td>
        </tr>
        <tr>
            <td><strong>Port of Loading:</strong> {{ $contract->port_of_charge ?? 'Valparaiso, Chile' }}</td>
            <td><strong>Port of Discharge:</strong> {{ $contract->destination_port }}</td>
        </tr>
        <tr>
            <td><strong>ETD:</strong> {{ $contract->etd_date ? $contract->etd_date->format('d/m/Y') : 'N/A' }}</td>
            <td><strong>Container Nr:</strong> {{ $contract->container_number }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>MARKS AND<br>NUMBERS</th>
                <th>DESCRIPTION</th>
                <th style="text-align: center;">NO. OF<br>PACKAGES</th>
                <th style="text-align: center;">NET WEIGHT<br>(kg)</th>
                <th style="text-align: center;">GROSS WEIGHT<br>(kg)</th>
                <th style="text-align: center;">MEASUREMENT<br>(m³)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <strong>COFRUPA</strong><br>
                    {{ $contract->contract_number }}<br>
                    LOT {{ $contract->product_description ?? '33-25' }}<br>
                    CHILE
                </td>
                <td>
                    <strong>{{ $contract->product_description ?? 'PITTED SORBATED PRUNES CHILEAN D\'AGEN SIZE EX 70/80' }}</strong><br>
                    {{ $contract->packing ?? 'IN 2150 BOXES OF NET 10 KG' }}<br>
                    <strong>Calibre:</strong> {{ $contract->quality_specification ?? 'EX 70/80' }}<br>
                    <strong>Crop:</strong> {{ $contract->crop_year ?? '2025' }}<br>
                    <strong>Packing:</strong> {{ $contract->packing ?? '2150 BOXES OF 10KG' }}<br>
                    <br>
                    <strong>Contract Nr:</strong> {{ $contract->contract_number }}
                </td>
                <td style="text-align: center;">
                    @php
                        // Calcular número de cajas basado en el stock y empaque
                        $boxes = round($contract->stock_committed / 10); // Asumiendo cajas de 10kg
                    @endphp
                    {{ number_format($boxes) }} BOXES
                </td>
                <td style="text-align: center;">
                    {{ number_format($contract->stock_committed, 2) }}
                </td>
                <td style="text-align: center;">
                    @php
                        // Peso bruto = peso neto + tara (aproximadamente 5% más)
                        $grossWeight = $contract->stock_committed * 1.05;
                    @endphp
                    {{ number_format($grossWeight, 2) }}
                </td>
                <td style="text-align: center;">
                    @php
                        // Aproximación de volumen (m³)
                        $volume = ($contract->stock_committed / 1000) * 1.5; // Estimación
                    @endphp
                    {{ number_format($volume, 2) }}
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>TOTALS:</strong></td>
                <td style="text-align: center;">{{ number_format($boxes) }}</td>
                <td style="text-align: center;">{{ number_format($contract->stock_committed, 2) }}</td>
                <td style="text-align: center;">{{ number_format($grossWeight, 2) }}</td>
                <td style="text-align: center;">{{ number_format($volume, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <strong>CONTAINER INFORMATION / Información del Contenedor:</strong><br>
        <strong>Type:</strong> {{ $contract->transportation_details ?? '1 x 20\' HIGH CUBE DRY CONTAINER' }}<br>
        <strong>Container Nr:</strong> {{ $contract->container_number }}<br>
        <strong>Seal Number:</strong> FX{{ rand(10000000, 99999999) }}<br>
        <strong>Tare Weight:</strong> 2,100 kg<br>
        <strong>Max Payload:</strong> 28,280 kg
    </div>

    <div style="margin-top: 20px; padding: 10px; background-color: #f0f0f0; border: 1px solid #000;">
        <strong>SHIPPING MARKS:</strong><br>
        {{ $contract->product_description ?? 'CHILEAN PRUNES' }}<br>
        LOT: {{ $contract->crop_year ?? '2025' }}<br>
        COFRUPA EXPORT - CHILE<br>
        CONTRACT: {{ $contract->contract_number }}
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <div style="border-top: 1px solid #000; display: inline-block; width: 300px; padding-top: 5px;">
            <strong>COFRUPA EXPORT SPA</strong><br>
            Benjamin Prieto A. - Commercial Manager
        </div>
    </div>
</body>
</html>



