<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #8B0000; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #666; }
        .button { display: inline-block; padding: 10px 20px; background-color: #8B0000; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>COFRUPA EXPORT</h2>
            <p>Premium Prunes from Chile</p>
        </div>
        
        <div class="content">
            <p>Estimado/a {{ $recipient_name ?? 'Cliente' }},</p>
            
            <p>Por la presente enviamos el siguiente documento relacionado con la exportación:</p>
            
            <p><strong>Documento:</strong> {{ $document->document_name }}</p>
            <p><strong>Exportación Nr:</strong> {{ $exportation->export_number }}</p>
            <p><strong>Contrato Nr:</strong> {{ $exportation->contract->contract_number }}</p>
            
            @if($message)
                <div style="margin: 20px 0; padding: 15px; background-color: white; border-left: 4px solid #8B0000;">
                    <strong>Mensaje:</strong><br>
                    {!! nl2br(e($message)) !!}
                </div>
            @endif
            
            <p>El documento adjunto contiene información detallada. Por favor, revíselo y no dude en contactarnos si tiene alguna pregunta.</p>
        </div>
        
        <div class="footer">
            <p><strong>COFRUPA EXPORT SPA</strong></p>
            <p>CAM LO MACKENNA PC 7-A, BUIN, SANTIAGO, CHILE</p>
            <p>Tel: +569 7794 9575 | Email: benjamin.prieto@patagoniannut.cl</p>
            <p>www.patagoniannut.cl</p>
        </div>
    </div>
</body>
</html>



