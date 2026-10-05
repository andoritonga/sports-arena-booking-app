<?php include 'header.php'; ?>


<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Kategori</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- section -->
<div class="section">
	<!-- container -->
	<div class="container">
		
		<?php
		$id = mysqli_real_escape_string($koneksi, $_GET['id']);
		$kategori = mysqli_query($koneksi,"select * from kategori where kategori_id='$id'");
		$k = mysqli_fetch_assoc($kategori);
		?>

		<!-- APP QUICK CATEGORIES (Native Horizontal Pill Carousel on Mobile) -->
		<div class="visible-xs visible-sm" style="margin-bottom: 15px;">
			<div class="app-categories-strip">
				<a href="index.php" class="category-pill">
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
					$is_act = ($ks['kategori_id'] == $id) ? 'active' : '';
					$cat_lower = strtolower($ks['kategori_nama']);
					$c_icon = 'fa-bolt';
					foreach($icon_map as $key => $icon){
						if(strpos($cat_lower, $key) !== false){
							$c_icon = $icon;
							break;
						}
					}
				?>
				<a href="lapangan_kategori.php?id=<?php echo $ks['kategori_id']; ?>" class="category-pill <?php echo $is_act; ?>">
					<i class="fa <?php echo $c_icon; ?>"></i> <?php echo $ks['kategori_nama']; ?>
				</a>
				<?php } ?>
			</div>
		</div>

		<!-- row -->
		<div class="row">
			
			<!-- MAIN -->
			<div id="main" class="col-md-12">

				<div class="pull-left">
					<h4 style="font-weight: 800; color: var(--color-text-main); font-family: 'Outfit', sans-serif;">
						<i class="fa fa-tag" style="color: var(--color-brand-primary);"></i> <?php echo __t('category_venues'); ?> <?php echo $k['kategori_nama']; ?>
					</h4>
				</div>


				<!-- store top filter -->
				<form action="" method="get">
					<input type="hidden" value="<?php echo mysqli_real_escape_string($koneksi, $k['kategori_id']); ?>" name="id">

					<div class="store-filter clearfix">
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
						$kategori = mysqli_real_escape_string($koneksi, $_GET['id']);

						$halaman = 12;
						$page = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
						$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
						$result = mysqli_query($koneksi, "select * from lapangan,kategori where kategori_id='$kategori' and kategori_id=lapangan_kategori");
						$total = mysqli_num_rows($result);
						$pages = ceil($total/$halaman);  
						if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){
							$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id='$kategori' and kategori_id=lapangan_kategori order by lapangan_harga asc LIMIT $mulai, $halaman");
						}else{
							$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id='$kategori' and kategori_id=lapangan_kategori order by lapangan_id desc LIMIT $mulai, $halaman");
						}          
						$no =$mulai+1;

						while($d = mysqli_fetch_array($data)){
							?>

							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="product product-single">
									<div class="product-thumb">
										<div class="product-label">
											<span><?php echo $d['kategori_nama'] ?></span>
										</div>

										<a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>" class="main-btn quick-view"><i class="fa fa-eye"></i> <?php echo __t('view_detail'); ?></a>
										
										<?php if($d['lapangan_foto1'] == ""){ ?>
											<img src="gambar/sistem/produk.png" style="height: 250px">
										<?php }else{ ?>
											<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="height: 250px">
										<?php } ?>
									</div>
									<div class="product-body">
										<h3 class="product-price">
											<?php echo "Rp. ".number_format($d['lapangan_harga']).",-"; ?>
											<span style="font-size: 13px; font-weight: 500; color: #64748b;"><?php echo __t('per_session'); ?></span>
										</h3>
										<div class="product-rating">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-o empty"></i>
										</div>
										<h2 class="product-name"><a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><?php echo $d['lapangan_nama']; ?></a></h2>
										<div class="product-btns">
											<a class="primary-btn btn-block text-center" href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><i class="fa fa-calendar"></i> <?php echo __t('book_now'); ?></a>
										</div>
									</div>
								</div>
							</div>
							<!-- /Product Single -->

							<?php 
						}
						?>


						<?php 
						if(mysqli_num_rows($data) == 0){
							echo "<center><h3>Belum Ada Lapangan</h3></center>";
						}
						?>

					</div>
					<!-- /row -->
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
									if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){
										?>
										<li><a href="?id=<?php echo $kategori ?>&halaman=<?php echo $i; ?>&urutan=harga"><?php echo $i; ?></a></li>
										<?php 
									}else{
										?>
										<li><a href="?id=<?php echo $kategori ?>&halaman=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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