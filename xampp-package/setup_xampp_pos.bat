@echo off
setlocal enabledelayedexpansion
title Setup Warungku POS untuk XAMPP Portable
cd /d "%~dp0"

echo =====================================================================
echo           SETUP OTOMATIS WARUNGKU POS DI XAMPP PORTABLE
echo =====================================================================
echo.

:: 1. Verifikasi folder XAMPP
if not exist "apache\bin\httpd.exe" (
    echo [PERHATIAN] Script ini harus dijalankan di dalam folder XAMPP Portable!
    echo.
    echo Struktur folder yang benar:
    echo C:\WarungkuPOS\ (atau D:\WarungkuPOS\)
    echo   ├── apache\
    echo   ├── php\
    echo   ├── htdocs\
    echo   └── setup_xampp_pos.bat
    echo.
    echo Pastikan file setup_xampp_pos.bat ditaruh di dalam folder XAMPP Anda.
    pause
    exit /b 1
)

echo [1/4] Menyiapkan folder aplikasi di htdocs\pos ...
if not exist "htdocs\pos" mkdir "htdocs\pos"

:: Salin file aplikasi jika script dijalankan dari paket instalasi
if exist "pos_source" (
    echo Menyalin source code POS ke htdocs\pos ...
    robocopy "pos_source" "htdocs\pos" /E /XD ".git" ".agents" ".claude" ".playwright-mcp" "runtime" "tests" "windows-installer" "desktop" "xampp-package" /XF "*.DS_Store" "*.git*" /NP /NFL /NDL
    if not exist "htdocs\pos\runtime" mkdir "htdocs\pos\runtime"
    if not exist "htdocs\pos\data" mkdir "htdocs\pos\data"
)

:: 2. Konfigurasi Port Apache ke 8080 (Mencegah bentrok port 80)
echo.
echo [2/4] Mengonfigurasi Apache Port 8080...
set HTTPD_CONF=apache\conf\httpd.conf
if exist "%HTTPD_CONF%" (
    powershell -NoProfile -ExecutionPolicy Bypass -Command "$c = Get-Content '%HTTPD_CONF%' -Raw; if ($c -notmatch 'Listen 8080') { $c = $c -replace '(?m)^Listen 80\b', 'Listen 8080'; $c = $c -replace '(?m)^ServerName localhost:80\b', 'ServerName localhost:8080'; Set-Content '%HTTPD_CONF%' $c; Write-Host 'Port 8080 berhasil diatur di httpd.conf' } else { Write-Host 'Port 8080 sudah aktif.' }"
)

:: 3. Mengaktifkan Driver SQLite di php.ini
echo.
echo [3/4] Mengaktifkan ekstensi SQLite di php\php.ini ...
set PHP_INI=php\php.ini
if exist "%PHP_INI%" (
    powershell -NoProfile -ExecutionPolicy Bypass -Command "$c = Get-Content '%PHP_INI%' -Raw; $c = $c -replace ';extension=pdo_sqlite', 'extension=pdo_sqlite'; $c = $c -replace ';extension=sqlite3', 'extension=sqlite3'; $c = $c -replace ';extension=gd', 'extension=gd'; $c = $c -replace ';extension=mbstring', 'extension=mbstring'; $c = $c -replace ';extension=fileinfo', 'extension=fileinfo'; $c = $c -replace ';extension=curl', 'extension=curl'; if ($c -notmatch 'date.timezone =') { $c += \"`r`ndate.timezone = Asia/Jakarta`r`n\" } else { $c = $c -replace '(?m)^;?date\.timezone\s*=.*$', 'date.timezone = Asia/Jakarta' }; Set-Content '%PHP_INI%' $c; Write-Host 'Ekstensi SQLite & PHP siap digunakan.' "
)

:: 4. Buat Shortcut Desktop Kasir
echo.
echo [4/4] Membuat Shortcut di Desktop...
call "%~dp0Buat_Shortcut_Desktop.bat"

echo.
echo =====================================================================
echo  [SUKSES] Warungku POS siap digunakan!
echo  Untuk membuka aplikasi:
echo  1. Klik shortcut "Warungku POS" di Desktop Anda, ATAU
echo  2. Klik 2x "Buka_Warungku_POS.vbs" di folder ini.
echo =====================================================================
echo.
pause
exit /b 0
