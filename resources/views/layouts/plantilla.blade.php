<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Parque Choroni - @yield('titulo')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-tema="claro">
    <div class="contenedor-principal">
        @include('components.navegacion_lateral')
        
        <div class="contenido-derecha">
            @include('components.navegacion_superior')
            
            <main>
                @yield('contenido')
                
                @if(session('success'))
                    <div class="tarjeta" style="background-color: #d1e7dd; color: #0f5132; margin-top: 1rem; border-color: #badbcc;">
                        {{ session('success') }}
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        // Lógica simple de cambio de tema si app.js no carga o para rapidez
        const botonTema = document.getElementById('boton-tema');
        const cuerpo = document.body;

        // Comprobar preferencia guardada
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
