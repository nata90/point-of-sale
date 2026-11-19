@echo off
title Buat Shortcut Desktop Warungku POS
cd /d "%~dp0"

echo Membuat shortcut Warungku POS di Desktop...

set "SCRIPT_DIR=%~dp0"
:: Hilangkan trailing backslash jika ada
if "%SCRIPT_DIR:~-1%"=="\" set "SCRIPT_DIR=%SCRIPT_DIR:~0,-1%"

set "TARGET_VBS=%SCRIPT_DIR%\Buka_Warungku_POS.vbs"
set "ICON_FILE=%SCRIPT_DIR%\favicon.ico"

powershell -NoProfile -ExecutionPolicy Bypass -Command "$ws = New-Object -ComObject WScript.Shell; $desktop = [System.Environment]::GetFolderPath('Desktop'); $s = $ws.CreateShortcut(\"$desktop\Warungku POS.lnk\"); $s.TargetPath = '%TARGET_VBS%'; $s.WorkingDirectory = '%SCRIPT_DIR%'; $s.IconLocation = '%ICON_FILE%'; $s.Description = 'Aplikasi Kasir Warungku POS'; $s.Save()"

if exist "%USERPROFILE%\Desktop\Warungku POS.lnk" (
    echo.
    echo ========================================================
    echo  [BERHASIL] Shortcut "Warungku POS" telah dibuat di Desktop!
    echo ========================================================
) else (
    echo.
    echo Shortcut berhasil diproses. Silakan periksa layar Desktop Anda.
)

echo.
timeout /t 3 >nul
exit /b 0
