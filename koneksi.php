<?php 
mysqli_report(MYSQLI_REPORT_OFF);

$db_host = getenv('DB_HOST') ?: 'db';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: 'asdfghjkl';
$db_name = getenv('DB_NAME') ?: 'project_sport_center';

$koneksi = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

/**
 * Anti-php2shell Secure Image Upload Validator & Saver (PHP 8.4 compatible)
 * Validates MIME magic bytes, image dimensions, rejects embedded PHP tags,
 * and generates a cryptographically random filename.
 */
if (!function_exists('sportkuy_secure_upload_image')) {
    function sportkuy_secure_upload_image(array $file, string $target_dir, int $max_bytes = 5242880): ?string {
        if (!isset($file['error']) || is_array($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }
        if ($file['size'] <= 0 || $file['size'] > $max_bytes) {
            return null;
        }

        // 1. Validate real MIME type via magic bytes (finfo)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed_mimes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];
        if (!isset($allowed_mimes[$mime])) {
            return null;
        }

        // 2. Verify valid image header & dimensions
        $img_info = @getimagesize($file['tmp_name']);
        if ($img_info === false || $img_info[0] <= 0 || $img_info[1] <= 0) {
            return null;
        }

        // 3. Scan raw file content for embedded PHP/shell payloads (php2shell defense)
        $raw = file_get_contents($file['tmp_name']);
        if ($raw === false) {
            return null;
        }
        if (preg_match('/<\?php|<\?=|<script\b|__HALT_COMPILER/i', $raw)) {
            return null;
        }

        // 4. Generate safe random filename with enforced canonical extension
        $ext = $allowed_mimes[$mime];
        $safe_name = bin2hex(random_bytes(12)) . '.' . $ext;

        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0755, true);
        }

        $dest = rtrim($target_dir, '/') . '/' . $safe_name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return null;
        }
        @chmod($dest, 0644);
        return $safe_name;
    }
}
