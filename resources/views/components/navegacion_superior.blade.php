<header class="barra-superior">
    <div class="barra-superior-izq">
        <h2 id="titulo-pagina">@yield('titulo', 'Panel de Control')</h2>
    </div>
    <div class="barra-superior-der" style="display: flex; align-items: center; gap: 1rem;">

        {{-- ── ESTADO DE CORREOS ── --}}
        @php
            $pctCorreos = $limiteCorreosDiario > 0 ? round(($correosEnviadosHoy / $limiteCorreosDiario) * 100) : 0;
            $colorCorreos = $pctCorreos >= 90 ? '#e74c3c' : ($pctCorreos >= 70 ? '#e67e22' : '#27ae60');
            $cupoRestante = $limiteCorreosDiario - $correosEnviadosHoy;
        @endphp
        <a href="{{ route('log-correos.index') }}"
           title="Ver log de correos"
           style="display: flex; align-items: center; gap: 0.6rem; text-decoration: none;
                  background: var(--color-superficie); border: 1px solid var(--color-borde);
                  border-radius: 999px; padding: 0.35rem 0.9rem;
                  font-size: 0.8rem; color: var(--color-texto); cursor: pointer;
                  transition: box-shadow 0.2s;"
           onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.12)'"
           onmouseout="this.style.boxShadow='none'">

            {{-- Icono + conteo --}}
            <span style="font-size: 1rem;">📧</span>
            <span style="font-weight: 600; color: {{ $colorCorreos }};">
                {{ $correosEnviadosHoy }}<span style="font-weight: 400; color: var(--color-texto-secundario);">/{{ $limiteCorreosDiario }}</span>
            </span>

            {{-- Mini barra de progreso --}}
            <div style="width: 50px; background: var(--color-borde); border-radius: 999px; height: 5px; overflow: hidden;">
                <div style="width: {{ $pctCorreos }}%; height: 100%; background: {{ $colorCorreos }}; border-radius: 999px;"></div>
            </div>

            {{-- Badge de pendientes (solo si hay) --}}
            @if($correosPendientesGlobal > 0)
                <span style="background: #e67e22; color: white; border-radius: 999px;
                             padding: 1px 7px; font-size: 0.7rem; font-weight: 700;
                             line-height: 1.5;">
                    ⏳ {{ $correosPendientesGlobal }}
                </span>
            @endif
        </a>

        <button id="boton-tema" class="boton" style="background: var(--color-acentuar-suave); color: var(--color-acentuar);">
            🌓 Cambiar Tema
        </button>
        <div class="usuario-perfil" style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 35px; height: 35px; background: var(--color-acentuar); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                A
            </div>
            <span>Administrador</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="boton" style="padding: 0.4rem 0.8rem; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Salir
            </button>
        </form>
    </div>
</header>

