@extends('layouts.plantilla')

@section('titulo', 'Gestión de Apartamentos')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Lista de Apartamentos</h1>
            <p style="color: var(--color-texto-secundario);">Administre las unidades habitacionales del condominio.</p>
        </div>
        <a href="{{ route('apartamentos.create') }}" class="boton boton-primario">+ Nuevo Apartamento</a>
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
        <form action="{{ route('apartamentos.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por número, torre o titular..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($apartamentos->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay apartamentos registrados. <a href="{{ route('apartamentos.create') }}" style="color: var(--color-acentuar);">Registre el primero</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">Torre</th>
                        <th style="padding: 1rem;">Inmueble</th>
                        <th style="padding: 1rem;">Tipo</th>
                        <th style="padding: 1rem;">Propietario</th>
                        <th style="padding: 1rem;">Deuda Actual</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apartamentos as $apartamento)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem;">{{ $apartamento->torre }}</td>
                        <td style="padding: 1rem;">{{ $apartamento->numero }}</td>
                        <td style="padding: 1rem;">{{ $apartamento->tipo->nombre ?? '—' }}</td>
                        <td style="padding: 1rem;">
                            {{ $apartamento->propietario->nombre ?? '' }} {{ $apartamento->propietario->apellido ?? '—' }}
                        </td>
                        <td style="padding: 1rem;">
                            @if($apartamento->deuda_actual > 0)
                                <span style="padding: 0.2rem 0.6rem; background: #fff3e0; color: #e65100; border-radius: 4px; font-size: 0.85rem;">
                                    ${{ number_format($apartamento->deuda_actual, 2) }}
                                </span>
                            @elseif($apartamento->deuda_actual < 0)
                                <span style="padding: 0.2rem 0.6rem; background: #e8f5e9; color: #2e7d32; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">
                                    Saldo a favor: ${{ number_format(abs($apartamento->deuda_actual), 2) }}
                                </span>
                            @else
                                <span style="padding: 0.2rem 0.6rem; background: #e3f2fd; color: #0d47a1; border-radius: 4px; font-size: 0.85rem;">Al día</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                            <a href="{{ route('apartamentos.edit', $apartamento) }}" class="boton" style="background: none; color: var(--color-acentuar);">Editar</a>
                            <form action="{{ route('apartamentos.destroy', $apartamento) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar el apartamento {{ $apartamento->numero }}?')">
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
