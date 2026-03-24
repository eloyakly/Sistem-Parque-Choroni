@extends('layouts.plantilla')

@section('titulo', 'Editar Tipo de Inmueble')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar Tipo de Inmueble</h1>
        <p style="color: var(--color-texto-secundario);">Modifique los datos del tipo.</p>

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

        <form action="{{ route('tipos-apartamentos.update', $tipo) }}" method="POST" style="margin-top: 2rem;">
            @csrf
            @method('PUT')
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Nombre del Tipo</label>
                    <select name="nombre" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);" required>
                        <option value="">— Seleccione una designación —</option>
                        <option value="Estudio" {{ old('nombre', $tipo->nombre) == 'Estudio' ? 'selected' : '' }}>Estudio</option>
                        <option value="Estándar" {{ old('nombre', $tipo->nombre) == 'Estándar' ? 'selected' : '' }}>Estándar</option>
                        <option value="Penthouse" {{ old('nombre', $tipo->nombre) == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Alícuota (%)</label>
                    <input type="number" name="alicuota" value="{{ old('alicuota', $tipo->alicuota) }}" step="0.0001" min="0" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    <small style="color: var(--color-texto-secundario);">Porcentaje de participación en gastos comunes (puede ingresar todos los decimales necesarios).</small>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Guardar Cambios</button>
                <a href="{{ route('tipos-apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
