<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Quality Certificate - {{ $contract->contract_number }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        .header {
            text-align: left;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        h1 {
            text-align: center;
            text-decoration: underline;
            font-size: 16pt;
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 3px 8px;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .quality-section {
            margin-top: 20px;
        }
        .quality-section h3 {
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 10px;
        }
        .quality-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .quality-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .quality-table td:first-child {
            width: 40%;
        }
        .quality-table td:nth-child(2) {
            width: 30%;
            text-align: center;
        }
        .quality-table td:last-child {
            width: 30%;
            text-align: center;
        }
        .including {
            margin-top: 15px;
            font-size: 9pt;
        }
        .including-title {
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .signature-area {
            margin-top: 80px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 300px;
            margin: 0 auto;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa Export" class="logo">
    </div>

    <h1>QUALITY CERTIFICATE</h1>

    <table class="info-table">
        <tr>
            <td>Exporter:</td>
            <td>COFRUPA / Registration CSE 153105</td>
        </tr>
        <tr>
            <td>Production plant</td>
            <td>Agrícola Siemel / Registration Nr. CCHL1301211201016</td>
        </tr>
        <tr>
            <td>Container Nr.</td>
            <td>{{ $contract->container_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>BL Nr</td>
            <td>{{ $contract->booking_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Invoice Nr.</td>
            <td>{{ $contract->contract_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Product:</td>
            <td>{{ $contract->product_description ?? "Chilean D'Agen Prunes Natural condition" }}</td>
        </tr>
        <tr>
            <td>Size</td>
            <td>{{ $contract->packing ?? '120/144 LOT COF 81' }}</td>
        </tr>
        <tr>
            <td>Production date</td>
            <td>{{ now()->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Expiration date</td>
            <td>{{ now()->addYear()->format('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="quality-section">
        <h3>QUALITY Inspection</h3>
        
        <table class="quality-table">
            <tr>
                <td><strong>Size:</strong></td>
                <td>Allowance<br>120/144</td>
                <td>Test results<br>129</td>
            </tr>
        </table>

        <table class="quality-table">
            <tr>
                <td><strong>Moisture Level (tested by DFA) 20% Max</strong></td>
                <td></td>
                <td>18%</td>
            </tr>
        </table>

        <table class="quality-table">
            <tr>
                <td><strong>Defects</strong></td>
                <td>Allowance</td>
                <td>Test results</td>
            </tr>
            <tr>
                <td><strong>Total Defects /</strong></td>
                <td>Max 10%</td>
                <td>4.4%</td>
            </tr>
        </table>
    </div>

    <div class="including">
        <span class="including-title">Including:</span><br>
        Poor texture, end cracks, fermentation, skin or flesh damage, decay, scars, heat damage, foreign material, dirt, decay, mould, dead insects.
    </div>

    <div class="signature-area">
        <div class="signature-line">
            <p style="margin: 5px 0;"><strong>Cofrupa Export SpA</strong></p>
            <p style="margin: 2px 0; font-size: 9pt;">RUT: 76.505.934-8</p>
            <p style="margin: 2px 0; font-size: 9pt;">Representante legal: Luis González Ojeda</p>
            <p style="margin: 2px 0; font-size: 9pt;">RUT: 10.286.728-4</p>
        </div>
    </div>
</body>
</html>

