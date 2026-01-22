<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Quality Certificate EU - {{ $contract->contract_number }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        h1 {
            text-align: center;
            text-decoration: underline;
            font-size: 18pt;
            margin: 15px 0 25px 0;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 35%;
            padding: 2px 10px 2px 0;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            padding: 2px 0;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 10px 0;
        }
        .analysis-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .analysis-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
        }
        .analysis-table td:first-child {
            font-weight: bold;
            width: 45%;
        }
        .analysis-table td:last-child {
            width: 55%;
        }
        .footer-text {
            margin-top: 20px;
            font-size: 9pt;
            line-height: 1.5;
        }
        .signature-area {
            margin-top: 40px;
            text-align: center;
        }
        .signature-img {
            max-width: 180px;
            height: auto;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 2px solid #000;
            width: 300px;
            margin: 0 auto;
            padding-top: 5px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 12pt;
            margin: 5px 0;
        }
        .signature-title {
            font-size: 10pt;
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa Export" class="logo">
    </div>

    <h1>CERTIFICATE OF QUALITY</h1>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">DATE OF EMISSION</div>
            <div class="info-value">: {{ \Carbon\Carbon::parse($certificate['emission_date'])->format('d-m-Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">CLIENT</div>
            <div class="info-value">: {{ $certificate['client_name'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">PRODUCT</div>
            <div class="info-value">: {{ $certificate['product'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">SIZE</div>
            <div class="info-value">: {{ $certificate['size'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">QUANTITY</div>
            <div class="info-value">: {{ $certificate['quantity'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">CONTRACT</div>
            <div class="info-value">: {{ $certificate['contract_number'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">INVOICE</div>
            <div class="info-value">: {{ $certificate['invoice_nr'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">M/N</div>
            <div class="info-value">: {{ $certificate['vessel'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">B/L</div>
            <div class="info-value">: {{ $certificate['bl_nr'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">FCL</div>
            <div class="info-value">: {{ $certificate['fcl'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">FROM</div>
            <div class="info-value">: {{ $certificate['origin'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">TO</div>
            <div class="info-value">: {{ $certificate['destination'] }}</div>
        </div>
    </div>

    <div class="section-title">ORGANOLEPTIC ANALYSIS</div>
    <table class="analysis-table">
        <tr>
            <td>COLOUR</td>
            <td>: {{ $certificate['colour'] }}</td>
        </tr>
        <tr>
            <td>FLAVOUR</td>
            <td>: {{ $certificate['flavour'] }}</td>
        </tr>
        <tr>
            <td>TEXTURE</td>
            <td>: {{ $certificate['texture'] }}</td>
        </tr>
    </table>

    <div class="section-title">CHEMICAL ANALYSIS</div>
    <table class="analysis-table">
        <tr>
            <td>MOISTURE</td>
            <td>: {{ $certificate['moisture'] }}  {{ $certificate['moisture_method'] }}</td>
        </tr>
        <tr>
            <td>POTASSIUM SORBATE</td>
            <td>: {{ $certificate['potassium_sorbate'] }}</td>
        </tr>
        <tr>
            <td>OIL</td>
            <td>: {{ $certificate['oil'] }}</td>
        </tr>
    </table>

    <div class="section-title">PHYSICAL ANALYSIS</div>
    <table class="analysis-table">
        <tr>
            <td>FRAGMENTS OF PITS</td>
            <td>: {{ $certificate['fragments_pits'] }}</td>
        </tr>
        <tr>
            <td>UNITS PER POUNDS</td>
            <td>: {{ $certificate['units_per_pound'] }}</td>
        </tr>
        <tr>
            <td>DEFECTS (%)</td>
            <td>: {{ $certificate['defects'] }}</td>
        </tr>
        <tr>
            <td>USDA GRADE</td>
            <td>: "{{ $certificate['usda_grade'] }}"  {{ $certificate['usda_reference'] }}</td>
        </tr>
    </table>

    <div class="section-title">MICROBIOLOGY</div>
    <table class="analysis-table">
        <tr>
            <td>TOTAL PLATE COUNT</td>
            <td>: {{ $certificate['total_plate_count'] }}</td>
        </tr>
        <tr>
            <td>MOULDS</td>
            <td>: {{ $certificate['moulds'] }}</td>
        </tr>
        <tr>
            <td>YEASTS</td>
            <td>: {{ $certificate['yeasts'] }}</td>
        </tr>
        <tr>
            <td>E. COLI</td>
            <td>: {{ $certificate['e_coli'] }}</td>
        </tr>
        <tr>
            <td>SALMONELLA</td>
            <td>: {{ $certificate['salmonella'] }}</td>
        </tr>
    </table>

    <div style="margin-top: 15px;">
        <div class="info-row">
            <div class="info-label">Aflatoxine B1, B2, G1 y G2</div>
            <div class="info-value">: {{ $certificate['aflatoxine_individual'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Aflatoxine Total</div>
            <div class="info-value">: {{ $certificate['aflatoxine_total'] }}</div>
        </div>
    </div>

    <div style="margin-top: 15px;">
        <div class="info-row">
            <div class="info-label">Production date</div>
            <div class="info-value">: {{ $certificate['production_date'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Expiry Date</div>
            <div class="info-value">: {{ $certificate['expiry_date'] }}</div>
        </div>
    </div>

    <div class="footer-text">
        <p>The goods comply with EU Food Health requirements, are fit for human consumption,</p>
        <p>and / or can be used for further processing in food products.</p>
        <p>Pesticides according to the EU regulation.</p>
        <p>This product has been fumigated with phosphine.</p>
        <p>This product has been controlled by metal detector during the process.</p>
        <p>The product is free from any form or genetically modified and free of ionizing treatments.</p>
        <p>The microbiological ans micotoxine results are referential and belong to monthly checking analysis of quality management system BRC. Sampling on finished products was made the same month this batch was produced.</p>
    </div>

    <div class="signature-area">
        <img src="{{ public_path('image/Firma.png') }}" alt="Firma" class="signature-img">
        <div class="signature-line">
            <div class="signature-name">LUIS GONZALEZ OJEDA</div>
            <div class="signature-title">EXPORT MANAGER</div>
            <div class="signature-title">COFRUPA EXPORT SPA</div>
        </div>
    </div>
</body>
</html>



