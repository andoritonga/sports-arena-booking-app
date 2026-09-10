<?php include 'header.php'; ?>

<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Detail Lapangan</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<?php 
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);
$data = mysqli_query($koneksi, "select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_id='$id_lapangan'");
if($d = mysqli_fetch_array($data)){

	// Date handling
	$tanggal = isset($_GET['tgl']) && !empty($_GET['tgl']) ? $_GET['tgl'] : (isset($_POST['tgl']) && !empty($_POST['tgl']) ? $_POST['tgl'] : date("Y-m-d"));
?>

<div class="section">
	<div class="container">
		
		<!-- VENUE HEADER & SHOWCASE ROW -->
		<div class="row" style="margin-bottom: 40px;">
			
			<!-- GALLERY IMAGES -->
			<div class="col-md-6" style="margin-bottom: 30px;">
				<div class="form-box" style="padding: 12px; overflow: hidden;">
					<div id="product-main-view" style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 12px;">
						<div class="product-view">
							<?php if($d['lapangan_foto1'] == ""){ ?>
								<img src="gambar/sistem/lapangan.png" style="width: 100%; height: 360px; object-fit: cover; border-radius: var(--radius-lg);">
							<?php }else{ ?>
								<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="width: 100%; height: 360px; object-fit: cover; border-radius: var(--radius-lg);">
							<?php } ?>
						</div>
					</div>

					<div id="product-view" class="row" style="margin: 0 -5px;">
						<div class="col-xs-4" style="padding: 0 5px;">
							<?php if($d['lapangan_foto1'] != ""){ ?>
								<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer; border: 2px solid var(--color-brand-primary);">
							<?php } ?>
						</div>
						<div class="col-xs-4" style="padding: 0 5px;">
							<?php if($d['lapangan_foto2'] != ""){ ?>
								<img src="gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer;">
							<?php } ?>
						</div>
						<div class="col-xs-4" style="padding: 0 5px;">
							<?php if($d['lapangan_foto3'] != ""){ ?>
								<img src="gambar/lapangan/<?php echo $d['lapangan_foto3'] ?>" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer;">
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<!-- VENUE INFO & BOOKING CARD -->
			<div class="col-md-6">
				<div class="form-box" style="padding: 32px; height: 100%;">
					<div style="margin-bottom: 12px;">
						<span class="badge-status badge-success" style="font-size: 12px; padding: 6px 14px;">
							<i class="fa fa-tag"></i> <?php echo $d['kategori_nama']; ?>
						</span>
					</div>

					<h1 style="font-size: 32px; font-weight: 800; margin: 0 0 14px 0; color: var(--color-text-main);">
						<?php echo $d['lapangan_nama']; ?>
					</h1>

					<div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 24px;">
						<span style="font-size: 32px; font-weight: 800; color: var(--color-brand-primary); font-family: var(--font-heading);">
							<?php echo "Rp. ".number_format($d['lapangan_harga']); ?>
						</span>
						<span style="color: var(--color-text-muted); font-size: 15px; font-weight: 600;">/ Jam Sesi</span>
					</div>

					<!-- SPEC CHIPS -->
					<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px;">
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-shield" style="color: var(--color-brand-primary);"></i> Standar Kompetisi
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-bolt" style="color: #f59e0b;"></i> LED 1000 Lux
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-shower" style="color: #06b6d4;"></i> Shower & Ruang Ganti
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-wifi" style="color: #10b981;"></i> Free WiFi
						</div>
					</div>

					<!-- ACTION BUTTON -->
					<div style="margin-top: auto;">
						<a class="primary-btn btn-block text-center" href="#schedule-section" style="padding: 16px; font-size: 18px; border-radius: var(--radius-lg);">
							<i class="fa fa-calendar-check-o"></i> Pilih Jadwal & Booking
						</a>
					</div>
				</div>
			</div>

		</div>

		<!-- TABS SECTION FOR SCHEDULE & DETAILS -->
		<div class="row" id="schedule-section">
			<div class="col-md-12">
				<div class="form-box" style="padding: 30px;">
					
					<ul class="nav nav-tabs" style="border-bottom: 2px solid var(--color-border-light); margin-bottom: 30px;">
						<li class="active"><a data-toggle="tab" href="#tab-jadwal" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-calendar-check-o" style="color: var(--color-brand-primary);"></i> Jadwal & Sesi Lapangan</a></li>
						<li><a data-toggle="tab" href="#tab-deskripsi" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-info-circle"></i> Deskripsi & Spesifikasi</a></li>
						<li><a data-toggle="tab" href="#tab-komentar" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-comments"></i> Ulasan & Komentar</a></li>
					</ul>

					<div class="tab-content">
						
						<!-- TAB 1: INTERACTIVE JADWAL GRID -->
						<div id="tab-jadwal" class="tab-pane fade in active">
							
							<!-- 1-CLICK QUICK DATE SELECTOR BAR -->
							<div style="margin-bottom: 25px;">
								<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
									<h4 style="margin: 0; font-size: 18px; font-weight: 800;"><i class="fa fa-calendar" style="color: var(--color-brand-primary);"></i> Pilih Tanggal Permainan:</h4>
									
									<form class="form-inline" action="lapangan_detail.php" method="GET" style="display: inline-flex; gap: 8px;">
										<input type="hidden" name="id" value="<?php echo $id_lapangan; ?>">
										<input autocomplete="off" type="text" value="<?php echo $tanggal; ?>" name="tgl" class="form-control datepicker2" placeholder="Pilih Tanggal Lain" style="max-width: 170px; background: #ffffff; padding: 8px 12px; font-weight: 600;">
										<button type="submit" class="primary-btn" style="padding: 8px 16px;"><i class="fa fa-search"></i> Cek</button>
									</form>
								</div>

								<div class="schedule-date-bar">
									<?php
									$today = date("Y-m-d");
									for($i = 0; $i < 7; $i++) {
										$t_date = date("Y-m-d", strtotime("+$i day", strtotime($today)));
										$day_name = date("D", strtotime($t_date));
										$day_formatted = date("d M", strtotime($t_date));
										
										// Translate Day
										$days_map = array('Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jumat','Sat'=>'Sabtu','Sun'=>'Minggu');
										$day_label = isset($days_map[$day_name]) ? $days_map[$day_name] : $day_name;
										if ($i == 0) { $day_label = "Hari Ini"; }
										else if ($i == 1) { $day_label = "Besok"; }
										
										$is_active = ($tanggal == $t_date) ? "active" : "";
										?>
										<a href="lapangan_detail.php?id=<?php echo $id_lapangan; ?>&tgl=<?php echo $t_date; ?>#schedule-section" class="date-pill <?php echo $is_active; ?>">
											<div class="date-day"><?php echo $day_label; ?></div>
											<div class="date-num"><?php echo $day_formatted; ?></div>
										</a>
									<?php } ?>
								</div>
							</div>

							<!-- TIME OF DAY FILTER CHIPS -->
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); margin-bottom: 25px;">
								<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
									<div class="time-filter-chips" style="margin-bottom: 0;">
										<button class="time-chip active" onclick="filterSlots('all', this)"><i class="fa fa-list"></i> Semua Sesi</button>
										<button class="time-chip" onclick="filterSlots('pagi', this)"><i class="fa fa-sun-o" style="color: #f59e0b;"></i> Pagi (08:00 - 12:00)</button>
										<button class="time-chip" onclick="filterSlots('siang', this)"><i class="fa fa-cloud" style="color: #06b6d4;"></i> Siang (12:00 - 17:00)</button>
										<button class="time-chip" onclick="filterSlots('malam', this)"><i class="fa fa-moon-o" style="color: #8b5cf6;"></i> Malam Prime (17:00 - 23:00)</button>
									</div>

									<div style="font-weight: 700; font-size: 14px; color: var(--color-text-main);">
										Tanggal Selected: <span style="color: var(--color-brand-primary);"><?php echo DateToIndo($tanggal); ?></span>
									</div>
								</div>
							</div>

							<!-- TIME SLOTS 2D GRID -->
							<?php
							// Database auto-populate if missing for date
							$cekjadwal = mysqli_query($koneksi,"select * from jadwal WHERE jadwal_lapangan = '$id_lapangan' AND jadwal_tanggal = '$tanggal'");
							$z = mysqli_fetch_all($cekjadwal);
							if (count($z) == 0){
								mysqli_query($koneksi, "insert into jadwal values(NULL ,'$id_lapangan','$tanggal','08.00','09.00',DEFAULT), (NULL ,'$id_lapangan','$tanggal','09.00','10.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','10.00','11.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','11.00','12.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','12.00','13.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','13.00','14.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','14.00','15.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','15.00','16.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','16.00','17.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','17.00','18.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','18.00','19.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','19.00','20.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','20.00','21.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','21.00','22.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','22.00','23.00',DEFAULT)")or die(mysqli_error($koneksi));
							}

							$jadwal = mysqli_query($koneksi,"select * from jadwal WHERE jadwal_lapangan = '$id_lapangan' AND jadwal_tanggal = '$tanggal' order by jadwal_id asc");
							
							$total_slots = 0;
							$available_slots = 0;
							$booked_slots = 0;
							
							$slots_data = array();
							while($x = mysqli_fetch_array($jadwal)){
								$slots_data[] = $x;
								$total_slots++;
								if($x['jadwal_status'] == "SUDAH DIPESAN") { $booked_slots++; }
								else { $available_slots++; }
							}
							?>

							<!-- SLOT SUMMARY STATS BANNER -->
							<div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
								<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 8px 16px; font-weight: 700; color: #166534; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
									<span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block;"></span>
									<?php echo $available_slots; ?> Sesi Tersedia
								</div>
								<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 8px 16px; font-weight: 700; color: #991b1b; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
									<span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block;"></span>
									<?php echo $booked_slots; ?> Sesi Terisi
								</div>
							</div>

							<!-- TIME CARDS GRID -->
							<div class="slot-grid" id="slots-container">
								<?php
								foreach($slots_data as $x){
									$jam_int = (int)substr($x['jadwal_mulai'], 0, 2);
									
									// Category filter tag
									$period = "pagi";
									if($jam_int >= 12 && $jam_int < 17) { $period = "siang"; }
									else if($jam_int >= 17) { $period = "malam"; }

									$is_booked = ($x['jadwal_status'] == "SUDAH DIPESAN");
									$card_class = $is_booked ? "booked" : "available";
								?>
									<div class="slot-card <?php echo $card_class; ?>" data-period="<?php echo $period; ?>">
										<div>
											<div class="slot-time">
												<i class="fa fa-clock-o" style="color: <?php echo $is_booked ? '#cbd5e1' : '#2563eb'; ?>;"></i>
												<?php echo $x['jadwal_mulai']; ?> - <?php echo $x['jadwal_selesai']; ?>
											</div>

											<div class="slot-tag">
												<?php if($is_booked) { ?>
													<i class="fa fa-lock"></i> Terisi
												<?php } else { ?>
													<i class="fa fa-circle" style="font-size: 6px; color: #22c55e;"></i> Rp <?php echo number_format($d['lapangan_harga']/1000); ?>k
												<?php } ?>
											</div>
										</div>

										<div>
											<?php if($is_booked) { ?>
												<button class="slot-btn" disabled><i class="fa fa-ban"></i> Terisi</button>
											<?php } else { ?>
												<a href="checkout.php?id=<?php echo $d['lapangan_id']; ?>" class="slot-btn">
													Pesan Sesi <i class="fa fa-chevron-right" style="font-size: 10px;"></i>
												</a>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>

						</div>

						<!-- TAB 2: DESKRIPSI -->
						<div id="tab-deskripsi" class="tab-pane fade">
							<h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px;">Informasi & Spesifikasi Arena</h3>
							<div style="font-size: 15px; line-height: 1.8; color: var(--color-text-main);">
								<?php echo $d['lapangan_keterangan']; ?>
							</div>
						</div>

						<!-- TAB 3: KOMENTAR -->
						<div id="tab-komentar" class="tab-pane fade">
							
							<?php if(isset($_SESSION['customer_status'])){ ?>
								<form method="post" action="komentar_act.php?id=<?php echo $id_lapangan ?>" style="margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
									<?php 
									if(isset($_GET['alert'])){
										if($_GET['alert'] == "sukses-komentar"){
											echo "<div class='alert alert-success' style='border-radius: 10px; font-weight: 600;'>Komentar Anda berhasil ditambahkan!</div>";
										}elseif($_GET['alert'] == "sukses-hapus"){
											echo "<div class='alert alert-danger' style='border-radius: 10px; font-weight: 600;'>Komentar berhasil dihapus!</div>";
										}
									}
									?>
									<div class="form-group">
										<label style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-pencil"></i> Tulis Ulasan / Komentar Anda:</label>
										<textarea class="form-control" name="komentar" rows="3" required="required" placeholder="Tulis pengamalan bermain atau ulasan fasilitas arena ini..."></textarea>
									</div>
									<button type="submit" class="primary-btn"><i class="fa fa-paper-plane"></i> Kirim Ulasan</button>
								</form>
							<?php } else { ?>
								<div class="alert alert-warning" style="border-radius: 12px; font-weight: 600; margin-bottom: 30px;">
									<i class="fa fa-info-circle"></i> Silahkan <a href="masuk.php" style="color: var(--color-brand-primary); font-weight: 800; text-decoration: underline;">Login</a> untuk dapat memberikan ulasan pada lapangan ini.
								</div>
							<?php } ?>

							<h4 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Ulasan Pelanggan</h4>
							
							<?php 
							$komentar = mysqli_query($koneksi,"select * from komentar where komentar_lapangan='$id_lapangan' order by komentar_id desc");
							if(mysqli_num_rows($komentar) == 0){
								echo "<p style='color: var(--color-text-muted); font-style: italic;'>Belum ada ulasan untuk lapangan ini. Jadilah yang pertama memberikan ulasan!</p>";
							}
							while($k=mysqli_fetch_array($komentar)){
							?>
								<div style="background: #ffffff; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 20px; margin-bottom: 16px; box-shadow: var(--shadow-sm);">
									<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
										<div style="display: flex; align-items: center; gap: 10px;">
											<div style="width: 38px; height: 38px; background: var(--color-brand-gradient); border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
												<?php echo strtoupper(substr($k['komentar_nama'], 0, 1)); ?>
											</div>
											<div>
												<h5 style="margin: 0; font-weight: 700; font-size: 15px; color: var(--color-text-main);"><?php echo $k['komentar_nama'];?></h5>
												<small style="color: var(--color-text-muted); font-size: 12px;"><?php echo(DateToIndo($k['komentar_tanggal']));?></small>
											</div>
										</div>
										
										<?php if (isset($_SESSION['customer_id']) && $k['komentar_customer'] == $_SESSION['customer_id']){ ?>
											<a href="komentar_hapus.php?id=<?php echo $k['komentar_id']?>&lapangan=<?php echo $id_lapangan?>" style="color: #ef4444; font-weight: 600; font-size: 13px;" onclick="return confirm('Yakin ingin menghapus komentar ini?')">
												<i class="fa fa-trash"></i> Hapus
											</a>
										<?php } ?>
									</div>
									<p style="margin: 0; color: var(--color-text-main); font-size: 14px; line-height: 1.6; padding-left: 48px;">
										<?php echo $k['komentar_isi'];?>
									</p>
								</div>
							<?php } ?>

						</div>

					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<script>
function filterSlots(period, btn) {
	// Update active filter chip
	var chips = document.querySelectorAll('.time-chip');
	chips.forEach(function(c){ c.classList.remove('active'); });
	btn.classList.add('active');

	// Filter slot cards
	var cards = document.querySelectorAll('.slot-card');
	cards.forEach(function(card){
		if(period === 'all' || card.getAttribute('data-period') === period) {
			card.style.display = 'flex';
		} else {
			card.style.display = 'none';
		}
	});
}
</script>

<?php 
}
include 'footer.php'; 

function DateToIndo($date) {
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
    
    return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
}
?>