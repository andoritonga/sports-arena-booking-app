<?php 
include 'header.php'; 

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

<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li><a href="customer_pesanan.php">Pesanan Saya</a></li>
			<li class="active">Invoice</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">

			<?php include 'customer_sidebar.php'; ?>

			<div id="main" class="col-md-9">
				<?php 
				$id_invoice = mysqli_real_escape_string($koneksi, $_GET['id']);
				$id_customer = $_SESSION['customer_id'];
				$invoice = mysqli_query($koneksi,"SELECT * FROM invoice WHERE invoice_customer='$id_customer' AND invoice_id='$id_invoice'");
				if($i = mysqli_fetch_array($invoice)){
					$start_h = (int)substr($i['invoice_jam_mulai'], 0, 2);
					$end_h = (int)substr($i['invoice_jam_selesai'], 0, 2);
					$durasi = ($end_h > $start_h) ? ($end_h - $start_h) : 1;
					
					$transaksi = mysqli_query($koneksi,"SELECT * FROM lapangan, kategori WHERE kategori_id=lapangan_kategori AND lapangan_id = '$i[invoice_lapangan]'");
					$d = mysqli_fetch_array($transaksi);
				?>

				<!-- ACTION BAR -->
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
					<a href="customer_pesanan.php" class="main-btn" style="display: inline-flex; align-items: center; gap: 8px;">
						<i class="fa fa-arrow-left"></i> Kembali ke Pesanan
					</a>
					<div style="display: flex; gap: 10px;">
						<a href="customer_invoice_cetak.php?id=<?php echo $i['invoice_id'] ?>" target="_blank" class="primary-btn" style="display: inline-flex; align-items: center; gap: 8px;">
							<i class="fa fa-print"></i> Cetak Invoice
						</a>
					</div>
				</div>

				<!-- INVOICE CARD CONTAINER -->
				<div class="form-box" style="padding: 36px; background: #ffffff; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--color-border-light);">
					
					<!-- INVOICE HEADER BRANDING -->
					<div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px dashed #e2e8f0; padding-bottom: 24px; margin-bottom: 28px; flex-wrap: wrap; gap: 20px;">
						<div>
							<div class="logo-text" style="font-size: 28px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
								<span class="badge-icon" style="width: 42px; height: 42px; background: var(--color-brand-gradient); color: #fff; border-radius: var(--radius-md); display: inline-flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
									<i class="fa fa-soccer-ball-o"></i>
								</span>
								SPORT<span style="color: #2563eb;">KUY</span>
							</div>
							<p style="color: var(--color-text-muted); font-size: 13px; margin: 0;">Platform Reservasi & Booking Lapangan Olahraga Online</p>
						</div>

						<div style="text-align: right;">
							<div style="font-size: 22px; font-weight: 800; color: var(--color-brand-primary); font-family: var(--font-heading);">
								INVOICE #INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?>
							</div>
							<p style="color: var(--color-text-muted); font-size: 13px; margin: 4px 0 10px 0;">
								Tgl Order: <?php echo DateToIndo($i['invoice_tanggal']); ?>
							</p>
							<div>
								<?php 
								if($i['invoice_status'] == 0){
									echo "<span class='badge-status badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>";
								}elseif($i['invoice_status'] == 1){
									echo "<span class='badge-status badge-warning'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>";
								}elseif($i['invoice_status'] == 2){
									echo "<span class='badge-status badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>";
								}elseif($i['invoice_status'] == 3){
									echo "<span class='badge-status badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>";
								}elseif($i['invoice_status'] == 4){
									echo "<span class='badge-status badge-success'><i class='fa fa-star'></i> Selesai</span>";
								}
								?>
							</div>
						</div>
					</div>

					<!-- BILLING DETAILS GRID -->
					<div class="row" style="margin-bottom: 28px;">
						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-md); border: 1px solid #e2e8f0; height: 100%;">
								<h5 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: 12px; font-weight: 700;">
									<i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Informasi Pemesan
								</h5>
								<h4 style="font-size: 16px; font-weight: 800; margin: 0 0 6px 0; color: #0f172a;">
									<?php echo $i['invoice_nama']; ?>
								</h4>
								<p style="margin: 0 0 4px 0; color: var(--color-text-main); font-size: 14px;">
									<i class="fa fa-phone" style="width: 18px; color: var(--color-brand-primary);"></i> <?php echo $i['invoice_hp']; ?>
								</p>
								<p style="margin: 0; color: var(--color-text-muted); font-size: 13px;">
									<i class="fa fa-map-marker" style="width: 18px; color: var(--color-brand-primary);"></i> <?php echo isset($i['invoice_alamat']) && !empty($i['invoice_alamat']) ? $i['invoice_alamat'] : "Pelanggan Terdaftar"; ?>
								</p>
							</div>
						</div>

						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-md); border: 1px solid #e2e8f0; height: 100%;">
								<h5 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: 12px; font-weight: 700;">
									<i class="fa fa-calendar" style="color: var(--color-brand-primary);"></i> Jadwal Pertandingan
								</h5>
								<p style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #0f172a;">
									<i class="fa fa-calendar-check-o" style="width: 18px; color: var(--color-brand-primary);"></i> <?php echo DateToIndo($i['invoice_tgl_main']); ?>
								</p>
								<p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: var(--color-brand-primary);">
									<i class="fa fa-clock-o" style="width: 18px;"></i> Sesi Jam: <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?> (<?php echo $durasi; ?> Jam)
								</p>
								<p style="margin: 0; color: var(--color-text-muted); font-size: 13px;">
									<i class="fa fa-tags" style="width: 18px; color: var(--color-brand-primary);"></i> Kategori: <?php echo isset($d['kategori_nama']) ? $d['kategori_nama'] : 'Olahraga'; ?>
								</p>
							</div>
						</div>
					</div>

					<!-- ITEM TABLE -->
					<div class="table-responsive" style="margin-bottom: 28px;">
						<table class="table" style="width: 100%;">
							<thead>
								<tr>
									<th width="5%" class="text-center">#</th>
									<th width="50%" colspan="2">Arena / Lapangan</th>
									<th width="15%" class="text-center">Harga / Jam</th>
									<th width="10%" class="text-center">Durasi</th>
									<th width="20%" class="text-right">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center" style="font-weight: 700;">1</td>
									<td width="70" style="padding: 10px;">
										<?php if(!empty($d['lapangan_foto1'])){ ?>
											<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="width: 60px; height: 45px; object-fit: cover; border-radius: 8px;">
										<?php } else { ?>
											<img src="gambar/sistem/lapangan.png" style="width: 60px; height: 45px; object-fit: cover; border-radius: 8px;">
										<?php } ?>
									</td>
									<td>
										<div style="font-weight: 800; font-size: 15px; color: #0f172a;"><?php echo isset($d['lapangan_nama']) ? $d['lapangan_nama'] : 'Lapangan SportKuy'; ?></div>
										<small style="color: var(--color-text-muted);"><?php echo DateToIndo($i['invoice_tgl_main']); ?> | Jam <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?></small>
									</td>
									<td class="text-center" style="font-weight: 600;">Rp <?php echo number_format($i['invoice_harga']); ?></td>
									<td class="text-center" style="font-weight: 700;"><?php echo $durasi; ?> Jam</td>
									<td class="text-right" style="font-weight: 800; color: #0f172a;">Rp <?php echo number_format($i['invoice_total_bayar']); ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- TOTAL SUMMARY BOX -->
					<div class="row" style="align-items: center;">
						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f1f5f9; padding: 14px 18px; border-radius: var(--radius-md); font-size: 13px; color: var(--color-text-muted);">
								<i class="fa fa-info-circle" style="color: var(--color-brand-primary);"></i> Tunjukkan invoice ini kepada petugas lokasi saat memasuki arena pertandingan.
							</div>
						</div>
						<div class="col-sm-6 text-right">
							<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 18px 24px; border-radius: var(--radius-md); display: inline-block; min-width: 260px; text-align: right; box-shadow: 0 4px 14px rgba(15,23,42,0.15);">
								<div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 4px;">Total Tagihan</div>
								<div style="font-size: 26px; font-weight: 800; color: #38bdf8; font-family: var(--font-heading);">
									Rp <?php echo number_format($i['invoice_total_bayar']); ?>
								</div>
							</div>
						</div>
					</div>

				</div>

				<?php } else { ?>
					<div class="alert alert-danger text-center" style="padding: 20px; border-radius: var(--radius-md);">
						<i class="fa fa-exclamation-triangle fa-2x"></i><br><br>
						Data Invoice tidak ditemukan atau Anda tidak memiliki akses.
					</div>
				<?php } ?>

			</div>

		</div>
	</div>
</div>

<?php include 'footer.php'; ?>