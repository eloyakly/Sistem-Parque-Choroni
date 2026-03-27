<aside class="barra-lateral">
    <div class="barra-lateral-encabezado" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1.5rem 1rem;">
        <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 12px; padding: 5px;">
        <span style="font-size: 1.2rem; font-weight: 700;">Parque Choroní</span>
    </div>
    <nav>
        <a href="{{ url('/inicio') }}" class="item-menu {{ Request::is('inicio*') ? 'activo' : '' }}">
            <span>🏠 Inicio</span>
        </a>
        <a href="{{ url('/propietarios') }}" class="item-menu {{ Request::is('propietarios*') ? 'activo' : '' }}">
            <span>👤 Propietarios</span>
        </a>
        <a href="{{ url('/apartamentos') }}" class="item-menu {{ Request::is('apartamentos*') ? 'activo' : '' }}">
            <span>🏢 Apartamentos</span>
        </a>
        <a href="{{ url('/tipos-apartamentos') }}" class="item-menu {{ Request::is('tipos-apartamentos*') ? 'activo' : '' }}">
            <span>📋 Tipos de Inmueble</span>
        </a>
        <a href="{{ url('/gastos-mensuales') }}" class="item-menu {{ Request::is('gastos-mensuales*') ? 'activo' : '' }}">
            <span>💰 Gastos del Mes</span>
        </a>
        <a href="{{ url('/recibos') }}" class="item-menu {{ Request::is('recibos*') ? 'activo' : '' }}">
            <span>📄 Recibos</span>
        </a>
        <a href="{{ url('/pagos') }}" class="item-menu {{ Request::is('pagos') || Request::is('pagos/create*') ? 'activo' : '' }}">
            <span>💳 Pagos</span>
        </a>
        <a href="{{ route('pagos.deudores') }}" class="item-menu {{ Request::is('deudores*') ? 'activo' : '' }}">
            <span>⚠️ Deudores</span>
        </a>
    </nav>
</aside>
