@extends('layouts.plantilla')

@section('titulo', 'Gestión de Gastos Mensuales')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Gastos Generales por Mes</h1>
            <p style="color: var(--color-texto-secundario);">Cargue los gastos comunes que se aplicarán a todas las facturas del mes.</p>
        </div>
        <a href="{{ route('gastos-mensuales.create') }}" class="boton boton-primario">+ Cargar Gastos del Mes</a>
    </div>

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Mes / Año</th>
                    <th style="padding: 1rem;">Total Gastos ($)</th>
                    <th style="padding: 1rem;">Conceptos</th>
                    <th style="padding: 1rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Marzo 2026</td>
                    <td style="padding: 1rem;">$ 2,450.00</td>
                    <td style="padding: 1rem;">Agua, Vigilancia, Limpieza...</td>
                    <td style="padding: 1rem;">
                        <a href="{{ route('gastos-mensuales.edit', 1) }}" class="boton" style="background: none; color: var(--color-acentuar);">Editar Gastos</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
