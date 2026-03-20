@extends('layouts.plantilla')

@section('titulo', 'Registrar Cobro')

@section('contenido')
    <div class="tarjeta">
        <h1>Registrar Pago Recibido</h1>
        <p style="color: var(--color-texto-secundario);">Ingrese los detalles del pago reportado por el propietario.</p>
        
        <form action="#" method="POST" style="margin-top: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Factura a Pagar</label>
                    <select style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option>Seleccione Factura...</option>
                        <option>FAC-0002 ($115.00)</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Método de Pago</label>
                    <select style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option>Transferencia</option>
                        <option>Efectivo</option>
                        <option>Pago Móvil</option>
                        <option>Zelle</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Referencia / Comprobante</label>
                    <input type="text" placeholder="Ej: #998273" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Monto Pagado</label>
                    <input type="number" step="0.01" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="boton boton-primario">Confirmar Pago</button>
                <a href="{{ route('pagos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
