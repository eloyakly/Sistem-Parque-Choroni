@extends('layouts.plantilla')

@section('titulo', 'Configuración de Inmuebles')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Tipos de Inmueble</h1>
            <p style="color: var(--color-texto-secundario);">Defina las categorías de apartamentos o casas locales.</p>
        </div>
        <a href="{{ route('tipos-apartamentos.create') }}" class="boton boton-primario">+ Nuevo Tipo</a>
    </div>

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Nombre del Tipo</th>
                    <th style="padding: 1rem;">Descripción</th>
                    <th style="padding: 1rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Penthouse</td>
                    <td style="padding: 1rem;">Apartamento de lujo en último piso</td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Editar</button>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Estudio</td>
                    <td style="padding: 1rem;">Monoambiente para 1-2 personas</td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Editar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
