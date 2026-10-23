<?php 
include 'koneksi.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
	header("location:masuk.php?alert=login-dulu-pesan");
	exit();
}

$id_customer = (int)$_SESSION['customer_id'];
$id = (int)($_POST['id'] ?? 0);

// Pastikan invoice milik customer yang sedang login
$lama = mysqli_query($koneksi, "SELECT * FROM invoice WHERE invoice_id='$id' AND invoice_customer='$id_customer'");
$l = mysqli_fetch_assoc($lama);
if (!$l) {
	header("location:customer_pesanan.php?alert=gagal");
	exit();
}

$file_gambar = isset($_FILES['bukti']) ? sportkuy_secure_upload_image($_FILES['bukti'], 'gambar/bukti_pembayaran') : null;

if ($file_gambar !== null) {
	// Salin juga ke gambar/bukti agar kompatibel dengan modal di halaman admin
	@mkdir('gambar/bukti', 0755, true);
	@copy('gambar/bukti_pembayaran/' . $file_gambar, 'gambar/bukti/' . $file_gambar);

	// Hapus gambar lama jika ada
	$foto = $l['invoice_bukti'] ?? '';
	if (!empty($foto)) {
		$safe_old = basename($foto);
		@unlink("gambar/bukti_pembayaran/$safe_old");
		@unlink("gambar/bukti/$safe_old");
	}

	mysqli_query($koneksi, "UPDATE invoice SET invoice_bukti='$file_gambar', invoice_status='1' WHERE invoice_id='$id' AND invoice_customer='$id_customer'");
	header("location:customer_pesanan.php?alert=upload");
} else {
	header("location:customer_pesanan.php?alert=gagal");
}
