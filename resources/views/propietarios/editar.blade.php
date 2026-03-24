@extends('layouts.plantilla')

@section('titulo', 'Editar Propietario')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar Propietario</h1>
        <p style="color: var(--color-texto-secundario);">Modifique los datos del propietario.</p>

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

        <form action="{{ route('propietarios.update', $propietario) }}" method="POST" style="margin-top: 2rem;">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $propietario->nombre) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $propietario->apellido) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Cédula / Identificación</label>
                    <input type="text" name="cedula" value="{{ old('cedula', $propietario->cedula) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Teléfono <span style="color: var(--color-texto-secundario); font-weight: 400;">(opcional)</span></label>
                    <input type="text" name="telefono" value="{{ old('telefono', $propietario->telefono) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: 1 / -1;">
                    <label style="font-weight: 500;">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $propietario->email) }}"
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Guardar Cambios</button>
                <a href="{{ route('propietarios.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
