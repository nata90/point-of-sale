@echo off
setlocal enabledelayedexpansion
title Warungku POS Launcher
cd /d "%~dp0"

set PORT=8080
set APP_URL=http://localhost:%PORT%/pos/web/index.php?r=site/login

:: 1. Jalankan Apache jika belum aktif
tasklist /fi "imagename eq httpd.exe" | find /i "httpd.exe" >nul 2>&1
if %errorlevel% neq 0 (
    echo Menyalakan server Warungku POS...
    if exist "apache\bin\httpd.exe" (
        start "" /b "apache\bin\httpd.exe"
    ) else if exist "apache_start.bat" (
        start "" /b cmd /c "apache_start.bat"
    )
    timeout /t 2 /nobreak >nul
)

:: 2. Cari Browser Modern (Microsoft Edge / Google Chrome) untuk App Mode
set BROWSER_EXE=""

if exist "%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_EXE="%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_EXE="%ProgramFiles%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%LocalAppData%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_EXE="%LocalAppData%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Google\Chrome\Application\chrome.exe" (
    set BROWSER_EXE="%ProgramFiles%\Google\Chrome\Application\chrome.exe"
) else if exist "%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" (
    set BROWSER_EXE="%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"
) else if exist "%LocalAppData%\Google\Chrome\Application\chrome.exe" (
    set BROWSER_EXE="%LocalAppData%\Google\Chrome\Application\chrome.exe"
)

:: 3. Buka jendela aplikasi kasir
if not %BROWSER_EXE%=="" (
    start "" %BROWSER_EXE% --app=%APP_URL% --window-size=1366,768
) else (
    start "" "%APP_URL%"
)

exit /b 0
