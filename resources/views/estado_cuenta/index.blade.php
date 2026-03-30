@extends('layouts.plantilla')

@section('titulo', 'Estado de Cuenta')

@section('contenido')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: var(--color-texto); font-size: 2rem; margin: 0;">Estado de Cuenta</h1>
            <p style="color: var(--color-texto-secundario); margin-top: 0.5rem;">Consulte la deuda por torre y mes de forma confidencial sin exponer a los propietarios.</p>
        </div>
        <div>
            <button type="button" onclick="imprimirEstadoCuenta()" class="boton boton-primario" style="background: var(--color-texto); display: flex; align-items: center; gap: 0.5rem;" {{ (!$torreSeleccionada || !$mesSeleccionado) ? 'disabled' : '' }}>
                🖨️ Imprimir PDF
            </button>
        </div>
    </div>

    @if(session('error'))
        <div class="tarjeta" style="background-color: #f8d7da; color: #842029; border-color: #f5c2c7; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="tarjeta" style="margin-bottom: 2rem;">
        <form id="formEstadoCuenta" action="{{ route('estado_cuenta.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
                
                <!-- Filtro Torre -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-texto-secundario);">Seleccione Torre</label>
                    <select name="torre" required style="padding: 0.7rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">-- Escoja una Torre --</option>
                        @foreach($torres as $torre)
                            <option value="{{ $torre }}" {{ $torreSeleccionada == $torre ? 'selected' : '' }}>Torre {{ $torre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Mes -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-texto-secundario);">Seleccione Mes</label>
                    <select name="mes" required style="padding: 0.7rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">-- Escoja un Periodo --</option>
                        @foreach($meses as $mes)
                            @php
                                $fecha = \Carbon\Carbon::createFromFormat('Y-m', $mes);
                            @endphp
                            <option value="{{ $mes }}" {{ $mesSeleccionado == $mes ? 'selected' : '' }}>{{ ucfirst($fecha->translatedFormat('F Y')) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Acciones -->
                <div style="display: flex; gap: 0.5rem;">
                    <button class="boton boton-primario" type="submit" style="padding: 0.7rem 1.5rem; width: 100%;">Cargar Estado</button>
                </div>
            </div>
        </form>
    </div>

    @if($torreSeleccionada && $mesSeleccionado)
        <div class="tarjeta" style="overflow-x: auto;">
            @if($apartamentos->isEmpty())
                <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                    No existen apartamentos registrados en esta torre seleccionada.
                </p>
            @else
                <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-borde);">
                    <h3 style="margin: 0; color: var(--color-texto);">Torre {{ $torreSeleccionada }}</h3>
                    <p style="margin: 0; color: var(--color-texto-secundario);">Periodo: {{ \Carbon\Carbon::createFromFormat('Y-m', $mesSeleccionado)->translatedFormat('F Y') }}</p>
                </div>

                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-borde); color: var(--color-texto-secundario);">
                            <th style="padding: 1rem;">N° Inmueble</th>
                            <th style="padding: 1rem;">Torre</th>
                            <th style="padding: 1rem; text-align: right;">Cargado / Debe (Mes)</th>
                            <th style="padding: 1rem; text-align: right;">Deuda Total Acumulada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($apartamentos as $apto)
                            <tr style="border-bottom: 1px solid var(--color-borde); transition: background 0.3s;">
                                <td style="padding: 1rem;">
                                    <strong>{{ $apto->numero }}</strong>
                                </td>
                                <td style="padding: 1rem;">
                                    {{ $apto->torre }}
                                </td>
                                <td style="padding: 1rem; text-align: right; color: {{ $apto->deuda_mes > 0 ? '#d32f2f' : 'var(--color-texto)' }};">
                                    $ {{ number_format($apto->deuda_mes, 2) }}
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: bold; color: {{ $apto->deuda_actual > 0 ? '#c62828' : '#2e7d32' }}; fontsize: 1.1rem;">
                                    $ {{ number_format($apto->deuda_actual, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @else
        <div class="tarjeta" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--color-borde);">📋</div>
            <h3 style="margin: 0; color: var(--color-texto-secundario);">Seleccione los filtros indicados</h3>
            <p style="color: var(--color-texto-secundario);">Escoja una Torre y un Mes para visualizar los estados de cuenta de ese periodo.</p>
        </div>
    @endif

    <script>
        function imprimirEstadoCuenta() {
            const torre = document.querySelector('select[name="torre"]').value;
            const mes = document.querySelector('select[name="mes"]').value;
            
            if(!torre || !mes) {
                alert("Por favor cargue una torre y mes válidos primero.");
                return;
            }

            const url = `{{ route('estado_cuenta.imprimir') }}?torre=${encodeURIComponent(torre)}&mes=${encodeURIComponent(mes)}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
