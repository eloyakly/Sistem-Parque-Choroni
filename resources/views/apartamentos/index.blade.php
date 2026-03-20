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

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Torre</th>
                    <th style="padding: 1rem;">Inmueble</th>
                    <th style="padding: 1rem;">Piso</th>
                    <th style="padding: 1rem;">Propietario</th>
                    <th style="padding: 1rem;">Estado</th>
                    <th style="padding: 1rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Torre A</td>
                    <td style="padding: 1rem;">A-101</td>
                    <td style="padding: 1rem;">1</td>
                    <td style="padding: 1rem;">Juan Pérez</td>
                    <td style="padding: 1rem;"><span style="padding: 0.2rem 0.6rem; background: #e3f2fd; color: #0d47a1; border-radius: 4px; font-size: 0.8rem;">Al día</span></td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Editar</button>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Torre B</td>
                    <td style="padding: 1rem;">B-205</td>
                    <td style="padding: 1rem;">2</td>
                    <td style="padding: 1rem;">María García</td>
                    <td style="padding: 1rem;"><span style="padding: 0.2rem 0.6rem; background: #fff3e0; color: #e65100; border-radius: 4px; font-size: 0.8rem;">Pendiente</span></td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Editar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
