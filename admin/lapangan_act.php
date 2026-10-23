<?php 
include '../koneksi.php';
session_start();
if (($_SESSION['status'] ?? '') != "login") {
	header("location:../login.php?alert=belum_login");
	exit();
}

$nama  = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$kategori = (int)($_POST['kategori'] ?? 1);
$harga = (int)($_POST['harga'] ?? 0);
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');

mysqli_query($koneksi, "INSERT INTO lapangan VALUES (NULL,'$nama','$kategori','$harga','$keterangan','','','')");
$last_id = mysqli_insert_id($koneksi);

foreach (['foto1' => 'lapangan_foto1', 'foto2' => 'lapangan_foto2', 'foto3' => 'lapangan_foto3'] as $input => $col) {
	if (!empty($_FILES[$input]['name'])) {
		$file_gambar = sportkuy_secure_upload_image($_FILES[$input], '../gambar/lapangan');
		if ($file_gambar !== null) {
			mysqli_query($koneksi, "UPDATE lapangan SET $col='$file_gambar' WHERE lapangan_id='$last_id'");
		}
	}
}

header("location:lapangan.php");
