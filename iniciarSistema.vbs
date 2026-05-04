Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Obtiene la ruta de la carpeta donde está este archivo
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' 1. Iniciar MySQL de XAMPP (Ruta estándar, cámbiala si tu XAMPP está en D:)
' El "0" al final oculta la ventana
WshShell.Run "C:\xampp\mysql\bin\mysqld.exe", 0, False

' 2. Iniciar Laravel
WshShell.Run "php artisan serve", 0, False

' 3. Iniciar Laravel
WshShell.Run "php artisan queue:work", 0, False

' 4. Iniciar Vite
WshShell.Run "npm run dev", 0, False

' 5. Esperar 8 segundos para que los servicios levanten
WScript.Sleep 8000

' 6. Abrir el navegador en el sistema de condominios
WshShell.Run "http://127.0.0.1:8000"

Set WshShell = Nothing
Set fso = Nothing