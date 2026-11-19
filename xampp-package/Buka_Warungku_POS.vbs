' ==============================================================
' Peluncur Senyap (Silent Launcher) - Warungku POS
' Membuka server & aplikasi langsung tanpa jendela hitam CMD
' ==============================================================

Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")
currentDir = fso.GetParentFolderName(WScript.ScriptFullName)

' Jalankan start script secara tersembunyi (0 = hidden)
WshShell.Run chr(34) & currentDir & "\Buka_Warungku_POS.bat" & chr(34), 0, False

Set WshShell = Nothing
Set fso = Nothing
