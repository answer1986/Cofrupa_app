<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Origin - {{ $exportation->export_number }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; border: 3px solid #000; padding: 15px; }
        .title { text-align: center; font-size: 20pt; font-weight: bold; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; }
        .label { font-weight: bold; }
        .signature-box { margin-top: 80px; text-align: center; }
        .signature-line { border-top: 2px solid #000; width: 300px; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-size: 18pt; font-weight: bold;">REPUBLIC OF CHILE</div>
        <div style="font-size: 14pt; font-weight: bold;">MINISTRY OF AGRICULTURE</div>
        <div style="font-size: 12pt;">AGRICULTURAL AND LIVESTOCK SERVICE - SAG</div>
    </div>

    <div class="title">CERTIFICATE OF ORIGIN</div>

    <div style="text-align: center; margin-bottom: 20px;">
        <strong>Certificate Nr:</strong> {{ $contract->contract_number }}-CO-{{ date('Y') }}
    </div>

    <table>
        <tr>
            <td width="30%" class="label">1. Exporter:</td>
            <td>
                <strong>COFRUPA EXPORT SPA</strong><br>
                RUT: {{ $contract->seller_tax_id ?? '76.505.934-8' }}<br>
                {{ $contract->seller_address ?? 'CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE' }}<br>
                Registration: CSE 153105
            </td>
        </tr>
        <tr>
            <td class="label">2. Consignee:</td>
            <td>
                <strong>{{ $contract->consignee_name }}</strong><br>
                {{ $contract->consignee_address }}<br>
                TAX ID: {{ $contract->consignee_tax_id }}
            </td>
        </tr>
        <tr>
            <td class="label">3. Country of Origin:</td>
            <td><strong>CHILE</strong></td>
        </tr>
        <tr>
            <td class="label">4. Country of Destination:</td>
            <td><strong>{{ $contract->destination_port }}</strong></td>
        </tr>
        <tr>
            <td class="label">5. Transport Details:</td>
            <td>
                <strong>Vessel:</strong> {{ $contract->vessel_name }}<br>
                <strong>Departure Port:</strong> {{ $contract->port_of_charge ?? 'Valparaiso, Chile' }}<br>
                <strong>Arrival Port:</strong> {{ $contract->destination_port }}<br>
                <strong>Container Nr:</strong> {{ $contract->container_number }}<br>
                <strong>Bill of Lading Nr:</strong> {{ $contract->booking_number }}
            </td>
        </tr>
        <tr>
            <td class="label">6. Invoice Number:</td>
            <td><strong>{{ $contract->contract_number }}</strong></td>
        </tr>
        <tr>
            <td class="label">7. Invoice Date:</td>
            <td>{{ $contract->contract_date ? $contract->contract_date->format('d/m/Y') : now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>8. Marks and Numbers on Packages</th>
                <th>9. Number and Kind of Packages</th>
                <th>10. Description of Goods</th>
                <th>11. Quantity and Weight</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">
                    <strong>COFRUPA</strong><br>
                    {{ $contract->contract_number }}<br>
                    CHILE<br>
                    CROP {{ $contract->crop_year ?? '2025' }}
                </td>
                <td>
                    {{ $contract->packing ?? '2150 BOXES' }}
                </td>
                <td>
                    <strong>{{ $contract->product_description ?? 'DRIED PITTED PRUNES' }}</strong><br>
                    Chilean D'Agen Prunes<br>
                    Natural Condition<br>
                    Size: {{ $contract->quality_specification ?? '120/144' }}<br>
                    Crop: {{ $contract->crop_year ?? '2025' }}<br>
                    Origin: Chile<br>
                    <br>
                    <strong>HS CODE: 081320</strong>
                </td>
                <td style="text-align: center;">
                    <strong>Net Weight:</strong><br>
                    {{ number_format($contract->stock_committed, 2) }} KG<br>
                    <br>
                    <strong>Gross Weight:</strong><br>
                    {{ number_format($contract->stock_committed * 1.05, 2) }} KG
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; padding: 10px; border: 2px solid #000;">
        <strong>12. DECLARATION:</strong><br>
        The undersigned hereby declares that the above details and statement are correct; that all the goods were produced in<br>
        <strong>CHILE</strong><br>
        and that they comply with the origin requirements specified for those goods in the applicable trade agreement.
    </div>

    <div style="margin-top: 30px;">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <strong>13. Place and Date:</strong><br>
                    Santiago, Chile<br>
                    {{ now()->format('d/m/Y') }}
                </td>
                <td style="border: none;">
                    <strong>14. Signature of Exporter:</strong><br>
                    <div class="signature-line" style="margin-top: 40px;">
                        BENJAMIN PRIETO A.<br>
                        Commercial Manager<br>
                        COFRUPA EXPORT SPA
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 40px; padding: 15px; border: 2px solid #000; background-color: #f0f0f0;">
        <strong>15. CERTIFICATION BY COMPETENT AUTHORITY:</strong><br>
        <br>
        It is hereby certified, on the basis of control carried out, that the declaration by the exporter is correct.<br>
        <br>
        <div style="margin-top: 40px;">
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 50%;">
                        Place and Date:<br>
                        Santiago, Chile<br>
                        {{ now()->format('d/m/Y') }}
                    </td>
                    <td style="border: none; text-align: center;">
                        <div style="margin-top: 40px;">
                            _________________________________<br>
                            Stamp and Signature<br>
                            SERVICIO AGRÍCOLA Y GANADERO (SAG)<br>
                            CHILE
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>



