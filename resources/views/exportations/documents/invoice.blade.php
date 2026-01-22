<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commercial Invoice - {{ $exportation->export_number }}</title>
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
        .info-section { margin-bottom: 20px; }
        .info-label { font-weight: bold; }
        .total-row { background-color: #e0e0e0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">COFRUPA EXPORT - PREMIUM PRUNES FROM CHILE</div>
        <div>CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE Tel: +569 7794 9575</div>
        <div>Email: benjamin.prieto@patagoniannut.cl</div>
    </div>

    <div class="title">COMMERCIAL INVOICE</div>

    <table>
        <tr>
            <td width="50%">
                <div class="info-label">EXPORTER / Exportador</div>
                <div>COFRUPA EXPORT SPA - RUT: {{ $contract->seller_tax_id ?? '76.505.934-8' }}</div>
                <div>{{ $contract->seller_address ?? 'CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE' }}</div>
            </td>
            <td>
                <div class="info-label">INVOICE Nr / Factura Nº:</div>
                <div style="font-size: 14pt; font-weight: bold;">{{ $contract->contract_number }}</div>
                <div class="info-label">Date / Fecha:</div>
                <div>{{ $contract->contract_date ? $contract->contract_date->format('d/m/Y') : now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%">
                <div class="info-label">CONSIGNEE / Consignatario</div>
                <div><strong>{{ $contract->consignee_name }}</strong></div>
                <div>{{ $contract->consignee_address }}</div>
                <div>Chinese Address: {{ $contract->consignee_chinese_address }}</div>
                <div>TAX ID (USCI): {{ $contract->consignee_tax_id }}</div>
                <div>TEL: {{ $contract->consignee_phone }}</div>
            </td>
            <td>
                <div class="info-label">NOTIFY / Notificar a:</div>
                <div><strong>{{ $contract->notify_name ?? $contract->consignee_name }}</strong></div>
                <div>{{ $contract->notify_address ?? $contract->consignee_address }}</div>
                <div>Chinese Address: {{ $contract->notify_chinese_address ?? $contract->consignee_chinese_address }}</div>
                <div>TAX ID (USCI): {{ $contract->notify_tax_id ?? $contract->consignee_tax_id }}</div>
                <div>TEL: {{ $contract->notify_phone ?? $contract->consignee_phone }}</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><strong>Customer Reference / PO#:</strong> {{ $contract->customer_reference }}</td>
            <td><strong>Contract Nr:</strong> {{ $contract->contract_number }}</td>
        </tr>
        <tr>
            <td><strong>Vessel Name / Buque:</strong> {{ $contract->vessel_name }}</td>
            <td><strong>Booking Nr:</strong> {{ $contract->booking_number }}</td>
        </tr>
        <tr>
            <td><strong>Port of Loading:</strong> {{ $contract->port_of_charge ?? 'Valparaiso o San Antonio, Chile' }}</td>
            <td><strong>Port of Discharge:</strong> {{ $contract->destination_port }}</td>
        </tr>
        <tr>
            <td><strong>ETD:</strong> {{ $contract->etd_date ? $contract->etd_date->format('d/m/Y') : 'N/A' }}</td>
            <td><strong>ETA:</strong> {{ $contract->eta_date ? $contract->eta_date->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Container Number:</strong> {{ $contract->container_number }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>DESCRIPTION OF GOODS</th>
                <th style="text-align: center;">QUANTITY</th>
                <th style="text-align: right;">UNIT PRICE<br>(USD)</th>
                <th style="text-align: right;">TOTAL AMOUNT<br>(USD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $contract->product_description ?? 'Lbs of Dried pitted prunes, size ex 70/90' }}</strong><br>
                    {{ $contract->quality_specification ?? 'As per attached spec / Chilean protocol' }}<br>
                    <strong>Crop:</strong> {{ $contract->crop_year ?? '2025' }}<br>
                    <strong>Packing:</strong> {{ $contract->packing ?? '2150 BOXES OF 10KG' }}<br>
                    <strong>Label:</strong> {{ $contract->label_info ?? 'To be provided by buyer' }}<br>
                    <strong>Shipment:</strong> {{ $contract->shipment_schedule ?? '1 FCL' }}<br>
                    <strong>Transportation:</strong> {{ $contract->transportation_details ?? '1 x 20\' HC' }}
                </td>
                <td style="text-align: center;">
                    {{ number_format($contract->stock_committed, 2) }} kg
                </td>
                <td style="text-align: right;">
                    {{ number_format($contract->unit_price_per_kg ?? $contract->price, 2) }}
                </td>
                <td style="text-align: right;">
                    {{ number_format($contract->total_amount ?? ($contract->stock_committed * $contract->price), 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td style="text-align: right; width: 75%;"><strong>Incoterm:</strong></td>
            <td style="text-align: center;"><strong>{{ $contract->incoterm ?? 'CFR' }} {{ $contract->destination_port }}</strong></td>
        </tr>
        <tr class="total-row">
            <td style="text-align: right;"><strong>TOTAL INVOICE AMOUNT (USD):</strong></td>
            <td style="text-align: right; font-size: 14pt;">{{ number_format($contract->total_amount ?? ($contract->stock_committed * $contract->price), 2) }}</td>
        </tr>
    </table>

    <div class="info-section">
        <div class="info-label">PAYMENT TERMS / Términos de Pago:</div>
        <div>{{ $contract->payment_terms ?? '20% advance payment 2 weeks before ETD, 80% balance against first presentation of full set of original documents by email' }}</div>
    </div>

    <div class="info-section">
        <div class="info-label">DOCUMENTS REQUIRED / Documentos Requeridos:</div>
        <div>{{ $contract->required_documents ?? 'Invoice, Packing List, Certificate of Origin, Quality Certificate, Phytosanitary certificate' }}</div>
    </div>

    <div class="info-section">
        <div class="info-label">SELLER BANK ACCOUNT / Cuenta Bancaria del Vendedor:</div>
        <div><strong>BENEFICIARY:</strong> COFRUPA EXPORT SPA - TAX ID {{ $contract->seller_tax_id ?? '76.505.934-8' }}</div>
        <div><strong>BANK:</strong> {{ $contract->seller_bank_name ?? 'BANCO SANTANDER' }}</div>
        <div><strong>BANK LOCATION:</strong> {{ $contract->seller_bank_address ?? 'Bandera 140, Santiago, Chile' }}</div>
        <div><strong>ACCOUNT NUMBER:</strong> {{ $contract->seller_bank_account_number ?? '5100166293' }}</div>
        <div><strong>SWIFT CODE:</strong> {{ $contract->seller_bank_swift ?? 'BSCHCLRM' }}</div>
        <div><strong>PAYMENT TYPE:</strong> {{ $contract->payment_type ?? 'OUR - NOT SHA' }}</div>
    </div>

    @if($contract->contract_clause)
    <div class="info-section" style="border: 1px solid #000; padding: 10px; background-color: #ffe6e6;">
        <div class="info-label">CONTRACT CLAUSE:</div>
        <div>{{ $contract->contract_clause }}</div>
    </div>
    @endif

    <div style="margin-top: 50px; text-align: center;">
        <div style="border-top: 1px solid #000; display: inline-block; width: 300px; padding-top: 5px;">
            <strong>COFRUPA EXPORT SPA</strong><br>
            Benjamin Prieto A. - Commercial Manager
        </div>
    </div>
</body>
</html>



