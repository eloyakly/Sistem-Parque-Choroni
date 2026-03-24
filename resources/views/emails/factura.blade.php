<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 12px; }
        .header { background-color: #fcfcfc; padding: 20px; text-align: center; border-radius: 12px 12px 0 0; border-bottom: 3px solid #0056b3; }
        .content { padding: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; color: #555; }
        .total { font-weight: bold; text-align: right; }
        .footer { text-align: center; font-size: 0.85em; color: #888; margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0; color: #0056b3;">Condominio Parque Choroní</h2>
        </div>
        <div class="content">
            <p>Estimado/a <strong>{{ $factura->apartamento->propietario->nombre }} {{ $factura->apartamento->propietario->apellido }}</strong>,</p>
            <p>Se ha emitido una nueva factura de condominio correspondiente a su inmueble <strong>{{ $factura->apartamento->numero }}</strong> (Torre {{ $factura->apartamento->torre }}).</p>
            
            <div style="background: #f1f8ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>Propietario a Facturar:</strong> {{ $factura->apartamento->propietario->nombre }} {{ $factura->apartamento->propietario->apellido }}</p>
                <p style="margin: 5px 0;"><strong>Cédula de Identidad:</strong> V-{{ $factura->apartamento->propietario->cedula }}</p>
                <hr style="border: 0; border-top: 1px solid #cce5ff; margin: 10px 0;">
                <p style="margin: 5px 0;"><strong>Concepto:</strong> {{ $factura->descripcion }}</p>
                <p style="margin: 5px 0;"><strong>Vencimiento:</strong> {{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</p>
                <p style="margin: 5px 0;"><strong>Participación (Alícuota):</strong> {{ $factura->apartamento->tipo->alicuota }}%</p>
            </div>

            <h3 style="color: #444; border-bottom: 1px solid #eee; padding-bottom: 8px;">Desglose Exacto de Gastos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Descripción del Gasto</th>
                        <th>Costo General ($)</th>
                        <th>Su Parte ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $alicuotaDec = $factura->apartamento->tipo->alicuota / 100; @endphp
                    @foreach($gastoMes->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->descripcion }}</td>
                        <td>$ {{ number_format($detalle->monto, 2) }}</td>
                        <td>$ {{ number_format($detalle->monto * $alicuotaDec, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="total">Total a Pagar de la Factura:</td>
                        <td style="font-weight: bold; color: #c62828; font-size: 1.1em;">$ {{ number_format($factura->monto_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <p style="margin-top: 20px;">Le agradecemos registrar su pago a tiempo a través de la administración para mantener la solvencia técnica de su cuenta y evitar mora.</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático del Sistema Integrado de Parque Choroní. Por favor no responda directamente a esta dirección de correo.</p>
        </div>
    </div>
</body>
</html>
