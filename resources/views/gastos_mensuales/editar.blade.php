@extends('layouts.plantilla')

@section('titulo', 'Editar Gastos del Mes')

@section('contenido')
<div class="tarjeta">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Editar Gastos del Mes</h1>
            <p style="color: var(--color-texto-secundario);">Modifique los conceptos y montos del período seleccionado.</p>
        </div>
        {{-- Indicador de estado --}}
        <span id="badge-estado" style="padding: 0.4rem 1rem; background: #fff3e0; color: #e65100; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
            Sin procesar
        </span>
    </div>

    <form action="{{ route('gastos-mensuales.index') }}" method="GET" style="margin-top: 2rem;">

        {{-- ── Cabecera ── --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label style="font-weight: 500;">Mes Correspondiente</label>
                <input type="month" name="mes_anio" value="2026-03"
                    style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
            </div>

            {{-- ⚠ Campo visual: "Nota interna" — NO existe en la BD --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label style="font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                    Nota Interna
                    <span style="font-size: 0.7rem; background: #fff3e0; color: #e65100; padding: 0.15rem 0.4rem; border-radius: 4px;">Solo visual</span>
                </label>
                <textarea rows="2" placeholder="Ej: Reconexión de agua por mantenimiento..." disabled
                    style="padding: 0.8rem; border-radius: 8px; border: 1px dashed var(--color-borde); background: var(--color-acentuar-suave); color: var(--color-texto-secundario); cursor: not-allowed; font-family: inherit; resize: none;"></textarea>
                <small style="color: var(--color-texto-secundario);">Campo referencial, no se guarda en la base de datos.</small>
            </div>
        </div>

        {{-- ── Tabla de Conceptos ── --}}
        <div style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">Detalle de Conceptos</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 0.8rem;">Concepto / Descripción</th>
                        <th style="padding: 0.8rem; width: 160px;">Monto ($)</th>
                        <th style="padding: 0.8rem; width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="cuerpo-gastos">
                    {{-- Filas precargadas (simuladas) --}}
                    <tr class="fila-gasto" style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 0.8rem;">
                            <input type="text" name="descripcion[]" value="Vigilancia Privada"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                        </td>
                        <td style="padding: 0.8rem;">
                            <input type="number" name="monto[]" step="0.01" value="1200" class="entrada-monto"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                        </td>
                        <td style="padding: 0.8rem; text-align: center;">
                            <button type="button" class="btn-eliminar-fila"
                                style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:1.3rem;line-height:1;"
                                title="Eliminar">&times;</button>
                        </td>
                    </tr>
                    <tr class="fila-gasto" style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 0.8rem;">
                            <input type="text" name="descripcion[]" value="Servicio de Agua"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                        </td>
                        <td style="padding: 0.8rem;">
                            <input type="number" name="monto[]" step="0.01" value="450" class="entrada-monto"
                                style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;">
                        </td>
                        <td style="padding: 0.8rem; text-align: center;">
                            <button type="button" class="btn-eliminar-fila"
                                style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:1.3rem;line-height:1;"
                                title="Eliminar">&times;</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" id="boton-agregar-gasto" class="boton"
                style="margin-top: 1rem; background: var(--color-acentuar-suave); color: var(--color-acentuar);">
                + Añadir Gasto
            </button>
        </div>

        {{-- ── Total ── --}}
        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
                    border-top: 2px solid var(--color-borde); padding-top: 1.5rem; margin-bottom: 2rem;">
            <span style="font-size: 1.2rem; font-weight: 600;">Total del Mes:</span>
            <span id="total-mes" style="font-size: 1.5rem; font-weight: bold; color: var(--color-acentuar);">$ 0.00</span>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="boton boton-primario">Guardar Cambios</button>
            <a href="{{ route('gastos-mensuales.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
        </div>
    </form>
</div>

<script>
    const cuerpoGastos  = document.getElementById('cuerpo-gastos');
    const totalMes      = document.getElementById('total-mes');
    const btnAgregar    = document.getElementById('boton-agregar-gasto');

    const estiloInput   = 'width:100%;padding:0.5rem;border:1px solid var(--color-borde);border-radius:4px;background:transparent;color:inherit;';

    /* ── Calcular total ── */
    function calcularTotal() {
        const entradas = cuerpoGastos.querySelectorAll('.entrada-monto');
        let total = 0;
        entradas.forEach(e => total += parseFloat(e.value) || 0);
        totalMes.textContent = '$ ' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
    }

    /* ── Vincular eliminar en filas existentes ── */
    function vincularEliminar(fila) {
        const btn = fila.querySelector('.btn-eliminar-fila');
        if (btn) btn.addEventListener('click', () => { fila.remove(); calcularTotal(); });
        const monto = fila.querySelector('.entrada-monto');
        if (monto) monto.addEventListener('input', calcularTotal);
    }

    cuerpoGastos.querySelectorAll('.fila-gasto').forEach(vincularEliminar);
    calcularTotal();

    /* ── Agregar fila nueva ── */
    btnAgregar.addEventListener('click', () => {
        const fila = document.createElement('tr');
        fila.className = 'fila-gasto';
        fila.style.borderBottom = '1px solid var(--color-borde)';
        fila.innerHTML = `
            <td style="padding:0.8rem;">
                <input type="text" name="descripcion[]" placeholder="Ej: Reparación ascensor" style="${estiloInput}">
            </td>
            <td style="padding:0.8rem;">
                <input type="number" name="monto[]" step="0.01" placeholder="0.00" class="entrada-monto" style="${estiloInput}">
            </td>
            <td style="padding:0.8rem;text-align:center;">
                <button type="button" class="btn-eliminar-fila"
                    style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:1.3rem;line-height:1;"
                    title="Eliminar">&times;</button>
            </td>
        `;
        cuerpoGastos.appendChild(fila);
        vincularEliminar(fila);
    });
</script>
@endsection
