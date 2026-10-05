<?php
/**
 * SportKuy Bilingual Internationalization Engine (ID / EN)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Language Switch via GET parameter ?lang=id or ?lang=en
if (isset($_GET['lang'])) {
    $selected_lang = strtolower(trim($_GET['lang']));
    if (in_array($selected_lang, ['id', 'en'])) {
        $_SESSION['sportkuy_lang'] = $selected_lang;
        setcookie('sportkuy_lang', $selected_lang, time() + (86400 * 365), '/');
    }
} elseif (isset($_COOKIE['sportkuy_lang']) && in_array($_COOKIE['sportkuy_lang'], ['id', 'en'])) {
    $_SESSION['sportkuy_lang'] = $_COOKIE['sportkuy_lang'];
} elseif (!isset($_SESSION['sportkuy_lang'])) {
    // Default language is Indonesian (id)
    $_SESSION['sportkuy_lang'] = 'id';
}

$CURRENT_LANG = $_SESSION['sportkuy_lang'];

// Language Dictionary
$LANG_DICT = [
    'id' => [
        // App & Navigation
        'app_name' => 'SportKuy',
        'home' => 'Beranda',
        'about' => 'Tentang Kami',
        'categories' => 'Kategori Lapangan',
        'all_categories' => 'Tampilkan Semua',
        'all' => 'Semua',
        'menu' => 'Menu',
        'login' => 'LOGIN',
        'register' => 'DAFTAR',
        'logout' => 'Keluar',
        'dashboard' => 'Dashboard',
        'my_bookings' => 'Pesanan Saya',
        'change_password' => 'Ganti Password',
        'install_app' => 'Pasang App',
        'account' => 'Akun',
        'search' => 'Cari',

        // Hero Banner
        'hero_title' => 'Sewa Lapangan Olahraga<br>Terfavorit & Terlengkap',
        'hero_subtitle' => 'Temukan dan booking lapangan Futsal, Badminton, Basketball, hingga Mini Soccer dalam hitungan detik.',
        'search_placeholder' => 'Cari nama lapangan atau lokasi...',
        'search_btn' => 'Cari Lapangan',
        'popular_categories' => 'Kategori Populer:',

        // Store & Listing
        'all_venues' => 'Semua Daftar Lapangan',
        'search_results_for' => 'Hasil Pencarian:',
        'category_venues' => 'Kategori:',
        'sort_by' => 'Urutkan:',
        'sort_latest' => 'Terbaru',
        'sort_price_low' => 'Harga Terendah',
        'per_session' => '/ Jam Sesi',
        'book_now' => 'Sewa Sekarang',
        'view_detail' => 'Lihat Detail',
        'select_category_title' => 'Pilih Kategori Lapangan',

        // Detail Page
        'venue_detail' => 'Detail Lapangan',
        'spec_competition' => 'Standar Kompetisi',
        'spec_lighting' => 'Lampu LED 1000 Lux',
        'spec_shower' => 'Shower & Ruang Ganti',
        'spec_wifi' => 'Free WiFi',
        'action_select_schedule' => 'Pilih Jadwal & Booking',
        'tab_schedule' => 'Jadwal & Sesi Lapangan',
        'tab_description' => 'Deskripsi & Spesifikasi',
        'tab_reviews' => 'Ulasan & Komentar',
        'select_date' => 'Pilih Tanggal Permainan:',
        'show' => 'Tampilkan',
        'session_time' => 'Jam Sesi',
        'session_price' => 'Harga',
        'session_status' => 'Status',
        'status_available' => 'Tersedia',
        'status_booked' => 'Sudah Dipesan',
        'btn_book' => 'Pesan Sekarang',

        // Customer & Orders
        'customer_menu' => 'Menu Pelanggan',
        'customer_greeting' => 'Halo, Selamat Datang!',
        'customer_name' => 'Nama',
        'customer_email' => 'Email',
        'customer_phone' => 'Nomor HP',
        'customer_address' => 'Alamat',
        'invoice_no' => 'No. Invoice',
        'booking_date' => 'Tanggal Booking',
        'play_date' => 'Tanggal Main',
        'total_amount' => 'Total Bayar',
        'payment_status' => 'Status Pembayaran',
        'action' => 'Aksi',

        // PWA & Prompts
        'pwa_prompt_title' => 'Pasang SportKuy App',
        'pwa_prompt_desc' => 'Akses booking instan di layar beranda tanpa browser!',
        'pwa_install_btn' => 'Pasang',
        'offline_toast' => 'Koneksi internet terputus. Mode offline aktif.',
        'online_toast' => 'Koneksi internet terhubung kembali.',
        'switch_lang_title' => 'Ganti Bahasa',
    ],
    'en' => [
        // App & Navigation
        'app_name' => 'SportKuy',
        'home' => 'Home',
        'about' => 'About Us',
        'categories' => 'Field Categories',
        'all_categories' => 'Show All',
        'all' => 'All',
        'menu' => 'Menu',
        'login' => 'LOGIN',
        'register' => 'REGISTER',
        'logout' => 'Logout',
        'dashboard' => 'Dashboard',
        'my_bookings' => 'My Bookings',
        'change_password' => 'Change Password',
        'install_app' => 'Install App',
        'account' => 'Account',
        'search' => 'Search',

        // Hero Banner
        'hero_title' => 'Book Your Favorite<br>Sports Venues Instantly',
        'hero_subtitle' => 'Find and book Futsal, Badminton, Basketball, and Mini Soccer courts in seconds.',
        'search_placeholder' => 'Search venue name or location...',
        'search_btn' => 'Search Venue',
        'popular_categories' => 'Popular Categories:',

        // Store & Listing
        'all_venues' => 'All Available Venues',
        'search_results_for' => 'Search Results:',
        'category_venues' => 'Category:',
        'sort_by' => 'Sort by:',
        'sort_latest' => 'Latest',
        'sort_price_low' => 'Lowest Price',
        'per_session' => '/ Hour Session',
        'book_now' => 'Book Now',
        'view_detail' => 'View Details',
        'select_category_title' => 'Select Field Category',

        // Detail Page
        'venue_detail' => 'Venue Details',
        'spec_competition' => 'Competition Standard',
        'spec_lighting' => 'LED Lights 1000 Lux',
        'spec_shower' => 'Shower & Locker Room',
        'spec_wifi' => 'Free WiFi',
        'action_select_schedule' => 'Select Schedule & Book',
        'tab_schedule' => 'Schedule & Court Sessions',
        'tab_description' => 'Description & Specs',
        'tab_reviews' => 'Reviews & Comments',
        'select_date' => 'Select Play Date:',
        'show' => 'Show',
        'session_time' => 'Session Time',
        'session_price' => 'Price',
        'session_status' => 'Status',
        'status_available' => 'Available',
        'status_booked' => 'Booked',
        'btn_book' => 'Book Now',

        // Customer & Orders
        'customer_menu' => 'Customer Menu',
        'customer_greeting' => 'Hello, Welcome!',
        'customer_name' => 'Name',
        'customer_email' => 'Email',
        'customer_phone' => 'Phone',
        'customer_address' => 'Address',
        'invoice_no' => 'Invoice No.',
        'booking_date' => 'Booking Date',
        'play_date' => 'Play Date',
        'total_amount' => 'Total Amount',
        'payment_status' => 'Payment Status',
        'action' => 'Action',

        // PWA & Prompts
        'pwa_prompt_title' => 'Install SportKuy App',
        'pwa_prompt_desc' => 'Fast booking access right from your home screen!',
        'pwa_install_btn' => 'Install',
        'offline_toast' => 'Internet connection lost. Offline mode active.',
        'online_toast' => 'Internet connection restored.',
        'switch_lang_title' => 'Switch Language',
    ]
];

/**
 * Translation helper function
 */
function __t($key, $default = '') {
    global $LANG_DICT, $CURRENT_LANG;
    if (isset($LANG_DICT[$CURRENT_LANG][$key])) {
        return $LANG_DICT[$CURRENT_LANG][$key];
    }
    // Fallback to Indonesian
    if (isset($LANG_DICT['id'][$key])) {
        return $LANG_DICT['id'][$key];
    }
    return !empty($default) ? $default : $key;
}

/**
 * Get current active language code ('id' or 'en')
 */
function get_current_lang() {
    global $CURRENT_LANG;
    return $CURRENT_LANG;
}

/**
 * Helper to build language switch URL while preserving existing query parameters
 */
function get_lang_switch_url($target_lang) {
    $params = $_GET;
    $params['lang'] = $target_lang;
    $current_file = basename($_SERVER['PHP_SELF']);
    return $current_file . '?' . http_build_query($params);
}
