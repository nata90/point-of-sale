@echo off
title Menyiapkan Paket Aplikasi POS
cd /d "%~dp0"

echo =======================================================
echo    MENYIAPKAN FILE APLIKASI UNTUK INSTALLER WINDOWS
echo =======================================================
echo.

if not exist "app" mkdir "app"

echo Menyalin file proyek ke folder windows-installer\app ...
robocopy ".." "app" /E /XD ".git" ".agents" ".claude" ".playwright-mcp" "runtime" "tests" "windows-installer" /XF "*.DS_Store" "*.git*" /NP /NFL /NDL

:: Pastikan folder data dan runtime ada
if not exist "app\data" mkdir "app\data"
if not exist "app\runtime" mkdir "app\runtime"

echo.
echo =======================================================
echo [SUKSES] Seluruh file aplikasi berhasil disalin!
echo Langkah berikutnya:
echo 1. Pastikan folder php\ sudah berisi file PHP Portable.
echo 2. Buka setup_script.iss dengan Inno Setup dan klik Compile.
echo =======================================================
pause
