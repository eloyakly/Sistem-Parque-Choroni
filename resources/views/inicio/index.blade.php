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


@endsection
