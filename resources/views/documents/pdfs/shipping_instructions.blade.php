<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instructivo de Embarque - {{ $contract->contract_number }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo {
            max-width: 100px;
            height: auto;
        }
        h1 {
            text-align: center;
            font-size: 16pt;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .ref-line {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .to-agency {
            font-size: 11pt;
            margin-bottom: 15px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 15px 0 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 4px 8px;
            border: 1px solid #999;
            vertical-align: top;
        }
        .field-label {
            font-weight: bold;
            width: 30%;
            background-color: #f9f9f9;
        }
        .highlight {
            background-color: #ffff00;
        }
        .advice-table td {
            border: 1px solid #999;
        }
        .advice-header {
            font-weight: bold;
            text-align: center;
            background-color: #e6e6e6;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa" class="logo">
    </div>

    <h1>INSTRUCTIVO DE EMBARQUE</h1>
    <div class="ref-line">{{ $shipping['ref_contract'] ?? '' }}</div>

    <div class="to-agency">TO: {{ $shipping['agent_name'] ?? '' }}</div>

    <div class="section-title">BOARDING INSTRUCTIONS</div>

    <table>
        <tr>
            <td class="field-label">CSNEE</td>
            <td>{{ $shipping['csnee'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">CONTRACT</td>
            <td>{{ $shipping['contract_number'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">NUMBERS CONTAINER</td>
            <td>{{ $shipping['numbers_container'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">BOOKING</td>
            <td>{{ $shipping['booking'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">CARRIER</td>
            <td>{{ $shipping['carrier'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">SHIP</td>
            <td>{{ $shipping['ship'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">LOADING PORT</td>
            <td>{{ $shipping['loading_port'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">DESTINATION PORT</td>
            <td>{{ $shipping['destination_port'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">DESTINATION FINAL</td>
            <td>{{ $shipping['destination_final'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">CLÁUSULA VENTA</td>
            <td>{{ $shipping['clausula_venta'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">FLETE</td>
            <td>{{ $shipping['flete'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">DEPÓSITO</td>
            <td>{{ $shipping['deposito'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">MODALIDAD DE VENTA</td>
            <td>{{ $shipping['modalidad_venta'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">FORMA DE PAGO</td>
            <td>{{ $shipping['forma_pago'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">PRECIO DE VENTA CFR</td>
            <td>{{ $shipping['precio_venta'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">DOCUMENTS</td>
            <td>{{ $shipping['documents'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label" colspan="2">{{ $shipping['hs_code'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">VALOR FOB TOTAL</td>
            <td>{{ $shipping['valor_fob'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">ETD</td>
            <td class="highlight">{{ $shipping['etd'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Cut Off Documental</td>
            <td class="highlight">{{ $shipping['cut_off'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Matriz</td>
            <td class="highlight">{{ $shipping['matriz'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Stacking</td>
            <td class="highlight">{{ $shipping['stacking'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Terminal de entrega</td>
            <td class="highlight">{{ $shipping['terminal_entrega'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Puerto de ingreso</td>
            <td class="highlight">{{ $shipping['puerto_ingreso'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Horario de Stacking</td>
            <td class="highlight">{{ $shipping['horario_stacking'] ?? '' }}</td>
        </tr>
    </table>

    <div class="section-title">BOARDING ADVICE</div>

    <table class="advice-table">
        <tr>
            <td class="advice-header">CONTAINER</td>
            <td class="advice-header">NET WEIGHT</td>
            <td class="advice-header">DETAIL</td>
            <td class="advice-header">US$ P/KG</td>
        </tr>
        <tr>
            <td>{{ $shipping['container_type'] ?? '' }}</td>
            <td>{{ $shipping['net_weight'] ?? '' }}</td>
            <td>{{ $shipping['detail'] ?? '' }}</td>
            <td>{{ $shipping['unit_price'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">TOTAL BOXES</td>
            <td colspan="3">{{ $shipping['total_boxes'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">TOTAL NET WIGHT</td>
            <td colspan="3" class="highlight">{{ $shipping['total_net_weight'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label" colspan="2">{{ $shipping['net_boxes'] ?? '' }}</td>
            <td class="field-label">TOTAL PALLET</td>
            <td>{{ $shipping['total_pallet'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label" colspan="2">{{ $shipping['gross_bags'] ?? '' }}</td>
            <td class="field-label">TOTAL GROSS WEIGHT</td>
            <td>{{ $shipping['total_gross_weight'] ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top: 20px;">
        <tr>
            <td class="field-label" style="width: 20%;">SHIPPER</td>
            <td style="width: 80%;">
                {!! nl2br(e($shipping['shipper_info'] ?? '')) !!}
            </td>
        </tr>
        <tr>
            <td class="field-label">CONSIGNEE</td>
            <td>{!! nl2br(e($shipping['consignee_info'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">NOTIFY</td>
            <td>{!! nl2br(e($shipping['notify_info'] ?? '')) !!}</td>
        </tr>
    </table>
</body>
</html>



