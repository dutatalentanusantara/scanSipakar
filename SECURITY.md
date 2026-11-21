# Security Policy — Portal Siantar Barat & SIPAKAR

Pemerintah Kecamatan Siantar Barat berkomitmen untuk menjaga keamanan data warga dan ASN serta integritas seluruh sistem informasi publik.

---

## 🛡️ Supported Versions

Hanya versi aktif berikut yang mendapatkan pembaruan keamanan:

| Komponen | Versi yang Didukung | Status |
|---|---|---|
| **Portal Siantar Barat / SIPAKAR** | `1.0.x` (Main branch) | :white_check_mark: Didukung |
| **CodeIgniter Framework** | `4.7.x` | :white_check_mark: Didukung |
| **PHP Runtime** | `8.3.x` | :white_check_mark: Didukung (Active Security Support) |
| **MySQL Database** | `8.0.x` | :white_check_mark: Didukung |

---

## 🔒 Kebijakan Keamanan & Hardening

Sistem ini menerapkan standar keamanan DevSecOps berlapis:

1. **Autentikasi & Password Hashing**:
   - Password disimpan menggunakan algoritma native secure hashing `PASSWORD_DEFAULT` (Bcrypt) dengan cost yang adaptif.
   - Tidak ada hardcoded credentials atau hash statis pada source code maupun database seeder.
2. **Proteksi File Upload**:
   - Validasi MIME type menggunakan `finfo` (bukan hanya ekstensi file).
   - Pembatasan format berkas ke dokumen PDF (`.pdf`) dan ukuran maksimum 2MB.
   - Penonaktifan eksekusi skrip PHP di seluruh direktori upload (`writable/uploads` dan `asset/`).
3. **Proteksi Web Application (OWASP Top 10)**:
   - Sanitasi XSS output otomatis via `safe_output()` dan `htmlspecialchars()`.
   - Proteksi Cross-Site Request Forgery (CSRF) aktif pada setiap form transaksi.
   - Penggunaan Prepared Statements / Query Builder untuk mencegah SQL Injection.
4. **Keamanan Container Docker (SIP-03 Compliant)**:
   - Base image resmi `php:8.3-apache-bookworm` (non-EOL, tag spesifik, bukan `:latest`).
   - Hardened `php.ini` (`expose_php = Off`, `display_errors = Off`, `log_errors = On`).
   - `.dockerignore` memblokir file `.env`, kredensial, dan backup database agar tidak masuk ke image.
   - Non-root user permissions untuk runtime Apache (`www-data`).

---

## 🚨 Pelaporan Kerentanan (Reporting a Vulnerability)

Jika Anda menemukan celah keamanan (*security vulnerability*) pada sistem ini:

1. **JANGAN** membuat public issue pada GitHub Issues.
2. Kirimkan laporan melalui email resmi:  
   📧 **kec.siantarbarat@pematangsiantar.go.id**
3. Sertakan informasi detail:
   - Deskripsi kerentanan dan potensi dampak.
   - Langkah-langkah reproduksi (Proof of Concept / PoC).
   - Saran perbaikan (jika ada).
4. Tim teknis akan merespons dalam waktu 1-3 hari kerja untuk verifikasi dan penyusunan perbaikan (*hotfix*).
