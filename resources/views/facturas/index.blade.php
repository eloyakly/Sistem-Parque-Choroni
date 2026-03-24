@extends('layouts.plantilla')

@section('titulo', 'Facturación')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Facturas Emitidas</h1>
            <p style="color: var(--color-texto-secundario);">Control de recibos de condominio y gastos comunes (orden descendente).</p>
        </div>
        <a href="{{ route('facturas.create') }}" class="boton boton-primario">+ Generar Factura</a>
    </div>

    @if(session('exito'))
        <div class="tarjeta" style="background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;">
            ✅ {{ session('exito') }}
        </div>
    @endif

    @if(session('error'))
        <div class="tarjeta" style="background-color: #f8d7da; color: #842029; border-color: #f5c2c7;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="tarjeta" style="margin-bottom: 1rem;">
        <form action="{{ route('facturas.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por concepto o apartamento..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('facturas.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($facturas->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay facturas emitidas. <a href="{{ route('facturas.create') }}" style="color: var(--color-acentuar);">Ir a generar factura</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Concepto</th>
                        <th style="padding: 1rem;">Inmueble / Torre</th>
                        <th style="padding: 1rem;">Total</th>
                        <th style="padding: 1rem;">Saldo Pendiente</th>
                        <th style="padding: 1rem;">Vencimiento</th>
                        <th style="padding: 1rem;">Estado</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturas as $factura)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem; font-weight: bold;">#{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 1rem;">{{ $factura->descripcion }}</td>
                        <td style="padding: 1rem;">
                            {{ $factura->apartamento->numero }} ({{ $factura->apartamento->torre }})
                        </td>
                        <td style="padding: 1rem;">$ {{ number_format($factura->monto_total, 2) }}</td>
                        <td style="padding: 1rem; font-weight: 500; color: {{ $factura->saldo_pendiente > 0 ? '#c62828' : '#2e7d32' }};">
                            $ {{ number_format($factura->saldo_pendiente, 2) }}
                        </td>
                        <td style="padding: 1rem;">
                            @if(\Carbon\Carbon::parse($factura->fecha_vencimiento)->isPast() && $factura->estado !== 'pagado')
                                <span style="color: #c62828;">{{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }} (Vencida)</span>
                            @else
                                {{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($factura->estado === 'pagado')
                                <span style="padding: 0.2rem 0.6rem; background: #c8e6c9; color: #2e7d32; border-radius: 4px; font-size: 0.85rem;">Pagada</span>
                            @elseif($factura->estado === 'pago_parcial')
                                <span style="padding: 0.2rem 0.6rem; background: #fff9c4; color: #f57f17; border-radius: 4px; font-size: 0.85rem;">Parcial</span>
                            @else
                                <span style="padding: 0.2rem 0.6rem; background: #ffcdd2; color: #c62828; border-radius: 4px; font-size: 0.85rem;">No Pagado</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('facturas.destroy', $factura) }}" method="POST" onsubmit="return confirm('¿Está seguro de anular esta factura? Se restaurará el saldo a favor del apartamento.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="boton" style="background: none; color: #dc3545;">Anular</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
