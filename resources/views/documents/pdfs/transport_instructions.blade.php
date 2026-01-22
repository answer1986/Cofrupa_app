<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instructivo de Transporte - {{ $contract->contract_number }}</title>
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
        }
        h1 {
            text-align: center;
            font-size: 16pt;
            margin: 15px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 6px 10px;
            border: 1px solid #999;
            vertical-align: top;
        }
        .field-label {
            font-weight: bold;
            width: 35%;
            background-color: #f9f9f9;
        }
        .highlight {
            background-color: #ffff00;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa" class="logo">
    </div>

    <h1>INSTRUCTIVO DE TRANSPORTE</h1>

    <table>
        <tr>
            <td class="field-label">Fecha de Emisión</td>
            <td>{{ isset($transport['emission_date']) ? \Carbon\Carbon::parse($transport['emission_date'])->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Transporte</td>
            <td>{{ $transport['transport_company'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Contacto (CTC)</td>
            <td>{{ $transport['contact_info'] ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Cliente</td>
            <td>{{ $transport['client_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Ref. Cliente</td>
            <td>{{ $transport['client_reference'] ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Reserva (Booking)</td>
            <td>{{ $transport['booking_number'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Cía. Naviera</td>
            <td>{{ $transport['shipping_company'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Nave</td>
            <td>{{ $transport['vessel_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Tipo y Cantidad de Contenedores</td>
            <td>{{ $transport['container_type_quantity'] ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Producto y Cantidad</td>
            <td>{{ $transport['product_quantity'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Peso Neto por Unidad</td>
            <td>{{ $transport['net_weight_per_unit'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Peso Bruto por Unidad</td>
            <td>{{ $transport['gross_weight_per_unit'] ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Lugar de Retiro Vacío</td>
            <td>{{ $transport['empty_pickup_location'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">PUERTO EMBARQUE</td>
            <td>{{ $transport['loading_port'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">PUERTO DESTINO</td>
            <td>{{ $transport['destination_port'] ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Lugar de Cargado</td>
            <td>{{ $transport['loading_location'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Dirección</td>
            <td>{!! nl2br(e($transport['loading_address'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Fecha y Hora de Presentación</td>
            <td class="highlight">
                {{ isset($transport['presentation_date']) ? \Carbon\Carbon::parse($transport['presentation_date'])->format('d/m/Y') : '' }}
                {{ isset($transport['presentation_time']) ? ' - ' . $transport['presentation_time'] : '' }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="field-label">Terminal de Entrega</td>
            <td class="highlight">{{ $transport['delivery_terminal'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Puerto de Entrega</td>
            <td class="highlight">{{ $transport['delivery_port'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Stacking Oficial</td>
            <td class="highlight">{{ $transport['official_stacking'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="field-label">Horario de Stacking</td>
            <td class="highlight">{{ $transport['stacking_schedule'] ?? '' }}</td>
        </tr>
    </table>
</body>
</html>

