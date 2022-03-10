<?php 
include 'koneksi.php';

session_start();


$id_customer = $_SESSION['customer_id'];
$customer = mysqli_query($koneksi,"select * from customer where customer_id='$id_customer'");
$c = mysqli_fetch_assoc($customer);
$nama_customer = $c['customer_nama'];
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);
$tanggal = date('Y-m-d');
$komentar = $_POST['komentar'];


mysqli_query($koneksi,"insert into komentar values(NULL,'$id_lapangan','$id_customer','$nama_customer','$tanggal','$komentar')")or die(mysqli_error($koneksi));

header("location:lapangan_detail.php?id=$id_lapangan&alert=sukses-komentar");