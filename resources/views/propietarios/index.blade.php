@extends('layouts.plantilla')

@section('titulo', 'Gestión de Propietarios')

@section('contenido')
    <div class="tarjeta" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Directorio de Propietarios</h1>
            <p style="color: var(--color-texto-secundario);">Gestione la información de contacto y legal de los residentes.</p>
        </div>
        <a href="{{ route('propietarios.create') }}" class="boton boton-primario">+ Nuevo Propietario</a>
    </div>

    <div class="tarjeta">
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--color-borde);">
                    <th style="padding: 1rem;">Nombre</th>
                    <th style="padding: 1rem;">Cédula / ID</th>
                    <th style="padding: 1rem;">Teléfono</th>
                    <th style="padding: 1rem;">Correo</th>
                    <th style="padding: 1rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--color-borde);">
                    <td style="padding: 1rem;">Juan Pérez</td>
                    <td style="padding: 1rem;">V-12.345.678</td>
                    <td style="padding: 1rem;">0414-1234567</td>
                    <td style="padding: 1rem;">juan.perez@email.com</td>
                    <td style="padding: 1rem;">
                        <button class="boton" style="background: none; color: var(--color-acentuar);">Ver Ficha</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
