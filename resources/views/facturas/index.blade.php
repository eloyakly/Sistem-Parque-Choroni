@extends('layouts.plantilla')

@section('titulo', 'Facturación')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Facturas Emitidas</h1>
            <p style="color: var(--color-texto-secundario);">Control de recibos de condominio y gastos comunes.</p>
        </div>
        <a href="{{ route('facturas.create') }}" class="boton boton-primario">+ Generar Factura</a>
    </div>

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Nro. Factura</th>
                    <th style="padding: 1rem;">Inmueble</th>
                    <th style="padding: 1rem;">Monto</th>
                    <th style="padding: 1rem;">Mes/Año</th>
                    <th style="padding: 1rem;">Estado</th>
                    <th style="padding: 1rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">FAC-0001</td>
                    <td style="padding: 1rem;">A-101</td>
                    <td style="padding: 1rem;">$120.00</td>
                    <td style="padding: 1rem;">03/2026</td>
                    <td style="padding: 1rem;"><span style="padding: 0.2rem 0.6rem; background: #c8e6c9; color: #2e7d32; border-radius: 4px; font-size: 0.8rem;">Pagada</span></td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">PDF</button>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">FAC-0002</td>
                    <td style="padding: 1rem;">B-205</td>
                    <td style="padding: 1rem;">$115.00</td>
                    <td style="padding: 1rem;">03/2026</td>
                    <td style="padding: 1rem;"><span style="padding: 0.2rem 0.6rem; background: #ffcdd2; color: #c62828; border-radius: 4px; font-size: 0.8rem;">Vencida</span></td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Enviar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
