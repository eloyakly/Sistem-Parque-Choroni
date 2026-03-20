@extends('layouts.plantilla')

@section('titulo', 'Cargar Gastos del Mes')

@section('contenido')
    <div class="tarjeta">
        <h1>Definir Gastos de Mensualidad</h1>
        <p style="color: var(--color-texto-secundario);">Estos montos servirán de base para las facturas individuales.</p>
        
        <form action="{{ route('gastos-mensuales.index') }}" method="GET" style="margin-top: 2rem;">
            <div style="margin-bottom: 2rem;">
                <label style="font-weight: 500; display: block; margin-bottom: 0.5rem;">Mes Correspondiente</label>
                <input type="month" value="2026-03" style="width: 300px; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
            </div>

            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">Detalle de Gastos Generales</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                            <th style="padding: 0.8rem;">Concepto</th>
                            <th style="padding: 0.8rem; width: 150px;">Monto ($)</th>
                            <th style="padding: 0.8rem; width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-gastos">
                        <tr style="border-bottom: 1px solid var(--color-borde);">
                            <td style="padding: 0.8rem;">
                                <input type="text" placeholder="Vigilancia Privada" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                            </td>
                            <td style="padding: 0.8rem;">
                                <input type="number" step="0.01" value="1200" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                            </td>
                            <td></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-borde);">
                            <td style="padding: 0.8rem;">
                                <input type="text" placeholder="Servicio de Agua" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                            </td>
                            <td style="padding: 0.8rem;">
                                <input type="number" step="0.01" value="450" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="boton" style="margin-top: 1rem; background: var(--color-acentuar-suave); color: var(--color-acentuar);">
                    + Añadir Gasto
                </button>
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; border-top: 2px solid var(--color-borde); padding-top: 1.5rem;">
                <span style="font-size: 1.2rem; font-weight: 600;">Total del Mes:</span>
                <span style="font-size: 1.5rem; font-weight: bold; color: var(--color-acentuar);">$ 1,650.00</span>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Guardar Gastos del Mes</button>
                <a href="{{ route('gastos-mensuales.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
