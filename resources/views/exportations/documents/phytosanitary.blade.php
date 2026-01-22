<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phytosanitary Certificate - {{ $exportation->export_number }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border: 3px double #000; padding: 15px; background-color: #f9f9f9; }
        .title { text-align: center; font-size: 18pt; font-weight: bold; margin: 15px 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; }
        .label { font-weight: bold; background-color: #e0e0e0; }
        .box { border: 2px solid #000; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-size: 16pt; font-weight: bold;">REPÚBLICA DE CHILE</div>
        <div style="font-size: 13pt; font-weight: bold;">MINISTERIO DE AGRICULTURA</div>
        <div style="font-size: 11pt;">SERVICIO AGRÍCOLA Y GANADERO - SAG</div>
        <div style="font-size: 10pt; margin-top: 10px;">AGRICULTURAL AND LIVESTOCK SERVICE</div>
    </div>

    <div class="title">Phytosanitary Certificate</div>
    <div style="text-align: center; margin-bottom: 15px;">
        <strong>Certificate Nr:</strong> {{ $contract->contract_number }}-PHYTO-{{ date('Y') }}-{{ rand(1000, 9999) }}
    </div>

    <table>
        <tr>
            <td class="label" width="40%">I. Description of Consignment</td>
            <td></td>
        </tr>
        <tr>
            <td class="label">Name and address of exporter:</td>
            <td>
                <strong>COFRUPA EXPORT SPA</strong><br>
                RUT: {{ $contract->seller_tax_id ?? '76.505.934-8' }}<br>
                {{ $contract->seller_address ?? 'CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE' }}<br>
                Plant Registration: CCHL13012112010016
            </td>
        </tr>
        <tr>
            <td class="label">Declared name and address of consignee:</td>
            <td>
                <strong>{{ $contract->consignee_name }}</strong><br>
                {{ $contract->consignee_address }}<br>
                TAX ID: {{ $contract->consignee_tax_id }}
            </td>
        </tr>
        <tr>
            <td class="label">Number and description of packages:</td>
            <td>{{ $contract->packing ?? '2150 BOXES OF 10 KG NET' }}</td>
        </tr>
        <tr>
            <td class="label">Distinguishing marks:</td>
            <td>
                <strong>COFRUPA - CHILE</strong><br>
                LOT {{ $contract->product_description ?? '33-25' }}<br>
                CONTRACT {{ $contract->contract_number }}<br>
                CROP {{ $contract->crop_year ?? '2025' }}
            </td>
        </tr>
        <tr>
            <td class="label">Place of origin:</td>
            <td><strong>CHILE</strong> - Región Metropolitana, Buin</td>
        </tr>
        <tr>
            <td class="label">Declared means of conveyance:</td>
            <td>
                <strong>Maritime Transport</strong><br>
                Vessel: {{ $contract->vessel_name }}<br>
                Container: {{ $contract->container_number }}
            </td>
        </tr>
        <tr>
            <td class="label">Declared point of entry:</td>
            <td>{{ $contract->destination_port }}</td>
        </tr>
        <tr>
            <td class="label">Name of produce and quantity declared:</td>
            <td>
                <strong>PRODUCT:</strong> {{ $contract->product_description ?? 'Dried pitted prunes (Prunus domestica)' }}<br>
                <strong>BOTANICAL NAME:</strong> Prunus domestica<br>
                <strong>QUANTITY:</strong> {{ number_format($contract->stock_committed, 2) }} KG NET
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="label" width="40%">II. Additional Declaration</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                This is to certify that the plants, plant products or other regulated articles described herein have been inspected and/or tested according to appropriate official procedures and are considered to be free from the quarantine pests specified by the importing contracting party and to conform with the current phytosanitary requirements of the importing contracting party, including those for regulated non-quarantine pests.
                <br><br>
                <strong>The consignment has been treated as follows:</strong><br>
                - Fumigation with Methyl Bromide<br>
                - Cold treatment during transport<br>
                - Free from: Insects, diseases, and quarantine pests
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="label" width="40%">III. Disinfection and/or Disinfestati on Treatment</td>
            <td></td>
        </tr>
        <tr>
            <td class="label">Date:</td>
            <td>{{ now()->subDays(2)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Treatment:</td>
            <td>Sulphur Dioxide (SO2) fumigation + Cold Storage</td>
        </tr>
        <tr>
            <td class="label">Chemical (active ingredient):</td>
            <td>Sulphur Dioxide (SO2) - 2000 ppm</td>
        </tr>
        <tr>
            <td class="label">Duration and temperature:</td>
            <td>24 hours at 4°C</td>
        </tr>
        <tr>
            <td class="label">Concentration:</td>
            <td>As per Chilean Protocol for Export of Dried Fruits</td>
        </tr>
        <tr>
            <td class="label">Additional information:</td>
            <td>
                Product processed according to HACCP system.<br>
                Meets all sanitary requirements for export.<br>
                Free from live insects and visible contamination.
            </td>
        </tr>
    </table>

    <div class="box">
        <strong>IV. PLACE OF ISSUE:</strong> Santiago, Chile<br>
        <strong>DATE:</strong> {{ now()->format('d/m/Y') }}<br>
        <br>
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; vertical-align: bottom;">
                    <div style="margin-top: 40px; text-align: center;">
                        _________________________________<br>
                        <strong>Name of authorized officer:</strong><br>
                        Dr. [SAG Official Name]<br>
                        SERVICIO AGRÍCOLA Y GANADERO
                    </div>
                </td>
                <td style="border: none; text-align: center; vertical-align: bottom;">
                    <div style="margin-top: 40px;">
                        _________________________________<br>
                        <strong>Signature and Official Stamp</strong><br>
                        SERVICIO AGRÍCOLA Y GANADERO<br>
                        CHILE
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; font-size: 8pt; text-align: center; color: #666;">
        This certificate is issued in conformity with the International Plant Protection Convention (IPPC).<br>
        No financial liability attaches to the SAG or to any of its officers or representatives in respect of this certificate.
    </div>
</body>
</html>



