@extends('layouts.plantilla')

@section('titulo', 'Editar Gastos del Mes')

@section('contenido')
<div class="tarjeta">
    <h1>Editar Gastos de Mensualidad</h1>
    <p style="color: var(--color-texto-secundario);">Ajuste los gastos comunes aplcables. Solo es posible editar si los recibos del mes no se han procesado.</p>

    @if($errors->any())
        <div style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 8px; padding: 1rem; margin-top: 1rem;">
            <strong>Corrija los siguientes errores:</strong>
            <ul style="margin-top: 0.5rem; padding-left: 1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 8px; padding: 1rem; margin-top: 1rem;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('gastos-mensuales.update', $gastoMes->id) }}" method="POST" style="margin-top: 2rem;">
        @csrf
        @method('PUT')

        {{-- ── Cabecera ── --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label style="font-weight: 500;">Mes Correspondiente</label>
                <input type="month" name="mes_anio" value="{{ old('mes_anio', $gastoMes->mes_anio) }}"
                    style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
            </div>
        </div>

        {{-- ── Tabla de Conceptos ── --}}
        <div style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">Detalle de Gastos Generales</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 0.8rem;">Concepto / Descripción</th>
                        <th style="padding: 0.8rem; width: 160px;">Monto ($)</th>
                        <th style="padding: 0.8rem; width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="cuerpo-gastos">
                    @if(old('descripcion'))
                        @foreach(old('descripcion') as $index => $descripcion)
                        <tr class="fila-gasto" style="border-bottom: 1px solid var(--color-borde);">
                            <td style="padding: 0.8rem;">
                                <input type="text" name="descripcion[]" value="{{ $descripcion }}"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;" required>
                            </td>
                            <td style="padding: 0.8rem;">
                                <input type="number" name="monto[]" step="0.01" value="{{ old('monto')[$index] }}" class="entrada-monto"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;" required>
                            </td>
                            <td style="padding: 0.8rem; text-align: center;">
                                <button type="button" class="btn-eliminar-fila"
                                    style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.3rem; line-height: 1;" title="Eliminar fila">&times;</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        @foreach($gastoMes->detalles as $detalle)
                        <tr class="fila-gasto" style="border-bottom: 1px solid var(--color-borde);">
                            <td style="padding: 0.8rem;">
                                <input type="text" name="descripcion[]" value="{{ $detalle->descripcion }}"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;" required>
                            </td>
                            <td style="padding: 0.8rem;">
                                <input type="number" name="monto[]" step="0.01" value="{{ $detalle->monto }}" class="entrada-monto"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-borde); border-radius: 4px; background: transparent; color: inherit;" required>
                            </td>
                            <td style="padding: 0.8rem; text-align: center;">
                                <button type="button" class="btn-eliminar-fila"
                                    style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.3rem; line-height: 1;" title="Eliminar fila">&times;</button>
                            </td>
                        </tr>
                        @endforeach
                    @endif
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
            <span id="total-mes" style="font-size: 1.5rem; font-weight: bold; color: var(--color-acentuar);">$ {{ number_format($gastoMes->total_gastos, 2) }}</span>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="boton boton-primario">Actualizar Gastos del Mes</button>
            <a href="{{ route('gastos-mensuales.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
        </div>
    </form>
</div>

<script>
    const cuerpoGastos  = document.getElementById('cuerpo-gastos');
    const totalMes      = document.getElementById('total-mes');
    const btnAgregar    = document.getElementById('boton-agregar-gasto');

    const estiloInput   = 'width:100%;padding:0.5rem;border:1px solid var(--color-borde);border-radius:4px;background:transparent;color:inherit;';

    function calcularTotal() {
        const entradas = cuerpoGastos.querySelectorAll('.entrada-monto');
        let total = 0;
        entradas.forEach(e => total += parseFloat(e.value) || 0);
        totalMes.textContent = '$ ' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
    }

    function adjuntarEventosEliminar() {
        cuerpoGastos.querySelectorAll('.btn-eliminar-fila').forEach(btn => {
            btn.onclick = function() {
                if(cuerpoGastos.querySelectorAll('.fila-gasto').length > 1) {
                    this.closest('tr').remove();
                    calcularTotal();
                } else {
                    alert('Debe haber al menos un concepto de gasto.');
                }
            };
        });
    }

    cuerpoGastos.addEventListener('input', function(e) {
        if(e.target.classList.contains('entrada-monto')) {
            calcularTotal();
        }
    });
    
    adjuntarEventosEliminar();
    calcularTotal();

    btnAgregar.addEventListener('click', () => {
        const fila = document.createElement('tr');
        fila.className = 'fila-gasto';
        fila.style.borderBottom = '1px solid var(--color-borde)';
        fila.innerHTML = `
            <td style="padding:0.8rem;">
                <input type="text" name="descripcion[]" placeholder="Ej: Nuevo concepto" style="${estiloInput}" required>
            </td>
            <td style="padding:0.8rem;">
                <input type="number" name="monto[]" step="0.01" placeholder="0.00" class="entrada-monto" style="${estiloInput}" required>
            </td>
            <td style="padding:0.8rem;text-align:center;">
                <button type="button" class="btn-eliminar-fila"
                    style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:1.3rem;line-height:1;"
                    title="Eliminar fila">&times;</button>
            </td>
        `;
        cuerpoGastos.appendChild(fila);
        adjuntarEventosEliminar();
    });
</script>
@endsection
