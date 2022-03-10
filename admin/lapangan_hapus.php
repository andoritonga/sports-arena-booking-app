<?php 
include '../koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "select * from lapangan where lapangan_id='$id'");
$d = mysqli_fetch_assoc($data);
$foto1 = $d['lapangan_foto1'];
$foto2 = $d['lapangan_foto2'];
$foto3 = $d['lapangan_foto3'];

error_reporting (0);
unlink("../gambar/lapangan/$foto1");
unlink("../gambar/lapangan/$foto2");
unlink("../gambar/lapangan/$foto3");

mysqli_query($koneksi, "delete from lapangan where lapangan_id='$id'");


$data = mysqli_query($koneksi, "select * from transaksi where transaksi_lapangan='$id'");
while($d=mysqli_fetch_array($data)){
	$id_invoice = $d['transaksi_invoice'];

	mysqli_query($koneksi, "delete from invoice where invoice_id='$id'");
}

mysqli_query($koneksi, "delete from transaksi where transaksi_lapangan='$id'");

header("location:lapangan.php");
