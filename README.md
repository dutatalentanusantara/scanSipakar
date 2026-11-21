# Portal Siantar Barat — Kecamatan Siantar Barat, Kota Pematangsiantar

> **Portal Resmi Pemerintah Kecamatan Siantar Barat** — Layanan Administrasi Warga, Siap1Pintu, Ahli Waris, SIPAKAR

[![PHP](https://img.shields.io/badge/PHP-8.3-blue)](https://php.net)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7-EF4223)](https://codeigniter.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)](https://mysql.com)
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

## 🚀 Instalasi Lokal

### 1. Clone repository
```bash
git clone https://github.com/boimanalu/sipakar.git
cd sipakar
```

### 2. Install dependencies
```bash
composer install
```

### 3. Konfigurasi environment
```bash
cp .env.example .env
# Edit .env: sesuaikan database dan baseURL
```

### 4. Buat database & import skema
```sql
CREATE DATABASE siantarbarat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Import: sql_siantarbarat.sql (tersedia terpisah, tidak disertakan di repo)
```

### 5. Jalankan server
```bash
# Laragon: aktifkan Apache + MySQL, buka http://localhost/siantarbarat/
php spark serve  # atau gunakan Laragon
```

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
├── docker/              # Hardened php.ini and Apache VirtualHost
└── deploy.ps1           # Script deployment Git + ZIP
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

## 📦 Deploy

Gunakan script `deploy.ps1` (PowerShell, Windows):

```powershell
# Git push + buat ZIP sekaligus
.\deploy.ps1

# Hanya git push dengan pesan custom
.\deploy.ps1 -Mode git -Msg "feat: update halaman pelayanan"

# Hanya buat ZIP export
.\deploy.ps1 -Mode zip

# Simulasi (tanpa eksekusi)
.\deploy.ps1 -DryRun
```

---

## 🏢 Kontak

**Kantor Camat Siantar Barat**  
Jl. Seram No. 3, Kelurahan Bantan, Kecamatan Siantar Barat  
Kota Pematangsiantar, Sumatera Utara  
Email: kec.siantarbarat@pematangsiantar.go.id

---

*Portal ini dikembangkan untuk meningkatkan kualitas pelayanan publik Kecamatan Siantar Barat.*
