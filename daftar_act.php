<?php 
include 'koneksi.php';
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cek_email = mysqli_query($koneksi,"select * from customer where customer_email='$email'");
if(mysqli_num_rows($cek_email) > 0){
	header("location:daftar.php?alert=duplikat");
}else{
	mysqli_query($koneksi, "insert into customer values (NULL,'$nama','$email','$hp','$alamat','$password')");
	header("location:masuk.php?alert=terdaftar");
}

