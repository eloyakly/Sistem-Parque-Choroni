@extends('layouts.plantilla')

@section('titulo', 'Reporte de Ingresos - ' . $nombreMes)

@section('contenido')
<div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.75rem;">📊 Reporte de Ingresos</h1>
        <p style="color: var(--color-texto-secundario); margin-top: 0.25rem;">
            Listado detallado de recaudación para <strong>{{ $nombreMes }}</strong> 
            @if($torre) - Torre <strong>{{ $torre }}</strong> @endif
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('pagos.index') }}" class="boton" style="background: var(--color-borde); color: var(--color-texto);">
            ← Volver
        </a>
        <a href="{{ route('pagos.ingresos.imprimir', ['mes' => $mes, 'torre' => $torre]) }}" target="_blank" class="boton boton-primario" style="display: flex; align-items: center; gap: 0.5rem;">
            🖨️ Imprimir Reporte
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="tarjeta" style="margin-bottom: 2rem;">
    <form action="{{ route('pagos.reporte') }}" method="GET" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--color-texto-secundario);">Seleccionar Mes</label>
            <input type="month" name="mes" value="{{ $mes }}" 
                style="width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); font-family: inherit;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--color-texto-secundario);">Filtrar por Torre</label>
            <select name="torre" style="width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); font-family: inherit;">
                @foreach($torres as $t)
                    <option value="{{ $t }}" {{ $torre == $t ? 'selected' : '' }}>Torre {{ $t }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="boton boton-primario" style="padding: 0.75rem 1.5rem;">
            Aplicar Filtros
        </button>
        @if($torre || $mes != date('Y-m'))
            <a href="{{ route('pagos.reporte') }}" class="boton" style="padding: 0.75rem 1rem; background: var(--color-borde); color: var(--color-texto);">
                Limpiar
            </a>
        @endif
    </form>
</div>

{{-- Estadísticas --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="tarjeta" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 5px solid #2e7d32;">
        <div style="background: rgba(46,125,50,0.1); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            💵
        </div>
        <div>
            <div style="font-size: 0.85rem; color: var(--color-texto-secundario); font-weight: 600; text-transform: uppercase;">Total Ingresado</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #2e7d32;">$ {{ number_format($totalMes, 2) }}</div>
        </div>
    </div>
    
    <div class="tarjeta" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 5px solid var(--color-acentuar);">
        <div style="background: var(--color-acentuar-suave); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            📝
        </div>
        <div>
            <div style="font-size: 0.85rem; color: var(--color-texto-secundario); font-weight: 600; text-transform: uppercase;">Número de Pagos</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--color-acentuar);">{{ $cantidadPagos }}</div>
        </div>
    </div>

    <div class="tarjeta" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 5px solid #f39c12;">
        <div style="background: rgba(243,156,18,0.1); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            🏢
        </div>
        <div>
            <div style="font-size: 0.85rem; color: var(--color-texto-secundario); font-weight: 600; text-transform: uppercase;">Apartamentos que abonaron</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #e67e22;">{{ $cantidadApartamentos }}</div>
        </div>
    </div>
</div>

{{-- Tabla de resultados --}}
<div class="tarjeta">
    @if($pagosMes->isEmpty())
        <div style="text-align: center; padding: 3rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
            <h3 style="color: var(--color-texto-secundario);">No se encontraron ingresos para este periodo y filtro.</h3>
            <p>Prueba seleccionando otro mes o torre.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem; font-weight: 600;">Fecha</th>
                        <th style="padding: 1rem; font-weight: 600;">Inmueble / Torre</th>
                        <th style="padding: 1rem; font-weight: 600;">Propietario</th>
                        <th style="padding: 1rem; font-weight: 600;">Referencia</th>
                        <th style="padding: 1rem; font-weight: 600;">Método</th>
                        <th style="padding: 1rem; font-weight: 600; text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagosMes as $pago)
                    <tr style="border-bottom: 1px solid var(--color-borde); transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td style="padding: 1rem;">
                            <span style="font-weight: 600;">{{ $pago->apartamento->numero }}</span>
                            <span style="font-size: 0.8rem; background: var(--color-borde); padding: 0.15rem 0.5rem; border-radius: 10px; margin-left: 0.4rem;">Torre {{ $pago->apartamento->torre }}</span>
                        </td>
                        <td style="padding: 1rem;">
                            {{ $pago->apartamento->propietario->nombre }} {{ $pago->apartamento->propietario->apellido }}
                        </td>
                        <td style="padding: 1rem;"><small style="color: var(--color-texto-secundario);">{{ $pago->referencia ?? 'N/A' }}</small></td>
                        <td style="padding: 1rem;">{{ ucfirst($pago->metodo_pago) }}</td>
                        <td style="padding: 1rem; text-align: right; font-weight: 700; color: #2e7d32;">
                            $ {{ number_format($pago->monto, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: var(--color-fondo); font-weight: 700;">
                        <td colspan="5" style="padding: 1rem; text-align: right; text-transform: uppercase; font-size: 0.9rem;">Total Recaudado:</td>
                        <td style="padding: 1rem; text-align: right; font-size: 1.2rem; color: #2e7d32;">$ {{ number_format($totalMes, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
@endsection
