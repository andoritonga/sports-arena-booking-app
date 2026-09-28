<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';

session_start();

$id = $_SESSION['customer_id'];

// Proteksi akun demo utama pada versi publik
$check_cust = mysqli_query($koneksi, "SELECT customer_email FROM customer WHERE customer_id='$id'");
$c = mysqli_fetch_assoc($check_cust);
if (defined('DEMO_MODE') && DEMO_MODE && ($c['customer_email'] == 'ritongando@gmail.com' || $id == 13 || $id == 8)) {
    header("location:customer_password.php?alert=demo_mode");
    exit();
}

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi,"update customer set customer_password='$password' where customer_id='$id'");

header("location:customer_password.php?alert=sukses");