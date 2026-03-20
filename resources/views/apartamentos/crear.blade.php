@extends('layouts.plantilla')

@section('titulo', 'Registrar Nuevo Apartamento')

@section('contenido')
    <div class="tarjeta">
        <h1>Nuevo Inmueble</h1>
        <p style="color: var(--color-texto-secundario);">Complete el formulario para dar de alta una nueva unidad.</p>
        
        <form action="#" method="POST" style="margin-top: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Torre / Bloque</label>
                    <input type="text" placeholder="Ej: Torre A" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Código / Número de Inmueble</label>
                    <input type="text" placeholder="Ej: A-101" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Piso</label>
                    <input type="number" placeholder="Ej: 1" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Tipo de Inmueble</label>
                    <select style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option>Seleccione...</option>
                        <option>Penthouse</option>
                        <option>Estudio</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Propietario Asignado</label>
                    <select style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option>Seleccione...</option>
                        <option>Juan Pérez</option>
                        <option>María García</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Guardar Registro</button>
                <a href="{{ route('apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
