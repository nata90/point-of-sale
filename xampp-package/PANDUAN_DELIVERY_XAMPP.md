# Panduan Lengkap Deliver Warungku POS dengan XAMPP Portable

Metode ini adalah cara paling **stabil, minim komplain teknis, dan 100% offline** untuk menjual aplikasi kasir berbasis web ke toko, minimarket, kafe, atau warung.

---

## 🌟 Mengapa Menggunakan XAMPP Portable?
1. **Tidak Perlu Proses Install**: Cukup copy folder atau tancapkan Flashdisk.
2. **Tidak Perlu MySQL**: Aplikasi menggunakan **SQLite** (`data/db_pos_2.sqlite`), sehingga database berupa 1 file lokal yang sangat cepat, tidak pernah bentrok password, dan sangat mudah dibackup.
3. **Bebas Masalah VC++ Runtime**: XAMPP sudah memaketkan runtime web server Apache yang sangat stabil di semua versi Windows (Windows 10, 11).
4. **Tampilan Tetap Native Desktop**: Browser Microsoft Edge / Chrome akan dibuka dalam mode `--app` (tanpa address bar, tanpa tab) dengan ikon resmi **Warungku POS**.

---

## 📁 Struktur Akhir Folder yang Diterima Pembeli

Pembeli akan menerima 1 folder rapi (misal di `C:\WarungkuPOS` atau di Flashdisk):

```text
WarungkuPOS/
├── apache/                      <-- Web Server Apache bawaan XAMPP
├── php/                         <-- PHP bawaan XAMPP
├── htdocs/
│   └── pos/                     <-- Aplikasi Warungku POS
│       ├── config/
│       ├── controllers/
│       ├── data/
│       │   └── db_pos_2.sqlite  <-- Database toko pembeli
│       ├── models/
│       ├── vendor/
│       ├── views/
│       └── web/
├── Buka_Warungku_POS.vbs        <-- Peluncur senyap (Ikon klik kasir)
├── Buka_Warungku_POS.bat        <-- Skrip peluncur cadangan
├── Hentikan_Warungku_POS.bat    <-- Skrip mematikan server
├── Buat_Shortcut_Desktop.bat    <-- 1-Klik membuat shortcut di Desktop kasir
├── setup_xampp_pos.bat          <-- Setup otomatis port 8080 & SQLite
└── favicon.ico                  <-- Ikon resmi Warungku POS
```

---

## 🛠️ Langkah Menyiapkan Master Folder (Dilakukan oleh Penjual)

Anda hanya perlu melakukan langkah ini **sekali saja** untuk membuat master folder yang siap dikirim ke pembeli:

### 1. Unduh XAMPP Portable Windows (.zip)
- Kunjungi link resmi XAMPP di SourceForge:  
  👉 **https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/**
- Masuk ke folder versi **8.2.x** atau **8.1.x**.
- Pilih berkas yang berakhiran **`-portable-windows-x64-....zip`** (misal: `xampp-portable-windows-x64-8.2.12-0-VS16.zip`).  
  *(Pastikan memilih yang `.zip`, bukan `.exe` installer).*

### 2. Ekstrak XAMPP Portable
- Ekstrak file zip tersebut. Ubah nama foldernya menjadi: **`WarungkuPOS`**.
- *(Opsional untuk menghemat ukuran)*: Di dalam folder `WarungkuPOS`, Anda bisa menghapus folder `mysql`, `tomcat`, `webalizer`, `filezillaftp`, `mailsend`, dan `sendmail` karena aplikasi POS ini hanya butuh Apache + PHP + SQLite. Ukuran master akan terpangkas hingga 50%!

### 3. Salin File Aplikasi POS
- Jalankan skrip **`xampp-package/pack_for_buyer.bat`** di komputer Anda. Skrip ini akan membuat folder `xampp-package/pos_source`.
- Salin folder `pos_source` ke dalam `WarungkuPOS/htdocs/pos`.
- Salin semua file dari `xampp-package/` (`Buka_Warungku_POS.vbs`, `Buka_Warungku_POS.bat`, `Hentikan_Warungku_POS.bat`, `Buat_Shortcut_Desktop.bat`, `setup_xampp_pos.bat`, `favicon.ico`) ke dalam root folder `WarungkuPOS/`.

### 4. Jalankan Setup 1 Kali
- Klik 2x file **`setup_xampp_pos.bat`** di dalam folder `WarungkuPOS`.
- Skrip ini akan:
  - Mengubah port Apache ke **8080** (mencegah bentrok port 80 dengan sistem Windows).
  - Mengaktifkan modul `pdo_sqlite` dan `sqlite3` di `php.ini`.
  - Mengatur zona waktu ke `Asia/Jakarta`.
  - Menyiapkan shortcut desktop.

### 5. Kompres Menjadi Master ZIP
- Sekarang folder `WarungkuPOS` sudah 100% matang dan siap pakai!
- Klik kanan folder `WarungkuPOS` > **Compress to ZIP file** (misal: `Warungku_POS_v1.0_Offline.zip`).

---

## 📦 Cara Deliver ke Pembeli (Customer Experience)

Ketika ada pembeli yang membeli aplikasi Anda:

1. **Kirim File**: Berikan file `Warungku_POS_v1.0_Offline.zip` (melalui Google Drive, Flashdisk, atau email).
2. **Instruksi untuk Pembeli (Sangat Mudah)**:
   > *"Halo Kak, silakan ekstrak file ZIP ke drive `C:\` atau `D:\`. Lalu klik 2x file `Buat_Shortcut_Desktop.bat`. Ikon Warungku POS akan langsung muncul di layar Desktop komputer kasir Anda!"*
3. **Saat Kasir Membuka Aplikasi**:
   - Kasir cukup klik ganda ikon **Warungku POS** di Desktop.
   - Server otomatis aktif di background secara senyap (tanpa jendela hitam CMD).
   - Layar kasir langsung terbuka rapi dalam jendela aplikasi mandiri, siap melayani transaksi penjualan!
