# Portal Siantar Barat — Kecamatan Siantar Barat, Kota Pematangsiantar

> **Portal Resmi Pemerintah Kecamatan Siantar Barat** — Layanan Administrasi Warga, Siap1Pintu, Ahli Waris, SIPAKAR

[![CI & Security Quality Gate](https://github.com/boimanalu/sipakar/actions/workflows/ci.yml/badge.svg)](https://github.com/boimanalu/sipakar/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7-EF4223?logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![Docker](https://img.shields.io/badge/Docker-PHP%208.3%20Bookworm-2496ED?logo=docker&logoColor=white)](Dockerfile)
[![Security Policy](https://img.shields.io/badge/Security-Hardened-success?logo=shield&logoColor=white)](SECURITY.md)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## 🏛️ Tentang Portal

Portal resmi informasi dan layanan publik Kecamatan Siantar Barat, Kota Pematangsiantar, Sumatera Utara. Menyediakan:

- **Pelayanan Siap1Pintu (PATEN)** — Standar SOP administrasi kecamatan
- **Kelas Waris** — Panduan persyaratan dokumen ahli waris (A/B/C/D)
- **SIPAKAR** — Sistem Informasi ASN Kecamatan Siantar Barat
- **8 Kelurahan** — Direktori dan informasi kantor kelurahan
- **Berita & Agenda** — Informasi publik terkini

---

## ⚙️ Teknologi

| Komponen | Versi |
|---|---|
| PHP | 8.3+ |
| CodeIgniter | 4.7 |
| MySQL / MariaDB | 8.0+ |
| Template Engine | PHP Native + Tailwind CSS |
| Server | Apache (Laragon) |

---

## 🚀 Instalasi & Menjalankan di Localhost

> 📖 **Panduan Lengkap & Multi-Platform**: Baca panduan mendalam untuk Laragon, XAMPP, Native PHP, dan Docker di [**`docs/BUILD_AND_RUN.md`**](docs/BUILD_AND_RUN.md).

### 1. Clone repository
```bash
git clone https://github.com/boimanalu/sipakar.git siantarbarat
cd siantarbarat
```

### 2. Install dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Konfigurasi environment
```bash
cp .env.example .env
# Edit .env: sesuaikan database, baseURL, dan INITIAL_ADMIN_PASSWORD
```

### 4. Buat database & import skema
```bash
# 1. Buat database di MySQL
mysql -u root -e "CREATE DATABASE siantarbarat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Import skema template bawaan
mysql -u root siantarbarat < database/schema.sql.example

# 3. Jalankan seeder akun awal administrator (SIP-02 Compliant)
php spark db:seed InitialDatabaseSeeder
```

### 5. Jalankan server
- **Laragon**: Aktifkan Apache + MySQL, buka `http://localhost/siantarbarat/`
- **XAMPP**: Jalankan Apache + MySQL, buka `http://localhost/siantarbarat/`
- **PHP Built-in Server**: `php spark serve --port 8080`
- **Docker Compose**: `docker compose up -d --build` (Akses di `http://localhost:8080/siantarbarat/`)

---

## 🔐 Keamanan

- File `.env` **tidak disertakan** di repository — wajib dikonfigurasi manual
- Direktori `writable/` dikecualikan dari tracking Git
- SQL dump **tidak disertakan** (berisi data warga — sensitif)
- Semua input warga disanitasi dengan `safe_output()` dan `htmlspecialchars()`

---

## 📁 Struktur Direktori

```
siantarbarat/
├── app/
│   ├── Config/          # Konfigurasi CI4
│   ├── Controllers/     # Logic controller
│   ├── Models/          # Model database
│   ├── Views/           # Template view PHP
│   └── Helpers/         # Helper functions
├── asset/               # Gambar, logo, media upload
├── template/diskominfo/ # Aset CSS/JS/plugin frontend
├── vendor/              # Composer dependencies (excluded from git)
├── writable/            # Cache, logs, uploads (excluded from git)
├── .env.example         # Template konfigurasi environment
├── .gitignore
├── .gitattributes
├── composer.json
├── Dockerfile           # Docker container image (PHP 8.3 Apache Debian Bookworm)
├── docker-compose.yml   # Multi-container orchestration (App + MySQL 8.0)
└── docker/              # Hardened php.ini and Apache VirtualHost
```

---

## 🐳 Docker Deployment (Security Hardened)

SIPAKAR menyediakan konfigurasi container produksi yang memenuhi standar keamanan Semgrep (SIP-03) berbasis **PHP 8.3 Apache Bookworm**:

```bash
# Build dan jalankan dengan Docker Compose
docker compose up -d --build

# Periksa status container & log
docker compose ps
docker compose logs -f app

# Akses aplikasi
# http://localhost:8080/siantarbarat/
```

### Standar Keamanan Docker (SIP-03 Compliant):
- Base Image: `php:8.3-apache-bookworm` (Official Debian Bookworm, non-EOL, security supported hingga 2027)
- Tidak menggunakan tag `latest` yang tidak terkontrol
- Hardened PHP: `expose_php = Off`, `display_errors = Off`, `upload_max_filesize = 2M`
- Perlindungan upload: Eksekusi script PHP dinonaktifkan pada direktori `writable/uploads` dan `asset/`
- `.dockerignore` memfilter file kredensial `.env`, backup database `*.sql`, log, dan cache

---

## 🏢 Kontak

**Kantor Camat Siantar Barat**  
Jl. Seram No. 3, Kelurahan Bantan, Kecamatan Siantar Barat  
Kota Pematangsiantar, Sumatera Utara  
Email: kec.siantarbarat@pematangsiantar.go.id

---

*Portal ini dikembangkan untuk meningkatkan kualitas pelayanan publik Kecamatan Siantar Barat.*
