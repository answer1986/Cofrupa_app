<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Calidad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-top: none;
        }
        .contract-info {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #0d6efd;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COFRUPA Export SpA</h1>
        <p>Certificado de Calidad</p>
    </div>
    
    <div class="content">
        <p>Estimado/a,</p>
        
        <p>{{ $message }}</p>
        
        <div class="contract-info">
            <strong>Información del Contrato:</strong><br>
            <strong>Número de Contrato:</strong> {{ $contract->contract_number }}<br>
            @if($contract->client)
                <strong>Cliente:</strong> {{ $contract->client->name }}<br>
            @endif
            @if($contract->product_description)
                <strong>Producto:</strong> {{ $contract->product_description }}<br>
            @endif
        </div>
        
        <p>Por favor, encuentre adjunto el Certificado de Calidad en formato PDF.</p>
        
        <p>Si tiene alguna pregunta o requiere información adicional, no dude en contactarnos.</p>
        
        <p>Saludos cordiales,<br>
        <strong>Cofrupa Export SpA</strong><br>
        RUT: 76.505.934-8<br>
        Camino Lo Mackenna PC 7-A, Buin<br>
        Teléfono: +56 9 9239 5293</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático generado por el sistema de gestión de COFRUPA.</p>
        <p>Por favor, no responda directamente a este correo.</p>
    </div>
</body>
</html>



