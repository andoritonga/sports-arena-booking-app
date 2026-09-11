<?php 
// menghubungkan dengan koneksi
include 'koneksi.php';

// menangkap data yang dikirim dari form
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password_input = $_POST['password'];

$login = mysqli_query($koneksi, "SELECT * FROM admin WHERE admin_username='$username'");

if ($login && mysqli_num_rows($login) > 0) {
	$data = mysqli_fetch_assoc($login);
	$stored_hash = $data['admin_password'];
	$authenticated = false;

	if (password_verify($password_input, $stored_hash)) {
		$authenticated = true;
	} elseif (md5($password_input) === $stored_hash) {
		// Legacy MD5 match: seamlessly upgrade hash to Bcrypt
		$authenticated = true;
		$new_hash = password_hash($password_input, PASSWORD_DEFAULT);
		$admin_id = $data['admin_id'];
		mysqli_query($koneksi, "UPDATE admin SET admin_password='$new_hash' WHERE admin_id='$admin_id'");
	}

	if ($authenticated) {
		session_start();
		$_SESSION['id'] = $data['admin_id'];
		$_SESSION['nama'] = $data['admin_nama'];
		$_SESSION['username'] = $data['admin_username'];
		$_SESSION['status'] = "login";

		header("location:admin/");
		exit();
	}
}

header("location:login.php?alert=gagal");
exit();
?>
