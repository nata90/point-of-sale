@echo off
setlocal enabledelayedexpansion
title Toko Cahaya Abadi POS Launcher
cd /d "%~dp0"

:: Konfigurasi Host & Port
set HOST=127.0.0.1
set PORT=8082

:: 1. Matikan proses PHP lama jika masih menggantung di background
taskkill /f /im php.exe >nul 2>&1

:: 2. Jalankan PHP Built-in Server di background secara senyap
start /b "" "%~dp0php\php.exe" -c "%~dp0php\php.ini" -S %HOST%:%PORT% -t "%~dp0app\web" >nul 2>&1

:: 3. Tunggu hingga server lokal siap merespons (maksimal 10 detik)
set RETRIES=0
:WAIT_SERVER
timeout /t 1 /nobreak >nul
powershell -Command "try { $r = Invoke-WebRequest -Uri 'http://%HOST%:%PORT%/index.php?r=site/login' -UseBasicParsing -TimeoutSec 1; if ($r.StatusCode -eq 200) { exit 0 } else { exit 1 } } catch { exit 1 }" >nul 2>&1
if %errorlevel% neq 0 (
    set /a RETRIES+=1
    if !RETRIES! lss 8 goto WAIT_SERVER
)

:: 4. Deteksi Microsoft Edge untuk membuka dalam App Mode (Jendela Native Desktop)
set BROWSER_CMD=""

if exist "%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_CMD="%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" --app=http://%HOST%:%PORT%/index.php?r=site/login --window-size=1366,768
) else if exist "%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_CMD="%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" --app=http://%HOST%:%PORT%/index.php?r=site/login --window-size=1366,768
) else if exist "%LocalAppData%\Microsoft\Edge\Application\msedge.exe" (
    set BROWSER_CMD="%LocalAppData%\Microsoft\Edge\Application\msedge.exe" --app=http://%HOST%:%PORT%/index.php?r=site/login --window-size=1366,768
) else if exist "%ProgramFiles%\Google\Chrome\Application\chrome.exe" (
    set BROWSER_CMD="%ProgramFiles%\Google\Chrome\Application\chrome.exe" --app=http://%HOST%:%PORT%/index.php?r=site/login --window-size=1366,768
) else if exist "%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" (
    set BROWSER_CMD="%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" --app=http://%HOST%:%PORT%/index.php?r=site/login --window-size=1366,768
)

:: 5. Buka jendela aplikasi kasir dan tunggu sampai kasir menutup jendela
if not %BROWSER_CMD%=="" (
    call %BROWSER_CMD%
) else (
    start http://%HOST%:%PORT%/index.php?r=site/login
)

:: 6. Saat jendela kasir ditutup, otomatis matikan server PHP lokal
taskkill /f /im php.exe >nul 2>&1
exit
