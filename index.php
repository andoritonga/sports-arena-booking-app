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

<!-- section -->
<div class="section">
	<!-- container -->
	<div class="container">

		<style type="text/css">
			
			@media (max-width: 480px) { 
				.col-xs-6.custom-width{
					/*background: blue !important;*/
					max-width:50% !important;
				}

				.col-xs-6.custom-width img{
					height: 150px !important;
				}

			} 
		</style>
		
		<!-- row -->
		<div class="row">
			
			<!-- MAIN -->
			<div id="main" class="col-md-12">

				<!-- store top filter -->
				<form action="" method="get">
					<?php 
					if(isset($_GET['cari'])){
						$c = "&cari=".$_GET['cari'];
						?>
						<input type="hidden" name="cari" value="<?php echo $_GET['cari']; ?>">
						<?php
					}else{
						?>
						
						<?php
					}
					?>
					<div class="store-filter clearfix">
						<div class="pull-right">
							<div class="sort-filter">
								<span class="text-uppercase">Urutkan :</span>
								<select class="input" name="urutan" onchange="this.form.submit()">
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "terbaru"){echo "selected='selected'";} ?> value="terbaru">Terbaru</option>
									<option <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "harga"){echo "selected='selected'";} ?> value="harga">Harga</option>
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

							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="product product-single">
									<div class="product-thumb">
										<div class="product-label">
											<span><?php echo $d['kategori_nama'] ?></span>
										</div>

										<a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>" class="main-btn quick-view"><i class="fa fa-search-plus"></i> Quick view</a>
										
										<?php if($d['lapangan_foto1'] == ""){ ?>
											<img src="gambar/sistem/lapangan.png" style="height: 250px">
										<?php }else{ ?>
											<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="height: 250px">
										<?php } ?>
									</div>
									<div class="product-body">
										<h3 class="product-price"><?php echo "Rp. ".number_format($d['lapangan_harga']).",-"; ?></h3>
										<h2 class="product-name"><a href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><?php echo $d['lapangan_nama']; ?></a></h2>
										<div class="product-btns">
											<a class="main-btn btn-block text-center" href="lapangan_detail.php?id=<?php echo $d['lapangan_id'] ?>"><i class="fa fa-search"></i> Lihat</a>
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