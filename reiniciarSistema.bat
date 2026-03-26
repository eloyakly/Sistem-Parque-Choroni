@echo off
title Reiniciando Todo el Sistema de Condominios
color 0E
setlocal

echo 🔄 1. Deteniendo procesos de Laravel y Vite...
taskkill /f /im php.exe >nul 2>&1
taskkill /f /im node.exe >nul 2>&1

echo 🔄 2. Reiniciando Servicio MySQL...
:: Detenemos el servicio (esto limpia conexiones trabadas)
net stop MySQL80 >nul 2>&1
timeout /t 2 >nul
:: Lo volvemos a iniciar
net start MySQL80 >nul 2>&1

echo 🧹 3. Limpiando cache y optimizando Laravel...
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
call php artisan cache:clear

echo 🚀 4. Re-lanzando servicios web...
start /b php artisan serve >nul 2>&1
start /b npm run dev >nul 2>&1

echo 🌐 5. Abriendo navegador...
timeout /t 5 >nul
start http://127.0.0.1:8000

echo ✅ ¡Sistema refrescado por completo!
pause