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

$id = $_SESSION['customer_id']; 
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);
$customer = mysqli_query($koneksi,"SELECT * FROM customer WHERE customer_id='$id'");
$i = mysqli_fetch_array($customer);

$data = mysqli_query($koneksi,"SELECT * FROM lapangan, kategori WHERE kategori_id=lapangan_kategori AND lapangan_id='$id_lapangan'");
$d = mysqli_fetch_array($data);

if (!$d) {
	echo "<script>alert('Lapangan tidak ditemukan'); window.location.href='index.php';</script>";
	exit();
}

$selected_tgl = isset($_GET['tgl']) && !empty($_GET['tgl']) ? htmlspecialchars($_GET['tgl']) : date('Y-m-d');
$selected_start = isset($_GET['start']) && !empty($_GET['start']) ? htmlspecialchars($_GET['start']) : '08.00';
$selected_end = isset($_GET['end']) && !empty($_GET['end']) ? htmlspecialchars($_GET['end']) : '09.00';

$start_h = (int)substr($selected_start, 0, 2);
$end_h = (int)substr($selected_end, 0, 2);
if ($end_h <= $start_h) { 
    $end_h = $start_h + 1; 
    $selected_end = sprintf("%02d.00", $end_h);
}
$durasi = $end_h - $start_h;
$total_harga = $durasi * (int)$d['lapangan_harga'];
?>

<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li><a href="lapangan_detail.php?id=<?php echo $d['lapangan_id']; ?>"><?php echo $d['lapangan_nama']; ?></a></li>
			<li class="active">Checkout & Konfirmasi</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section" style="background: #f8fafc; padding: 40px 0;">
	<div class="container">
		
		<div style="margin-bottom: 25px;">
			<a href="lapangan_detail.php?id=<?php echo $d['lapangan_id']; ?>&tgl=<?php echo $selected_tgl; ?>" class="main-btn" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700;">
				<i class="fa fa-arrow-left"></i> Kembali ke Pemilihan Slot
			</a>
		</div>

		<div class="row">
			<!-- LEFT COLUMN: BOOKING FORM -->
			<div class="col-lg-7 col-md-7">
				<div style="background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); padding: 30px; box-shadow: var(--shadow-sm); margin-bottom: 30px;">
					<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #f1f5f9;">
						<div style="width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; color: var(--color-brand-primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
							<i class="fa fa-user"></i>
						</div>
						<div>
							<h4 style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a;">Data Pemesan & Waktu Bermain</h4>
							<p style="margin: 0; font-size: 13px; color: var(--color-text-muted);">Lengkapi data diri Anda dan verifikasi jam sewa lapangan.</p>
						</div>
					</div>

					<form id="form-checkout" method="post" action="checkout_act.php?id=<?php echo $d['lapangan_id']; ?>">
						
						<div class="form-group" style="margin-bottom: 20px;">
							<label style="font-weight: 700; color: #334155; margin-bottom: 8px;"><i class="fa fa-user-circle" style="color: var(--color-brand-primary);"></i> Nama Lengkap</label>
							<input type="text" class="input" name="nama" value="<?php echo htmlspecialchars($i['customer_nama']); ?>" required="required" placeholder="Nama lengkap pemesan">
						</div>

						<div class="form-group" style="margin-bottom: 20px;">
							<label style="font-weight: 700; color: #334155; margin-bottom: 8px;"><i class="fa fa-phone" style="color: var(--color-brand-primary);"></i> Nomor WhatsApp / HP</label>
							<input type="text" class="input" name="hp" value="<?php echo htmlspecialchars($i['customer_hp']); ?>" required="required" placeholder="Nomor aktif WhatsApp">
							<small class="text-muted" style="display: block; margin-top: 4px;">Konfirmasi dan kode booking akan diverifikasi ke nomor ini.</small>
						</div>

						<div class="form-group" style="margin-bottom: 20px;">
							<label style="font-weight: 700; color: #334155; margin-bottom: 8px;"><i class="fa fa-calendar" style="color: var(--color-brand-primary);"></i> Tanggal Bermain</label>
							<input type="date" class="form-control" name="tglMain" id="tglMain" value="<?php echo $selected_tgl; ?>" min="<?php echo date('Y-m-d'); ?>" required="required" style="height: 48px; border-radius: var(--radius-md); font-weight: 600;" onchange="syncCheckoutDate()">
						</div>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group" style="margin-bottom: 20px;">
									<label style="font-weight: 700; color: #334155; margin-bottom: 8px;"><i class="fa fa-clock-o" style="color: var(--color-brand-primary);"></i> Jam Mulai</label>
									<select class="form-control" name="jamMulai" id="jamMulai" onchange="syncCheckoutTimes()" style="height: 48px; border-radius: var(--radius-md); font-weight: 700;">
										<?php 
										for($h = 8; $h <= 22; $h++) {
											$val = sprintf("%02d.00", $h);
											$sel = ($val == $selected_start) ? "selected" : "";
											echo "<option value='$val' $sel>$val WIB</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group" style="margin-bottom: 20px;">
									<label style="font-weight: 700; color: #334155; margin-bottom: 8px;"><i class="fa fa-clock-o" style="color: var(--color-brand-primary);"></i> Jam Selesai</label>
									<select class="form-control" name="jamSelesai" id="jamSelesai" onchange="syncCheckoutTimes()" style="height: 48px; border-radius: var(--radius-md); font-weight: 700;">
										<?php 
										for($h = 9; $h <= 23; $h++) {
											$val = sprintf("%02d.00", $h);
											$sel = ($val == $selected_end) ? "selected" : "";
											echo "<option value='$val' $sel>$val WIB</option>";
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<input type="hidden" name="harga" id="field-harga" value="<?php echo $d['lapangan_harga']; ?>">

						<div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: var(--radius-md); padding: 14px 18px; margin-top: 10px; display: flex; align-items: flex-start; gap: 12px;">
							<i class="fa fa-shield" style="font-size: 20px; color: var(--color-brand-primary); margin-top: 2px;"></i>
							<div style="font-size: 13px; color: #475569; line-height: 1.5;">
								<strong>Proteksi Reservasi:</strong> Jadwal yang Anda booking akan dicek secara otomatis ke database untuk menghindari bentrok jadwal ganda dengan penyewa lain.
							</div>
						</div>

					</form>
				</div>
			</div>

			<!-- RIGHT COLUMN: ORDER SUMMARY CARD -->
			<div class="col-lg-5 col-md-5">
				<div style="background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); padding: 26px; box-shadow: var(--shadow-md); position: sticky; top: 25px;">
					
					<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f1f5f9;">
						<i class="fa fa-list-alt" style="font-size: 20px; color: var(--color-brand-primary);"></i>
						<h4 style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a;">Ringkasan Pesanan</h4>
					</div>

					<!-- VENUE CARD PREVIEW -->
					<div style="display: flex; gap: 16px; margin-bottom: 22px; background: #f8fafc; padding: 14px; border-radius: var(--radius-md); border: 1px solid #e2e8f0;">
						<div style="width: 90px; height: 75px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #0f172a;">
							<img src="gambar/lapangan/<?php echo $d['lapangan_foto1']; ?>" alt="<?php echo $d['lapangan_nama']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
						</div>
						<div style="display: flex; flex-direction: column; justify-content: center;">
							<span class="badge-status badge-primary" style="align-self: flex-start; margin-bottom: 6px; font-size: 10px; padding: 2px 8px;">
								<?php echo $d['kategori_nama']; ?>
							</span>
							<h5 style="margin: 0 0 4px; font-size: 15px; font-weight: 800; color: #0f172a;">
								<?php echo $d['lapangan_nama']; ?>
							</h5>
							<div style="font-size: 12px; color: #64748b;">
								Tarif: <strong style="color: #0f172a;">Rp <?php echo number_format($d['lapangan_harga'], 0, ',', '.'); ?></strong> / jam
							</div>
						</div>
					</div>

					<!-- BREAKDOWN ROWS -->
					<div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px; color: #475569; margin-bottom: 20px;">
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span><i class="fa fa-calendar-check-o" style="color: var(--color-brand-primary); width: 18px;"></i> Tanggal Main</span>
							<strong id="summary-tgl" style="color: #0f172a;"><?php echo DateToIndo($selected_tgl); ?></strong>
						</div>
						
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span><i class="fa fa-clock-o" style="color: var(--color-brand-primary); width: 18px;"></i> Jadwal Sewa</span>
							<strong id="summary-jam" style="color: #0f172a;"><?php echo $selected_start; ?> - <?php echo $selected_end; ?> WIB</strong>
						</div>

						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span><i class="fa fa-hourglass-half" style="color: var(--color-brand-primary); width: 18px;"></i> Total Durasi</span>
							<strong id="summary-durasi" style="color: #0f172a;"><?php echo $durasi; ?> Jam</strong>
						</div>

						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span><i class="fa fa-tag" style="color: var(--color-brand-primary); width: 18px;"></i> Tarif Satuan</span>
							<span style="color: #0f172a;">Rp <?php echo number_format($d['lapangan_harga'], 0, ',', '.'); ?> / jam</span>
						</div>

						<div style="height: 1px; background: #e2e8f0; margin: 8px 0;"></div>

						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span style="font-size: 16px; font-weight: 800; color: #0f172a;">Total Tagihan</span>
							<span id="summary-total" style="font-size: 22px; font-weight: 900; color: #2563eb;">
								Rp <?php echo number_format($total_harga, 0, ',', '.'); ?>
							</span>
						</div>
					</div>

					<!-- SUBMIT BUTTON -->
					<button type="submit" form="form-checkout" class="primary-btn" style="width: 100%; padding: 15px; font-size: 15px; font-weight: 800; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 15px rgba(37,99,235,0.3); cursor: pointer;">
						<i class="fa fa-check-circle"></i> Konfirmasi Booking Lapangan
					</button>

					<div style="margin-top: 18px; text-align: center;">
						<small style="color: #94a3b8; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
							<i class="fa fa-lock" style="color: #10b981;"></i> Pembayaran aman via Transfer Bank & Verifikasi Admin
						</small>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<!-- /SECTION -->

<script>
var pricePerHour = <?php echo (int)$d['lapangan_harga']; ?>;

function syncCheckoutTimes() {
	var startSelect = document.getElementById('jamMulai');
	var endSelect = document.getElementById('jamSelesai');
	
	var startVal = startSelect.value;
	var endVal = endSelect.value;

	var startH = parseInt(startVal.substring(0, 2));
	var endH = parseInt(endVal.substring(0, 2));

	if (endH <= startH) {
		endH = startH + 1;
		var nextEndVal = (endH < 10 ? '0' + endH : endH) + '.00';
		endSelect.value = nextEndVal;
		endVal = nextEndVal;
	}

	var durasi = endH - startH;
	var total = durasi * pricePerHour;

	document.getElementById('summary-jam').innerText = startVal + ' - ' + endVal + ' WIB';
	document.getElementById('summary-durasi').innerText = durasi + ' Jam';
	document.getElementById('summary-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

function syncCheckoutDate() {
	var dateInput = document.getElementById('tglMain');
	if (!dateInput.value) return;

	var parts = dateInput.value.split('-');
	if (parts.length === 3) {
		var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		var mIdx = parseInt(parts[1], 10) - 1;
		var formatted = parts[2] + ' ' + (months[mIdx] || parts[1]) + ' ' + parts[0];
		document.getElementById('summary-tgl').innerText = formatted;
	}
}
</script>

<?php include 'footer.php'; ?>
