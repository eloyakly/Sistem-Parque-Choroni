@extends('layouts.plantilla')

@section('titulo', 'Nuevo Tipo de Inmueble')

@section('contenido')
    <div class="tarjeta">
        <h1>Registrar Tipo de Inmueble</h1>
        <p style="color: var(--color-texto-secundario);">Defina un nuevo tipo de unidad para el condominio.</p>

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

        <form action="{{ route('tipos-apartamentos.store') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @php $isCustom = !in_array(old('nombre'), ['', 'Estudio', 'Estándar', 'Penthouse']) && old('nombre'); @endphp
                    <label style="font-weight: 500;">Nombre del Tipo</label>
                    <select id="select-nombre" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);" required>
                        <option value="">— Seleccione una designación —</option>
                        <option value="Estudio" {{ old('nombre') == 'Estudio' ? 'selected' : '' }}>Estudio</option>
                        <option value="Estándar" {{ old('nombre') == 'Estándar' ? 'selected' : '' }}>Estándar</option>
                        <option value="Penthouse" {{ old('nombre') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                        <option value="Otro" {{ $isCustom ? 'selected' : '' }}>Otro (Especificar)</option>
                    </select>

                    <input type="text" id="input-nombre-personalizado" placeholder="Escriba el nombre personalizado" 
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); display: {{ $isCustom ? 'block' : 'none' }};">
                    
                    <input type="hidden" name="nombre" id="nombre-final" value="{{ old('nombre') }}">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Alícuota (%)</label>
                    <input type="number" name="alicuota" value="{{ old('alicuota') }}" placeholder="Ej: 0.0500" step="0.0001" min="0" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    <small style="color: var(--color-texto-secundario);">Porcentaje de participación en gastos comunes (puede ingresar todos los decimales necesarios).</small>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Crear Tipo</button>
                <a href="{{ route('tipos-apartamentos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectNombre = document.getElementById('select-nombre');
            const inputPersonalizado = document.getElementById('input-nombre-personalizado');
            const nombreFinal = document.getElementById('nombre-final');

            function actualizarNombre() {
                if (selectNombre.value === 'Otro') {
                    inputPersonalizado.style.display = 'block';
                    inputPersonalizado.required = true;
                    nombreFinal.value = inputPersonalizado.value;
                } else {
                    inputPersonalizado.style.display = 'none';
                    inputPersonalizado.required = false;
                    nombreFinal.value = selectNombre.value;
                }
            }

            selectNombre.addEventListener('change', actualizarNombre);
            inputPersonalizado.addEventListener('input', actualizarNombre);
            
            if (selectNombre.value === 'Otro') {
                inputPersonalizado.value = nombreFinal.value;
            }
            
            selectNombre.form.addEventListener('submit', function(e) {
                actualizarNombre();
                if (!nombreFinal.value.trim()) {
                    e.preventDefault();
                    alert('Por favor, ingrese el nombre del tipo de inmueble.');
                }
            });
        });
    </script>
@endsection
