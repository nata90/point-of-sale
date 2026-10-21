@echo off
title Hentikan Server POS
echo Menghentikan proses server Toko Cahaya Abadi POS...
taskkill /f /im php.exe >nul 2>&1
echo Selesai.
timeout /t 2 >nul
exit
