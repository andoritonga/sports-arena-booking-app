<?php 
include 'header.php'; 

if (!isset($_SESSION['customer_status']) || $_SESSION['customer_status'] != 'login') {
	echo "<script>window.location.href='masuk.php?alert=login-dulu-pesan';</script>";
	exit();
}

if (!function_exists('DateToIndo')) {
    function DateToIndo($date) {
        if (empty($date) || $date == "0000-00-00") return "-";
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
        $idx   = (int)$bulan - 1;
        if (isset($BulanIndo[$idx])) {
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
			<li class="active">Konfirmasi Pembayaran</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section" style="background: #f8fafc; padding: 40px 0;">
	<div class="container">
		<div class="row">
			
			<?php include 'customer_sidebar.php'; ?>

			<div id="main" class="col-md-9">
				
				<?php 
				$id_invoice = mysqli_real_escape_string($koneksi, $_GET['id']);
				$id_customer = $_SESSION['customer_id'];
				$invoice = mysqli_query($koneksi,"SELECT * FROM invoice WHERE invoice_customer='$id_customer' AND invoice_id='$id_invoice'");
				
				if (mysqli_num_rows($invoice) == 0) {
				?>
					<div class="alert alert-danger" style="border-radius: var(--radius-md); padding: 25px; text-align: center;">
						<i class="fa fa-exclamation-circle" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
						<h4 style="font-weight: 800;">Data Tagihan / Invoice Tidak Ditemukan</h4>
						<p style="color: #64748b; margin-bottom: 15px;">Invoice ini tidak ditemukan dalam akun Anda atau ID tidak valid.</p>
						<a href="customer_pesanan.php" class="primary-btn"><i class="fa fa-arrow-left"></i> Kembali ke Riwayat Pesanan</a>
					</div>
				<?php
				} else {
					$i = mysqli_fetch_array($invoice);
					$lapangan = mysqli_query($koneksi,"SELECT * FROM lapangan, kategori WHERE kategori_id=lapangan_kategori AND lapangan_id='$i[invoice_lapangan]'");
					$l = mysqli_fetch_array($lapangan);

					$start_h = (int)substr($i['invoice_jam_mulai'], 0, 2);
					$end_h = (int)substr($i['invoice_jam_selesai'], 0, 2);
					$durasi = ($end_h > $start_h) ? ($end_h - $start_h) : 1;
				?>

					<!-- NOTIFICATION ALERTS -->
					<?php if(isset($_GET['alert'])){ ?>
						<?php if($_GET['alert'] == "sukses_booking"){ ?>
							<div class="alert alert-success" style="border-radius: var(--radius-md); font-weight: 600; margin-bottom: 25px;">
								<i class="fa fa-check-circle"></i> <strong>Reservasi Berhasil Dibuat!</strong> Silakan lakukan transfer sejumlah total tagihan ke salah satu rekening di bawah ini, lalu unggah bukti transfer.
							</div>
						<?php }elseif($_GET['alert'] == "upload"){ ?>
							<div class="alert alert-success" style="border-radius: var(--radius-md); font-weight: 600; margin-bottom: 25px;">
								<i class="fa fa-check-circle"></i> <strong>Bukti Transfer Berhasil Diunggah!</strong> Status telah diubah menjadi "Menunggu Konfirmasi". Admin akan memverifikasi pembayaran Anda segera.
							</div>
						<?php }elseif($_GET['alert'] == "gagal"){ ?>
							<div class="alert alert-danger" style="border-radius: var(--radius-md); font-weight: 600; margin-bottom: 25px;">
								<i class="fa fa-times-circle"></i> <strong>Gagal Mengunggah:</strong> Harap gunakan file gambar berekstensi JPG, JPEG, PNG, atau GIF.
							</div>
						<?php } ?>
					<?php } ?>

					<!-- INVOICE HEADER CARD -->
					<div style="background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); padding: 25px; box-shadow: var(--shadow-sm); margin-bottom: 25px;">
						<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding-bottom: 18px; border-bottom: 2px solid #f1f5f9;">
							<div>
								<span style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Nomor Tagihan</span>
								<h3 style="margin: 4px 0 0; font-size: 22px; font-weight: 900; color: #0f172a;">
									INVOICE-00<?php echo $i['invoice_id']; ?>
								</h3>
							</div>
							<div>
								<?php 
								if($i['invoice_status'] == 0){
									echo "<span class='badge-status badge-warning' style='font-size: 13px; padding: 6px 14px;'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>";
								}elseif($i['invoice_status'] == 1){
									echo "<span class='badge-status badge-warning' style='font-size: 13px; padding: 6px 14px;'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>";
								}elseif($i['invoice_status'] == 2){
									echo "<span class='badge-status badge-danger' style='font-size: 13px; padding: 6px 14px;'><i class='fa fa-times-circle'></i> Ditolak</span>";
								}elseif($i['invoice_status'] == 3){
									echo "<span class='badge-status badge-success' style='font-size: 13px; padding: 6px 14px;'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>";
								}elseif($i['invoice_status'] == 4){
									echo "<span class='badge-status badge-success' style='font-size: 13px; padding: 6px 14px;'><i class='fa fa-star'></i> Selesai</span>";
								}
								?>
							</div>
						</div>

						<div class="row" style="margin-top: 20px;">
							<div class="col-sm-6">
								<div style="margin-bottom: 12px;">
									<div style="font-size: 12px; color: #64748b; font-weight: 600;">Arena / Lapangan</div>
									<div style="font-size: 15px; font-weight: 800; color: #0f172a;">
										<?php echo $l ? $l['lapangan_nama'] : 'Lapangan #'.$i['invoice_lapangan']; ?> 
										<span class="badge-status badge-primary" style="font-size: 10px; margin-left: 5px;"><?php echo $l['kategori_nama']; ?></span>
									</div>
								</div>
								<div style="margin-bottom: 12px;">
									<div style="font-size: 12px; color: #64748b; font-weight: 600;">Tanggal Pemesanan</div>
									<div style="font-size: 14px; font-weight: 700; color: #334155;">
										<?php echo DateToIndo($i['invoice_tanggal']); ?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div style="margin-bottom: 12px;">
									<div style="font-size: 12px; color: #64748b; font-weight: 600;">Jadwal Bermain</div>
									<div style="font-size: 14px; font-weight: 800; color: #2563eb;">
										<i class="fa fa-calendar-check-o"></i> <?php echo DateToIndo($i['invoice_tgl_main']); ?> (<?php echo $i['invoice_jam_mulai']; ?> - <?php echo $i['invoice_jam_selesai']; ?> WIB)
									</div>
								</div>
								<div style="margin-bottom: 12px;">
									<div style="font-size: 12px; color: #64748b; font-weight: 600;">Total Tagihan Pembayaran</div>
									<div style="font-size: 22px; font-weight: 900; color: #166534;">
										Rp <?php echo number_format($i['invoice_total_bayar'], 0, ',', '.'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- BANK ACCOUNTS CARD -->
					<div style="background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); padding: 25px; box-shadow: var(--shadow-sm); margin-bottom: 25px;">
						<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
							<div style="width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; color: var(--color-brand-primary); display: flex; align-items: center; justify-content: center; font-size: 18px;">
								<i class="fa fa-university"></i>
							</div>
							<div>
								<h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">Pilihan Rekening Transfer Bank</h4>
								<p style="margin: 0; font-size: 12px; color: #64748b;">Transfer tepat sesuai nominal tagihan ke salah satu rekening resmi di bawah:</p>
							</div>
						</div>

						<div class="row">
							<!-- BCA -->
							<div class="col-sm-4" style="margin-bottom: 15px;">
								<div style="border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 16px; background: #f8fafc; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
									<div>
										<div style="font-weight: 800; color: #0f172a; font-size: 15px; margin-bottom: 4px;">Bank BCA</div>
										<div style="font-size: 18px; font-weight: 800; color: #2563eb; letter-spacing: 0.05em;" id="rek-bca">123-456-7890</div>
										<div style="font-size: 12px; color: #64748b; margin-top: 4px;">a.n. PT Sport Kuy Indonesia</div>
									</div>
									<button type="button" class="btn btn-sm btn-default" style="margin-top: 12px; width: 100%; font-weight: 700; border-radius: var(--radius-sm);" onclick="copyToClipboard('1234567890', this)">
										<i class="fa fa-copy"></i> Salin Rekening
									</button>
								</div>
							</div>

							<!-- MANDIRI -->
							<div class="col-sm-4" style="margin-bottom: 15px;">
								<div style="border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 16px; background: #f8fafc; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
									<div>
										<div style="font-weight: 800; color: #0f172a; font-size: 15px; margin-bottom: 4px;">Bank Mandiri</div>
										<div style="font-size: 18px; font-weight: 800; color: #2563eb; letter-spacing: 0.05em;" id="rek-mandiri">987-654-3210</div>
										<div style="font-size: 12px; color: #64748b; margin-top: 4px;">a.n. PT Sport Kuy Indonesia</div>
									</div>
									<button type="button" class="btn btn-sm btn-default" style="margin-top: 12px; width: 100%; font-weight: 700; border-radius: var(--radius-sm);" onclick="copyToClipboard('9876543210', this)">
										<i class="fa fa-copy"></i> Salin Rekening
									</button>
								</div>
							</div>

							<!-- BRI -->
							<div class="col-sm-4" style="margin-bottom: 15px;">
								<div style="border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 16px; background: #f8fafc; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
									<div>
										<div style="font-weight: 800; color: #0f172a; font-size: 15px; margin-bottom: 4px;">Bank BRI</div>
										<div style="font-size: 18px; font-weight: 800; color: #2563eb; letter-spacing: 0.05em;" id="rek-bri">555-444-3333</div>
										<div style="font-size: 12px; color: #64748b; margin-top: 4px;">a.n. PT Sport Kuy Indonesia</div>
									</div>
									<button type="button" class="btn btn-sm btn-default" style="margin-top: 12px; width: 100%; font-weight: 700; border-radius: var(--radius-sm);" onclick="copyToClipboard('5554443333', this)">
										<i class="fa fa-copy"></i> Salin Rekening
									</button>
								</div>
							</div>
						</div>
					</div>

					<!-- UPLOAD RECEIPT SECTION -->
					<div style="background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); padding: 25px; box-shadow: var(--shadow-sm);">
						
						<?php if($i['invoice_status'] == 0 || $i['invoice_status'] == 2) { ?>
							<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
								<div style="width: 36px; height: 36px; border-radius: 8px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 18px;">
									<i class="fa fa-cloud-upload"></i>
								</div>
								<div>
									<h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">Unggah Bukti Pembayaran</h4>
									<p style="margin: 0; font-size: 12px; color: #64748b;">Lampirkan bukti transfer ATM, Mobile Banking, atau Internet Banking.</p>
								</div>
							</div>

							<?php if($i['invoice_status'] == 2) { ?>
								<div class="alert alert-danger" style="border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
									<i class="fa fa-exclamation-triangle"></i> Bukti pembayaran sebelumnya ditolak oleh admin. Harap periksa kejelasan struk/resi dan unggah kembali bukti transfer yang valid.
								</div>
							<?php } ?>

							<form action="customer_pembayaran_act.php" method="post" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?php echo $id_invoice; ?>">
								
								<div class="form-group" style="margin-bottom: 20px;">
									<label style="font-weight: 700; color: #334155; margin-bottom: 8px;">Pilih File Bukti Struk / Screenshot</label>
									<input type="file" name="bukti" id="bukti-file-input" class="form-control" required="required" accept="image/*" onchange="previewImage(this)" style="height: auto; padding: 10px; border-radius: var(--radius-md);">
									<small class="text-muted" style="display: block; margin-top: 6px;">Format yang didukung: JPG, JPEG, PNG, GIF (Maks. 5 MB).</small>
								</div>

								<!-- IMAGE PREVIEW CONTAINER -->
								<div id="preview-box" style="display: none; margin-bottom: 20px; padding: 15px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: var(--radius-md); text-align: center;">
									<div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Pratinjau File:</div>
									<img id="preview-img" src="" alt="Pratinjau Bukti" style="max-height: 250px; max-width: 100%; border-radius: 8px; box-shadow: var(--shadow-sm);">
								</div>

								<button type="submit" class="primary-btn" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 12px 24px; border-radius: var(--radius-md);">
									<i class="fa fa-upload"></i> Unggah & Konfirmasi Pembayaran
								</button>
							</form>

						<?php } elseif($i['invoice_status'] == 1) { ?>

							<div style="text-align: center; padding: 20px 0;">
								<div style="width: 60px; height: 60px; border-radius: 50%; background: #fef3c7; color: #d97706; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 16px;">
									<i class="fa fa-hourglass-half"></i>
								</div>
								<h4 style="font-weight: 800; font-size: 18px; color: #0f172a; margin-bottom: 8px;">Bukti Pembayaran Sedang Diverifikasi</h4>
								<p style="color: #64748b; max-width: 500px; margin: 0 auto 20px; font-size: 14px;">
									Terima kasih, bukti pembayaran Anda telah berhasil kami terima. Tim admin Sport Kuy sedang memverifikasi transfer Anda.
								</p>

								<?php if(!empty($i['invoice_bukti'])) { ?>
									<div style="margin: 20px auto; max-width: 320px; border: 1px solid #e2e8f0; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
										<div style="background: #f1f5f9; padding: 8px 14px; font-size: 12px; font-weight: 700; color: #475569;">
											Bukti Transfer Terunggah
										</div>
										<img src="gambar/bukti_pembayaran/<?php echo $i['invoice_bukti']; ?>" alt="Bukti Transfer" style="width: 100%; max-height: 250px; object-fit: contain; background: #0f172a; padding: 6px;">
									</div>
								<?php } ?>

								<div style="margin-top: 25px;">
									<button type="button" class="btn btn-default" onclick="toggleReupload()" style="font-weight: 700; font-size: 13px;">
										<i class="fa fa-refresh"></i> Ingin Ganti Bukti Transfer?
									</button>
								</div>

								<div id="reupload-box" style="display: none; margin-top: 20px; max-width: 500px; margin-left: auto; margin-right: auto; text-align: left; background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid #e2e8f0;">
									<form action="customer_pembayaran_act.php" method="post" enctype="multipart/form-data">
										<input type="hidden" name="id" value="<?php echo $id_invoice; ?>">
										<div class="form-group">
											<label style="font-weight: 700; font-size: 13px;">Pilih File Bukti Baru</label>
											<input type="file" name="bukti" class="form-control" required="required" accept="image/*">
										</div>
										<button type="submit" class="primary-btn" style="font-size: 13px; padding: 8px 16px;">
											<i class="fa fa-upload"></i> Unggah Bukti Baru
										</button>
									</form>
								</div>
							</div>

						<?php } elseif($i['invoice_status'] == 3 || $i['invoice_status'] == 4) { ?>

							<div style="text-align: center; padding: 25px 0;">
								<div style="width: 65px; height: 65px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; font-size: 30px; margin-bottom: 16px;">
									<i class="fa fa-check"></i>
								</div>
								<h4 style="font-weight: 800; font-size: 20px; color: #0f172a; margin-bottom: 8px;">Pembayaran Terverifikasi & Jadwal Terkonfirmasi</h4>
								<p style="color: #64748b; max-width: 500px; margin: 0 auto 20px; font-size: 14px;">
									Selamat! Pesanan Anda telah diverifikasi oleh admin. Silakan datang sesuai jadwal bermain yang tertera pada invoice.
								</p>
								<a href="customer_invoice.php?id=<?php echo $i['invoice_id']; ?>" class="primary-btn" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700;">
									<i class="fa fa-print"></i> Lihat & Cetak Invoice Resmi
								</a>
							</div>

						<?php } ?>

					</div>

				<?php } ?>

			</div>
		</div>
	</div>
</div>

<script>
function copyToClipboard(text, btn) {
	navigator.clipboard.writeText(text).then(function() {
		var orig = btn.innerHTML;
		btn.innerHTML = '<i class="fa fa-check" style="color: #22c55e;"></i> Tersalin!';
		btn.classList.add('btn-success');
		btn.classList.remove('btn-default');
		setTimeout(function() {
			btn.innerHTML = orig;
			btn.classList.remove('btn-success');
			btn.classList.add('btn-default');
		}, 2000);
	}).catch(function() {
		alert('Nomor rekening: ' + text);
	});
}

function previewImage(input) {
	var previewBox = document.getElementById('preview-box');
	var previewImg = document.getElementById('preview-img');
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			previewImg.src = e.target.result;
			previewBox.style.display = 'block';
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function toggleReupload() {
	var box = document.getElementById('reupload-box');
	if (box.style.display === 'none' || box.style.display === '') {
		box.style.display = 'block';
	} else {
		box.style.display = 'none';
	}
}
</script>

<?php include 'footer.php'; ?>