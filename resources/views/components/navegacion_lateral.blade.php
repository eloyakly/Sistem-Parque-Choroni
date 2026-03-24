<aside class="barra-lateral">
    <div class="barra-lateral-encabezado">
        Parque Choroni
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
        <a href="{{ url('/facturas') }}" class="item-menu {{ Request::is('facturas*') ? 'activo' : '' }}">
            <span>📄 Facturas</span>
        </a>
        <a href="{{ url('/pagos') }}" class="item-menu {{ Request::is('pagos') || Request::is('pagos/create*') ? 'activo' : '' }}">
            <span>💳 Pagos</span>
        </a>
        <a href="{{ route('pagos.deudores') }}" class="item-menu {{ Request::is('deudores*') ? 'activo' : '' }}">
            <span>⚠️ Deudores</span>
        </a>
    </nav>
</aside>
