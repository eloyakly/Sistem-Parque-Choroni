@extends('layouts.plantilla')

@section('titulo', 'Editar Apartamento')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar Inmueble</h1>
        <p style="color: var(--color-texto-secundario);">Modifique los datos del apartamento.</p>

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

        <form action="{{ route('apartamentos.update', $apartamento) }}" method="POST" style="margin-top: 2rem;">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Torre / Bloque</label>
                    <select name="torre" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);" required>
                        <option value="">— Seleccione una torre —</option>
                        <option value="Torre 1" {{ old('torre', $apartamento->torre) == 'Torre 1' ? 'selected' : '' }}>Torre 1</option>
                        <option value="Torre 2" {{ old('torre', $apartamento->torre) == 'Torre 2' ? 'selected' : '' }}>Torre 2</option>
                        <option value="Torre 3" {{ old('torre', $apartamento->torre) == 'Torre 3' ? 'selected' : '' }}>Torre 3</option>
                        <option value="Torre 4" {{ old('torre', $apartamento->torre) == 'Torre 4' ? 'selected' : '' }}>Torre 4</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Código / Número de Inmueble</label>
                    <input type="text" name="numero" value="{{ old('numero', $apartamento->numero) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Tipo de Inmueble</label>
                    <select name="tipo_apartamento_id"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">— Seleccione un tipo —</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_apartamento_id', $apartamento->tipo_apartamento_id) == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Propietario Asignado</label>
                    <select name="propietario_id"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">— Seleccione un propietario —</option>
                        @foreach($propietarios as $propietario)
                            <option value="{{ $propietario->id }}" {{ old('propietario_id', $apartamento->propietario_id) == $propietario->id ? 'selected' : '' }}>
                                {{ $propietario->apellido }}, {{ $propietario->nombre }} ({{ $propietario->cedula }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Deuda Actual</label>
                    <input type="number" name="deuda_actual" value="{{ old('deuda_actual', $apartamento->deuda_actual) }}" step="0.01" min="0"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Guardar Cambios</button>
                <a href="{{ route('apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
