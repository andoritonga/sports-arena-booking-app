<?php
include 'header.php';
$tanggal = $_POST['tgl'];
$id_lapangan = $id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);
mysqli_query($koneksi, "insert into jadwal values(NULL ,'$id_lapangan','$tanggal','08.00','09.00',DEFAULT), (NULL ,'$id_lapangan','$tanggal','09.00','10.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','10.00','11.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','11.00','12.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','12.00','13.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','13.00','14.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','14.00','15.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','15.00','16.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','16.00','17.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','17.00','18.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','18.00','19.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','19.00','20.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','20.00','21.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','21.00','22.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','22.00','23.00',DEFAULT)")or die(mysqli_error($koneksi));


header("location:lapangan_detail.php?id=$id_lapangan");
?>