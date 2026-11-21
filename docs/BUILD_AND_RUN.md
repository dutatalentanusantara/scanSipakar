# 🚀 Panduan Build & Running Localhost — Portal Siantar Barat & SIPAKAR
> **Panduan Lengkap, Modern, dan Foolproof untuk Menjalankan Proyek di Komputer Berbeda (Windows / macOS / Linux / Docker)**

Dokumen ini memuat instruksi langkah demi langkah agar developer atau tim teknis dapat melakukan *cloning*, instalasi *dependencies*, inisialisasi basis data, konfigurasi *environment*, dan menjalankan aplikasi pada lingkungan *localhost* komputer manapun tanpa mengalami kendala.

---

## 📋 Daftar Isi
1. [Prasyarat Sistem & Dependensi](#1-prasyarat-sistem--dependensi)
2. [Langkah Awal: Clone & Konfigurasi Dasar](#2-langkah-awal-clone--konfigurasi-dasar)
3. [Opsi Menjalankan Aplikasi](#3-opsi-menjalankan-aplikasi)
   - [Opsi A: Windows Laragon (Sangat Direkomendasikan)](#opsi-a-windows-laragon-sangat-direkomendasikan)
   - [Opsi B: XAMPP (Windows / macOS / Linux)](#opsi-b-xampp-windows--macos--linux)
   - [Opsi C: PHP Built-in Server (`spark serve`)](#opsi-c-php-built-in-server-spark-serve)
   - [Opsi D: Docker & Docker Compose (Zero Dependency)](#opsi-d-docker--docker-compose-zero-dependency)
4. [Inisialisasi Database & Seeder Administrator](#4-inisialisasi-database--seeder-administrator)
5. [Verifikasi Fitur Utama & Validasi Keamanan](#5-verifikasi-fitur-utama--validasi-keamanan)
6. [Troubleshooting & Solusi Error Umum](#6-troubleshooting--solusi-error-umum)

---

## 1. Prasyarat Sistem & Dependensi

Sebelum memulai di komputer baru, pastikan perangkat telah terpasang:

| Perangkat Lunak | Versi Minimal | Versi Rekomendasi | Catatan |
|---|---|---|---|
| **PHP** | 8.2+ | **8.3.x** | Wajib ekstensi: `intl`, `mbstring`, `mysqli`, `pdo_mysql`, `gd`, `zip`, `xml`, `fileinfo`, `curl` |
| **MySQL / MariaDB** | MySQL 8.0+ / MariaDB 10.4+ | **MySQL 8.0** | Karakter set: `utf8mb4_unicode_ci` |
| **Composer** | 2.2+ | **2.7+** | Manajemen pustaka PHP |
| **Web Server** | Apache 2.4+ / Nginx | **Apache 2.4** | Wajib aktif modul: `mod_rewrite`, `mod_headers` |
| **Docker** *(Opsional)* | 24.0+ | **Terbaru** | Jika ingin menjalankan via container |

### Memastikan Ekstensi PHP Aktif
Buka terminal/PowerShell dan jalankan:
```bash
php -m
```
Pastikan modul berikut muncul dalam daftar: `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `mysqli`, `pdo_mysql`, `xml`, `zip`. Jika belum aktif, buka file `php.ini` Anda dan hilangkan tanda titik koma (`;`) di depan nama ekstensi terkait.

---

## 2. Langkah Awal: Clone & Konfigurasi Dasar

### Langkah 1: Clone Repositori
Buka terminal pada direktori web server lokal Anda:
```bash
# Untuk Laragon (Windows):
cd C:\laragon\www
git clone https://github.com/boimanalu/sipakar.git siantarbarat
cd siantarbarat

# Untuk XAMPP (Windows):
cd C:\xampp\htdocs
git clone https://github.com/boimanalu/sipakar.git siantarbarat
cd siantarbarat

# Untuk Linux / macOS:
cd /var/www/html # atau direktori kerja Anda
git clone https://github.com/boimanalu/sipakar.git siantarbarat
cd siantarbarat
```

### Langkah 2: Install Dependensi Composer
Jalankan composer untuk memasang pustaka CodeIgniter 4:
```bash
composer install --no-dev --optimize-autoloader
```

### Langkah 3: Setup File Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
# Windows (CMD / PowerShell)
copy .env.example .env

# Linux / macOS
cp .env.example .env
```

Buka file `.env` dan sesuaikan parameter berikut:
```ini
CI_ENVIRONMENT = development

# Sesuaikan dengan URL localhost Anda:
# - Laragon/XAMPP: http://localhost/siantarbarat/
# - Spark Serve:   http://localhost:8080/
app.baseURL = 'http://localhost/siantarbarat/'

# Konfigurasi Database Lokal:
database.default.hostname = localhost
database.default.database = siantarbarat
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306

# Akun Awal Administrator (SIP-02 Compliance):
INITIAL_ADMIN_USERNAME = admin
INITIAL_ADMIN_NAME = 'Administrator Siantar Barat'
INITIAL_ADMIN_EMAIL = admin@siantarbarat.pematangsiantar.go.id
INITIAL_ADMIN_PASSWORD = AdminRahasia2026!
```

---

## 3. Opsi Menjalankan Aplikasi

Pilih salah satu metode berikut sesuai preferensi atau lingkungan komputer lokal Anda:

### Opsi A: Windows Laragon (Sangat Direkomendasikan)
1. Letakkan repositori di `C:\laragon\www\siantarbarat`.
2. Buka aplikasi **Laragon**, klik tombol **Start All** (Apache & MySQL).
3. Pastikan ekstensi `intl`, `fileinfo`, `gd`, `zip` aktif via menu Laragon:
   - Klik kanan di Laragon > **PHP** > **Extensions** > centang `intl`, `fileinfo`, `gd`, `zip`, `mysqli`, `pdo_mysql`.
4. Buka peramban (browser) dan akses:
   - **Portal Siantar Barat**: `http://localhost/siantarbarat/`
   - **SIPAKAR Form Usulan**: `http://localhost/siantarbarat/sipakar`
   - **Login Administrator**: `http://localhost/siantarbarat/sipakar/login`

---

### Opsi B: XAMPP (Windows / macOS / Linux)
1. Letakkan repositori di dalam folder `htdocs/siantarbarat`.
2. Buka **XAMPP Control Panel**, nyalakan **Apache** dan **MySQL**.
3. Pastikan konfigurasi `php.ini` di XAMPP telah mengaktifkan ekstensi yang diperlukan:
   ```ini
   extension=intl
   extension=fileinfo
   extension=gd
   extension=zip
   extension=mysqli
   extension=pdo_mysql
   ```
4. Pastikan `upload_max_filesize` dan `post_max_size` mencukupi:
   ```ini
   upload_max_filesize = 2M
   post_max_size = 8M
   ```
5. Akses peramban pada URL `http://localhost/siantarbarat/`.

---

### Opsi C: PHP Built-in Server (`spark serve`)
Jika Anda tidak menggunakan Laragon/XAMPP dan hanya memiliki PHP 8.2/8.3 CLI:
1. Pastikan nilai `app.baseURL` pada `.env` telah disetel ke `http://localhost:8080/`.
2. Jalankan perintah spark serve:
   ```bash
   php spark serve --port 8080
   ```
3. Akses aplikasi di `http://localhost:8080/`.

---

### Opsi D: Docker & Docker Compose (Zero Dependency)
Jika di komputer Anda sudah terpasang **Docker Desktop**, Anda tidak perlu memasang PHP atau MySQL lokal:
1. Cukup buka terminal pada folder repositori dan jalankan:
   ```bash
   docker compose up -d --build
   ```
2. Docker akan secara otomatis:
   - Membangun container `sipakar-app` berbasis **PHP 8.3 Apache Debian Bookworm** resmi.
   - Menjalankan container database `sipakar-db` (**MySQL 8.0**).
   - Mengatur izin hak akses dan network isolation.
3. Akses aplikasi di: `http://localhost:8080/siantarbarat/`.

---

## 4. Inisialisasi Database & Seeder Administrator

### Cara 1: Menggunakan Template SQL Bawaan (Paling Mudah)
1. Buka database tool Anda (HeidiSQL, phpMyAdmin, DBeaver, atau MySQL CLI).
2. Buat basis data baru bernama `siantarbarat`:
   ```sql
   CREATE DATABASE siantarbarat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import file template yang telah disediakan:
   - File template: `database/schema.sql.example`
   - Melalui terminal:
     ```bash
     mysql -u root -p siantarbarat < database/schema.sql.example
     ```
4. Jalankan seeder akun administrator awal:
   ```bash
   php spark db:seed InitialDatabaseSeeder
   ```
   *Seeder akan membaca password aman dari `INITIAL_ADMIN_PASSWORD` pada `.env` dan menyimpannya menggunakan hashing bcrypt standar PHP 8.*

---

### Cara 2: Menggunakan CodeIgniter Migrations (CLI)
Jika Anda ingin membangun skema secara otomatis menggunakan migration tool CI4:
```bash
# 1. Pastikan database `siantarbarat` sudah dibuat
# 2. Jalankan migrasi tabel
php spark migrate

# 3. Jalankan seeder akun awal
php spark db:seed InitialDatabaseSeeder
```

---

## 5. Verifikasi Fitur Utama & Validasi Keamanan

Setelah server dan database berjalan di komputer baru, lakukan verifikasi terhadap fitur-fitur mutakhir:

### 1. Form SIPAKAR Usulan Kepegawaian (`/sipakar`)
- Buka `http://localhost/siantarbarat/sipakar`.
- **Uji KGB (Kenaikan Gaji Berkala)**:
  - Pilih dropdown **Jenis Layanan**: *Kenaikan Gaji Berkala (KGB)*.
  - Periksa berkas persyaratan: Sistem secara dinamis **hanya menampilkan 3 berkas**:
    1. *SK Pangkat* (Wajib)
    2. *KGB Terakhir* (Wajib)
    3. *Dokumen Pendukung Lainnya* (Opsional)
- **Uji KP (Kenaikan Pangkat)**:
  - Pilih dropdown **Jenis Layanan**: *Kenaikan Pangkat (KP)*.
  - Periksa berkas: Sistem secara dinamis menampilkan seluruh **7 berkas persyaratan**.
- **Uji Validasi Berkas PDF 2MB**:
  - Coba unggah file selain `.pdf` (misal `.jpg` / `.docx`) → Sistem menolak dan mewajibkan format PDF.
  - Coba unggah file `.pdf` dengan ukuran lebih dari 2MB → Sistem menolak dan meminta ukuran maksimum 2MB.

### 2. Halaman Standar Pelayanan (`/pelayanan`)
- Buka `http://localhost/siantarbarat/pelayanan`.
- Pastikan seluruh waktu penyelesaian layanan tercantum **30 Menit**.
- Pastikan tombol unduh berkas formulir bertuliskan badge **Contoh Surat**.

### 3. Autentikasi Administrator SIPAKAR (`/sipakar/login`)
- Buka `http://localhost/siantarbarat/sipakar/login`.
- Masukkan username dan password sesuai `INITIAL_ADMIN_*` yang dikonfigurasi di file `.env`.
- Sistem berhasil masuk ke dashboard administrator tanpa error PHP 8.

---

## 6. Troubleshooting & Solusi Error Umum

### Kasus 1: Error 404 (Not Found) Saat Mengakses Sub-Halaman
**Penyebab**: Modul Apache `mod_rewrite` belum aktif atau konfigurasi `AllowOverride All` belum diaktifkan.
**Solusi**:
- Pada Laragon: Modul rewrite sudah aktif otomatis.
- Pada XAMPP: Buka `httpd.conf`, cari `#LoadModule rewrite_module modules/mod_rewrite.so` dan hapus tanda `#`. Pastikan blok `<Directory "C:/xampp/htdocs">` memiliki `AllowOverride All`.

### Kasus 2: `CodeIgniter\Database\Exceptions\DatabaseException`
**Penyebab**: Kredensial MySQL pada file `.env` tidak sesuai dengan server MySQL lokal komputer tersebut.
**Solusi**:
- Buka file `.env`.
- Periksa `database.default.hostname`, `database.default.username`, `database.default.password`, dan port (default: 3306).
- Pastikan servis MySQL sudah dalam keadaan *Running*.

### Kasus 3: `Call to undefined function Locale::getDefault()` atau `finfo_open()`
**Penyebab**: Ekstensi `intl` atau `fileinfo` belum aktif di `php.ini`.
**Solusi**:
- Buka `php.ini` yang digunakan oleh PHP aktif Anda.
- Cari baris `;extension=intl` dan ubah menjadi `extension=intl`.
- Cari baris `;extension=fileinfo` dan ubah menjadi `extension=fileinfo`.
- Restart web server Apache.

### Kasus 4: Permission Denied pada Folder `writable/` (Linux / macOS)
**Penyebab**: Web server tidak memiliki hak tulis pada direktori cache dan upload.
**Solusi**:
```bash
chmod -R 775 writable/
sudo chown -R www-data:www-data writable/
```

### Kasus 5: File Upload Ditolak Melebihi Batas PHP Server
**Penyebab**: Nilai konfigurasi `upload_max_filesize` atau `post_max_size` pada PHP server lokal terlalu kecil.
**Solusi**:
- Buka `php.ini`, setel:
  ```ini
  upload_max_filesize = 2M
  post_max_size = 8M
  memory_limit = 256M
  ```
- Restart web server Apache.

---

*Panduan ini disusun sesuai standar DevSecOps resmi Pemerintah Kecamatan Siantar Barat untuk menjamin portabilitas, keamanan, dan kehandalan aplikasi di seluruh lingkungan pengembangan.*
