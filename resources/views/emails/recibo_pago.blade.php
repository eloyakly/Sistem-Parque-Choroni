<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Pago</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; margin-bottom: 20px;">
        <h1 style="color: #2e7d32; margin: 0;">Recibo de Pago Procesado</h1>
        <p style="color: #666; margin: 5px 0 0;">Condominio Conjunto Residencial Parque Choroní II</p>
    </div>

    <p>Estimado(a) <strong>{{ $pago->apartamento->propietario->nombre }} {{ $pago->apartamento->propietario->apellido }}</strong>,</p>

    <p>Hemos procesado exitosamente un abono/pago a favor de su apartamento <strong>{{ $pago->apartamento->numero }} ({{ $pago->apartamento->torre }})</strong>.</p>

    <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #2e7d32; margin: 20px 0;">
        <ul style="list-style-type: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 5px;"><strong>Comprobante Nro:</strong> #P-{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</li>
            <li style="margin-bottom: 5px;"><strong>Fecha del Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</li>
            <li style="margin-bottom: 5px;"><strong>Método de Pago:</strong> {{ ucfirst($pago->metodo_pago) }}</li>
            @if($pago->referencia)
            <li style="margin-bottom: 5px;"><strong>Referencia:</strong> {{ $pago->referencia }}</li>
            @endif
            <li><strong>Monto Abonado:</strong> <span style="color: #2e7d32; font-weight: bold; font-size: 1.1em;">$ {{ number_format($pago->monto, 2) }}</span></li>
        </ul>
    </div>

    <p>Adjunto a este correo encontrará el recibo de pago detallado en formato PDF para su archivo personal.</p>

    <p>Agradecemos su puntualidad y compromiso con el condominio.</p>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 0.9em; color: #666; text-align: center;">
        <p style="margin: 0;">Este es un mensaje automático, por favor no responda a este correo.</p>
        <p style="margin: 5px 0 0;">&copy; {{ date('Y') }} Sistema Parque Choroní II</p>
    </div>
</body>
</html>
