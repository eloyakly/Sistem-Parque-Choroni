<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistem Parque Choroni</title>
    
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
            <div class="login-logo">Parque Choroni</div>
            
            <h2 style="text-align: center; margin-bottom: 0.5rem;">Bienvenido</h2>
            <p style="text-align: center; color: var(--color-texto-secundario); margin-bottom: 2rem;">Ingrese sus credenciales para continuar</p>
            
            <form action="{{ url('/inicio') }}" method="GET">
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500;">Correo Electrónico</label>
                        <input type="email" placeholder="admin@parquechoroni.com" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 500;">Contraseña</label>
                        <input type="password" placeholder="••••••••" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                    </div>
                    
                    <button type="submit" class="boton boton-primario" style="padding: 1rem; margin-top: 1rem;">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button id="boton-tema" class="boton" style="background: var(--color-acentuar-suave); color: var(--color-acentuar); width: 100%;">
                    🌓 Cambiar Tema
                </button>
            </div>
        </div>
    </div>

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
    </script>
</body>
</html>
