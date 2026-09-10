<?php 

$db_host = getenv('DB_HOST') ?: 'db';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: 'asdfghjkl';
$db_name = getenv('DB_NAME') ?: 'project_sport_center';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}
