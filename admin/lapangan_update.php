<?php 
include '../koneksi.php';
session_start();
if (($_SESSION['status'] ?? '') != "login") {
	header("location:../login.php?alert=belum_login");
	exit();
}

$id  = (int)($_POST['id'] ?? 0);
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$kategori = (int)($_POST['kategori'] ?? 1);
$harga = (int)($_POST['harga'] ?? 0);
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');

mysqli_query($koneksi, "UPDATE lapangan SET lapangan_nama='$nama', lapangan_kategori='$kategori', lapangan_harga='$harga', lapangan_keterangan='$keterangan' WHERE lapangan_id='$id'");

$lama = mysqli_query($koneksi, "SELECT * FROM lapangan WHERE lapangan_id='$id'");
$l = mysqli_fetch_assoc($lama);

foreach (['foto1' => 'lapangan_foto1', 'foto2' => 'lapangan_foto2', 'foto3' => 'lapangan_foto3'] as $input => $col) {
	if (!empty($_FILES[$input]['name'])) {
		$file_gambar = sportkuy_secure_upload_image($_FILES[$input], '../gambar/lapangan');
		if ($file_gambar !== null) {
			mysqli_query($koneksi, "UPDATE lapangan SET $col='$file_gambar' WHERE lapangan_id='$id'");
		}
	}
}

header("location:lapangan.php");
