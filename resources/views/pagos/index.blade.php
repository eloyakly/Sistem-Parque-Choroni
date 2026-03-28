@extends('layouts.plantilla')

@section('titulo', 'Pagos de Condominio')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Registro de Pagos</h1>
            <p style="color: var(--color-texto-secundario);">Historial de pagos recibidos y abonados por los propietarios.</p>
        </div>
        <a href="{{ route('pagos.create') }}" class="boton boton-primario">+ Registrar Pago</a>
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
        <form action="{{ route('pagos.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por referencia, apartamento o propietario..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('pagos.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($pagos->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay pagos registrados. <a href="{{ route('pagos.create') }}" style="color: var(--color-acentuar);">Ir a registrar el primer pago</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">ID / Referencia</th>
                        <th style="padding: 1rem;">Fecha</th>
                        <th style="padding: 1rem;">Inmueble</th>
                        <th style="padding: 1rem;">Monto</th>
                        <th style="padding: 1rem;">Método</th>
                        <th style="padding: 1rem; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem;">
                            <strong>#P-{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            <br>
                            <small class="texto-secundario">{{ $pago->referencia ?? 'Sin ref.' }}</small>
                        </td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td style="padding: 1rem;">{{ $pago->apartamento->numero }} ({{ $pago->apartamento->torre }})</td>
                        <td style="padding: 1rem; color: #2e7d32; font-weight: bold;">$ {{ number_format($pago->monto, 2) }}</td>
                        <td style="padding: 1rem;">{{ ucfirst($pago->metodo_pago) }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('pagos.recibo', $pago) }}" target="_blank" class="boton" style="padding: 0.4rem 0.8rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: var(--color-texto); font-size: 0.85rem; margin-right: 0.5rem;" title="Ver/Imprimir Recibo">
                                🖨️
                            </a>
                            <form action="{{ route('pagos.enviar_recibo', $pago) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="boton boton-primario" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" title="Enviar por Correo">
                                    📧 Enviar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @if(session('nuevo_pago_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Abrir el recibo en una nueva pestaña
                const urlRecibo = '{{ route("pagos.recibo", session("nuevo_pago_id")) }}';
                window.open(urlRecibo, '_blank');
            });
        </script>
    @endif
@endsection
