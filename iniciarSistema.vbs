Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Obtiene la ruta de la carpeta donde está este archivo
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' 1. Iniciar MySQL de XAMPP (Ruta estándar, cámbiala si tu XAMPP está en D:)
' El "0" al final oculta la ventana
WshShell.Run "C:\xampp\mysql\bin\mysqld.exe", 0, False

' 2. Iniciar servidor Vite (en segundo plano)
WshShell.Run "cmd /c npm run dev", 0, False

' 3. Esperar 3 segundos para que MySQL esté listo
WScript.Sleep 3000

' 4. Iniciar Laravel
WshShell.Run "php artisan serve", 0, False

' 5. Iniciar Cola de correos
WshShell.Run "php artisan queue:work", 0, False

' 5.1. Iniciar el Reloj del sistema (por si la dejan prendida)
WshShell.Run "php artisan schedule:work", 0, False

' 5.2. Forzar envío de pendientes al encender (los pendientes se procesan a las 00:01 o al iniciar)
WshShell.Run "php artisan correos:enviar-pendientes", 0, False

' 6. Esperar 5 segundos para que el servidor levante
WScript.Sleep 5000

' 7. Abrir el navegador en el sistema de condominios
WshShell.Run "http://127.0.0.1:8000"

Set WshShell = Nothing
Set fso = Nothing