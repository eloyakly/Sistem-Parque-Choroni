@extends('layouts.plantilla')

@section('titulo', 'Cartera de Deudores')

@section('contenido')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: var(--color-texto); font-size: 2rem; margin: 0;">Cartera de Deudores</h1>
            <p style="color: var(--color-texto-secundario); margin-top: 0.5rem;">Gestione los atrasos y efectúe cobranzas directas a apartamentos en mora.</p>
        </div>
    </div>

    @if(session('exito'))
        <div class="tarjeta" style="background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;">
            {{ session('exito') }}
        </div>
    @endif
    @if(session('error'))
        <div class="tarjeta" style="background-color: #f8d7da; color: #842029; border-color: #f5c2c7;">
            {{ session('error') }}
        </div>
    @endif

    <div class="tarjeta" style="margin-bottom: 1rem;">
        <form action="{{ route('pagos.deudores') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por propietario, cédula o apartamento..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar Deudor</button>
            @if(request('buscar'))
                <a href="{{ route('pagos.deudores') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta" style="overflow-x: auto;">
        @if($deudores->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay apartamentos en situación de mora actualmente o no coincide la búsqueda. ¡Excelente trabajo de cobranzas!
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-borde); color: var(--color-texto-secundario);">
                        <th style="padding: 1rem;">Inmueble</th>
                        <th style="padding: 1rem;">Propietario / Cédula</th>
                        <th style="padding: 1rem; text-align: right;">Deuda Acumulada</th>
                        <th style="padding: 1rem; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deudores as $deudor)
                        <tr style="border-bottom: 1px solid var(--color-borde); transition: background 0.3s;">
                            <td style="padding: 1rem;">
                                <strong>{{ $deudor->numero }}</strong><br>
                                <small style="color: var(--color-texto-secundario);">Torre {{ $deudor->torre }}</small>
                            </td>
                            <td style="padding: 1rem;">
                                {{ $deudor->propietario->nombre }} {{ $deudor->propietario->apellido }}<br>
                                <small style="color: var(--color-texto-secundario);">V-{{ $deudor->propietario->cedula }}</small>
                            </td>
                            <td style="padding: 1rem; text-align: right; color: #c62828; font-weight: bold; font-size: 1.1rem;">
                                $ {{ number_format($deudor->deuda_actual, 2) }}
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <button onclick="abrirModalAbono({{ $deudor->id }}, '{{ $deudor->numero }}', '{{ $deudor->torre }}', {{ $deudor->deuda_actual }})" class="boton boton-primario" style="background: #2e7d32; border: none; padding: 0.5rem 1rem;">Abonar</button>
                                    <a href="{{ route('pagos.create') }}" class="boton" style="background: var(--color-borde); padding: 0.5rem 1rem;">Opciones</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal de Abono Inteligente -->
    <dialog id="modalAbono" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--color-borde); background: var(--color-superficie); box-shadow: 0 10px 30px rgba(0,0,0,0.15); max-width: 450px; text-align: left; margin: auto;">
        <div style="font-size: 2.5rem; text-align: center; margin-bottom: 0.5rem;">💸</div>
        <h3 style="margin-top: 0; color: var(--color-texto); text-align: center; margin-bottom: 0.5rem;">Abono Inteligente</h3>
        <p style="color: var(--color-texto-secundario); margin-bottom: 1.5rem; text-align: center; line-height: 1.4; font-size: 0.9em;" id="abono_texto">
            ...
        </p>
        
        <form action="{{ route('pagos.abonar') }}" method="POST">
            @csrf
            <input type="hidden" name="apartamento_id" id="abono_apartamento_id">
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500; font-size: 0.9em;">Monto a Pagar / Abonar ($)</label>
                    <input type="number" name="monto" id="abono_monto" step="0.01" min="0.01" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); font-size: 1.2rem; font-weight: bold; color: #2e7d32;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500; font-size: 0.9em;">Método de Pago</label>
                        <select name="metodo_pago" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                            <option value="">Seleccione...</option>
                            <option value="Transferencia">Transferencia Bancaria</option>
                            <option value="Pago Móvil">Pago Móvil</option>
                            <option value="Zelle">Zelle</option>
                            <option value="Efectivo USD">Efectivo USD</option>
                            <option value="Efectivo Bs">Efectivo Bs</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500; font-size: 0.9em;">Fecha</label>
                        <input type="date" name="fecha_pago" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                            style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500; font-size: 0.9em;">N° Referencia <span style="font-weight: normal; color: #888;">(Opcional si es efectivo)</span></label>
                    <input type="text" name="referencia" placeholder="Ej: 8091244"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                <button type="button" class="boton" onclick="document.getElementById('modalAbono').close();" style="background: var(--color-borde); color: var(--color-texto);">Cancelar</button>
                <button type="submit" class="boton boton-primario" style="background: #2e7d32; border: none;">Procesar Pago</button>
            </div>
        </form>
    </dialog>

    <script>
        function abrirModalAbono(id, numero, torre, deuda) {
            document.getElementById('abono_apartamento_id').value = id;
            document.getElementById('abono_monto').value = deuda.toFixed(2);
            document.getElementById('abono_texto').innerHTML = `Se descontará automáticamente de la deuda del <strong>Apto ${numero}</strong> (Torre ${torre}) y se saldarán proporcionalmente sus facturas más antiguas en cola.`;
            document.getElementById('modalAbono').showModal();
        }
    </script>
@endsection
