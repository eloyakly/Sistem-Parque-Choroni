<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartera de Deudores</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #2e7d32; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; color: #444; }
        .deuda-monto { text-align: right; color: #c62828; font-weight: bold; }
        .filtros-info { margin-bottom: 15px; font-size: 11px; color: #666; font-style: italic; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; position: fixed; bottom: 0; width: 100%; }
        .resumen { margin-top: 20px; text-align: right; padding: 10px; background: #ffebee; border: 1px solid #ffcdd2; font-size: 14px;}
    </style>
</head>
<body>

    <div class="header">
        <h1>Cartera de Deudores - Parque Choroní II</h1>
        <p>Reporte Oficial de Cobranzas</p>
        <p>Generado el {{ date('d/m/Y h:i A') }}</p>
    </div>

    @if($request->anyFilled(['buscar', 'monto_min', 'monto_max', 'torre']))
    <div class="filtros-info">
        Filtros aplicados: 
        {{ $request->buscar ? "Búsqueda: '{$request->buscar}' | " : "" }}
        {{ $request->monto_min ? "Monto Mín: $ {$request->monto_min} | " : "" }}
        {{ $request->monto_max ? "Monto Máx: $ {$request->monto_max} | " : "" }}
        {{ $request->torre ? "Torre: {$request->torre}" : "" }}
    </div>
    @endif

    @if($deudores->isEmpty())
        <p style="text-align: center; font-size: 14px; margin-top: 30px; color: #2e7d32;">
            No hay apartamentos en situación de mora actualmente con los filtros seleccionados.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Inmueble</th>
                    <th>Propietario</th>
                    <th>Cédula</th>
                    <th>Datos de Contacto</th>
                    <th style="text-align: right;">Deuda ($)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDeuda = 0; @endphp
                @foreach($deudores as $index => $deudor)
                    @php $totalDeuda += $deudor->deuda_actual; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>Apto. {{ $deudor->numero }}<br><small>Torre {{ $deudor->torre }}</small></td>
                        <td>{{ $deudor->propietario->nombre }} {{ $deudor->propietario->apellido }}</td>
                        <td>V-{{ $deudor->propietario->cedula }}</td>
                        <td>
                            Telf: {{ $deudor->propietario->telefono ?? 'N/A' }}<br>
                            Em: {{ $deudor->propietario->email ?? 'N/A' }}
                        </td>
                        <td class="deuda-monto">{{ number_format($deudor->deuda_actual, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="resumen">
            Total Deuda Listada: <strong>$ {{ number_format($totalDeuda, 2) }}</strong>
        </div>
    @endif

</body>
</html>
