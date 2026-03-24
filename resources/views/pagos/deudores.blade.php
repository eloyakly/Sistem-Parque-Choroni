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
                                <a href="{{ route('pagos.create', ['apartamento_id' => $deudor->id]) }}" class="boton boton-primario" style="background: var(--color-secundario);">
                                    Registrar Pago
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
