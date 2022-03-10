<?php
include 'koneksi.php';

session_start();
$id = $_GET['id'];
$data = mysqli_query($koneksi,"select * from template where template_kontak='$id'");
while($x = mysqli_fetch_array($data)){

    $logo = $x['template_logo'];

    unlink("frontend/img/$logo");

    mysqli_query($koneksi, "update template set template_status='DITOLAK' where template_kontak='$id'");
}
header("location:owner.php?alert=status");