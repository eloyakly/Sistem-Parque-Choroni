<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Ingresos - {{ $nombreMes }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11pt; color: #333; margin: 0; padding: 0; }
        .header { border-bottom: 2px solid #2e7d32; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 18pt; font-weight: bold; color: #2e7d32; }
        .titulo-reporte { font-size: 14pt; margin-top: 5px; font-weight: bold; }
        .filtros { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 10pt; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #2e7d32; color: white; padding: 10px; text-align: left; font-size: 10pt; }
        td { border-bottom: 1px solid #ddd; padding: 8px; font-size: 9pt; }
        
        .resumen { float: right; width: 300px; margin-top: 10px; }
        .resumen-item { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 5px 0; }
        .total { font-size: 12pt; font-weight: bold; color: #2e7d32; margin-top: 10px; }
        
        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 9pt; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .paginacion:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 20%; border: none; text-align: left; vertical-align: middle;">
                    <img src="{{ public_path('logo.png') }}" style="height: 60px;">
                </td>
                <td style="width: 80%; border: none; text-align: left; vertical-align: middle; padding-left: 20px;">
                    <div class="logo">PARQUE CHORONÍ</div>
                    <div class="titulo-reporte">ESTADO MENSUAL DE INGRESOS</div>
                    <p style="margin: 3px 0; font-size: 10pt; color: #666;">Fecha de generación: {{ date('d/m/Y h:i A') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="filtros">
        <strong>Periodo:</strong> {{ $nombreMes }} | 
        <strong>Torre:</strong> {{ $torre }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Fecha</th>
                <th width="30%">Inmueble</th>
               
                <th width="40%">Referencia</th>
                <th width="15%" style="text-align: right;">Monto ($)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagosMes as $pago)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                    <td>{{ $pago->apartamento->numero }} ({{ $pago->apartamento->torre }})</td>
                   
                    <td>{{ $pago->referencia ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($pago->monto, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #999;">No hay registros para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f1f8e9;">
                <td colspan="3" style="text-align: right; font-weight: bold; padding: 10px;">TOTAL RECAUDADO:</td>
                <td style="text-align: right; font-weight: bold; color: #2e7d32; padding: 10px;">$ {{ number_format($totalMes, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="resumen">
        <div class="resumen-item">
            <span>Total de Pagos Registrados:</span>
            <strong>{{ count($pagosMes) }}</strong>
        </div>
        <div class="resumen-item">
            <span>Apartamentos que abonaron:</span>
            <strong>{{ $cantidadApartamentos }}</strong>
        </div>
        <div class="total">
            Total General: $ {{ number_format($totalMes, 2) }}
        </div>
    </div>

    <div class="footer">
        Página <span class="paginacion"></span> | Sistema de Gestión Parque Choroní
    </div>
</body>
</html>
