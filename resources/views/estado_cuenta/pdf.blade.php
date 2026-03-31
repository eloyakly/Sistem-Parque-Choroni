<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - Torre {{ $torreSeleccionada }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #2e7d32; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; color: #444; }
        .deuda-monto { text-align: right; font-weight: bold; }
        .filtros-info { margin-bottom: 15px; font-size: 12px; color: #444; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; position: fixed; bottom: 0; width: 100%; }
        .resumen { margin-top: 20px; text-align: right; padding: 10px; background: #fdfdfd; border: 1px solid #ddd; font-size: 14px;}
        .deuda-peligro { color: #c62828; }
        .deuda-sana { color: #2e7d32; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 20%; border: none; text-align: left; vertical-align: middle;">
                    <img src="{{ public_path('logo.png') }}" style="height: 60px;">
                </td>
                <td style="width: 80%; border: none; text-align: center; vertical-align: middle;">
                    <h1>Cuentas por Cobrar</h1>
                    <p>Condominio Parque Choroní II</p>
                    <p>Emitido el {{ date('d/m/Y h:i A') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="filtros-info">
        <strong>Torre:</strong> {{ $torreSeleccionada }} <br>
        <strong>Periodo de Facturación:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m', $mesSeleccionado)->translatedFormat('F Y') }}
    </div>

    @if($apartamentos->isEmpty())
        <p style="text-align: center; font-size: 14px; margin-top: 30px; color: #2e7d32;">
            No hay apartamentos registrados en la torre seleccionada.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Inmueble</th>
                    <th style="text-align: right;">Cargado / Debe (Mes {{ \Carbon\Carbon::createFromFormat('Y-m', $mesSeleccionado)->translatedFormat('F Y') }})</th>
                    <th style="text-align: right;">Deuda Total Acumulada</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalDeudaMes = 0; 
                    $totalDeudaAcumulada = 0; 
                @endphp
                @foreach($apartamentos as $index => $apto)
                    @php 
                        $totalDeudaMes += $apto->deuda_mes; 
                        $totalDeudaAcumulada += $apto->deuda_actual; 
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>Apto. {{ $apto->numero }}</td>
                        <td class="deuda-monto {{ $apto->deuda_mes > 0 ? 'deuda-peligro' : 'deuda-sana' }}">
                            {{ number_format($apto->deuda_mes, 2) }}
                        </td>
                        <td class="deuda-monto {{ $apto->deuda_actual > 0 ? 'deuda-peligro' : 'deuda-sana' }}">
                            {{ number_format($apto->deuda_actual, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="resumen">
            Total Deuda Mes Seleccionado: <strong>$ {{ number_format($totalDeudaMes, 2) }}</strong><br>
            Total Deuda Acumulada Torre {{ $torreSeleccionada }}: <strong>$ {{ number_format($totalDeudaAcumulada, 2) }}</strong>
        </div>
    @endif

</body>
</html>
