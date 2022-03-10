<?php 
include '../koneksi.php';
$invoice  = $_POST['invoice'];
$status  = $_POST['status'];

mysqli_query($koneksi, "update invoice set invoice_status='$status' where invoice_id='$invoice'");


$transaksi = mysqli_query($koneksi, "select * from invoice where invoice_id = '$invoice'");
$x=mysqli_fetch_array($transaksi);
    if ($x['invoice_status'] == "3" OR $x['invoice_status'] == "4"){
        mysqli_query($koneksi, "update jadwal set jadwal_status = 'SUDAH DIPESAN' where jadwal_lapangan = '$x[invoice_lapangan]' AND jadwal_tanggal = '$x[invoice_tgl_main]' AND jadwal_mulai BETWEEN '$x[invoice_jam_mulai]' AND '$x[invoice_jam_selesai]' AND jadwal_selesai BETWEEN '$x[invoice_jam_mulai]' AND '$x[invoice_jam_selesai]'");
    } elseif ($x['invoice_status'] == "0" OR $x['invoice_status'] == "1" OR $x['invoice_status'] == "2"){
        mysqli_query($koneksi, "update jadwal set jadwal_status = 'TERSEDIA' where jadwal_lapangan = '$x[invoice_lapangan]' AND jadwal_tanggal = '$x[invoice_tgl_main]' AND jadwal_mulai BETWEEN '$x[invoice_jam_mulai]' AND '$x[invoice_jam_selesai]' AND jadwal_selesai BETWEEN '$x[invoice_jam_mulai]' AND '$x[invoice_jam_selesai]'");
    }

header("location:transaksi.php?alert=sukses");

?>