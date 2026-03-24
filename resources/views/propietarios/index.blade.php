@extends('layouts.plantilla')

@section('titulo', 'Gestión de Propietarios')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Directorio de Propietarios</h1>
            <p style="color: var(--color-texto-secundario);">Gestione la información de contacto y legal de los residentes.</p>
        </div>
        <a href="{{ route('propietarios.create') }}" class="boton boton-primario">+ Nuevo Propietario</a>
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
        <form action="{{ route('propietarios.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, apellido o cédula..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('propietarios.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($propietarios->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay propietarios registrados. <a href="{{ route('propietarios.create') }}" style="color: var(--color-acentuar);">Registre el primero</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">Nombre</th>
                        <th style="padding: 1rem;">Cédula / ID</th>
                        <th style="padding: 1rem;">Inmuebles Vinculados</th>
                        <th style="padding: 1rem;">Deuda Acumulada</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propietarios as $propietario)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem;">
                            <strong>{{ $propietario->nombre }} {{ $propietario->apellido }}</strong><br>
                            <small style="color: var(--color-texto-secundario);">{{ $propietario->email }}</small>
                        </td>
                        <td style="padding: 1rem;">
                            {{ $propietario->cedula }}<br>
                            <small style="color: var(--color-texto-secundario);">{{ $propietario->telefono ?? '—' }}</small>
                        </td>
                        <td style="padding: 1rem;">
                            @if($propietario->apartamentos->isEmpty())
                                <span style="color: var(--color-texto-secundario);">Ninguno</span>
                            @else
                                @foreach($propietario->apartamentos as $apto)
                                    <span style="display: inline-block; background: var(--color-superficie); border: 1px solid var(--color-borde); border-radius: 4px; padding: 0.2rem 0.5rem; font-size: 0.8em; margin-right: 0.3rem; margin-bottom: 0.3rem;">
                                        {{ $apto->numero }} (T{{ $apto->torre }})
                                    </span>
                                @endforeach
                            @endif
                        </td>
                        <td style="padding: 1rem; font-weight: bold; color: {{ $propietario->apartamentos->sum('deuda_actual') > 0 ? '#c62828' : 'var(--color-texto-secundario)' }};">
                            $ {{ number_format($propietario->apartamentos->sum('deuda_actual'), 2) }}
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                            <a href="{{ route('propietarios.edit', $propietario) }}" class="boton" style="background: none; color: var(--color-acentuar);">Editar</a>
                            <form action="{{ route('propietarios.destroy', $propietario) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar a {{ $propietario->nombre }} {{ $propietario->apellido }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="boton" style="background: none; color: #dc3545;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
