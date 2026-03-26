<header class="barra-superior">
    <div class="barra-superior-izq">
        <h2 id="titulo-pagina">@yield('titulo', 'Panel de Control')</h2>
    </div>
    <div class="barra-superior-der" style="display: flex; align-items: center; gap: 1rem;">
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
