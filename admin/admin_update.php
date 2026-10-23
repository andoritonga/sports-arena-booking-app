<?php 
include '../koneksi.php';
session_start();
if (($_SESSION['status'] ?? '') != "login") {
	header("location:../login.php?alert=belum_login");
	exit();
}

$id  = (int)($_POST['id'] ?? 0);
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$pwd = $_POST['password'] ?? '';
$password = md5($pwd);

$filename = $_FILES['foto']['name'] ?? '';

if ($pwd == "" && $filename == "") {
	mysqli_query($koneksi, "UPDATE admin SET admin_nama='$nama', admin_username='$username' WHERE admin_id='$id'");
	header("location:admin.php");
} elseif ($pwd == "") {
	$file_gambar = sportkuy_secure_upload_image($_FILES['foto'], '../gambar/user');
	if ($file_gambar === null) {
		header("location:admin.php?alert=gagal");
	} else {
		mysqli_query($koneksi, "UPDATE admin SET admin_nama='$nama', admin_username='$username', admin_foto='$file_gambar' WHERE admin_id='$id'");
		header("location:admin.php?alert=berhasil");
	}
} elseif ($filename == "") {
	mysqli_query($koneksi, "UPDATE admin SET admin_nama='$nama', admin_username='$username', admin_password='$password' WHERE admin_id='$id'");
	header("location:admin.php");
} else {
	$file_gambar = sportkuy_secure_upload_image($_FILES['foto'], '../gambar/user');
	if ($file_gambar === null) {
		header("location:admin.php?alert=gagal");
	} else {
		mysqli_query($koneksi, "UPDATE admin SET admin_nama='$nama', admin_username='$username', admin_password='$password', admin_foto='$file_gambar' WHERE admin_id='$id'");
		header("location:admin.php?alert=berhasil");
	}
}
