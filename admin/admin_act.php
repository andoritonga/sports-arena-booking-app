<?php 
include '../koneksi.php';
session_start();
if (($_SESSION['status'] ?? '') != "login") {
	header("location:../login.php?alert=belum_login");
	exit();
}

$nama  = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$password = md5($_POST['password'] ?? '');

$filename = $_FILES['foto']['name'] ?? '';

if ($filename == "") {
	mysqli_query($koneksi, "INSERT INTO admin VALUES (NULL,'$nama','$username','$password','')");
	header("location:admin.php");
} else {
	$file_gambar = sportkuy_secure_upload_image($_FILES['foto'], '../gambar/user');
	if ($file_gambar === null) {
		header("location:admin.php?alert=gagal");
	} else {
		mysqli_query($koneksi, "INSERT INTO admin VALUES (NULL,'$nama','$username','$password','$file_gambar')");
		header("location:admin.php");
	}
}
