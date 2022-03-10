<?php 
include 'koneksi.php';

session_start();

$id_customer = $_SESSION['customer_id'];
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);

$tanggal = date('Y-m-d');
$jamMulai = $_POST['jamMulai'];
$jamSelesai = $_POST['jamSelesai'];
$nama = $_POST['nama'];
$hp = $_POST['hp'];
$tglMain = $_POST['tglMain'];
$harga = $_POST['harga'];
$jumlahJam = (int)$jamSelesai - (int)$jamMulai;
$total_bayar = $harga*$jumlahJam;

mysqli_query($koneksi,"insert into invoice values(NULL,'$id_lapangan','$tanggal','$id_customer','$nama','$hp','$tglMain','$jamMulai','$jamSelesai','$harga','$total_bayar','0','')")or die(mysqli_error($koneksi));

header("location:customer_pesanan.php?alert=sukses");