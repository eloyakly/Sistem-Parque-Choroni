@extends('layouts.plantilla')

@section('titulo', 'Pagos de Condominio')

@section('contenido')

{{-- ══════════════════════════════════════════════════════════════════════
     MODAL: Resumen de Ingresos
══════════════════════════════════════════════════════════════════════ --}}
<div id="modal-ingresos" style="
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 1rem;
">
    <div style="
        background: var(--color-superficie, #1e1e2e);
        border: 1px solid var(--color-borde, #333);
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 60px rgba(0,0,0,0.5);
        animation: slideUpModal 0.3s ease;
    ">
        {{-- Cabecera --}}
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.75rem 1rem;
            border-bottom: 1px solid var(--color-borde, #333);
        ">
            <div>
                <h2 style="margin: 0; font-size: 1.25rem;">📊 Resumen de Ingresos</h2>
                <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--color-texto-secundario, #aaa);">
                    Total de pagos registrados en el sistema
                </p>
            </div>
            <button onclick="cerrarModalIngresos()" style="
                background: transparent;
                border: none;
                color: var(--color-texto, #fff);
                font-size: 1.4rem;
                cursor: pointer;
                line-height: 1;
                padding: 0.25rem 0.5rem;
                border-radius: 6px;
            " title="Cerrar">✕</button>
        </div>

        {{-- Tarjetas resumen --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding: 1.5rem 1.75rem 1rem;">
            <div style="
                background: linear-gradient(135deg, #1a3a2a, #0f5132);
                border-radius: 12px;
                padding: 1.25rem 1.5rem;
                text-align: center;
            ">
                <div style="font-size: 0.8rem; letter-spacing: 0.05em; color: #a8e6c1; text-transform: uppercase; margin-bottom: 0.4rem;">
                    Total Pagos Registrados
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: #fff;">
                    {{ number_format($totalPagos) }}
                </div>
                <div style="font-size: 0.75rem; color: #a8e6c1; margin-top: 0.25rem;">pagos en el historial</div>
            </div>
            <div style="
                background: linear-gradient(135deg, #1a2a3a, #0f3151);
                border-radius: 12px;
                padding: 1.25rem 1.5rem;
                text-align: center;
            ">
                <div style="font-size: 0.8rem; letter-spacing: 0.05em; color: #a8c8e6; text-transform: uppercase; margin-bottom: 0.4rem;">
                    Monto Total Ingresado
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: #fff;">
                    $ {{ number_format($totalIngresado, 2) }}
                </div>
                <div style="font-size: 0.75rem; color: #a8c8e6; margin-top: 0.25rem;">acumulado histórico</div>
            </div>
        </div>

        {{-- Tabla desglose mensual --}}
        <div style="padding: 0 1.75rem 1.75rem;">
            <h3 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--color-texto-secundario, #aaa); font-weight: 600;">
                Desglose por Mes
            </h3>
            @if($ingresosPorMes->isEmpty())
                <p style="text-align: center; color: var(--color-texto-secundario); padding: 1.5rem;">
                    No hay datos de ingresos aún.
                </p>
            @else
                <div style="overflow-x: auto; border-radius: 10px; border: 1px solid var(--color-borde, #333);">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--color-fondo, #12121e);">
                                <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-secundario, #aaa); border-bottom: 1px solid var(--color-borde, #333);">
                                    Mes / Año
                                </th>
                                <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-secundario, #aaa); border-bottom: 1px solid var(--color-borde, #333);">
                                    Pagos
                                </th>
                                <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-secundario, #aaa); border-bottom: 1px solid var(--color-borde, #333);">
                                    Monto Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $meses = [
                                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
                                    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
                                    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
                                    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre',
                                ];
                            @endphp
                            @foreach($ingresosPorMes as $fila)
                                @php
                                    [$anio, $mes] = explode('-', $fila->mes);
                                    $nombreMes = ($meses[$mes] ?? $mes) . ' ' . $anio;
                                @endphp
                                <tr style="border-bottom: 1px solid var(--color-borde, #333); transition: background 0.15s;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                                    onmouseout="this.style.background='transparent'">
                                    <td style="padding: 0.8rem 1rem; font-weight: 500;">
                                        {{ $nombreMes }}
                                    </td>
                                    <td style="padding: 0.8rem 1rem; text-align: center;">
                                        <span style="
                                            display: inline-block;
                                            background: rgba(46,125,50,0.15);
                                            color: #4caf50;
                                            border-radius: 20px;
                                            padding: 0.15rem 0.7rem;
                                            font-size: 0.85rem;
                                            font-weight: 600;
                                        ">{{ $fila->cantidad }}</span>
                                    </td>
                                    <td style="padding: 0.8rem 1rem; text-align: right; color: #4caf50; font-weight: 700; font-size: 1.05rem;">
                                        $ {{ number_format($fila->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--color-fondo, #12121e);">
                                <td style="padding: 0.85rem 1rem; font-weight: 700; border-top: 2px solid var(--color-borde, #333);">
                                    TOTAL GENERAL
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 700; border-top: 2px solid var(--color-borde, #333);">
                                    {{ number_format($totalPagos) }}
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: right; font-weight: 700; color: #4caf50; font-size: 1.1rem; border-top: 2px solid var(--color-borde, #333);">
                                    $ {{ number_format($totalIngresado, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ══ Header ══ --}}
<div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Registro de Pagos</h1>
        <p style="color: var(--color-texto-secundario);">Historial de pagos recibidos y abonados por los propietarios.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('pagos.reporte') }}" class="boton" style="
            background: linear-gradient(135deg, #1a3a2a, #2e7d32);
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 600;
            text-decoration: none;
        ">
            📊 Ver Ingresos
        </a>
        <a href="{{ route('pagos.create') }}" class="boton boton-primario">+ Registrar Pago</a>
    </div>
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
                        <form action="{{ route('pagos.destroy', $pago) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este pago? Esta acción revertirá la deuda del apartamento y reabrirá sus facturas pendientes.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="boton" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background: #e74c3c; color: white;" title="Eliminar Pago">
                                🗑️ Eliminar
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
            const urlRecibo = '{{ route("pagos.recibo", session("nuevo_pago_id")) }}';
            window.open(urlRecibo, '_blank');
        });
    </script>
@endif

{{-- ══ Estilos y scripts del modal ══ --}}
<style>
@keyframes slideUpModal {
    from { opacity: 0; transform: translateY(24px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0)   scale(1);    }
}
#modal-ingresos.activo {
    display: flex !important;
}
</style>

<script>
function abrirModalIngresos() {
    const modal = document.getElementById('modal-ingresos');
    modal.classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModalIngresos() {
    const modal = document.getElementById('modal-ingresos');
    modal.classList.remove('activo');
    document.body.style.overflow = '';
}
// Cerrar al hacer clic fuera del panel
document.getElementById('modal-ingresos').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalIngresos();
});
// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModalIngresos();
});
</script>

@endsection
