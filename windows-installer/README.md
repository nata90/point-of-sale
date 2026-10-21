# Panduan Lengkap Pembuatan Installer Windows (.exe)
### Aplikasi POS Toko Cahaya Abadi

Paket ini dirancang untuk mengemas seluruh aplikasi POS (Yii2 Framework, database SQLite, dan runtime PHP portable) menjadi satu berkas installer mandiri:
👉 **`Setup_TokoCahayaAbadi_POS_v1.0.exe`**

---

## Mengapa Metode Ini Sangat Efisien?
- **Tanpa XAMPP / Web Server Terpisah**: Runtime PHP sudah portable dan ikut terpasang di komputer kasir.
- **Tanpa MySQL / Konfigurasi Database**: Menggunakan SQLite yang sudah tertanam di dalam folder `app/data/db_pos_2.sqlite`.
- **Tampilan Desktop Mandiri (App Mode)**: Memanfaatkan mode aplikasi Microsoft Edge / Chrome bawaan Windows sehingga antarmuka kasir terbuka bersih tanpa *address bar* dan tanpa tab browser.
- **Ukuran Sangat Ringan**: Hanya sekitar ~35–45 MB (bandingkan dengan Electron yang mencapai 150 MB+).

---

## Struktur Folder Paket Installer

Sebelum melakukan kompilasi dengan Inno Setup, pastikan struktur folder Anda seperti ini:

```text
windows-installer/
├── app/                  <-- Folder berisi seluruh source code POS Anda
│   ├── config/
│   ├── controllers/
│   ├── data/
│   │   └── db_pos_2.sqlite   <-- Database SQLite
│   ├── models/
│   ├── vendor/
│   ├── views/
│   └── web/
│       ├── favicon.ico
│       └── index.php
├── php/                  <-- Folder PHP Windows Portable (VS16 atau VS17)
│   ├── php.exe
│   ├── php.ini          <-- Gunakan php.ini dari folder ini
│   └── ext/             <-- Berisi php_pdo_sqlite.dll, php_sqlite3.dll, dll.
├── launcher.vbs          <-- Peluncur senyap (tanpa layar hitam CMD)
├── start.bat             <-- Skrip start server & buka app
├── stop.bat              <-- Skrip mematikan server lokal
├── prepare_package.bat   <-- Skrip otomatis untuk menyalin file ke folder app/
└── setup_script.iss      <-- Skrip Inno Setup Compiler
```

---

## Langkah 1: Siapkan PHP Portable (VS17 / VS16 x64)

1. Buka situs resmi PHP Windows:  
   👉 **https://windows.php.net/download/**
2. Pilih versi **PHP 8.2 atau PHP 8.3** (atau 8.4):
   - Pilih paket: **VS17 x64 Non Thread Safe** (atau Thread Safe).
   - Pastikan berformat `.zip` (misal: `php-8.2.x-nts-Win32-vs17-x64.zip`).
3. Ekstrak seluruh isi file `.zip` tersebut ke dalam folder:  
   `windows-installer/php/`
4. Salin file `windows-installer/php.ini` ke dalam folder `windows-installer/php/php.ini`.

---

## Langkah 2: Siapkan File Aplikasi ke Folder `app/`

Anda bisa menggunakan cara otomatis atau manual:

### Cara Otomatis (di Windows):
- Cukup klik ganda berkas **`windows-installer/prepare_package.bat`**. Skrip ini akan otomatis menyalin seluruh file proyek ke folder `windows-installer/app/` tanpa mengikutsertakan file temporary.

### Cara Manual:
- Buat folder `app` di dalam `windows-installer/`.
- Salin folder: `config`, `controllers`, `data` *(wajib ada db_pos_2.sqlite)*, `models`, `vendor`, `views`, `web`, `components`, dan file `composer.json` ke dalam `windows-installer/app/`.

---

## Langkah 3: Kompilasi Menjadi `.exe` dengan Inno Setup

1. Pasang aplikasi gratis **Inno Setup** di komputer Windows Anda:  
   👉 **https://jrsoftware.org/isdl.php**
2. Buka aplikasi **Inno Setup Compiler**.
3. Buka file skrip: `windows-installer/setup_script.iss`.
4. Klik tombol **Run / Compile** di toolbar (atau tekan `Ctrl + F9`).
5. Inno Setup akan mengompres seluruh file menjadi satu berkas:  
   📁 `windows-installer/Output/Setup_TokoCahayaAbadi_POS_v1.0.exe`

---

## Pengalaman Pengguna Akhir (Kasir):

1. Kasir membuka file `Setup_TokoCahayaAbadi_POS_v1.0.exe`.
2. Klik tombol **Lanjut > Pasang (Install)**.
3. Ikon shortcut **Toko Cahaya Abadi POS** otomatis muncul di Desktop.
4. Saat ikon di-klik, aplikasi kasir langsung terbuka dalam jendela desktop penuh tanpa *address bar*, siap melayani transaksi!
