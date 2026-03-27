@extends('layouts.plantilla')

@section('titulo', 'Gestión de Gastos Mensuales')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Gastos Generales por Mes</h1>
            <p style="color: var(--color-texto-secundario);">Cargue los gastos comunes que se aplicarán a todos los recibos del mes.</p>
        </div>
        <a href="{{ route('gastos-mensuales.create') }}" class="boton boton-primario">+ Cargar Gastos del Mes</a>
    </div>

    @if(session('exito'))
        <div class="tarjeta" style="background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;">
            ✅ {{ session('exito') }}
        </div>
    @endif

    @if(session('error'))
        <div class="tarjeta" style="background-color: #f8d7da; color: #842029; border-color: #f5c2c7;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="tarjeta" style="margin-bottom: 1rem;">
        <form action="{{ route('gastos-mensuales.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por mes, año o concepto del gasto..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('gastos-mensuales.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($gastosMes->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay registros de gastos mensuales. <a href="{{ route('gastos-mensuales.create') }}" style="color: var(--color-acentuar);">Registre el primero</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">Mes / Año</th>
                        <th style="padding: 1rem;">Total Gastos ($)</th>
                        <th style="padding: 1rem;">Estado</th>
                        <th style="padding: 1rem;">Conceptos (#)</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gastosMes as $gasto)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($gasto->mes_anio)->translatedFormat('F Y') }}</td>
                        <td style="padding: 1rem;">$ {{ number_format($gasto->total_gastos, 2) }}</td>
                        <td style="padding: 1rem;">
                            @if($gasto->procesado)
                                <span style="padding: 0.2rem 0.6rem; background: #c8e6c9; color: #2e7d32; border-radius: 4px; font-size: 0.85rem;">Procesado</span>
                            @else
                                <span style="padding: 0.2rem 0.6rem; background: #fff3e0; color: #e65100; border-radius: 4px; font-size: 0.85rem;">Borrador</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; color: var(--color-texto-secundario);">
                                @foreach($gasto->detalles->take(2) as $detalle)
                                    <li>{{ $detalle->descripcion }}</li>
                                @endforeach
                                @if($gasto->detalles->count() > 2)
                                    <li><em>... y {{ $gasto->detalles->count() - 2 }} más</em></li>
                                @endif
                            </ul>
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button class="boton" onclick="verDetalles({{ $gasto->id }}, '{{ \Carbon\Carbon::parse($gasto->mes_anio)->translatedFormat('F Y') }}', {{ $gasto->detalles->toJson() }})" style="background: none; color: var(--color-acentuar);">Ver</button>
                            @if(!$gasto->procesado)
                                <a href="{{ route('gastos-mensuales.edit', $gasto->id) }}" class="boton" style="background: none; color: var(--color-acentuar);">Editar</a>
                                <form action="{{ route('gastos-mensuales.destroy', $gasto->id) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar el registro de gastos para el mes seleccionado? Esta acción no se puede deshacer.')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton" style="background: none; color: #dc3545;">Eliminar</button>
                                </form>
                            @else
                                <span class="boton" style="background: none; color: var(--color-texto-secundario); cursor: not-allowed; opacity: 0.6;" title="Ya no se puede modificar">Bloqueado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal para ver detalles de gastos -->
    <dialog id="modalDetallesGasto" style="padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-borde); background: var(--color-superficie); box-shadow: 0 10px 30px rgba(0,0,0,0.3); width: 90%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-borde); padding-bottom: 1rem;">
            <h3 style="margin: 0; color: var(--color-texto);">Desglose de Gastos - <span id="modalMesTitulo"></span></h3>
            <button class="boton" onclick="document.getElementById('modalDetallesGasto').close()" style="background: #e74c3c; color: white; padding: 0.4rem 0.8rem; border-radius: 6px; border: none; cursor: pointer;">Cerrar</button>
        </div>
        
        <div style="max-height: 60vh; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde); background: var(--color-acentuar-suave);">
                        <th style="padding: 0.8rem;">Concepto</th>
                        <th style="padding: 0.8rem; text-align: right;">Monto ($)</th>
                    </tr>
                </thead>
                <tbody id="tablaDetallesCuerpo">
                    <!-- Se puebla con JS -->
                </tbody>
                <tfoot>
                    <tr style="border-top: 2px solid var(--color-borde); font-weight: bold;">
                        <td style="padding: 1rem;">TOTAL GENERAL</td>
                        <td style="padding: 1rem; text-align: right; color: var(--color-acentuar);" id="modalTotalGasto">$ 0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </dialog>

    <script>
        function verDetalles(id, mesAnio, detalles) {
            document.getElementById('modalMesTitulo').textContent = mesAnio;
            const cuerpo = document.getElementById('tablaDetallesCuerpo');
            cuerpo.innerHTML = '';
            
            let total = 0;
            detalles.forEach(d => {
                const monto = parseFloat(d.monto);
                total += monto;
                const fila = document.createElement('tr');
                fila.style.borderBottom = '1px solid var(--color-borde)';
                fila.innerHTML = `
                    <td style="padding: 0.8rem;">${d.descripcion}</td>
                    <td style="padding: 0.8rem; text-align: right; font-weight: 500; color: ${monto < 0 ? '#c62828' : 'inherit'}">
                        $ ${monto.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                    </td>
                `;
                cuerpo.appendChild(fila);
            });
            
            document.getElementById('modalTotalGasto').textContent = `$ ${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            document.getElementById('modalDetallesGasto').showModal();
        }
    </script>
@endsection
