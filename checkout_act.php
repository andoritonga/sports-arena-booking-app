<?php 
include 'koneksi.php';

session_start();

if (!isset($_SESSION['customer_status']) || $_SESSION['customer_status'] != 'login') {
	header("location:masuk.php?alert=login-dulu-pesan");
	exit();
}

$id_customer = $_SESSION['customer_id'];
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);

$tanggal = date('Y-m-d');
$jamMulai = mysqli_real_escape_string($koneksi, $_POST['jamMulai']);
$jamSelesai = mysqli_real_escape_string($koneksi, $_POST['jamSelesai']);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
$tglMain = mysqli_real_escape_string($koneksi, $_POST['tglMain']);

// Ambil data harga langsung dari database agar aman dari manipulasi form
$court_query = mysqli_query($koneksi, "SELECT lapangan_harga FROM lapangan WHERE lapangan_id='$id_lapangan'");
$court = mysqli_fetch_array($court_query);
if (!$court) {
	header("location:index.php");
	exit();
}
$harga = (int)$court['lapangan_harga'];

$start_h = (int)substr($jamMulai, 0, 2);
$end_h = (int)substr($jamSelesai, 0, 2);
$jumlahJam = $end_h - $start_h;

if ($jumlahJam <= 0) {
	header("location:lapangan_detail.php?id=$id_lapangan&tgl=$tglMain&alert=jam_tidak_valid");
	exit();
}

$total_bayar = $harga * $jumlahJam;

// Validasi Anti Double-Booking (Server-Side Conflict Check)
// Cek apakah ada invoice lain di lapangan dan tanggal yang sama yang belum ditolak
$check_conflict = mysqli_query($koneksi, "
    SELECT invoice_id FROM invoice 
    WHERE invoice_lapangan = '$id_lapangan' 
      AND invoice_tgl_main = '$tglMain' 
      AND invoice_status != '2'
      AND CAST(SUBSTRING(invoice_jam_mulai, 1, 2) AS UNSIGNED) < $end_h 
      AND CAST(SUBSTRING(invoice_jam_selesai, 1, 2) AS UNSIGNED) > $start_h
");

if (mysqli_num_rows($check_conflict) > 0) {
	header("location:lapangan_detail.php?id=$id_lapangan&tgl=$tglMain&alert=bentrok");
	exit();
}

// Simpan booking ke database
mysqli_query($koneksi, "INSERT INTO invoice (
    invoice_lapangan, 
    invoice_tanggal, 
    invoice_customer, 
    invoice_nama, 
    invoice_hp, 
    invoice_tgl_main, 
    invoice_jam_mulai, 
    invoice_jam_selesai, 
    invoice_harga, 
    invoice_total_bayar, 
    invoice_status, 
    invoice_bukti
) VALUES (
    '$id_lapangan',
    '$tanggal',
    '$id_customer',
    '$nama',
    '$hp',
    '$tglMain',
    '$jamMulai',
    '$jamSelesai',
    '$harga',
    '$total_bayar',
    '0',
    ''
)") or die(mysqli_error($koneksi));

$invoice_id = mysqli_insert_id($koneksi);

// Arahkan customer langsung ke halaman pembayaran untuk konfirmasi transfer bank
header("location:customer_pembayaran.php?id=$invoice_id&alert=sukses_booking");
exit();