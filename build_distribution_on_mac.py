#!/usr/bin/env python3
import os
import sys
import shutil
import subprocess
import re

ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
STAGING_DIR = os.path.join(ROOT_DIR, "dist_staging")
ZIP_CACHE = os.path.join(STAGING_DIR, "xampp-portable-8.2.12.zip")
TARGET_DIR = os.path.join(STAGING_DIR, "WarungkuPOS")
FINAL_ZIP = os.path.join(ROOT_DIR, "Warungku_POS_Siap_Pakai.zip")

DOWNLOAD_URL = "https://downloads.sourceforge.net/project/xampp/XAMPP%20Windows/8.2.12/xampp-portable-windows-x64-8.2.12-0-VS16.zip"

def run(cmd, cwd=None):
    print(f"--> Menjalankan: {cmd}")
    res = subprocess.run(cmd, shell=True, cwd=cwd)
    if res.returncode != 0:
        print(f"[ERROR] Perintah gagal dengan kode: {res.returncode}")
        sys.exit(res.returncode)

def main():
    print("=" * 65)
    print("   MEMBANGUN PAKET DISTRIBUSI WINDOWS DARI MACOS")
    print("   Output: Warungku_POS_Siap_Pakai.zip")
    print("=" * 65)

    os.makedirs(STAGING_DIR, exist_ok=True)

    # 1. Unduh XAMPP Portable jika belum ada di cache
    if not os.path.exists(ZIP_CACHE) or os.path.getsize(ZIP_CACHE) < 50 * 1024 * 1024:
        print("\n[1/6] Mengunduh XAMPP Portable Windows (~150 MB)...")
        run(f"curl -L --progress-bar -o '{ZIP_CACHE}' '{DOWNLOAD_URL}'")
    else:
        print(f"\n[1/6] XAMPP Portable sudah tersedia di cache ({os.path.getsize(ZIP_CACHE) // (1024*1024)} MB).")

    # 2. Ekstrak XAMPP
    if os.path.exists(TARGET_DIR):
        print(f"\n[2/6] Membersihkan folder staging lama: {TARGET_DIR} ...")
        shutil.rmtree(TARGET_DIR)

    unpacked_tmp = os.path.join(STAGING_DIR, "unpacked_tmp")
    if os.path.exists(unpacked_tmp):
        shutil.rmtree(unpacked_tmp)
    os.makedirs(unpacked_tmp, exist_ok=True)

    print("\n[2/6] Mengekstrak XAMPP Portable...")
    run(f"unzip -q '{ZIP_CACHE}' -d '{unpacked_tmp}'")

    raw_xampp = os.path.join(unpacked_tmp, "xampp")
    if os.path.exists(raw_xampp):
        shutil.move(raw_xampp, TARGET_DIR)
    else:
        # Jika tidak ada subfolder xampp
        shutil.move(unpacked_tmp, TARGET_DIR)
    
    if os.path.exists(unpacked_tmp):
        shutil.rmtree(unpacked_tmp)

    # 3. Pangkas Komponen yang Tidak Diperlukan (Menghemat ~150 MB)
    print("\n[3/6] Merampingkan XAMPP (hapus mysql, tomcat, dll)...")
    bloat_folders = [
        "mysql", "tomcat", "webalizer", "filezillaftp",
        "sendmail", "mailsend", "anonymous", "perl", "cgi-bin"
    ]
    for b in bloat_folders:
        path = os.path.join(TARGET_DIR, b)
        if os.path.exists(path):
            shutil.rmtree(path, ignore_errors=True)

    # Bersihkan isi default htdocs
    htdocs_dir = os.path.join(TARGET_DIR, "htdocs")
    if os.path.exists(htdocs_dir):
        shutil.rmtree(htdocs_dir)
    os.makedirs(htdocs_dir, exist_ok=True)

    # 4. Konfigurasi Apache (Port 8080) & PHP (SQLite)
    print("\n[4/6] Mengonfigurasi Apache (Port 8080) & PHP (SQLite & Timezone)...")
    httpd_conf = os.path.join(TARGET_DIR, "apache", "conf", "httpd.conf")
    if os.path.exists(httpd_conf):
        with open(httpd_conf, "r", encoding="utf-8", errors="ignore") as f:
            content = f.read()
        content = re.sub(r"(?m)^Listen 80\b", "Listen 8080", content)
        content = re.sub(r"(?m)^ServerName localhost:80\b", "ServerName localhost:8080", content)
        with open(httpd_conf, "w", encoding="utf-8") as f:
            f.write(content)
        print("  -> httpd.conf diatur ke Port 8080.")

    php_ini = os.path.join(TARGET_DIR, "php", "php.ini")
    if os.path.exists(php_ini):
        with open(php_ini, "r", encoding="utf-8", errors="ignore") as f:
            content = f.read()
        extensions_to_enable = [
            "pdo_sqlite", "sqlite3", "gd", "mbstring", "fileinfo", "curl", "openssl"
        ]
        for ext in extensions_to_enable:
            content = re.sub(rf"(?m)^;extension={ext}\b", f"extension={ext}", content)

        if "date.timezone" in content:
            content = re.sub(r"(?m)^;?date\.timezone\s*=.*$", "date.timezone = Asia/Jakarta", content)
        else:
            content += "\r\ndate.timezone = Asia/Jakarta\r\n"

        with open(php_ini, "w", encoding="utf-8") as f:
            f.write(content)
        print("  -> php.ini diatur (pdo_sqlite, sqlite3, gd, timezone aktif).")

    # 5. Salin Source Code Aplikasi POS ke htdocs/pos
    print("\n[5/6] Menyalin aplikasi Warungku POS ke htdocs/pos ...")
    app_target = os.path.join(htdocs_dir, "pos")
    os.makedirs(app_target, exist_ok=True)

    items_to_copy = [
        "assets", "commands", "components", "composer.json", "composer.lock",
        "config", "controllers", "data", "extensions", "mail",
        "migrations", "models", "requirements.php", "vendor", "views",
        "web", "widgets"
    ]
    for item in items_to_copy:
        src = os.path.join(ROOT_DIR, item)
        dst = os.path.join(app_target, item)
        if os.path.isdir(src):
            shutil.copytree(
                src, dst,
                symlinks=False,
                ignore_dangling_symlinks=True,
                ignore=shutil.ignore_patterns("*.git*", ".DS_Store", "codecept*", "phpunit*")
            )
        elif os.path.isfile(src):
            shutil.copy2(src, dst)

    # Pastikan runtime kosong & database ada
    os.makedirs(os.path.join(app_target, "runtime"), exist_ok=True)
    os.makedirs(os.path.join(app_target, "data"), exist_ok=True)
    src_db = os.path.join(ROOT_DIR, "data", "db_pos_2.sqlite")
    dst_db = os.path.join(app_target, "data", "db_pos_2.sqlite")
    if os.path.exists(src_db):
        shutil.copy2(src_db, dst_db)

    # Buat redirect di htdocs/index.php
    with open(os.path.join(htdocs_dir, "index.php"), "w", encoding="utf-8") as f:
        f.write("<?php\nheader('Location: /pos/web/index.php?r=site/login');\nexit;\n")

    # Salin file launcher Windows ke root WarungkuPOS
    launchers = [
        "Buka_Warungku_POS.vbs",
        "Buka_Warungku_POS.bat",
        "Hentikan_Warungku_POS.bat",
        "Buat_Shortcut_Desktop.bat",
        "favicon.ico"
    ]
    for l in launchers:
        src = os.path.join(ROOT_DIR, "xampp-package", l)
        dst = os.path.join(TARGET_DIR, l)
        if os.path.exists(src):
            shutil.copy2(src, dst)

    # Buat file README petunjuk untuk pembeli
    with open(os.path.join(TARGET_DIR, "PETUNJUK_PENGGUNAAN.txt"), "w", encoding="utf-8") as f:
        f.write(
            "=================================================================\n"
            "         APLIKASI KASIR WARUNGKU POS - SIAP PAKAI\n"
            "=================================================================\n\n"
            "CARA MEMASANG DI KOMPUTER KASIR:\n"
            "1. Ekstrak folder 'WarungkuPOS' ini ke Drive C:\\ atau D:\\\n"
            "   Contoh: C:\\WarungkuPOS\n\n"
            "2. Klik 2x file: 'Buat_Shortcut_Desktop.bat'\n"
            "   Shortcut 'Warungku POS' berlogo resmi akan otomatis muncul di Desktop!\n\n"
            "3. Buka aplikasi dengan mengklik ganda ikon 'Warungku POS' di Desktop.\n"
            "   Aplikasi kasir langsung terbuka siap melayani transaksi.\n\n"
            "CARA MEMATIKAN SERVER (OPSIONAL):\n"
            "- Klik 2x file: 'Hentikan_Warungku_POS.bat'\n"
            "=================================================================\n"
        )

    # 6. Kompres ke ZIP Siap Kirim
    print(f"\n[6/6] Mengompres folder ke: {FINAL_ZIP} ...")
    if os.path.exists(FINAL_ZIP):
        os.remove(FINAL_ZIP)

    # Gunakan zip native macOS untuk kecepatan dan kompresi maksimal
    run(f"cd '{STAGING_DIR}' && zip -r -q '{FINAL_ZIP}' 'WarungkuPOS'")

    final_size_mb = os.path.getsize(FINAL_ZIP) / (1024 * 1024)
    print("\n" + "=" * 65)
    print("  [SELESAI 100%] PAKET DISTRIBUSI BERHASIL DIBUAT DARI MAC!")
    print(f"  Lokasi Berkas: {FINAL_ZIP}")
    print(f"  Ukuran Berkas: {final_size_mb:.1f} MB")
    print("=" * 65)
    print("\nSekarang Anda cukup mengirim berkas Warungku_POS_Siap_Pakai.zip ini ke pembeli!")

if __name__ == "__main__":
    main()
