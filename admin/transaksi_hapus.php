<?php 
include '../koneksi.php';

if (defined('DEMO_MODE') && DEMO_MODE) {
    header("location:transaksi.php?alert=demo_mode");
    exit();
}

$id = $_GET['id'];

mysqli_query($koneksi, "delete from invoice where invoice_id='$id'");

mysqli_query($koneksi,"delete from transaksi where transaksi_invoice='$id'");

header("location:transaksi.php");