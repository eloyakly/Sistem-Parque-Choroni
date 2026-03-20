@extends('layouts.plantilla')

@section('titulo', 'Emitir Factura Mensual')

@section('contenido')
    <div class="tarjeta">
        <h1>Generar Factura Individual</h1>
        <p style="color: var(--color-texto-secundario);">Seleccione el mes y el apartamento para aplicar los gastos cargados.</p>
        
        <form action="{{ route('facturas.index') }}" method="GET" style="margin-top: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Selección de Mes Cargado -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Seleccionar Mes de Gastos</label>
                    <select id="select-mes" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="0">Seleccione un mes...</option>
                        <option value="1650" selected>Marzo 2026 (Base: $ 1,650.00)</option>
                        <option value="1800">Febrero 2026 (Base: $ 1,800.00)</option>
                    </select>
                </div>

                <!-- Selección de Apartamento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Inmueble / Apartamento</label>
                    <select style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option>A-101 (Torre A)</option>
                        <option>B-205 (Torre B)</option>
                    </select>
                </div>

                <!-- Alícuota -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Alícuota del Apartamento (%)</label>
                    <input type="number" id="input-alicuota" step="0.01" value="5.50" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>

                <!-- Fecha Vencimiento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Fecha de Vencimiento</label>
                    <input type="date" value="2026-03-31" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>

            <!-- Vista Previa de Gastos Aplicados -->
            <div style="margin-bottom: 2rem; background: var(--color-acentuar-suave); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-acentuar);">
                <h3 style="color: var(--color-acentuar); margin-bottom: 1rem;">Calculo Programado</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Base de Gastos del Mes:</span>
                    <span id="base-gastos" style="font-weight: 600;">$ 1,650.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Participación (Alícuota):</span>
                    <span id="label-alicuota" style="font-weight: 600;">5.50 %</span>
                </div>
                <hr style="border: 0.5px solid var(--color-acentuar); opacity: 0.3; margin: 1rem 0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.2rem; font-weight: bold;">Monto a Facturar:</span>
                    <span id="monto-total" style="font-size: 1.8rem; font-weight: bold; color: var(--color-acentuar);">$ 90.75</span>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Generar Factura Individual</button>
                <a href="{{ route('facturas.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const selectMes = document.getElementById('select-mes');
        const inputAlicuota = document.getElementById('input-alicuota');
        const baseGastos = document.getElementById('base-gastos');
        const labelAlicuota = document.getElementById('label-alicuota');
        const montoTotal = document.getElementById('monto-total');

        function recalcular() {
            const base = parseFloat(selectMes.value);
            const alicuota = parseFloat(inputAlicuota.value) || 0;
            
            baseGastos.textContent = `$ ${base.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            labelAlicuota.textContent = `${alicuota.toFixed(2)} %`;
            
            const total = base * (alicuota / 100);
            montoTotal.textContent = `$ ${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        }

        selectMes.addEventListener('change', recalcular);
        inputAlicuota.addEventListener('input', recalcular);
    </script>
@endsection
