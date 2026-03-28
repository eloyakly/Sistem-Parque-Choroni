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
                    <p style="margin-top: 2px; font-size: 10px; color: #2e7d32; font-weight: bold;">Recibo de Pago —
                        Comprobante Nro: #P-{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</p>
                </td>
                <td class="header-logo">
                    @php

                        $logoPath = public_path('logo.png');
                        $logoSrc = '';
                        if (file_exists($logoPath)) {
                            try {
                                $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                                $logoImage = file_get_contents($logoPath);
                                $logoSrc = 'data:image/' . $logoType . ';base64,' . base64_encode($logoImage);
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
            <p>Estimado/a <strong>{{ $pago->apartamento->propietario->nombre }}
                    {{ $pago->apartamento->propietario->apellido }}</strong>,</p>
            <p>Se emite el presente recibo como comprobante de pago procesado a favor de su inmueble
                <strong>{{ $pago->apartamento->numero }}</strong> (Torre {{ $pago->apartamento->torre }}).</p>

            <div class="info-box">
                <p><strong>Propietario:</strong> {{ $pago->apartamento->propietario->nombre }}
                    {{ $pago->apartamento->propietario->apellido }}</p>
                <p><strong>Cédula:</strong> V-{{ $pago->apartamento->propietario->cedula }}</p>
                <hr>
                <p><strong>Fecha de Registro:</strong>
                    {{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y h:i A') }}</p>
                <p><strong>Fecha del Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                <p><strong>Método de Pago:</strong> {{ ucfirst($pago->metodo_pago) }}</p>
                <p><strong>Referencia:</strong> {{ $pago->referencia ?? 'N/A' }}</p>
            </div>

            <h4
                style="color: #444; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin: 10px 0 4px 0; font-size: 11px;">
                Resumen del Pago</h4>
            <table class="desglose">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Monto Abonado ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Abono a Cuenta Inmueble {{ $pago->apartamento->numero }}</td>
                        <td style="color: #2e7d32; font-weight: bold;">PROCESADO</td>
                        <td>$ {{ number_format($pago->monto, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="total-label">Total Abonado:</td>
                        <td style="font-weight: bold; color: #2e7d32; font-size: 12px;">$
                            {{ number_format($pago->monto, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ── INFORMACIÓN IMPORTANTE (FOOTER) ── --}}
        <div class="footer-info">
            <h4>Información de la Transacción</h4>
            <p>Este documento es un comprobante de abono o pago y no sustituye la factura física si ésta resulta
                requerida por ley.</p>
            <p>El abono ha sido acreditado a su cuenta y descontado del balance general de deuda de manera
                satisfactoria.</p>
        </div>

        <div class="footer-auto">
            <p>Este es un documento generado automáticamente por el Sistema Integrado de Parque Choroní II el
                {{ now()->format('d/m/Y h:i A') }}.</p>
        </div>
    </div>
</body>

</html>
