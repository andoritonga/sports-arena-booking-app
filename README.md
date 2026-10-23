# 🏆 SportKuy - Modern Sports Arena Booking & Management System

[![PHP](https://img.shields.io/badge/PHP-8.4-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Cloudflare Workers](https://img.shields.io/badge/Cloudflare-Workers%20%2B%20D1-F38020?style=for-the-badge&logo=cloudflare&logoColor=white)](https://workers.cloudflare.com/)
[![Hono](https://img.shields.io/badge/Framework-Hono-E36002?style=for-the-badge&logo=hono&logoColor=white)](https://hono.dev/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ed?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![PWA](https://img.shields.io/badge/PWA-Ready-f6820c?style=for-the-badge&logo=pwa&logoColor=white)](manifest.json)
[![i18n](https://img.shields.io/badge/Language-ID%20%7C%20EN-blue?style=for-the-badge)](lang.php)
[![Security Hardened](https://img.shields.io/badge/Security-Anti--php2shell%20%7C%20PHP%208.4-green?style=for-the-badge)](Dockerfile)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**SportKuy** is an end-to-end web and mobile application for sports center reservations and arena facility management. It provides a sleek, modern, app-like booking experience for customers and a full-featured back-office management portal for arena owners and administrators.

Aplikasi ini mendukung dua mode operasional:
1. **Cloudflare Serverless (Modern & 100% Gratis Tanpa Kartu Kredit)**: Ditenagai **Cloudflare Workers (with Static Assets) + Cloudflare D1 (SQLite) + Hono TypeScript**.
2. **Docker / PHP Standalone**: Ditenagai **PHP 8.4 Apache + MariaDB 10.11**, dengan perlindungan penuh dari kerentanan upload web shell (*php2shell / CVE*).

---

## ⚡ Deployment ke Cloudflare Workers & D1 (100% Free Tier)

SportKuy dapat di-hosting secara **100% Gratis di Cloudflare** tanpa memerlukan kartu kredit.

### Arsitektur Cloudflare Serverless:
- **Routing & Serverless Engine**: [Hono](https://hono.dev/) berjalan di Cloudflare Workers.
- **Database**: Cloudflare D1 (Distributed SQLite Serverless).
- **Static Assets**: Cloudflare Static Assets (`/public`) untuk CSS, JS, Icon PWA, font, dan template gambar.
- **Dynamic File Storage (Upload Bukti & Foto)**: Disimpan secara aman di tabel D1 `uploaded_files` sebagai BLOB (zero credit card), dengan fallback otomatis ke Cloudflare R2 jika binding `BUCKET` dipasang di masa depan.
- **Keamanan**: Validasi MIME magic bytes, deteksi polyglot script/PHP, sanitasi XSS, dan cookie session terenkripsi HMAC-SHA256.

### Langkah Deploy ke Cloudflare:

#### 1. Login ke Akun Cloudflare
```bash
npx wrangler login
```

#### 2. Buat Cloudflare D1 Database
```bash
npx wrangler d1 create sportkuy-db
```
Salin nilai `database_id` yang dihasilkan dari output terminal, lalu perbarui baris `database_id` di file `wrangler.toml`:
```toml
[[d1_databases]]
binding = "DB"
database_name = "sportkuy-db"
database_id = "PASTE_DATABASE_ID_ANDA_DISINI"
```

#### 3. Jalankan Migrasi Skema & Data Awal ke D1
```bash
npx wrangler d1 execute sportkuy-db --remote --file=./migrations/0001_init.sql
```

#### 4. Deploy Aplikasi ke Cloudflare Workers
```bash
npm run deploy
```
Aplikasi Anda akan langsung aktif dengan URL global Cloudflare (misal: `https://sportkuy.yourname.workers.dev`).

---

## 💻 Menjalankan Cloudflare Worker Secara Lokal

Untuk menguji performa Cloudflare Workers dan D1 di komputer lokal Anda:

```bash
# 1. Install dependencies
npm install

# 2. Inisialisasi database lokal D1
npm run db:init:local

# 3. Jalankan development server lokal
npm run dev:cf
```
Aplikasi akan aktif di `http://localhost:8787` dengan fitur D1 SQLite lokal dan Static Assets.

---

## ✨ Fitur Utama

### 👤 Customer Facing Portal
* **Native Mobile App UI & Feel**:
  * **Bottom Navigation Bar**: Fixed docked tab bar with glassmorphism blur on mobile (*Beranda*, *Kategori*, *Pesanan*, *Akun*).
  * **Quick Category Carousels**: Swipeable horizontal pill strip for instant 1-tap sport filtering (Futsal, Badminton, Basketball, Mini Soccer, Tennis, Volley).
  * **Interactive Bottom Sheet Drawer**: Slide-up category explore modal with modern grid icons.
  * **Modern CSS Preloader**: Smooth circular glowing spinner.
* **Progressive Web App (PWA) & Offline Mode**:
  * **Installable Standalone App**: Installable to Android, iOS, and Desktop homescreen with native app window experience.
  * **Floating Install Prompt**: Smart in-app prompt banner with 1-click install action.
  * **Offline Fallback Engine**: Service Worker caching strategy with bilingual offline page (`offline.html`).
  * **Complete Icon Suite**: Full set of high-res maskable and standard app icons (`72px` up to `512px` + Apple Touch Icon).
* **Bilingual Multi-Language Support (ID 🇮🇩 / EN 🇬🇧)**:
  * 1-tap language toggle pill (`[ 🇮🇩 ID | 🇬🇧 EN ]`) on headers, bottom drawers, and offline pages.
  * Persistent language preferences remembered via cookies.
* **Modern Venue Showcase & Schedulers**: Browse arenas, cek jadwal lapangan real-time, dan tentukan sesi jam main.
* **Payment Receipt Upload**: Upload transfer proofs (`.jpg`, `.png`, `.jpeg`, `.webp`) secara aman.
* **Printable Executive Invoices**: High-contrast, pixel-perfect printable invoice statements (`@media print` supported).

### 🛡️ Admin Management Portal
* **Executive Dashboard**: Real-time business metrics including confirmed revenue, active reservations, arena count, and recent transactions.
* **Booking & Transaction Workflow**: Instant payment receipt verification modal, one-click status transitions (*Menunggu Bayar*, *Menunggu Konfirmasi*, *Dikonfirmasi*, *Selesai*, *Ditolak*).
* **Arena & Facility Management**: Create, edit, and manage fields with multi-photo gallery uploads and hourly rate configuration.
* **Category Directory**: Organize sports centers and facility types with registered venue counters.
* **Customer Directory**: Manage customer databases, contact info, and booking histories.
* **Financial & Sales Reports**: Filter sales performance by preset intervals (*Hari Ini*, *Bulan Ini*, *30 Hari Terakhir*, *Tahun Ini*), export formal PDF statements, and print formatted revenue recaps.
* **Administrator Security**: Role-based access control and admin profile/password settings.

---

## 🔒 Keamanan & Mitigasi Celah (CVE / php2shell)

Codebase ini telah di-upgrade secara menyeluruh untuk mencegah eksploitasi Remote Code Execution (RCE) dan upload web shell:
1. **Upgrade Runtime**: PHP di-upgrade ke **PHP 8.4** (`FROM php:8.4-apache`).
2. **Server-Level Execution Block**: Konfigurasi Apache `no-php-in-uploads.conf` dan `.htaccess` di direktori `gambar/` secara mutlak menonaktifkan PHP engine (`php_flag engine off`) dan memblokir eksekusi skrip `.php`, `.phtml`, `.php5`, `.phar`.
3. **MIME Magic Bytes Verification**: Validasi header biner file gambar menggunakan `finfo` (pada PHP) dan byte inspection (pada TypeScript Worker) untuk memastikan file benar-benar merupakan gambar valid (JPEG, PNG, GIF, WEBP).
4. **Polyglot & Script Inspection**: Scanning payload teks terhadap tag `<?php`, `<?=`, `<script`, dan `__HALT_COMPILER()`.
5. **Randomized Filenames**: Nama file acak kriptografis (misal `d41d8cd98f00b204e9800998ecf8427e.jpg`), mencegah serangan path traversal maupun tebak nama file.
6. **Penghapusan Dependency Rentan**: Seluruh file generator warisan yang tidak terpakai (`PHPMailer`, `dompdf`, `template.php`, `owner.php`) telah dibersihkan dari repositori.

---

## 🚀 Menjalankan via Docker Compose (PHP 8.4)

Jika Anda ingin menjalankan aplikasi secara lokal dengan kontainer Docker:

### 1. Launch with Docker Compose
```bash
docker compose up -d
```

Docker Compose akan secara otomatis:
1. Menjalankan MariaDB 10.11 container (`sportkuy-db`) pada port `3307`.
2. Mengimpor skema awal dan data demo dari `project_sport_center.sql`.
3. Membangun dan menjalankan Apache **PHP 8.4** container (`sportkuy-app`) pada port `8080`.

### 2. Buka di Browser
* **Customer Website & Login**: [http://localhost:8080](http://localhost:8080)
* **Admin Portal**: [http://localhost:8080/admin/](http://localhost:8080/admin/)

---

## 🔐 Akun Demo Default

| Role | Username / Identifier | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin` |
| **Customer** | `carras16@gmail.com` | `123456` |

*(Customer baru juga dapat langsung mendaftar via form registrasi di `daftar.php`)*

---

## 📁 Struktur Direktori

```text
sport-kuy/
├── src/                        # Cloudflare Workers App (TypeScript + Hono)
│   ├── index.ts                # Main application entry point & routing
│   ├── auth.ts                 # Session security & secure file upload handlers
│   ├── layout.ts               # Layout components (Customer & AdminLTE)
│   ├── views_customer.ts       # Customer pages renderers
│   ├── views_admin.ts          # Admin portal renderers
│   ├── utils.ts                # Date formatting, numbers, escaping, hash helpers
│   └── i18n.ts                 # Bilingual translation engine (ID / EN)
├── public/                     # Static Assets for Cloudflare Workers
│   ├── assets/                 # AdminLTE assets & scripts
│   ├── frontend/               # Customer CSS, JS, fonts, & PWA icons
│   ├── gambar/                 # Base visual images & assets
│   ├── manifest.json           # Web App Manifest
│   ├── sw.js                   # Service Worker
│   └── offline.html            # Offline fallback page
├── migrations/                 # Cloudflare D1 SQLite Database Migrations
│   └── 0001_init.sql           # Initial D1 schema, seed data, & uploaded_files table
├── wrangler.toml               # Cloudflare Workers & D1 configuration
├── admin/                      # Admin portal (PHP 8.4 Apache)
├── Dockerfile                  # Hardened PHP 8.4 Apache image
├── docker-compose.yml          # MariaDB 10.11 & PHP 8.4 orchestration
└── README.md                   # Dokumentasi proyek
```

---

## 📄 Lisensi

Proyek ini berlisensi open source di bawah [MIT License](LICENSE).
