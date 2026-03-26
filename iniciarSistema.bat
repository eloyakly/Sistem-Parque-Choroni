@echo off
title Iniciando Sistema de Condominios
color 0A

:: 1. Iniciar Servicio MySQL (Requiere ejecutar como administrador)
echo [1/4] Levantando Base de Datos...
net start MySQL80 >nul 2>&1

:: 2. Iniciar Servidor Laravel en segundo plano
echo [2/4] Iniciando Servidor PHP...
start /b php artisan serve >nul 2>&1

:: 3. Iniciar Vite para los assets (JS/CSS)
echo [3/4] Compilando Frontend con Vite...
start /b npm run dev >nul 2>&1

:: 4. Abrir el navegador
echo [4/4] Abriendo navegador en http://127.0.0.1:8000...
timeout /t 5 >nul
start http://127.0.0.1:8000

echo ✅ ¡Todo listo! No cierres esta ventana mientras uses la app.