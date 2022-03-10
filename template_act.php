<?php 
include 'koneksi.php';

session_start();

$nama  = $_POST['nama'];
$profil = $_POST['profil'];
$jam = $_POST['jam'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$kontak = $_POST['kontak'];

$allowed =  array('gif','png','jpg','jpeg');

$gambar = $_FILES['foto']['name'];
$ext = pathinfo($gambar, PATHINFO_EXTENSION);
$file_gambar = $gambar;

	if(!in_array($ext,$allowed) ) {
        header("location:template.php?alert=gagal");
    }else{
        move_uploaded_file($_FILES['foto']['tmp_name'], 'frontend/img/'.$file_gambar);

    }

mysqli_query($koneksi,"insert into template values('$nama','$profil','$jam','$alamat','$email','$kontak','$file_gambar','PENDING')")or die(mysqli_error($koneksi));




header("location:template.php?alert=berhasil");