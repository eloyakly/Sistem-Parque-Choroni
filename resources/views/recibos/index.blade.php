@extends('layouts.plantilla')

@section('titulo', 'Recibos')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Recibos Emitidos</h1>
            <p style="color: var(--color-texto-secundario);">Control de recibos de condominio y gastos comunes (orden descendente).</p>
        </div>
        <a href="{{ route('recibos.create') }}" class="boton boton-primario">+ Generar Recibo</a>
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
        <form action="{{ route('recibos.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por concepto o apartamento..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('recibos.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($recibos->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay recibos emitidos. <a href="{{ route('recibos.create') }}" style="color: var(--color-acentuar);">Ir a generar recibo</a>.
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
                    @foreach($recibos as $recibo)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem; font-weight: bold;">{{ str_pad($recibo->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 1rem;">{{ $recibo->descripcion }}</td>
                        <td style="padding: 1rem;">
                            {{ $recibo->apartamento->numero }} ({{ $recibo->apartamento->torre }})
                        </td>
                        <td style="padding: 1rem;">$ {{ number_format($recibo->monto_total, 2) }}</td>
                        <td style="padding: 1rem; font-weight: 500; color: {{ $recibo->saldo_pendiente > 0 ? '#c62828' : '#2e7d32' }};">
                            $ {{ number_format($recibo->saldo_pendiente, 2) }}
                        </td>
                        <td style="padding: 1rem;">
                            @if(\Carbon\Carbon::parse($recibo->fecha_vencimiento)->isPast() && $recibo->estado !== 'pagado')
                                <span style="color: #c62828;">{{ \Carbon\Carbon::parse($recibo->fecha_vencimiento)->format('d/m/Y') }} (Vencida)</span>
                            @else
                                {{ \Carbon\Carbon::parse($recibo->fecha_vencimiento)->format('d/m/Y') }}
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($recibo->estado === 'pagado')
                                <span style="padding: 0.2rem 0.6rem; background: #c8e6c9; color: #2e7d32; border-radius: 4px; font-size: 0.85rem;">Pagado</span>
                            @elseif($recibo->estado === 'pago_parcial')
                                <span style="padding: 0.2rem 0.6rem; background: #fff9c4; color: #f57f17; border-radius: 4px; font-size: 0.85rem;">Parcial</span>
                            @else
                                <span style="padding: 0.2rem 0.6rem; background: #ffcdd2; color: #c62828; border-radius: 4px; font-size: 0.85rem;">No Pagado</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem; align-items: center;">
                            <button type="button" class="boton boton-primario" onclick="verRecibo('{{ route('recibos.show', $recibo->id) }}')" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Ver</button>
                            <form action="{{ route('recibos.destroy', $recibo) }}" method="POST" onsubmit="return confirm('¿Está seguro de anular este recibo? Se restaurará el saldo a favor del apartamento.')" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="boton" style="background: none; color: #dc3545; padding: 0.3rem; font-size: 0.85rem;">Anular</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal para ver recibo -->
    <dialog id="modalVerRecibo" style="padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-borde); background: var(--color-superficie); box-shadow: 0 10px 30px rgba(0,0,0,0.3); width: 90%; max-width: 800px; height: 85vh; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; color: var(--color-texto);">Recibo Electrónico</h3>
            <button class="boton" onclick="document.getElementById('modalVerRecibo').close()" style="background: #e74c3c; color: white; padding: 0.4rem 0.8rem; border-radius: 6px; border: none; cursor: pointer;">Cerrar</button>
        </div>
        <!-- Iframe para montar el PDF estático -->
        <iframe id="iframeRecibo" src="" style="width: 100%; height: calc(100% - 4rem); border: 1px solid var(--color-borde); border-radius: 8px; background: #fff;"></iframe>
    </dialog>

    <script>
        function verRecibo(url) {
            document.getElementById('iframeRecibo').src = url;
            document.getElementById('modalVerRecibo').showModal();
        }
    </script>
@endsection
