@extends('layouts.plantilla')

@section('titulo', 'Configuración de Inmuebles')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Tipos de Inmueble</h1>
            <p style="color: var(--color-texto-secundario);">Defina las categorías de apartamentos o casas del condominio.</p>
        </div>
        <a href="{{ route('tipos-apartamentos.create') }}" class="boton boton-primario">+ Nuevo Tipo</a>
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
        <form action="{{ route('tipos-apartamentos.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por designación..."
                style="padding: 0.6rem; border-radius: 6px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); flex: 1;">
            <button class="boton boton-primario" type="submit">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('tipos-apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="tarjeta">
        @if($tipos->isEmpty())
            <p style="text-align: center; color: var(--color-texto-secundario); padding: 2rem;">
                No hay tipos registrados. <a href="{{ route('tipos-apartamentos.create') }}" style="color: var(--color-acentuar);">Cree el primero</a>.
            </p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                        <th style="padding: 1rem;">Nombre del Tipo</th>
                        <th style="padding: 1rem;">Alícuota (%)</th>
                        <th style="padding: 1rem;">Nº Apartamentos</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $tipo)
                    <tr style="border-bottom: 1px solid var(--color-borde);">
                        <td style="padding: 1rem;">{{ $tipo->nombre }}</td>
                        <td style="padding: 1rem;">{{ number_format($tipo->alicuota, 4) }}</td>
                        <td style="padding: 1rem;">{{ $tipo->apartamentos()->count() }}</td>
                        <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                            <a href="{{ route('tipos-apartamentos.edit', $tipo) }}" class="boton" style="background: none; color: var(--color-acentuar);">Editar</a>
                            <form action="{{ route('tipos-apartamentos.destroy', $tipo) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar el tipo {{ $tipo->nombre }}?')">
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
