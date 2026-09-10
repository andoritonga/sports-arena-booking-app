<?php include 'header.php'; ?>

<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- MODERN HERO BANNER -->
<div class="container">
	<div class="modern-hero-section">
		<div class="container">
			<div class="hero-content-wrapper">
				<h1 class="hero-title">Sewa Lapangan Olahraga<br>Terfavorit & Terlengkap</h1>
				<p class="hero-subtitle">Temukan dan booking lapangan Futsal, Badminton, Basketball, hingga Mini Soccer dalam hitungan detik.</p>
				
				<form action="index.php" method="get">
					<div class="hero-search-box">
						<i class="fa fa-search" style="color: #94a3b8; font-size: 18px; margin-left: 15px;"></i>
						<input type="text" name="cari" placeholder="Cari nama lapangan atau lokasi..." value="<?php if(isset($_GET['cari'])){ echo htmlspecialchars($_GET['cari']); } ?>">
						<button type="submit" class="hero-search-btn"><i class="fa fa-paper-plane"></i> Cari Lapangan</button>
					</div>
				</form>

				<div class="hero-chips">
					<span style="color: #cbd5e1; font-weight: 600; font-size: 13px; align-self: center; margin-right: 5px;">Kategori Populer:</span>
					<?php 
					$kat_chips = mysqli_query($koneksi,"SELECT * FROM kategori LIMIT 5");
					while($kc = mysqli_fetch_array($kat_chips)){
					?>
						<a href="lapangan_kategori.php?id=<?php echo $kc['kategori_id']; ?>" class="hero-chip"><i class="fa fa-bolt" style="color: #60a5fa;"></i> <?php echo $kc['kategori_nama']; ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- section -->
<div class="section">
	<!-- container -->
	<div class="container">
		
		<!-- row -->
		<div class="row">
			
			<!-- MAIN -->
			<div id="main" class="col-md-12">

				<!-- store top filter -->
				<form action="" method="get">
					<?php 
					if(isset($_GET['cari'])){
						?>
						<input type="hidden" name="cari" value="<?php echo htmlspecialchars($_GET['cari']); ?>">
						<?php
					}
					?>
					<div class="store-filter clearfix">
						<div class="pull-left" style="line-height: 36px;">
							<h3 style="margin: 0; font-size: 20px; font-weight: 800;">
								<?php 
								if(isset($_GET['cari'])){
									echo "Hasil Pencarian: \"".htmlspecialchars($_GET['cari'])."\"";
								} else {
									echo "Semua Daftar Lapangan";
								}
								?>
							</h3>
						</div>
						<div class="pull-right">
							<div class="sort-filter">
								<span class="text-uppercase" style="font-weight: 700; margin-right: 8px;">Urutkan:</span>
								<select class="input" name="urutan" onchange="this.form.submit()">
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "terbaru"){echo "selected='selected'";} ?> value="terbaru">Terbaru</option>
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){echo "selected='selected'";} ?> value="harga">Harga Terendah</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<!-- /store top filter -->

				<!-- STORE -->
				<div id="store">
					<!-- row -->
					<div class="row">

						<?php
						


						$halaman = 12;
						$page = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
						$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
						// $result = mysqli_query($koneksi, "SELECT * FROM lapangan");

						if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){
							if(isset($_GET['cari'])){
								$cari = $_GET['cari'];
								$result = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_nama like '%$cari%' order by lapangan_harga asc");
							}else{
								$result = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori order by lapangan_harga asc");
							}
						}else{

							if(isset($_GET['cari'])){
								$cari = $_GET['cari'];
								$result = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_nama like '%$cari%' order by lapangan_id desc");
							}else{
								$result = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori order by lapangan_id desc");
							}

						}    
						
						$total = mysqli_num_rows($result);
						$pages = ceil($total/$halaman);  
						if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){
							if(isset($_GET['cari'])){
								$cari = $_GET['cari'];
								$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_nama like '%$cari%' order by lapangan_harga asc LIMIT $mulai, $halaman");
							}else{
								$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori order by lapangan_harga asc LIMIT $mulai, $halaman");
							}
						}else{

							if(isset($_GET['cari'])){
								$cari = $_GET['cari'];
								$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_nama like '%$cari%' order by lapangan_id desc LIMIT $mulai, $halaman");
							}else{
								$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori order by lapangan_id desc LIMIT $mulai, $halaman");
							}

						}          
						$no =$mulai+1;

						while($d = mysqli_fetch_array($data)){
							?>

							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="product product-single">
									<div class="product-thumb">
										<div class="product-label">
											<span><i class="fa fa-tag"></i> <?php echo $d['kategori_nama'] ?></span>
										</div>

										<a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>" class="main-btn quick-view"><i class="fa fa-eye"></i> Detail</a>
										
										<?php if($d['lapangan_foto1'] == ""){ ?>
											<img src="gambar/sistem/lapangan.png">
										<?php }else{ ?>
											<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>">
										<?php } ?>
									</div>
									<div class="product-body">
										<h2 class="product-name"><a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><?php echo $d['lapangan_nama']; ?></a></h2>
										<div class="product-price"><?php echo "Rp. ".number_format($d['lapangan_harga']); ?></div>
										
										<div class="product-btns" style="margin-top: 15px;">
											<a class="primary-btn btn-block text-center" href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><i class="fa fa-calendar"></i> Detail & Booking</a>
										</div>
									</div>
								</div>
							</div>
							<!-- /Product Single -->

							<?php 
						}
						?>

					</div>
					<!-- /row -->

					<?php 
					if($total == 0){
						?>
						<center><h4>Belum ada Lapangan.</h4></center>
						<?php
					}
					?>
				</div>
				<!-- /STORE -->

				
				<div class="store-filter clearfix">
					<div class="pull-right">
						<ul class="store-pages">
							<li><span class="text-uppercase">Page:</span></li>
							<?php for ($i=1; $i<=$pages ; $i++){ ?>
								<?php if($page==$i){ ?>
									<li class="active"><?php echo $i; ?></li>
								<?php }else{ ?>

									<?php 
									if(isset($_GET['cari'])){
										$cari = $_GET['cari'];
										$c = "&cari=".$cari;
									}else{
										$c = "";
									}
									if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){
										?>
										<li><a href="?halaman=<?php echo $i; ?>&urutan=harga<?php echo $c ?>"><?php echo $i; ?></a></li>
										<?php 
									}else{
										?>
										<li><a href="?halaman=<?php echo $i; ?><?php echo $c ?>"><?php echo $i; ?></a></li>
										<?php
									}
									?>

								<?php } ?>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<!-- /MAIN -->
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /section -->


<?php include 'footer.php'; ?>