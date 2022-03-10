<?php 
include '../koneksi.php';
$id  = $_POST['id'];
$nama  = $_POST['nama'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$keterangan = $_POST['keterangan'];

$rand = rand();
$allowed =  array('gif','png','jpg','jpeg');

$filename1 = $_FILES['foto1']['name'];
$filename2 = $_FILES['foto2']['name'];
$filename3 = $_FILES['foto3']['name'];

mysqli_query($koneksi, "update lapangan set lapangan_nama='$nama', lapangan_kategori='$kategori', lapangan_harga='$harga', lapangan_keterangan='$keterangan' where lapangan_id='$id'");



if($filename1 != ""){
	$ext = pathinfo($filename1, PATHINFO_EXTENSION);

	if(in_array($ext,$allowed) ) {
		move_uploaded_file($_FILES['foto1']['tmp_name'], '../gambar/lapangan/'.$rand.'_'.$filename1);
		$file_gambar = $rand.'_'.$filename1;

		// hapus foto lama
		$lama = mysqli_query($koneksi, "select * from lapangan where lapangan_id='$id'");
		$l = mysqli_fetch_assoc($lama);
		$foto = $l['lapangan_foto1'];
		unlink("../gambar/lapangan/$foto");

		mysqli_query($koneksi,"update lapangan set lapangan_foto1='$file_gambar' where lapangan_id='$id'");
	}
}

if($filename2 != ""){
	$ext = pathinfo($filename2, PATHINFO_EXTENSION);

	if(in_array($ext,$allowed) ) {
		move_uploaded_file($_FILES['foto2']['tmp_name'], '../gambar/lapangan/'.$rand.'_'.$filename2);
		$file_gambar = $rand.'_'.$filename2;

		// hapus foto lama
		$lama = mysqli_query($koneksi, "select * from lapangan where lapangan_id='$id'");
		$l = mysqli_fetch_assoc($lama);
		$foto = $l['lapangan_foto2'];
		unlink("../gambar/lapangan/$foto");

		mysqli_query($koneksi,"update lapangan set lapangan_foto2='$file_gambar' where lapangan_id='$id'");
	}
}

if($filename3 != ""){
	$ext = pathinfo($filename3, PATHINFO_EXTENSION);

	if(in_array($ext,$allowed) ) {
		move_uploaded_file($_FILES['foto3']['tmp_name'], '../gambar/lapangan/'.$rand.'_'.$filename3);
		$file_gambar = $rand.'_'.$filename3;

		// hapus foto lama
		$lama = mysqli_query($koneksi, "select * from lapangan where lapangan_id='$id'");
		$l = mysqli_fetch_assoc($lama);
		$foto = $l['lapangan_foto3'];
		unlink("../gambar/lapangan/$foto");

		mysqli_query($koneksi,"update lapangan set lapangan_foto3='$file_gambar' where lapangan_id='$id'");
	}
}

header("location:lapangan.php");