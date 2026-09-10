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
	<title>Ekspor PDF Laporan Penjualan - SportKuy</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<link rel="stylesheet" href="../frontend/css/font-awesome.min.css">
	<style>
		* { box-sizing: border-box; }
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
			color: #0f172a;
			background: #f8fafc;
			margin: 0;
			padding: 40px 20px;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}
		.report-card {
			max-width: 960px;
			margin: 0 auto;
			background: #ffffff;
			border-radius: 16px;
			padding: 40px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
			border: 1px solid #e2e8f0;
		}
		.report-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			border-bottom: 2px dashed #cbd5e1;
			padding-bottom: 24px;
			margin-bottom: 24px;
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
			width: 40px;
			height: 40px;
			background: linear-gradient(135deg, #2563eb, #1d4ed8);
			color: #fff;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 20px;
		}
		.report-title-badge {
			font-family: 'Outfit', sans-serif;
			font-size: 22px;
			font-weight: 800;
			color: #2563eb;
			text-align: right;
		}
		.grid-3 {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 16px;
			margin-bottom: 24px;
		}
		.stat-box {
			background: #f8fafc;
			padding: 16px 20px;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.stat-box.highlight {
			background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
			color: #ffffff;
			border: none;
		}
		.stat-box h6 {
			margin: 0 0 6px 0;
			font-size: 11px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #64748b;
			font-weight: 700;
		}
		.stat-box.highlight h6 {
			color: #38bdf8;
		}
		.stat-box .val {
			font-size: 20px;
			font-weight: 800;
			font-family: 'Outfit', sans-serif;
			margin: 0;
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
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.03em;
			padding: 12px 14px;
			text-align: left;
			border-bottom: 2px solid #cbd5e1;
		}
		.table td {
			padding: 12px 14px;
			font-size: 13px;
			border-bottom: 1px solid #f1f5f9;
		}
		.table tr:last-child td {
			border-bottom: none;
		}
		.badge {
			padding: 4px 10px;
			border-radius: 9999px;
			font-weight: 700;
			font-size: 11px;
			display: inline-block;
		}
		.badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
		.badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
		.badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
		.badge-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
		
		.signature-section {
			display: flex;
			justify-content: space-between;
			align-items: flex-end;
			margin-top: 40px;
			padding-top: 20px;
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
			.report-card { box-shadow: none; border: none; padding: 0; width: 100%; max-width: 100%; }
			.no-print { display: none !important; }
		}
	</style>
</head>
<body>

	<div style="max-width: 960px; margin: 0 auto 10px auto; text-align: right;" class="no-print">
		<button onclick="window.print()" class="no-print-btn"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF / Cetak</button>
	</div>

	<div class="report-card">
		<?php 
		if(isset($_GET['tanggal_sampai']) && isset($_GET['tanggal_dari'])){
			$tgl_dari = mysqli_real_escape_string($koneksi, $_GET['tanggal_dari']);
			$tgl_sampai = mysqli_real_escape_string($koneksi, $_GET['tanggal_sampai']);
			
			$tot_q = mysqli_query($koneksi, "SELECT 
				COUNT(invoice_id) as total_trx,
				SUM(CASE WHEN invoice_status IN ('3','4') THEN invoice_total_bayar ELSE 0 END) as revenue_konfirmasi,
				SUM(CASE WHEN invoice_status IN ('3','4') THEN 1 ELSE 0 END) as count_sukses
				FROM invoice WHERE date(invoice_tanggal) >= '$tgl_dari' AND date(invoice_tanggal) <= '$tgl_sampai'");
			$st = mysqli_fetch_assoc($tot_q);
			$sum_revenue = isset($st['revenue_konfirmasi']) ? $st['revenue_konfirmasi'] : 0;
			$total_trx = isset($st['total_trx']) ? $st['total_trx'] : 0;
			$count_sukses = isset($st['count_sukses']) ? $st['count_sukses'] : 0;
		?>

		<!-- BRANDING HEADER -->
		<div class="report-header">
			<div>
				<div class="logo-title">
					<span class="logo-icon"><i class="fa fa-trophy"></i></span>
					SPORT<span style="color: #2563eb;">KUY</span> FINANCIAL STATEMENT
				</div>
				<p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">Dokumen Laporan Keuangan & Rekapitulasi Sewa Lapangan</p>
			</div>

			<div class="report-title-badge">
				FINANCIAL STATEMENT
				<div style="font-size: 13px; color: #64748b; font-weight: 600; margin-top: 4px;">
					Periode: <?php echo DateToIndo($tgl_dari); ?> s/d <?php echo DateToIndo($tgl_sampai); ?>
				</div>
				<div style="font-size: 11px; color: #94a3b8; font-weight: 500; margin-top: 2px;">
					Dicetak Pada: <?php echo DateToIndo(date('Y-m-d')); ?> | Jam <?php echo date('H:i'); ?> WIB
				</div>
			</div>
		</div>

		<!-- EXECUTIVE SUMMARY CARDS -->
		<div class="grid-3">
			<div class="stat-box">
				<h6>Periode Filter</h6>
				<div class="val" style="font-size: 15px; color: #0f172a;">
					<?php echo date('d/m/Y', strtotime($tgl_dari)); ?> - <?php echo date('d/m/Y', strtotime($tgl_sampai)); ?>
				</div>
			</div>

			<div class="stat-box">
				<h6>Volume Reservasi</h6>
				<div class="val" style="color: #2563eb;">
					<?php echo number_format($total_trx); ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;">Order</span>
				</div>
			</div>

			<div class="stat-box highlight">
				<h6>Total Omset Terkonfirmasi</h6>
				<div class="val" style="color: #38bdf8;">
					Rp <?php echo number_format($sum_revenue); ?>
				</div>
			</div>
		</div>

		<!-- RECAP TABLE -->
		<table class="table">
			<thead>
				<tr>
					<th width="4%" style="text-align: center;">NO</th>
					<th width="14%">INVOICE</th>
					<th width="12%">TGL ORDER</th>
					<th width="14%">JADWAL MAIN</th>
					<th width="20%">NAMA CUSTOMER</th>
					<th width="20%">ARENA / LAPANGAN</th>
					<th width="16%" style="text-align: center;">STATUS</th>
					<th width="15%" style="text-align: right;">TOTAL</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$no = 1;
				$grand_total = 0;
				$data = mysqli_query($koneksi,"SELECT * FROM invoice 
					INNER JOIN customer ON invoice_customer=customer_id 
					LEFT JOIN lapangan ON invoice_lapangan=lapangan_id 
					WHERE date(invoice_tanggal) >= '$tgl_dari' AND date(invoice_tanggal) <= '$tgl_sampai' 
					ORDER BY invoice_id DESC");

				if(mysqli_num_rows($data) > 0){
					while($i = mysqli_fetch_array($data)){
						if($i['invoice_status'] == 3 || $i['invoice_status'] == 4) {
							$grand_total += $i['invoice_total_bayar'];
						}
					?>
					<tr>
						<td style="text-align: center; font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
						<td style="font-weight: 800; color: #2563eb;">#INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?></td>
						<td style="font-weight: 600; color: #334155;"><?php echo date('d/m/Y', strtotime($i['invoice_tanggal'])); ?></td>
						<td style="font-weight: 600; color: #0f172a;">
							<?php echo date('d/m/Y', strtotime($i['invoice_tgl_main'])); ?>
							<div style="font-size: 11px; color: #64748b;"><?php echo $i['invoice_jam_mulai']; ?> - <?php echo $i['invoice_jam_selesai']; ?></div>
						</td>
						<td>
							<div style="font-weight: 700; color: #0f172a;"><?php echo $i['customer_nama']; ?></div>
							<div style="font-size: 11px; color: #64748b;"><?php echo $i['customer_hp']; ?></div>
						</td>
						<td>
							<div style="font-weight: 600; color: #334155;"><?php echo isset($i['lapangan_nama']) ? $i['lapangan_nama'] : 'Lapangan SportKuy'; ?></div>
						</td>
						<td style="text-align: center;">
							<?php 
							if($i['invoice_status'] == 0){
								echo "<span class='badge badge-warning'>Menunggu Bayar</span>";
							}elseif($i['invoice_status'] == 1){
								echo "<span class='badge badge-info'>Konfirmasi</span>";
							}elseif($i['invoice_status'] == 2){
								echo "<span class='badge badge-danger'>Ditolak</span>";
							}elseif($i['invoice_status'] == 3){
								echo "<span class='badge badge-success'>Dikonfirmasi</span>";
							}elseif($i['invoice_status'] == 4){
								echo "<span class='badge badge-success'>Selesai</span>";
							}
							?>
						</td>
						<td style="text-align: right; font-weight: 800; color: #0f172a;">
							Rp <?php echo number_format($i['invoice_total_bayar']); ?>
						</td>
					</tr>
					<?php 
					}
				} else {
					echo "<tr><td colspan='8' style='text-align:center; padding: 20px; color: #94a3b8;'>Tidak ada data transaksi pada periode ini.</td></tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
					<td colspan="7" style="text-align: right; font-weight: 800; font-size: 13px; letter-spacing: 0.05em; padding: 14px;">
						TOTAL PENDAPATAN TERKONFIRMASI:
					</td>
					<td style="text-align: right; font-weight: 800; font-size: 16px; color: #38bdf8; font-family: 'Outfit', sans-serif; padding: 14px;">
						Rp <?php echo number_format($grand_total); ?>
					</td>
				</tr>
			</tfoot>
		</table>

		<!-- SIGNATURE & STAMP FOOTER -->
		<div class="signature-section">
			<div style="font-size: 12px; color: #64748b; max-width: 400px;">
				<p style="margin: 0 0 4px 0;"><strong>Catatan Administrator:</strong></p>
				<p style="margin: 0;">Dokumen ini merupakan laporan rekapitulasi penjualan resmi yang diterbitkan secara otomatis oleh sistem manajemen arena <strong>SportKuy</strong>.</p>
			</div>

			<div style="text-align: center; width: 220px;">
				<div style="font-size: 13px; color: #334155; margin-bottom: 60px;">
					Jakarta, <?php echo DateToIndo(date('Y-m-d')); ?><br/>
					<strong>Manager Operasional / Admin</strong>
				</div>
				<div style="border-bottom: 2px dashed #0f172a; width: 100%; margin: 0 auto 4px auto;"></div>
				<div style="font-weight: 800; font-size: 14px; color: #0f172a; text-transform: uppercase;">
					SportKuy Management
				</div>
			</div>
		</div>

		<?php } else { ?>
			<div style="text-align: center; padding: 40px; color: #ef4444; font-weight: 700;">
				Silakan tentukan periode tanggal laporan terlebih dahulu.
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