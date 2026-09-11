<?php 
include 'koneksi.php';

session_start();

if (!isset($_SESSION['customer_status']) || $_SESSION['customer_status'] != 'login') {
	header("location:masuk.php?alert=login-dulu-pesan");
	exit();
}

$id_customer = $_SESSION['customer_id'];
$id = mysqli_real_escape_string($koneksi, $_POST['id']);

$rand = rand();
$allowed = array('gif', 'png', 'jpg', 'jpeg');

$filename = $_FILES['bukti']['name'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (in_array($ext, $allowed)) {
	$file_gambar = $rand . '.' . $ext;

	move_uploaded_file($_FILES['bukti']['tmp_name'], 'gambar/bukti_pembayaran/' . $file_gambar);

	// Hapus foto bukti lama jika ada
	$lama = mysqli_query($koneksi, "SELECT invoice_bukti FROM invoice WHERE invoice_id='$id' AND invoice_customer='$id_customer'");
	if ($l = mysqli_fetch_assoc($lama)) {
		$foto = $l['invoice_bukti'];
		if (!empty($foto) && file_exists("gambar/bukti_pembayaran/$foto")) {
			@unlink("gambar/bukti_pembayaran/$foto");
		}
	}

	mysqli_query($koneksi, "UPDATE invoice SET invoice_bukti='$file_gambar', invoice_status='1' WHERE invoice_id='$id' AND invoice_customer='$id_customer'") or die(mysqli_error($koneksi));
	header("location:customer_pembayaran.php?id=$id&alert=upload");
	exit();
} else {
	header("location:customer_pembayaran.php?id=$id&alert=gagal");
	exit();
}