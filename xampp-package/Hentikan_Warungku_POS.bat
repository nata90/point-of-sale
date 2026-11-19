@echo off
title Hentikan Server Warungku POS
echo Menghentikan server Warungku POS...
taskkill /f /im httpd.exe >nul 2>&1
echo Server berhasil dihentikan.
timeout /t 2 >nul
exit /b 0
