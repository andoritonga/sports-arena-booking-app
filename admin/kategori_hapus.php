<?php 
include '../koneksi.php';
session_start();
if (($_SESSION['status'] ?? '') != "login") {
	header("location:../login.php?alert=belum_login");
	exit();
}

$id = (int)($_GET['id'] ?? 0);

mysqli_query($koneksi, "DELETE FROM kategori WHERE kategori_id='$id'");
mysqli_query($koneksi, "UPDATE lapangan SET lapangan_kategori='1' WHERE lapangan_kategori='$id'");

header("location:kategori.php");
