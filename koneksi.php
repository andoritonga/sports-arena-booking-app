<?php 

$db_host = getenv('DB_HOST') ?: 'db';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: 'asdfghjkl';
$db_name = getenv('DB_NAME') ?: 'project_sport_center';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// =========================================================================
// DEMO MODE CONFIGURATION (PUBLIC GITHUB PROTECTION)
// =========================================================================
// Nilai 'true' mengaktifkan proteksi master data untuk repositori publik / demo:
// - Mencegah perubahan kata sandi admin utama & akun demo
// - Mencegah penghapusan master data lapangan, kategori, admin, dan transaksi
// Dapat diatur juga via environment variable: DEMO_MODE=false
$demo_env = getenv('DEMO_MODE');
define('DEMO_MODE', $demo_env !== false ? filter_var($demo_env, FILTER_VALIDATE_BOOLEAN) : true);
