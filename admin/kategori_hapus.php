<?php 
include '../koneksi.php';

if (defined('DEMO_MODE') && DEMO_MODE) {
    header("location:kategori.php?alert=demo_mode");
    exit();
}

$id = $_GET['id'];

mysqli_query($koneksi, "delete from kategori where kategori_id='$id'");


mysqli_query($koneksi,"update produk set produk_kategori='1' where produk_kategori='$id'");

header("location:kategori.php");
