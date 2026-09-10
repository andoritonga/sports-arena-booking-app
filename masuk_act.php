<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';

// menangkap data yang dikirim dari form
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$password = mysqli_real_escape_string($koneksi, md5($_POST['password']));

// 1. Cek Customer
$login_customer = mysqli_query($koneksi, "SELECT * FROM customer WHERE (customer_email='$email' OR customer_nama='$email') AND customer_password='$password'");
$cek_customer = mysqli_num_rows($login_customer);

if($cek_customer > 0){
	session_start();
	$data = mysqli_fetch_assoc($login_customer);

	// hapus session yg lain, agar tidak bentrok dengan session admin
	unset($_SESSION['id']);
	unset($_SESSION['nama']);
	unset($_SESSION['username']);
	unset($_SESSION['status']);

	// buat session customer
	$_SESSION['customer_id'] = $data['customer_id'];
	$_SESSION['customer_status'] = "login";
	header("location:customer.php");
	exit();
}

// 2. Cek Admin
$login_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE admin_username='$email' AND admin_password='$password'");
$cek_admin = mysqli_num_rows($login_admin);

if($cek_admin > 0){
	session_start();
	$data_admin = mysqli_fetch_assoc($login_admin);

	// hapus session customer agar tidak bentrok
	unset($_SESSION['customer_id']);
	unset($_SESSION['customer_status']);

	// buat session admin
	$_SESSION['id'] = $data_admin['admin_id'];
	$_SESSION['nama'] = $data_admin['admin_nama'];
	$_SESSION['username'] = $data_admin['admin_username'];
	$_SESSION['status'] = "login";

	header("location:admin/");
	exit();
}

// 3. Jika tidak ditemukan di kedua tabel
header("location:masuk.php?alert=gagal");
?>
