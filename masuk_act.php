<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';

// menangkap data yang dikirim dari form
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$password_input = $_POST['password'];

// 1. Cek Customer
$query_customer = mysqli_query($koneksi, "SELECT * FROM customer WHERE customer_email='$email' OR customer_nama='$email'");
if ($query_customer && mysqli_num_rows($query_customer) > 0) {
	$data = mysqli_fetch_assoc($query_customer);
	$stored_hash = $data['customer_password'];
	$authenticated = false;

	if (password_verify($password_input, $stored_hash)) {
		$authenticated = true;
	} elseif (md5($password_input) === $stored_hash) {
		// Legacy MD5 match: seamlessly upgrade hash to Bcrypt
		$authenticated = true;
		$new_hash = password_hash($password_input, PASSWORD_DEFAULT);
		$cust_id = $data['customer_id'];
		mysqli_query($koneksi, "UPDATE customer SET customer_password='$new_hash' WHERE customer_id='$cust_id'");
	}

	if ($authenticated) {
		session_start();
		// hapus session admin agar tidak bentrok
		unset($_SESSION['id'], $_SESSION['nama'], $_SESSION['username'], $_SESSION['status']);

		// buat session customer
		$_SESSION['customer_id'] = $data['customer_id'];
		$_SESSION['customer_status'] = "login";
		header("location:customer.php");
		exit();
	}
}

// 2. Cek Admin
$query_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE admin_username='$email'");
if ($query_admin && mysqli_num_rows($query_admin) > 0) {
	$data_admin = mysqli_fetch_assoc($query_admin);
	$stored_hash = $data_admin['admin_password'];
	$authenticated = false;

	if (password_verify($password_input, $stored_hash)) {
		$authenticated = true;
	} elseif (md5($password_input) === $stored_hash) {
		// Legacy MD5 match: seamlessly upgrade hash to Bcrypt
		$authenticated = true;
		$new_hash = password_hash($password_input, PASSWORD_DEFAULT);
		$admin_id = $data_admin['admin_id'];
		mysqli_query($koneksi, "UPDATE admin SET admin_password='$new_hash' WHERE admin_id='$admin_id'");
	}

	if ($authenticated) {
		session_start();
		// hapus session customer agar tidak bentrok
		unset($_SESSION['customer_id'], $_SESSION['customer_status']);

		// buat session admin
		$_SESSION['id'] = $data_admin['admin_id'];
		$_SESSION['nama'] = $data_admin['admin_nama'];
		$_SESSION['username'] = $data_admin['admin_username'];
		$_SESSION['status'] = "login";

		header("location:admin/");
		exit();
	}
}

// 3. Jika tidak ditemukan atau password salah
header("location:masuk.php?alert=gagal");
exit();
?>
