# 🏆 SportKuy - Modern Sports Arena Booking & Management System

[![Live Demo](https://img.shields.io/badge/Live_Demo-sportkuy.ritonga.xyz-059669?style=for-the-badge&logo=google-chrome&logoColor=white)](https://sportkuy.ritonga.xyz)
[![PWA Ready](https://img.shields.io/badge/PWA-Installable-8b5cf6?style=for-the-badge&logo=pwa&logoColor=white)](https://sportkuy.ritonga.xyz)
[![Bilingual](https://img.shields.io/badge/Language-ID%20%7C%20EN-3b82f6?style=for-the-badge)](https://sportkuy.ritonga.xyz)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/MariaDB-10.11-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ed?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

> ### 🌐 **Live Demo Online**: [https://sportkuy.ritonga.xyz](https://sportkuy.ritonga.xyz)  
> Experience SportKuy live in your browser, or install it directly to your smartphone's home screen as a Progressive Web App (PWA)!

---

## 📌 About SportKuy (Public Demo Edition)

**SportKuy** is an end-to-end web platform and Progressive Web App for sports center court reservations, schedule tracking, and arena facility management. It features a modern, mobile-optimized customer booking interface alongside a back-office administration portal for venue operators.

> **ℹ️ Public Demo Edition Notice:**  
> This repository contains the **Public Demo Edition** of SportKuy. It is pre-configured with built-in data integrity safeguards to allow visitors, developers, and potential clients to safely test and evaluate all core reservation flows without corrupting shared demonstration data.

---

## ✨ Features & Highlights

### 📱 Modern Mobile App Shell & PWA (Progressive Web App)
* **Installable Native Experience**: Add SportKuy to your Android, iOS, or desktop home screen with a standalone app shell.
* **Service Worker Caching**: Fast loading, offline fallback page, and smooth navigation.
* **Mobile App Navigation**: Floating bottom tab bar (Home, Courts, Categories, Orders/Account) optimized for one-thumb mobile browsing.
* **Category Drawer**: Interactive bottom sheet drawer for filtering venues across sports categories.

### 🌐 Bilingual Support (Indonesian & English)
* **One-Click Language Switcher**: Switch effortlessly between Bahasa Indonesia (`ID`) and English (`EN`).
* **Persistent Preference**: Language choice is automatically remembered across sessions and devices.

### 👤 Customer Booking Portal
* **Venue Directory**: Browse sports fields across categories (Futsal, Badminton, Basketball, Mini Soccer, etc.) with HD galleries, hourly rates, and facility tags.
* **Real-time Court Availability**: Interactive court schedulers prevent double-bookings and display busy slots.
* **Online Booking & Invoice Generation**: Instant reservation calculation, booking codes, and printable invoices (`@media print` supported).
* **Payment Proof Upload**: Submit transfer payment receipts (`.jpg`, `.png`, `.jpeg`) directly from the customer dashboard.
* **Customer Dashboard**: Track active reservations, confirmation statuses, and past booking history.

### 🛡️ Admin Management Portal
* **Executive Dashboard**: Real-time revenue overview, total court facilities, registered customers, and incoming transaction feeds.
* **Transaction Workflow**: Review uploaded payment proofs in an interactive modal, verify payments, and update booking status (*Menunggu Bayar*, *Menunggu Konfirmasi*, *Dikonfirmasi*, *Selesai*, *Ditolak*).
* **Arena & Facility Management**: Create and manage court listings, photo galleries, and hourly pricing.
* **Sales & Financial Reports**: Filter revenue by custom date ranges, export formatted PDF statements, and print official financial summaries.
* **Category & Customer Directories**: Overview of registered venue categories and customer records.

---

## 🛠️ Tech Stack

* **Backend**: PHP 7.4+
* **Database**: MariaDB 10.11 / MySQL 8.0
* **Frontend**: HTML5, Modern CSS3 (`pwa-app.css`, `modern-custom.css`, `admin-modern.css`), JavaScript, Bootstrap 3
* **PWA Engine**: Service Worker (v1.1.0), Web App Manifest, App Icons (72px to 512px)
* **Typography & Icons**: Plus Jakarta Sans, Outfit, Font Awesome 4.7, Ionicons
* **Containerization**: Docker, Docker Compose
* **Orchestration**: Kubernetes manifests (`kubernetes.yaml`) included

---

## 🚀 Quick Start with Docker (Recommended)

Get the entire application up and running locally with a single command:

### 1. Clone the Repository
```bash
git clone https://github.com/andoritonga/sports-arena-booking-app.git
cd sports-arena-booking-app
```

### 2. Launch with Docker Compose
```bash
docker compose up -d
```

Docker Compose will automatically:
1. Spin up a MariaDB 10.11 database container (`sportkuy-db`) on port `3307`.
2. Automatically import the initial schema and demo data from `project_sport_center.sql`.
3. Build and launch the Apache PHP 7.4 application container (`sportkuy-app`) on port `8080`.

### 3. Open in Browser
* **Customer Website & Login**: [http://localhost:8080](http://localhost:8080)
* **Admin Portal**: [http://localhost:8080/admin/](http://localhost:8080/admin/)

---

## 💻 Manual Installation (Local Server / XAMPP / Laragon)

If you prefer running on a local Apache + MySQL environment:

1. **Clone or Copy Source Code**:  
   Place the project folder inside your web server document root (e.g. `htdocs/sport-kuy` or `www/sport-kuy`).

2. **Create Database & Import Schema**:  
   * Open phpMyAdmin or your MySQL client.
   * Create a database named `project_sport_center`.
   * Import `project_sport_center.sql` into the database.

3. **Configure Database Connection**:  
   Adjust credentials in `koneksi.php` or provide environment variables if needed:
   ```php
   $db_host = getenv('DB_HOST') ?: 'localhost';
   $db_user = getenv('DB_USER') ?: 'root';
   $db_pass = getenv('DB_PASSWORD') ?: '';
   $db_name = getenv('DB_NAME') ?: 'project_sport_center';
   ```

4. **Access the Application**:  
   Navigate to `http://localhost/sport-kuy/` in your browser.

---

## 🔐 Default Demo Accounts

| Role | Username / Email | Password | Access Portal |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin` | [http://localhost:8080/admin/](http://localhost:8080/admin/) |
| **Demo Customer** | `ritongando@gmail.com` | `12345678` | [http://localhost:8080/masuk.php](http://localhost:8080/masuk.php) |

*(Visitors can also register a brand new customer account directly via `daftar.php`)*

---

## 🛡️ Public Demo Safeguards

This Public Demo Edition includes active protection to preserve system availability and showcase integrity:
* **Account Safety**: Default administrative and demo customer credentials cannot be overwritten or locked out.
* **Master Data Protection**: Deletion of demonstration court facilities, sports categories, and foundational records is prevented.
* **Safe Sandbox**: Users are free to browse all menus, make test reservations, test the PWA features, and preview administrative workflows without risk of breaking the demo environment.

---

## 📁 Directory Structure

```text
sports-arena-booking-app/
├── admin/                      # Back-office admin portal & dashboard
│   ├── index.php               # Admin overview & stat metrics
│   ├── transaksi.php           # Booking & payment verification
│   ├── lapangan.php            # Arena facility management
│   ├── kategori.php            # Sports category management
│   ├── customer.php            # Registered customer directory
│   ├── laporan.php             # Financial sales report & filters
│   ├── laporan_print.php       # Printable financial statement
│   └── laporan_pdf.php         # PDF statement generator
├── assets/                     # Core vendor libraries & AdminLTE plugins
├── frontend/                   # Modern stylesheets, scripts & static assets
│   ├── css/
│   │   ├── pwa-app.css         # Modern Mobile PWA & App Shell stylesheet
│   │   ├── modern-custom.css   # Modern Customer Portal styling
│   │   └── admin-modern.css    # Modernized Admin Portal styling
│   ├── js/
│   │   └── pwa-app.js          # PWA Service Worker registrar & UI interactions
│   └── img/
│       ├── icons/              # PWA application icons (72px to 512px)
│       └── logo.png            # SportKuy brand logo
├── gambar/                     # Dynamic uploaded media
│   ├── bukti/                  # Customer payment transfer receipts
│   ├── lapangan/               # Arena facility photographs
│   └── user/                   # User profile avatars
├── lang.php                    # Bilingual translation engine (ID / EN)
├── manifest.json               # Progressive Web App manifest
├── sw.js                       # PWA Service Worker caching engine
├── offline.html                # Offline fallback experience
├── Dockerfile                  # PHP 7.4 Apache container specification
├── docker-compose.yml          # Multi-container orchestration config
├── kubernetes.yaml             # Kubernetes deployment & service spec
├── koneksi.php                 # Database connection handler & demo safeguards
├── masuk.php                   # Unified login gateway
├── index.php                   # Public landing & venue showcase
├── project_sport_center.sql    # MariaDB database dump
└── README.md                   # Project documentation
```

---

## 🗄️ Database Backup & Dumping

To regenerate an up-to-date SQL dump directly from the running Docker container:

```bash
docker exec sportkuy-db mariadb-dump -u root -pasdfghjkl project_sport_center > project_sport_center.sql
```

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).
