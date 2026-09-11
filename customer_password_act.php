<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';

session_start();

$id = $_SESSION['customer_id'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi,"update customer set customer_password='$password' where customer_id='$id'");

header("location:customer_password.php?alert=sukses");