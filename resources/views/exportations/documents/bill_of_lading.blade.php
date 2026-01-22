<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bill of Lading - {{ $exportation->export_number }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: Arial, sans-serif; font-size: 9pt; }
        .header { text-align: center; margin-bottom: 20px; border: 2px solid black; padding: 10px; }
        .company-name { font-weight: bold; font-size: 14pt; }
        .title { text-align: center; font-size: 16pt; font-weight: bold; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; font-size: 8pt; }
        th { background-color: #f0f0f0; }
        .section-title { font-weight: bold; background-color: #e0e0e0; padding: 5px; margin-top: 10px; }
        .small-text { font-size: 7pt; }
        .barcode { text-align: right; font-family: 'Courier New', monospace; font-size: 12pt; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MEDITERRANEAN SHIPPING COMPANY S.A.</div>
        <div>12-14, chemin Rieu - CH -1208 GENEVA, Switzerland</div>
        <div>website: www.msc.com</div>
    </div>

    <table style="border: 2px solid black;">
        <tr>
            <td colspan="2">
                <strong>BILL OF LADING No.</strong><br>
                <div style="font-size: 14pt; font-weight: bold;">{{ $contract->booking_number ?? 'MEDUFP718812' }}</div>
                <div class="small-text">NON-NEGOTIABLE COPY</div>
            </td>
            <td>
                <strong>NO & SEQUENCE OF ORIGINAL B/L's</strong><br>
                Or Zero
            </td>
            <td rowspan="2" class="barcode">
                <div>NO. OF RIDER PAGES</div>
                <div style="font-size: 20pt;">0 Zero</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><strong>SHIPPER:</strong><br>
                COFRUPA EXPORT SPA<br>
                {{ $contract->seller_address ?? 'CAMINO LO MACKENNA PARCELA 7-A BUIN SANTIAGO - CHILE' }}<br>
                RUT: {{ $contract->seller_tax_id ?? '76.505.934-8' }}
            </td>
            <td><strong>CARRIER'S AGENTS ENDORSEMENTS</strong> (Include Agent(s) at POD)<br>
                <small>SHIPPER'S LOAD, STOW AND COUNT #CL/FEE.-SAID TO CONTAIN</small>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2"><strong>CONSIGNEE: This B/L is not negotiable unless marked "To Order" or "To Order of ..." here.</strong><br>
                {{ $contract->consignee_name }}<br>
                {{ $contract->consignee_address }}<br>
                EOR: {{ $contract->consignee_tax_id }}
            </td>
        </tr>
        <tr>
            <td colspan="2"><strong>NOTIFY PARTIES - (No responsibility shall attach to Carrier for failure to notify - see Clause 20)</strong><br>
                {{ $contract->notify_name ?? $contract->consignee_name }}<br>
                {{ $contract->notify_address ?? $contract->consignee_address }}<br>
                EOR: {{ $contract->notify_tax_id ?? $contract->consignee_tax_id }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><strong>VESSEL AND VOYAGE NO</strong> (see Clause 8 & 9.1)<br>
                {{ $contract->vessel_name ?? 'MSC EDNA - NX544R' }}
            </td>
            <td><strong>PORT OF LOADING</strong><br>
                {{ $contract->port_of_charge ?? 'Valparaiso, Chile' }}
            </td>
            <td><strong>PLACE OF RECEIPT: (Combined Transport ONLY - see Clause 1 & 5.2)</strong><br>
                XXXXXXXXXXXX
            </td>
        </tr>
        <tr>
            <td><strong>BOOKING REF.</strong> (OP)<br>
                {{ $contract->booking_number ?? '070ISA1207952' }}
            </td>
            <td><strong>PORT OF DISCHARGE</strong><br>
                {{ $contract->destination_port ?? 'Fos-sur-Mer, France' }}
            </td>
            <td><strong>PLACE OF DELIVERY: (Combined Transport ONLY - see Clause 1 & 5.2)</strong><br>
                XXXXXXXXXXXXXXXX
            </td>
        </tr>
    </table>

    <div class="section-title">PARTICULARS FURNISHED BY THE SHIPPER - NOT CHECKED BY CARRIER - CARRIER NOT RESPONSIBLE (see Clause 14)</div>

    <table>
        <thead>
            <tr>
                <th>Container Numbers, Seal<br>Numbers and Marks</th>
                <th>Description of Packages and Goods<br>(Continued on attached Bill of Lading Rider page(s), if applicable)</th>
                <th>Gross Cargo<br>Weight</th>
                <th>Measurement</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $contract->container_number ?? 'MSBU3370927' }}<br>
                    20' DRY VAN<br>
                    <strong>Seal Number:</strong><br>
                    FX41628068<br>
                    <strong>Tare Weight:</strong> 2,100 kgs.<br>
                    <strong>Marks and Numbers:</strong> COFRUPA
                </td>
                <td>
                    <strong>80 Drum(s) of</strong><br><br>
                    <strong>{{ $contract->stock_committed ?? '18,400' }} KGS NET OF DEHYDRATED PRUNES PULP IN ASEPTIC BAGS INTO 80 METALLIC DRUMS CROP 2025 IN 20 PALLETS</strong><br>
                    LOT {{ $contract->product_description ?? '33-25' }}<br>
                    <strong>CONTRACT No {{ $contract->contract_number ?? '005-2025' }}</strong><br><br>
                    E- 901546<br><br>
                    Documentation request:<br>
                    Commercial invoice, Packing List, Bill of Lading, Certificate of Origin<br>
                    Quality Certificate, Phytosanitary certificate, GMO Certificate.
                </td>
                <td>19,660,600 kgs.</td>
                <td>30,000 cu. m.</td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>CFR {{ $contract->destination_port ?? 'HOUSTON OR MIAMI' }}</strong></td>
            <td><strong>US$</strong></td>
            <td>{{ number_format($contract->total_amount ?? 154000, 2) }}</td>
        </tr>
    </table>

    <div class="small-text" style="margin-top: 20px;">
        <strong>"THIS CONTRACT IS SUBJECT BY THE STANDARD ICC ARBITRATION, IF ANY PARTY DECLINE TO GOT FORWARD WITH THE CONTRACT, AFTER SIGNED, SHOULD PAID A 20% OF THE TOTAL VALUE AS PENALTY"</strong>
    </div>

    <div class="small-text" style="margin-top: 30px;">
        <p><strong>Cofrupa Export SpA</strong> - RUT: 76.505.934-8</p>
        <p>BENJAMIN PRIETO A. - COMMERCIAL MANAGER</p>
        <p>CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE - TEL.: (56-9) 7794 9575</p>
        <p>www.patagoniannut.cl - benjamin.prieto@patagoniannut.cl</p>
    </div>
</body>
</html>



