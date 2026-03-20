@extends('layouts.plantilla')

@section('titulo', 'Pagos de Condominio')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Registro de Pagos</h1>
            <p style="color: var(--color-texto-secundario);">Historial de pagos recibidos de los propietarios.</p>
        </div>
        <a href="{{ route('pagos.create') }}" class="boton boton-primario">+ Registrar Pago</a>
    </div>

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Referencia</th>
                    <th style="padding: 1rem;">Fecha</th>
                    <th style="padding: 1rem;">Factura</th>
                    <th style="padding: 1rem;">Monto</th>
                    <th style="padding: 1rem;">Método</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">#TR-98827</td>
                    <td style="padding: 1rem;">15/03/2026</td>
                    <td style="padding: 1rem;">FAC-0001</td>
                    <td style="padding: 1rem;">$120.00</td>
                    <td style="padding: 1rem;">Transferencia Zelle</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
