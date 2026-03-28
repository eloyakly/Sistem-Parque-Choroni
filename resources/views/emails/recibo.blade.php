@php
    // Compatibilidad: el Mailable envía $factura, el PDF envía $recibo
    if (!isset($factura) && isset($recibo)) {
        $factura = $recibo;
    }
    $mesRecibo = \Carbon\Carbon::parse($factura->fecha_vencimiento)->translatedFormat('F Y');
@endphp
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 540px;
            margin: 0 auto;
            padding: 12px 16px;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .header-info {
            padding-right: 10px;
        }

        .header-info h2 {
            margin: 0 0 2px 0;
            color: #2e7d32;
            font-size: 13px;
            line-height: 1.3;
        }

        .header-info p {
            margin: 0;
            font-size: 9px;
            color: #666;
            line-height: 1.4;
        }

        .header-logo {
            text-align: right;
            width: 80px;
        }

        .divider {
            border: 0;
            border-top: 2px solid #2e7d32;
            margin: 8px 0;
        }

        .content {
            padding: 6px 0;
        }

        .content p {
            margin: 4px 0;
            font-size: 11px;
        }

        .info-box {
            background: #e8f5e9;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
        }

        .info-box p {
            margin: 3px 0;
            font-size: 10.5px;
        }

        .info-box hr {
            border: 0;
            border-top: 1px solid #c8e6c9;
            margin: 6px 0;
        }

        table.desglose {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 10.5px;
        }

        table.desglose th,
        table.desglose td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        table.desglose th {
            background-color: #f0f0f0;
            color: #444;
            font-size: 10px;
        }

        .total-label {
            font-weight: bold;
            text-align: right;
        }

        .footer-info {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px;
            margin-top: 12px;
            font-size: 9.5px;
            color: #444;
        }

        .footer-info h4 {
            margin: 0 0 6px 0;
            font-size: 11px;
            color: #2e7d32;
        }

        .footer-info p {
            margin: 2px 0;
        }

        .footer-info .nota {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid #ddd;
            font-weight: bold;
            color: #c62828;
            font-size: 9.5px;
        }

        .footer-auto {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- ── HEADER: Título a la izquierda, Logo a la derecha ── --}}
        <table class="header-table">
            <tr>
                <td class="header-info">
                    <h2>Condominio Conjunto Residencial Parque Choroní II</h2>
                    <p>Av. Prolongación 19 de Abril c/ Agustín Zerpa, Urb. Base Aragua, Maracay.</p>
                    <p style="margin-top: 2px; font-size: 10px; color: #2e7d32; font-weight: bold;">Recibo —
                        {{ ucfirst($mesRecibo) }}</p>
                </td>
                <td class="header-logo">
                    @php
                        $logoPath = public_path('logo.png');
                        $logoSrc = '';
                        if (file_exists($logoPath)) {
                            try {
                                if (isset($message)) {
                                    $logoSrc = $message->embed($logoPath);
                                } else {
                                    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                                    $logoImage = file_get_contents($logoPath);
                                    $logoSrc = 'data:image/' . $logoType . ';base64,' . base64_encode($logoImage);
                                }
                            } catch (\Exception $e) {
                                $logoSrc = '';
                            }
                        }
                    @endphp
                    @if ($logoSrc)
                        <img src="{{ $logoSrc }}" alt="Logo" style="max-height: 60px; max-width: 75px;">
                    @endif
                </td>
            </tr>
        </table>
        <hr class="divider">

        {{-- ── CONTENIDO PRINCIPAL ── --}}
        <div class="content">
            <p>Estimado/a <strong>{{ $factura->apartamento->propietario->nombre }}
                    {{ $factura->apartamento->propietario->apellido }}</strong>,</p>
            <p>Se ha emitido un nuevo recibo de condominio correspondiente a su inmueble
                <strong>{{ $factura->apartamento->numero }}</strong> (Torre {{ $factura->apartamento->torre }}).</p>

            <div class="info-box">
                <p><strong>Propietario:</strong> {{ $factura->apartamento->propietario->nombre }}
                    {{ $factura->apartamento->propietario->apellido }}</p>
                <p><strong>Cédula:</strong> V-{{ $factura->apartamento->propietario->cedula }}</p>
                <hr>
                <p><strong>Concepto:</strong> {{ $factura->descripcion }}</p>
                <p><strong>Vencimiento:</strong>
                    {{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</p>
                <p><strong>Alícuota:</strong> {{ $factura->apartamento->tipo->alicuota }}%</p>
            </div>

            <h4
                style="color: #444; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin: 10px 0 4px 0; font-size: 11px;">
                Desglose de Gastos</h4>
            <table class="desglose">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Costo General ($)</th>
                        <th>Su Parte ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $alicuotaDec = $factura->apartamento->tipo->alicuota / 100; @endphp
                    @foreach ($gastoMes->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->descripcion }}</td>
                            <td>$ {{ number_format($detalle->monto, 2) }}</td>
                            <td>$ {{ number_format($detalle->monto * $alicuotaDec, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="total-label">Total a Pagar:</td>
                        <td style="font-weight: bold; color: #c62828; font-size: 12px;">$
                            {{ number_format($factura->monto_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ── INFORMACIÓN IMPORTANTE (FOOTER) ── --}}
        <div class="footer-info">
            <h4> Información Importante</h4>
            <p><strong>Plazo de pago:</strong> Se debe realizar dentro de los primeros 5 días del mes.</p>
            <p><strong>Tasa de cambio:</strong> Se utiliza la tasa del BCV del día en que se realice el pago.</p>
            <p><strong>Datos bancarios:</strong></p>
            <p style="padding-left: 12px;">
                Banco: <strong>Banesco</strong> — Cuenta Corriente<br>
                Nº <strong>0134-0034-2603-4103-8472</strong><br>
                A nombre de: <strong>Condominio Resd Parque Choroni II</strong><br>
                RIF: <strong>J-30671165-1</strong>
            </p>
            <p><strong>Reporte de pago:</strong> Enviar el soporte al correo
                <strong>parquechoroni2.nueva@gmail.com</strong> o al celular <strong>0422-800 73 33</strong>.</p>
            <p class="nota"> Nota: Se recuerda que con 3 meses de insolvencia, el caso pasará automáticamente al
                Departamento Legal.</p>
        </div>

        <div class="footer-auto">
            <p>Este es un mensaje automático del Sistema Integrado de Parque Choroní. Por favor no responda directamente
                a esta dirección de correo.</p>
        </div>
    </div>
</body>

</html>
