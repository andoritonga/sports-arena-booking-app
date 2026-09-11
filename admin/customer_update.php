<?php 
include '../koneksi.php';

$id  = mysqli_real_escape_string($koneksi, $_POST['id']);
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email  = mysqli_real_escape_string($koneksi, $_POST['email']);
$hp  = mysqli_real_escape_string($koneksi, $_POST['hp']);
$alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);

if(empty($_POST['password'])){
	mysqli_query($koneksi, "update customer set customer_nama='$nama', customer_email='$email', customer_hp='$hp', customer_alamat='$alamat' where customer_id='$id'");
}else{
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	mysqli_query($koneksi, "update customer set customer_nama='$nama', customer_email='$email', customer_hp='$hp', customer_alamat='$alamat', customer_password='$password' where customer_id='$id'");
}

header("location:customer.php");