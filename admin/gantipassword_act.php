<?php 
include '../koneksi.php';
session_start();

if (defined('DEMO_MODE') && DEMO_MODE) {
    header("location:gantipassword.php?alert=demo_mode");
    exit();
}

$id = $_SESSION['id'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi, "UPDATE admin SET admin_password='$password' WHERE admin_id='$id'")or die(mysqli_error($koneksi));

header("location:gantipassword.php?alert=sukses");