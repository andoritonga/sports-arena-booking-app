<?php 
include '../koneksi.php';
$id  = mysqli_real_escape_string($koneksi, $_POST['id']);

if (defined('DEMO_MODE') && DEMO_MODE && $id == 1) {
    header("location:admin.php?alert=demo_mode");
    exit();
}
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$pwd = $_POST['password'];
$password = !empty($pwd) ? password_hash($pwd, PASSWORD_DEFAULT) : '';


// cek gambar
$rand = rand();
$allowed =  array('gif','png','jpg','jpeg');
$filename = $_FILES['foto']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);

if($pwd=="" && $filename==""){
	mysqli_query($koneksi, "update admin set admin_nama='$nama', admin_username='$username' where admin_id='$id'");
	header("location:admin.php");
}elseif($pwd==""){
	if(!in_array($ext,$allowed) ) {
		header("location:admin.php?alert=gagal");
	}else{
		move_uploaded_file($_FILES['foto']['tmp_name'], '../gambar/user/'.$rand.'_'.$filename);
		$x = $rand.'_'.$filename;
		mysqli_query($koneksi, "update admin set admin_nama='$nama', admin_username='$username', admin_foto='$x' where admin_id='$id'");		
		header("location:admin.php?alert=berhasil");
	}
}elseif($filename==""){
	mysqli_query($koneksi, "update admin set admin_nama='$nama', admin_username='$username', admin_password='$password' where admin_id='$id'");
	header("location:admin.php");
}

