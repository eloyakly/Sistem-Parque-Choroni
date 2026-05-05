@extends('layouts.plantilla')

@section('titulo', 'Inicio - Resumen')

@section('contenido')
    <div class="tarjeta">
        <h1>Bienvenido al Sistema Parque Choroni</h1>
        <p>Este es el panel principal de gestión de su condominio. Desde aquí puede acceder a todos los módulos.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="tarjeta" style="border-left: 4px solid var(--color-acentuar);">
            <h3>🏢 Apartamentos</h3>
            <p>{{ $totalApartamentos }} Unidades</p>
        </div>
        <div class="tarjeta" style="border-left: 4px solid #cc8e35;">
            <h3>👤 Propietarios</h3>
            <p>{{ $totalPropietarios }} Personas</p>
        </div>
        <div class="tarjeta" style="border-left: 4px solid #27ae60;">
            <h3>📄 Recibos Pendientes</h3>
            <p>{{ $facturasPendientes }} Recibos</p>
        </div>
        <div class="tarjeta" style="border-left: 4px solid #8e44ad;">
            <h3>💳 Pagos del Mes</h3>
            <p>{{ $pagosMes }} Registros</p>
        </div>
    </div>

    {{-- ── CORREOS ── --}}
    @php
        $pct = $limiteCorreos > 0 ? round(($correosEnviadosHoy / $limiteCorreos) * 100) : 0;
        $colorBarra = $pct >= 90 ? '#e74c3c' : ($pct >= 70 ? '#e67e22' : '#27ae60');
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
        <div class="tarjeta" style="border-left: 4px solid {{ $colorBarra }};">
            <h3>📧 Correos enviados hoy</h3>
            <p style="font-size: 1.6rem; font-weight: 700; color: {{ $colorBarra }}; margin: 0.2rem 0;">
                {{ $correosEnviadosHoy }} <span style="font-size: 1rem; font-weight: 400; color: var(--color-texto-secundario);">/ {{ $limiteCorreos }}</span>
            </p>
            <div style="margin-top: 0.5rem; background: var(--color-borde); border-radius: 999px; height: 6px; overflow: hidden;">
                <div style="width: {{ $pct }}%; height: 100%; background: {{ $colorBarra }}; border-radius: 999px;"></div>
            </div>
            <p style="font-size: 0.8rem; color: var(--color-texto-secundario); margin-top: 0.4rem;">
                {{ $limiteCorreos - $correosEnviadosHoy }} cupos disponibles hoy
            </p>
        </div>
        <div class="tarjeta" style="border-left: 4px solid {{ $correosPendientes > 0 ? '#e67e22' : '#27ae60' }};">
            <h3>⏳ Correos pendientes</h3>
            <p style="font-size: 1.6rem; font-weight: 700; color: {{ $correosPendientes > 0 ? '#e67e22' : '#27ae60' }}; margin: 0.2rem 0;">
                {{ $correosPendientes }}
            </p>
            @if($correosPendientes > 0)
                <p style="font-size: 0.8rem; color: var(--color-texto-secundario); margin-top: 0.3rem;">
                    Se enviarán mañana a las 8:00 AM automáticamente.
                </p>
                <a href="{{ route('log-correos.index') }}?estado=pendiente" style="font-size: 0.8rem; color: var(--color-acentuar);">Ver pendientes →</a>
            @else
                <p style="font-size: 0.8rem; color: var(--color-texto-secundario); margin-top: 0.3rem;">Sin correos en espera.</p>
            @endif
        </div>
    </div>

@endsection

