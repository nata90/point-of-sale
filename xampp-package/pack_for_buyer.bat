@echo off
title Siapkan Paket Warungku POS untuk XAMPP Portable
cd /d "%~dp0"

echo =====================================================================
echo       MENYIAPKAN PAKET APLIKASI WARUNGKU POS UNTUK XAMPP PORTABLE
echo =====================================================================
echo.

set TARGET_DIR=%~dp0pos_source
if not exist "%TARGET_DIR%" mkdir "%TARGET_DIR%"

echo Menyalin file source code POS ke folder xampp-package\pos_source ...
robocopy ".." "%TARGET_DIR%" /E /XD ".git" ".agents" ".claude" ".playwright-mcp" "runtime" "tests" "windows-installer" "desktop" "xampp-package" /XF "*.DS_Store" "*.git*" /NP /NFL /NDL

:: Pastikan folder data dan runtime ada dan database ikut tersalin
if not exist "%TARGET_DIR%\data" mkdir "%TARGET_DIR%\data"
if not exist "%TARGET_DIR%\runtime" mkdir "%TARGET_DIR%\runtime"
if exist "..\data\db_pos_2.sqlite" (
    copy /y "..\data\db_pos_2.sqlite" "%TARGET_DIR%\data\db_pos_2.sqlite" >nul
)

echo.
echo =====================================================================
echo  [SUKSES] Source code aplikasi berhasil disalin ke:
echo  %TARGET_DIR%
echo.
echo  Langkah Selanjutnya:
echo  1. Unduh XAMPP Portable (.zip) dari https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/
echo  2. Ekstrak XAMPP Portable ke satu folder (misal: C:\WarungkuPOS).
echo  3. Salin seluruh isi folder 'xampp-package' ini ke dalam folder XAMPP tersebut.
echo  4. Jalankan 'setup_xampp_pos.bat' 1 kali. Selesai!
echo =====================================================================
echo.
pause
