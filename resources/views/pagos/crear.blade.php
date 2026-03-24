@extends('layouts.plantilla')

@section('titulo', 'Registrar Cobro')

@section('contenido')
    <div class="tarjeta">
        <h1>Registrar Pago Recibido</h1>
        <p style="color: var(--color-texto-secundario);">Ingrese los detalles del pago para ser abonado a una factura pendiente.</p>
        
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

        <form action="{{ route('pagos.store') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Facturas que deudadas -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                    <label style="font-weight: 500;">Factura a Abonar</label>
                    <select name="factura_id" id="factura_id" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="" data-saldo="0">Seleccione una Factura Pendiente...</option>
                        @foreach($facturas as $factura)
                            <option value="{{ $factura->id }}" data-saldo="{{ $factura->saldo_pendiente }}" {{ old('factura_id') == $factura->id ? 'selected' : '' }}>
                                {{ $factura->apartamento->numero }} - {{ $factura->descripcion }} (Deuda: $ {{ number_format($factura->saldo_pendiente, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Monto Pagado ($)</label>
                    <input type="number" step="0.01" min="0.01" name="monto" id="input-monto" value="{{ old('monto') }}" required
                           placeholder="Ej: 50.00"
                           style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    <small id="max-aviso" style="color: var(--color-texto-secundario);">Seleccione factura para ver abono máximo sugerido.</small>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Fecha del Pago</label>
                    <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" required
                           max="{{ date('Y-m-d') }}"
                           style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Método de Pago</label>
                    <select name="metodo_pago" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="pago móvil" {{ old('metodo_pago') == 'pago móvil' ? 'selected' : '' }}>Pago Móvil</option>
                        <option value="zelle" {{ old('metodo_pago') == 'zelle' ? 'selected' : '' }}>Zelle</option>
                    </select>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Referencia / Comprobante (Opcional)</label>
                    <input type="text" name="referencia" value="{{ old('referencia') }}" placeholder="Ej: #998273"
                           style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Confirmar Pago</button>
                <a href="{{ route('pagos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectFactura = document.getElementById('factura_id');
            const inputMonto = document.getElementById('input-monto');
            const maxAviso = document.getElementById('max-aviso');

            function sugerirMonto() {
                const opcion = selectFactura.options[selectFactura.selectedIndex];
                const saldo = parseFloat(opcion.getAttribute('data-saldo'));
                
                if (saldo && saldo > 0) {
                    inputMonto.value = saldo.toFixed(2);
                    maxAviso.innerHTML = `Monto de la factura: <strong>$ ${saldo.toFixed(2)}</strong> (Puede excederlo para generar saldo a favor).`;
                    inputMonto.setAttribute('data-base', saldo.toFixed(2));
                    inputMonto.style.borderColor = 'var(--color-borde)';
                } else {
                    inputMonto.value = '';
                    maxAviso.textContent = 'Seleccione factura para ver monto base.';
                    inputMonto.removeAttribute('data-base');
                }
            }

            selectFactura.addEventListener('change', sugerirMonto);
            
            // Si no hay valor previo escrito pero hay factura old seleccionada, sugerir.
            if(selectFactura.value && !inputMonto.value) {
                sugerirMonto();
            }

            // Validar exceso en vivo
            inputMonto.addEventListener('input', function() {
                const base = parseFloat(this.getAttribute('data-base'));
                const val = parseFloat(this.value);
                if (base && val > base) {
                    this.style.borderColor = '#2e7d32'; // Verde si paga de más (bueno)
                    maxAviso.innerHTML = `Monto base: <strong>$ ${base.toFixed(2)}</strong> <br><span style="color:#2e7d32; font-weight:bold;">¡Pago excedente! Acumulará saldo a favor en el apartamento.</span>`;
                } else if (base && val < base) {
                    this.style.borderColor = '#e65100'; // Naranja si paga parcial
                    maxAviso.innerHTML = `Monto base: <strong>$ ${base.toFixed(2)}</strong> <br><span style="color:#e65100; font-weight:bold;">Pago parcial. La factura no se saldará por completo.</span>`;
                } else {
                    this.style.borderColor = 'var(--color-borde)';
                    if(base) maxAviso.innerHTML = `Monto de la factura: <strong>$ ${base.toFixed(2)}</strong> (Puede excederlo para generar saldo a favor).`;
                }
            });
        });
    </script>
@endsection
