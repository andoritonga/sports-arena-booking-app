<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

if (!function_exists('DateToIndo')) {
    function DateToIndo($date) {
        if(empty($date) || $date == "0000-00-00") return "-";
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
        $idx   = (int)$bulan - 1;
        if(isset($BulanIndo[$idx])) {
            return $tgl . " " . $BulanIndo[$idx] . " " . $tahun;
        }
        return date('d-m-Y', strtotime($date));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Cetak Invoice Admin #INV-<?php echo isset($_GET['id']) ? str_pad($_GET['id'], 5, '0', STR_PAD_LEFT) : ''; ?> - SportKuy</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<link rel="stylesheet" href="../frontend/css/font-awesome.min.css">
	<style>
		* { box-sizing: border-box; }
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
			color: #1e293b;
			background: #f8fafc;
			margin: 0;
			padding: 40px 20px;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}
		.invoice-card {
			max-width: 800px;
			margin: 0 auto;
			background: #ffffff;
			border-radius: 16px;
			padding: 40px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
			border: 1px solid #e2e8f0;
		}
		.invoice-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			border-bottom: 2px dashed #cbd5e1;
			padding-bottom: 24px;
			margin-bottom: 28px;
		}
		.logo-title {
			font-family: 'Outfit', sans-serif;
			font-size: 26px;
			font-weight: 800;
			color: #0f172a;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.logo-icon {
			width: 38px;
			height: 38px;
			background: linear-gradient(135deg, #2563eb, #1d4ed8);
			color: #fff;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 18px;
		}
		.invoice-num {
			font-family: 'Outfit', sans-serif;
			font-size: 22px;
			font-weight: 800;
			color: #2563eb;
			text-align: right;
		}
		.grid-2 {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 20px;
			margin-bottom: 28px;
		}
		.info-box {
			background: #f8fafc;
			padding: 18px;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.info-box h5 {
			margin: 0 0 10px 0;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #64748b;
			font-weight: 700;
		}
		.info-box h4 {
			margin: 0 0 6px 0;
			font-size: 16px;
			font-weight: 800;
			color: #0f172a;
		}
		.info-box p {
			margin: 0 0 4px 0;
			font-size: 13px;
			color: #334155;
		}
		.table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid #e2e8f0;
			margin-bottom: 24px;
		}
		.table th {
			background: #f1f5f9;
			color: #0f172a;
			font-weight: 700;
			font-size: 13px;
			padding: 12px 16px;
			text-align: left;
			border-bottom: 2px solid #e2e8f0;
		}
		.table td {
			padding: 14px 16px;
			font-size: 14px;
			border-bottom: 1px solid #f1f5f9;
		}
		.badge {
			padding: 6px 14px;
			border-radius: 9999px;
			font-weight: 700;
			font-size: 12px;
			display: inline-block;
		}
		.badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
		.badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
		.badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
		.badge-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
		.total-box {
			background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
			color: #ffffff;
			padding: 16px 24px;
			border-radius: 12px;
			display: inline-block;
			text-align: right;
			float: right;
		}
		.no-print-btn {
			background: #2563eb;
			color: #ffffff;
			border: none;
			padding: 12px 24px;
			border-radius: 10px;
			font-weight: 700;
			cursor: pointer;
			margin-bottom: 20px;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			box-shadow: 0 4px 12px rgba(37,99,235,0.3);
			text-decoration: none;
		}
		@media print {
			body { background: #ffffff; padding: 0; }
			.invoice-card { box-shadow: none; border: none; padding: 0; }
			.no-print { display: none !important; }
		}
	</style>
</head>
<body>

	<div style="max-width: 800px; margin: 0 auto 10px auto; text-align: right;" class="no-print">
		<button onclick="window.print()" class="no-print-btn"><i class="fa fa-print"></i> Cetak Invoice Sekarang</button>
	</div>

	<div class="invoice-card">
		<?php 
		$id_invoice = mysqli_real_escape_string($koneksi, $_GET['id']);
		$invoice = mysqli_query($koneksi,"SELECT * FROM invoice WHERE invoice_id='$id_invoice'");

		if($i = mysqli_fetch_array($invoice)){
			$start_h = (int)substr($i['invoice_jam_mulai'], 0, 2);
			$end_h = (int)substr($i['invoice_jam_selesai'], 0, 2);
			$durasi = ($end_h > $start_h) ? ($end_h - $start_h) : 1;
			
			$transaksi = mysqli_query($koneksi,"SELECT * FROM lapangan, kategori WHERE kategori_id=lapangan_kategori AND lapangan_id = '$i[invoice_lapangan]'");
			$d = mysqli_fetch_array($transaksi);
		?>

		<!-- HEADER BRANDING -->
		<div class="invoice-header">
			<div>
				<div class="logo-title">
					<span class="logo-icon"><i class="fa fa-trophy"></i></span>
					SPORT<span style="color: #2563eb;">KUY</span> ADMIN
				</div>
				<p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">Bukti Reservasi & Transaksi Resmi System</p>
			</div>

			<div class="invoice-num">
				INVOICE #INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?>
				<div style="font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px;">
					Tanggal Order: <?php echo DateToIndo($i['invoice_tanggal']); ?>
				</div>
				<div style="margin-top: 8px;">
					<?php 
					if($i['invoice_status'] == 0){
						echo "<span class='badge badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>";
					}elseif($i['invoice_status'] == 1){
						echo "<span class='badge badge-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>";
					}elseif($i['invoice_status'] == 2){
						echo "<span class='badge badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>";
					}elseif($i['invoice_status'] == 3){
						echo "<span class='badge badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>";
					}elseif($i['invoice_status'] == 4){
						echo "<span class='badge badge-success'><i class='fa fa-star'></i> Selesai</span>";
					}
					?>
				</div>
			</div>
		</div>

		<!-- GRID DETAILS -->
		<div class="grid-2">
			<div class="info-box">
				<h5><i class="fa fa-user" style="color: #2563eb;"></i> Data Pemesan</h5>
				<h4><?php echo $i['invoice_nama']; ?></h4>
				<p><i class="fa fa-phone" style="color: #2563eb; width: 16px;"></i> <?php echo $i['invoice_hp']; ?></p>
				<p><i class="fa fa-map-marker" style="color: #2563eb; width: 16px;"></i> <?php echo isset($i['invoice_alamat']) && !empty($i['invoice_alamat']) ? $i['invoice_alamat'] : "Pelanggan Terdaftar"; ?></p>
			</div>

			<div class="info-box">
				<h5><i class="fa fa-calendar" style="color: #2563eb;"></i> Detail Jadwal Pertandingan</h5>
				<p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
					<i class="fa fa-calendar-check-o" style="color: #2563eb; width: 16px;"></i> <?php echo DateToIndo($i['invoice_tgl_main']); ?>
				</p>
				<p style="font-size: 14px; font-weight: 600; color: #2563eb; margin-bottom: 6px;">
					<i class="fa fa-clock-o" style="width: 16px;"></i> Jam Sesi: <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?> (<?php echo $durasi; ?> Jam)
				</p>
				<p style="color: #64748b; font-size: 13px;">
					<i class="fa fa-tags" style="color: #2563eb; width: 16px;"></i> Kategori: <?php echo isset($d['kategori_nama']) ? $d['kategori_nama'] : 'Olahraga'; ?>
				</p>
			</div>
		</div>

		<!-- TABLE ITEMS -->
		<table class="table">
			<thead>
				<tr>
					<th width="5%" style="text-align: center;">#</th>
					<th width="55%">Arena / Lapangan Olahraga</th>
					<th width="15%" style="text-align: center;">Harga / Jam</th>
					<th width="10%" style="text-align: center;">Durasi</th>
					<th width="15%" style="text-align: right;">Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center; font-weight: 700;">1</td>
					<td>
						<div style="font-weight: 800; font-size: 15px; color: #0f172a;"><?php echo isset($d['lapangan_nama']) ? $d['lapangan_nama'] : 'Lapangan SportKuy'; ?></div>
						<div style="font-size: 12px; color: #64748b;"><?php echo DateToIndo($i['invoice_tgl_main']); ?> | Jam <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?></div>
					</td>
					<td style="text-align: center; font-weight: 600;">Rp <?php echo number_format($i['invoice_harga']); ?></td>
					<td style="text-align: center; font-weight: 700;"><?php echo $durasi; ?> Jam</td>
					<td style="text-align: right; font-weight: 800; color: #0f172a;">Rp <?php echo number_format($i['invoice_total_bayar']); ?></td>
				</tr>
			</tbody>
		</table>

		<!-- TOTAL & FOOTER -->
		<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
			<div style="font-size: 12px; color: #64748b; max-width: 400px;">
				Catatan: Dokumen ini dicetak dari Admin Portal <strong>SportKuy</strong> sebagai arsip transaksi resmi.
			</div>
			<div class="total-box">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 2px;">Total Tagihan</div>
				<div style="font-size: 24px; font-weight: 800; color: #38bdf8; font-family: 'Outfit', sans-serif;">
					Rp <?php echo number_format($i['invoice_total_bayar']); ?>
				</div>
			</div>
		</div>

		<?php } else { ?>
			<div style="text-align: center; padding: 40px; color: #ef4444; font-weight: 700;">
				Data invoice tidak ditemukan!
			</div>
		<?php } ?>
	</div>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>
</html>