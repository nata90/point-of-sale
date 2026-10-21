' Peluncur Senyap (Silent Launcher) untuk Toko Cahaya Abadi POS
' Menghilangkan layar hitam Command Prompt (CMD) saat aplikasi dibuka

Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")
scriptDir = fso.GetParentFolderName(WScript.ScriptFullName)

' Jalankan start.bat dengan window style 0 (tersembunyi total)
WshShell.Run chr(34) & scriptDir & "\start.bat" & chr(34), 0, False
Set WshShell = Nothing
Set fso = Nothing
