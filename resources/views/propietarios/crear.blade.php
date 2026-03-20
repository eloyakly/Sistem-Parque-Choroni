@extends('layouts.plantilla')

@section('titulo', 'Añadir Propietario')

@section('contenido')
    <div class="tarjeta">
        <h1>Nuevo Propietario</h1>
        <p style="color: var(--color-texto-secundario);">Registre los datos personales y de contacto.</p>
        
        <form action="#" method="POST" style="margin-top: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Nombre Completo</label>
                    <input type="text" placeholder="Ej: Juan Pérez" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Cédula / Identificación</label>
                    <input type="text" placeholder="Ej: V-12.345.678" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Teléfono</label>
                    <input type="text" placeholder="Ej: 0414-0000000" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Correo Electrónico</label>
                    <input type="email" placeholder="ejemplo@correo.com" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Registrar Propietario</button>
                <a href="{{ route('propietarios.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
