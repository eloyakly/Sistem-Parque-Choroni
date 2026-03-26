<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistem Parque Choroni</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos -->
    @vite(['resources/css/app.css'])

    <style>
        .login-contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--color-fondo);
        }

        .login-caja {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--color-acentuar);
        }
    </style>
</head>

<body data-tema="claro">

    <div class="login-contenedor">
        <div class="login-caja tarjeta">
            <div class="login-logo">
                <img src="{{ asset('logo.png') }}" alt="Parque Choroni" style="max-height: 80px; width: auto; margin-bottom: 0.5rem;">
                <div style="font-size: 1.8rem; font-weight: bold; color: var(--color-acentuar);">Parque Choroni</div>
            </div>

            <h2 style="text-align: center; margin-bottom: 0.5rem;">Bienvenido</h2>
            <p style="text-align: center; color: var(--color-texto-secundario); margin-bottom: 2rem;">Ingrese sus
                credenciales para continuar</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500;">Correo Electrónico</label>
                        <input type="email" placeholder="admin@parquechoroni.com" name="email"
                            style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500;">Contraseña</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" id="campo-clave" placeholder="••••••••" name="clave"
                                style="padding: 0.8rem; padding-right: 2.5rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto); width: 100%;">
                            <button type="button" id="toggle-clave"
                                style="position: absolute; right: 0.8rem; background: none; border: none; cursor: pointer; color: var(--color-texto-secundario); display: flex; align-items: center; justify-content: center; padding: 0;">
                                <svg id="icono-ojo" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="boton boton-primario" style="padding: 1rem; margin-top: 1rem;">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div style="margin-top: 2rem; text-align: center;">
                <button id="boton-tema" class="boton"
                    style="background: var(--color-acentuar-suave); color: var(--color-acentuar); width: 100%;">
                    🌓 Cambiar Tema
                </button>
            </div>
        </div>


    </div>
    <footer
        style="padding-top: 1.5rem; text-align: center; border-top: 1px solid var(--color-borde); color: var(--color-texto-secundario); font-size: 0.95rem;">
        <p>Desarrollado por <strong>Easistem</strong>. Derechos reservados &copy; 2026.</p>
        <p style="margin-top: 0.8rem;">
            <a href="mailto:eloyalimalaveakly@gmail.com" class="boton"
                style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-superficie); border: 1px solid var(--color-borde); text-decoration: none; color: var(--color-texto);">
                ✉️ Contacto: eloyalimalaveakly@gmail.com
            </a>
        </p>
    </footer>
    <script>
        const botonTema = document.getElementById('boton-tema');
        const cuerpo = document.body;
        const temaGuardado = localStorage.getItem('tema') || 'claro';
        cuerpo.setAttribute('data-tema', temaGuardado);

        botonTema.addEventListener('click', () => {
            const temaActual = cuerpo.getAttribute('data-tema');
            const nuevoTema = temaActual === 'claro' ? 'oscuro' : 'claro';
            cuerpo.setAttribute('data-tema', nuevoTema);
            localStorage.setItem('tema', nuevoTema);
        });

        // Lógica de visibilidad de contraseña
        const campoClave = document.getElementById('campo-clave');
        const botonToggle = document.getElementById('toggle-clave');
        const iconoOjo = document.getElementById('icono-ojo');

        const ojoAbierto = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
        const ojoCerrado = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        `;

        botonToggle.addEventListener('click', () => {
            const esPassword = campoClave.type === 'password';
            campoClave.type = esPassword ? 'text' : 'password';
            iconoOjo.innerHTML = esPassword ? ojoCerrado : ojoAbierto;
        });
    </script>
    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @elseif (isset($error))
        <script>
            alert('{{ $error }}');
        </script>
    @endif
    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

</body>

</html>
