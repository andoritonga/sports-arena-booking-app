<?php 
include 'koneksi.php';

session_start();

$id_komentar = mysqli_real_escape_string($koneksi, $_GET['id']);
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['lapangan']);

mysqli_query($koneksi, "delete from komentar where komentar_id=$id_komentar");

header("location:lapangan_detail.php?id=$id_lapangan&alert=sukses-hapus");