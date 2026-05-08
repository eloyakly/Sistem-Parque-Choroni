Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' 1. Obtener la ruta de la carpeta actual
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' 2. DETENER PROCESOS (Limpieza profunda)
' Detenemos PHP, Node y MySQL para liberar los puertos
WshShell.Run "cmd /c taskkill /f /im php.exe", 0, True
WshShell.Run "cmd /c taskkill /f /im node.exe", 0, True
WshShell.Run "cmd /c taskkill /f /im mysqld.exe", 0, True


' 3. LIMPIAR CACHE DE LARAVEL
' Esto asegura que si cambiaste algo en el .env o rutas, se actualice
WshShell.Run "cmd /c php artisan config:clear", 0, True
WshShell.Run "cmd /c php artisan cache:clear", 0, True

' 4. INICIAR SERVIDOR VITE (En segundo plano)
WshShell.Run "cmd /c npm run dev", 0, False

' 5. REINICIAR SERVICIOS
' Iniciamos MySQL de XAMPP (Ajusta la ruta si es necesario)
WshShell.Run "C:\xampp\mysql\bin\mysqld.exe", 0, False

' Esperar 3 segundos para que MySQL levante antes de los comandos artisan
WScript.Sleep 3000

' Iniciamos Laravel y utilidades
WshShell.Run "php artisan serve", 0, False
WshShell.Run "php artisan queue:work", 0, False
WshShell.Run "php artisan schedule:work", 0, False
WshShell.Run "php artisan correos:enviar-pendientes", 0, False

' 6. ESPERAR Y ABRIR NAVEGADOR
' Esperamos 5 segundos para que el servidor esté listo
WScript.Sleep 5000
WshShell.Run "http://127.0.0.1:8000"

Set WshShell = Nothing
Set fso = Nothing