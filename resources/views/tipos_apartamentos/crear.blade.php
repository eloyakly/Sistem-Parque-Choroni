@extends('layouts.plantilla')

@section('titulo', 'Nuevo Tipo de Inmueble')

@section('contenido')
    <div class="tarjeta">
        <h1>Registrar Categoría</h1>
        <p style="color: var(--color-texto-secundario);">Defina un nuevo tipo de unidad para el condominio.</p>
        
        <form action="#" method="POST" style="margin-top: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Nombre del Tipo</label>
                    <input type="text" placeholder="Ej: Townhouse" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Descripción Corta</label>
                    <textarea rows="3" placeholder="Descripción de las características de este tipo..." style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); font-family: inherit;"></textarea>
                </div>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Crear Categoría</button>
                <a href="{{ route('tipos-apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
