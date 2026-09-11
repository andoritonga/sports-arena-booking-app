# 🏆 SportKuy - Modern Sports Arena Booking & Management System

[![PHP](https://img.shields.io/badge/PHP-7.4+-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/MariaDB-10.11-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ed?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**SportKuy** is an end-to-end web application for sports center reservations and arena facility management. It provides a sleek, modern booking experience for customers and a full-featured back-office management portal for arena owners and administrators.

---

## ✨ Features

### 👤 Customer Facing Portal
* **Modern Venue Showcase**: Browse arenas across various categories (Futsal, Badminton, Basketball, Mini Soccer, etc.) with HD galleries and hourly rates.
* **Interactive Field Schedule & Schedulers**: Check real-time court availability, active bookings, and select match duration.
* **Unified Dual-Role Authentication**: Seamless login gateway automatically directing customers to their dashboard and administrators to the management portal.
* **Online Booking & Checkout**: Streamlined reservation flow with invoice generation and payment instructions.
* **Payment Receipt Upload**: Upload transfer proofs (`.jpg`, `.png`, `.jpeg`) directly from the customer order panel.
* **Printable Executive Invoices**: High-contrast, pixel-perfect printable invoice statements (`@media print` supported).
* **Progressive Web App (PWA) Ready**: Offline-capable service worker and installable app manifest.

### 🛡️ Admin Management Portal
* **Executive Dashboard**: Real-time business metrics including confirmed revenue, active reservations, arena count, and recent transactions.
* **Booking & Transaction Workflow**: Instant payment receipt verification modal, one-click status transitions (*Menunggu Bayar*, *Menunggu Konfirmasi*, *Dikonfirmasi*, *Selesai*, *Ditolak*).
* **Arena & Facility Management**: Create, edit, and manage fields with multi-photo gallery uploads and hourly rate configuration.
* **Category Directory**: Organize sports centers and facility types with registered venue counters.
* **Customer Directory**: Manage customer databases, contact info, and booking histories.
* **Financial & Sales Reports**: Filter sales performance by preset intervals (*Hari Ini*, *Bulan Ini*, *30 Hari Terakhir*, *Tahun Ini*), export formal PDF statements, and print formatted revenue recaps.
* **Administrator Security**: Role-based access control and admin profile/password settings.

---

## 🛠️ Tech Stack

* **Backend**: PHP 7.4+
* **Database**: MariaDB 10.11 / MySQL 8.0
* **Frontend**: HTML5, Modern CSS3 (`admin-modern.css`, `modern-custom.css`), JavaScript, Bootstrap 3
* **Typography & Icons**: Google Fonts (*Outfit*, *Plus Jakarta Sans*), Font Awesome 4.7, Ionicons
* **Containerization**: Docker, Docker Compose
* **Orchestration**: Kubernetes manifests (`kubernetes.yaml`) included

---

## 🚀 Quick Start with Docker (Recommended)

Get the entire application up and running with a single command:

### 1. Clone the Repository
```bash
git clone https://github.com/andoritonga/sport-kuy.git
cd sport-kuy
```

### 2. Launch with Docker Compose
```bash
docker compose up -d
```

Docker Compose will automatically:
1. Spin up a MariaDB 10.11 container (`sportkuy-db`) on port `3307`.
2. Automatically import the initial schema and demo data from `project_sport_center.sql`.
3. Build and run the Apache PHP 7.4 container (`sportkuy-app`) on port `8080`.

### 3. Open in Browser
* **Customer Website & Login**: [http://localhost:8080](http://localhost:8080)
* **Admin Portal**: [http://localhost:8080/admin/](http://localhost:8080/admin/)

---

## 💻 Manual Installation (Local Server / XAMPP / Laragon)

If you prefer running the project on a local Apache + MySQL stack:

1. **Clone or Copy Source Code**:
   Place the project directory inside your web server document root (e.g. `htdocs/sport-kuy` or `www/sport-kuy`).

2. **Create Database & Import Schema**:
   * Open phpMyAdmin or your MySQL client.
   * Create a new database named `project_sport_center`.
   * Import the file `project_sport_center.sql` into the database.

3. **Configure Database Connection**:
   Edit `koneksi.php` or provide environment variables if needed:
   ```php
   $db_host = getenv('DB_HOST') ?: 'localhost';
   $db_user = getenv('DB_USER') ?: 'root';
   $db_pass = getenv('DB_PASSWORD') ?: '';
   $db_name = getenv('DB_NAME') ?: 'project_sport_center';
   ```

4. **Access the Application**:
   Navigate to `http://localhost/sport-kuy/` in your browser.

---

## 🔐 Default Demo Account

| Role | Username | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin` |

*(New customers can register an account directly via the website registration page at `daftar.php`)*

---

## 📁 Directory Structure

```text
sport-kuy/
├── admin/                      # Admin dashboard & management portal
│   ├── index.php               # Admin overview & stat metrics
│   ├── transaksi.php           # Booking & payment verification
│   ├── lapangan.php            # Arena facility management
│   ├── kategori.php            # Sports category management
│   ├── customer.php            # Registered customer directory
│   ├── laporan.php             # Financial sales report & filters
│   ├── laporan_print.php       # Printable financial statement
│   └── laporan_pdf.php         # PDF statement generator
├── assets/                     # Core vendor libraries & AdminLTE plugins
├── frontend/                   # Modern stylesheets, fonts & static images
│   ├── css/
│   │   ├── admin-modern.css    # Modernized stylesheet for Admin Portal
│   │   ├── modern-custom.css   # Modernized stylesheet for Customer Portal
│   │   └── style.css           # Base theme stylesheet
│   └── img/                    # Hero banners & illustrations
├── gambar/                     # Dynamic uploaded assets
│   ├── bukti/                  # Customer payment transfer receipts
│   ├── lapangan/               # Arena facility photographs
│   └── user/                   # User profile avatars
├── Dockerfile                  # PHP 7.4 Apache image definition
├── docker-compose.yml          # Multi-container orchestration config
├── kubernetes.yaml             # Kubernetes deployment & service spec
├── koneksi.php                 # Database connection handler
├── masuk.php                   # Unified authentication page
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
