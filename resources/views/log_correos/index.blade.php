@extends('layouts.plantilla')

@section('titulo', 'Estado de Correos')

@section('contenido')

    {{-- ── ENCABEZADO ── --}}
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>📧 Log de Correos</h1>
            <p style="color: var(--color-texto-secundario);">Registro de todos los correos electrónicos procesados por el sistema.</p>
        </div>
    </div>

    {{-- ── TARJETAS DE RESUMEN DEL DÍA ── --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Enviados Hoy --}}
        <div class="tarjeta" style="border-left: 4px solid #27ae60; padding: 1.2rem;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-texto-secundario); text-transform: uppercase; letter-spacing: 0.05em;">Enviados hoy</p>
            <p style="margin: 0.3rem 0 0; font-size: 2rem; font-weight: 700; color: #27ae60;">{{ $enviadosHoy }}</p>
            <p style="margin: 0; font-size: 0.75rem; color: var(--color-texto-secundario);">de {{ \App\Models\LogCorreo::LIMITE_DIARIO }} disponibles</p>
        </div>

        {{-- Cupo Restante --}}
        @php
            $pct = round(($enviadosHoy / \App\Models\LogCorreo::LIMITE_DIARIO) * 100);
            $colorCupo = $pct >= 90 ? '#e74c3c' : ($pct >= 70 ? '#e67e22' : '#27ae60');
        @endphp
        <div class="tarjeta" style="border-left: 4px solid {{ $colorCupo }}; padding: 1.2rem;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-texto-secundario); text-transform: uppercase; letter-spacing: 0.05em;">Cupo disponible</p>
            <p style="margin: 0.3rem 0 0; font-size: 2rem; font-weight: 700; color: {{ $colorCupo }};">{{ $limiteDisponible }}</p>
            <div style="margin-top: 0.5rem; background: var(--color-borde); border-radius: 999px; height: 6px; overflow: hidden;">
                <div style="width: {{ $pct }}%; height: 100%; background: {{ $colorCupo }}; border-radius: 999px; transition: width 0.3s;"></div>
            </div>
            <p style="margin: 0.3rem 0 0; font-size: 0.75rem; color: var(--color-texto-secundario);">{{ $pct }}% utilizado</p>
        </div>

        {{-- Fallidos Hoy --}}
        <div class="tarjeta" style="border-left: 4px solid #e74c3c; padding: 1.2rem;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-texto-secundario); text-transform: uppercase; letter-spacing: 0.05em;">Fallidos hoy</p>
            <p style="margin: 0.3rem 0 0; font-size: 2rem; font-weight: 700; color: #e74c3c;">{{ $fallidos }}</p>
        </div>

        {{-- Omitidos Hoy --}}
        <div class="tarjeta" style="border-left: 4px solid #95a5a6; padding: 1.2rem;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-texto-secundario); text-transform: uppercase; letter-spacing: 0.05em;">Omitidos hoy</p>
            <p style="margin: 0.3rem 0 0; font-size: 2rem; font-weight: 700; color: #95a5a6;">{{ $omitidosHoy }}</p>
            <p style="margin: 0; font-size: 0.75rem; color: var(--color-texto-secundario);">por límite diario</p>
        </div>

        {{-- Pendientes totales --}}
        @php $totalPend = \App\Models\LogCorreo::totalPendientes(); @endphp
        <div class="tarjeta" style="border-left: 4px solid {{ $totalPend > 0 ? '#e67e22' : '#27ae60' }}; padding: 1.2rem;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-texto-secundario); text-transform: uppercase; letter-spacing: 0.05em;">Pendientes</p>
            <p style="margin: 0.3rem 0 0; font-size: 2rem; font-weight: 700; color: {{ $totalPend > 0 ? '#e67e22' : '#27ae60' }};">{{ $totalPend }}</p>
            <p style="margin: 0; font-size: 0.75rem; color: var(--color-texto-secundario);">se enviarán a las 8:00 AM</p>
        </div>
    </div>

    {{-- ── FILTROS ── --}}
    <div class="tarjeta" style="margin-bottom: 1rem;">
        <form action="{{ route('log-correos.index') }}" method="GET"
            style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                <label style="font-size: 0.85rem; font-weight: 500;">Estado</label>
                <select name="estado"
                    style="padding: 0.6rem 1rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    <option value="">Todos</option>
                    <option value="enviado" {{ request('estado') === 'enviado' ? 'selected' : '' }}>✅ Enviado</option>
                    <option value="fallido" {{ request('estado') === 'fallido' ? 'selected' : '' }}>❌ Fallido</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                    <option value="omitido" {{ request('estado') === 'omitido' ? 'selected' : '' }}>⏭ Omitido</option>
                </select>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                <label style="font-size: 0.85rem; font-weight: 500;">Tipo</label>
                <select name="tipo"
                    style="padding: 0.6rem 1rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    <option value="">Todos</option>
                    <option value="factura" {{ request('tipo') === 'factura' ? 'selected' : '' }}>Recibo Mensual</option>
                    <option value="recibo_pago" {{ request('tipo') === 'recibo_pago' ? 'selected' : '' }}>Comprobante de Pago</option>
                </select>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                <label style="font-size: 0.85rem; font-weight: 500;">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha', today()->format('Y-m-d')) }}"
                    style="padding: 0.6rem 1rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                <label style="font-size: 0.85rem; font-weight: 500;">Correo Destino</label>
                <input type="text" name="correo" value="{{ request('correo') }}" placeholder="ejemplo@correo.com"
                    style="padding: 0.6rem 1rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
            </div>
            <button class="boton boton-primario" type="submit">Filtrar</button>
            <a href="{{ route('log-correos.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
        </form>
    </div>

    {{-- ── TABLA DE REGISTROS ── --}}
    <div class="tarjeta">
        @if($logs->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay registros de correos para los filtros seleccionados.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 0.8rem 1rem;">#</th>
                        <th style="padding: 0.8rem 1rem;">Estado</th>
                        <th style="padding: 0.8rem 1rem;">Tipo</th>
                        <th style="padding: 0.8rem 1rem;">Destinatario</th>
                        <th style="padding: 0.8rem 1rem;">Asunto</th>
                        <th style="padding: 0.8rem 1rem;">Fecha y Hora</th>
                        <th style="padding: 0.8rem 1rem;">Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $bgColor = match($log->estado) {
                                'enviado'   => 'rgba(39,174,96,0.07)',
                                'fallido'   => 'rgba(231,76,60,0.07)',
                                'omitido'   => 'rgba(149,165,166,0.07)',
                                'pendiente' => 'rgba(230,126,34,0.07)',
                                default     => 'transparent',
                            };
                            $estadoBadge = match($log->estado) {
                                'enviado'   => '<span style="background:#d5f5e3;color:#1e8449;padding:2px 10px;border-radius:999px;font-size:0.8rem;font-weight:600;">✅ Enviado</span>',
                                'fallido'   => '<span style="background:#fadbd8;color:#922b21;padding:2px 10px;border-radius:999px;font-size:0.8rem;font-weight:600;">❌ Fallido</span>',
                                'omitido'   => '<span style="background:#eaecee;color:#626567;padding:2px 10px;border-radius:999px;font-size:0.8rem;font-weight:600;">⏭ Omitido</span>',
                                'pendiente' => '<span style="background:#fdebd0;color:#9c640c;padding:2px 10px;border-radius:999px;font-size:0.8rem;font-weight:600;">⏳ Pendiente</span>',
                                default     => $log->estado,
                            };
                            $tipoLabel = $log->tipo === 'factura' ? 'Recibo Mensual' : 'Comprobante Pago';
                        @endphp
                        <tr style="border-bottom: 1px solid var(--color-borde); background: {{ $bgColor }};">
                            <td style="padding: 0.7rem 1rem; color: var(--color-texto-secundario); font-size: 0.8rem;">{{ $log->id }}</td>
                            <td style="padding: 0.7rem 1rem;">{!! $estadoBadge !!}</td>
                            <td style="padding: 0.7rem 1rem;">{{ $tipoLabel }}</td>
                            <td style="padding: 0.7rem 1rem;">{{ $log->destinatario }}</td>
                            <td style="padding: 0.7rem 1rem; font-size: 0.85rem;">{{ $log->asunto ?? '—' }}</td>
                            <td style="padding: 0.7rem 1rem; white-space: nowrap; font-size: 0.85rem; color: var(--color-texto-secundario);">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y h:i A') }}
                            </td>
                            <td style="padding: 0.7rem 1rem; font-size: 0.8rem; color: #e74c3c; max-width: 250px; word-break: break-word;">
                                {{ $log->error ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Paginación Personalizada --}}
            <div style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--color-borde); padding-top: 1rem;">
                <div>
                    @if ($logs->onFirstPage())
                        <span class="boton" style="background: var(--color-superficie); color: var(--color-texto-secundario); border: 1px solid var(--color-borde); opacity: 0.5; cursor: not-allowed;">&laquo; Anterior</span>
                    @else
                        <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="boton" style="background: var(--color-superficie); color: var(--color-texto); border: 1px solid var(--color-borde);">&laquo; Anterior</a>
                    @endif
                </div>

                <span style="font-size: 0.9rem; color: var(--color-texto-secundario);">
                    Página {{ $logs->currentPage() }} (Máx 50 por pág.)
                </span>

                <div>
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="boton boton-primario">Siguiente &raquo;</a>
                    @else
                        <span class="boton" style="background: var(--color-superficie); color: var(--color-texto-secundario); border: 1px solid var(--color-borde); opacity: 0.5; cursor: not-allowed;">Siguiente &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

@endsection
