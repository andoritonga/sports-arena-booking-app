<?php 
include '../koneksi.php';
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email  = mysqli_real_escape_string($koneksi, $_POST['email']);
$hp  = mysqli_real_escape_string($koneksi, $_POST['hp']);
$alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi, "insert into customer values (NULL,'$nama','$email','$hp','$alamat','$password')");
header("location:customer.php");