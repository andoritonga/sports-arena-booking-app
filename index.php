<?php include 'header.php'; ?>

<!-- BREADCRUMB (Hidden on mobile for native app look) -->
<div id="breadcrumb" class="hidden-xs">
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
				<h1 class="hero-title"><?php echo __t('hero_title'); ?></h1>
				<p class="hero-subtitle"><?php echo __t('hero_subtitle'); ?></p>
				
				<form action="index.php" method="get">
					<div class="hero-search-box">
						<i class="fa fa-search" style="color: #94a3b8; font-size: 18px; margin-left: 15px;"></i>
						<input type="text" name="cari" placeholder="<?php echo __t('search_placeholder'); ?>" value="<?php if(isset($_GET['cari'])){ echo htmlspecialchars($_GET['cari']); } ?>">
						<button type="submit" class="hero-search-btn"><i class="fa fa-paper-plane"></i> <?php echo __t('search_btn'); ?></button>
					</div>
				</form>

				<div class="hero-chips">
					<span style="color: #cbd5e1; font-weight: 600; font-size: 13px; align-self: center; margin-right: 5px;"><?php echo __t('popular_categories'); ?></span>
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

	<!-- APP QUICK CATEGORIES (Native Horizontal Pill Carousel on Mobile) -->
	<div class="visible-xs visible-sm" style="margin-top: 10px; margin-bottom: 5px;">
		<div class="app-categories-strip">
			<a href="index.php" class="category-pill active">
				<i class="fa fa-th-large"></i> <?php echo __t('all'); ?>
			</a>
			<?php 
			$kategori_strip = mysqli_query($koneksi, "SELECT * FROM kategori");
			$icon_map = [
				'futsal' => 'fa-soccer-ball-o',
				'badminton' => 'fa-trophy',
				'bulu tangkis' => 'fa-trophy',
				'basket' => 'fa-dribbble',
				'basketball' => 'fa-dribbble',
				'mini soccer' => 'fa-futbol-o',
				'tennis' => 'fa-circle-o',
				'tenis' => 'fa-circle-o',
				'voli' => 'fa-bullseye',
			];
			while($ks = mysqli_fetch_array($kategori_strip)){
				$cat_lower = strtolower($ks['kategori_nama']);
				$c_icon = 'fa-bolt';
				foreach($icon_map as $key => $icon){
					if(strpos($cat_lower, $key) !== false){
						$c_icon = $icon;
						break;
					}
				}
			?>
			<a href="lapangan_kategori.php?id=<?php echo $ks['kategori_id']; ?>" class="category-pill">
				<i class="fa <?php echo $c_icon; ?>"></i> <?php echo $ks['kategori_nama']; ?>
			</a>
			<?php } ?>
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
									echo __t('search_results_for') . " \"" . htmlspecialchars($_GET['cari']) . "\"";
								} else {
									echo __t('all_venues');
								}
								?>
							</h3>
						</div>
						<div class="pull-right">
							<div class="sort-filter">
								<span class="text-uppercase" style="font-weight: 700; margin-right: 8px;"><?php echo __t('sort_by'); ?></span>
								<select class="input" name="urutan" onchange="this.form.submit()">
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "terbaru"){echo "selected='selected'";} ?> value="terbaru"><?php echo __t('sort_latest'); ?></option>
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){echo "selected='selected'";} ?> value="harga"><?php echo __t('sort_price_low'); ?></option>
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

										<a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>" class="main-btn quick-view"><i class="fa fa-eye"></i> <?php echo __t('view_detail'); ?></a>
										
										<?php if($d['lapangan_foto1'] == ""){ ?>
											<img src="gambar/sistem/lapangan.png">
										<?php }else{ ?>
											<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>">
										<?php } ?>
									</div>
									<div class="product-body">
										<h2 class="product-name"><a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><?php echo $d['lapangan_nama']; ?></a></h2>
										<div class="product-price">
											<?php echo "Rp. ".number_format($d['lapangan_harga']); ?>
											<span style="font-size: 13px; font-weight: 500; color: #64748b;"><?php echo __t('per_session'); ?></span>
										</div>
										
										<div class="product-btns" style="margin-top: 15px;">
											<a class="primary-btn btn-block text-center" href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><i class="fa fa-calendar"></i> <?php echo __t('book_now'); ?></a>
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