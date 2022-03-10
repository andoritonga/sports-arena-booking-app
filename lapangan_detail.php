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
$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_id='$id_lapangan'");
while($d=mysqli_fetch_array($data)){
	?>
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!--  Product Details -->
				<div class="product product-details clearfix">
					<div class="col-md-6">
						<div id="product-main-view">

							<div class="product-view">
								<?php if($d['lapangan_foto1'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto2'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto3'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto3'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto2'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>">
								<?php } ?>
							</div>

						</div>
						<div id="product-view">

							<div class="product-view">
								<?php if($d['lapangan_foto1'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto2'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto3'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto3'] ?>">
								<?php } ?>
							</div>

							<div class="product-view">
								<?php if($d['lapangan_foto2'] == ""){ ?>
									<img src="gambar/sistem/produk.png">
								<?php }else{ ?>
									<img src="gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>">
								<?php } ?>
							</div>

						</div>
					</div>
					<div class="col-md-6">
						<div class="product-body">
							<div class="product-label">
								<span><?php echo $d['kategori_nama']; ?></span>
							</div>
							<br>
							<h2 class="product-name"><?php echo $d['lapangan_nama']; ?></h2>
							<br>
							<h3 class="product-price"><?php echo "Rp. ".number_format($d['lapangan_harga']).",-"; ?></h3>
							<br>
							<form action="">
								<div class="product-btns">
									<a class="primary-btn calendar-alt" href="checkout.php?id=<?php echo $d['lapangan_id']?>"><i class="fa fa-calendar-alt"></i>Pesan Lapangan</a>
									<div class="pull-right">
									</div>
								</div>	
							</form>
							<br>
							<div class="product-body">
								<h4>Deskripsi Lapangan:</h4>
								<p><?php echo $d['lapangan_keterangan']; ?></p>
							</div>
							<?php 
							} mysqli_free_result($data);
							?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="product-tab">
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab" href="#tab1">Jadwal Lapangan</a></li>
							</ul>
							<div class="tab-content">
								<div>
									<form class="form-inline" action="#jadwal" method="POST">
                            			<div class="form-group">
											<input autocomplete="off" type="text" value="<?php date("Y-m-d");?>" name="tgl" class="form-control datepicker2" placeholder="Pilih Tanggal" required="required">                        				
                    					</div>
										<div class="form-group">
											<input type="submit" class="btn btn-md btn-primary" value="cari">
										</div>
                      				</form>
									<br>
                      				<div>
									  	Jadwal Tanggal <?php error_reporting (0);
										  					$tanggal = date("Y-m-d");
										  					if($_POST['tgl']){
																$tanggal = $_POST['tgl'];      
																echo(DateToIndo($tanggal));
				 											}else{
				  												echo(DateToIndo($tanggal));
				   											}
														?>
									</div>
									<br>
									<div class="table-responsive">
  										<table class="table table-striped" id="jadwal">
										  <?php
										  $cekjadwal = mysqli_query($koneksi,"select * from jadwal WHERE jadwal_lapangan = '$id_lapangan' AND jadwal_tanggal = '$tanggal'");
										  $z = mysqli_fetch_all($cekjadwal);
										  if (count($z) == 0){
											mysqli_query($koneksi, "insert into jadwal values(NULL ,'$id_lapangan','$tanggal','08.00','09.00',DEFAULT), (NULL ,'$id_lapangan','$tanggal','09.00','10.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','10.00','11.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','11.00','12.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','12.00','13.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','13.00','14.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','14.00','15.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','15.00','16.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','16.00','17.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','17.00','18.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','18.00','19.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','19.00','20.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','20.00','21.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','21.00','22.00',DEFAULT),(NULL ,'$id_lapangan','$tanggal','22.00','23.00',DEFAULT)")or die(mysqli_error($koneksi));
										  }			   
										  
										  ?>
                              				<thead>
                              					<tr>
                              						<th>Jam Mulai</th>
													<th>Jam Selesai</th>
                              						<th>Status</th>
												</tr>
                              				</thead>
                              				<tbody>
											  	<?php
												  $jadwal = mysqli_query($koneksi,"select * from jadwal WHERE jadwal_lapangan = '$id_lapangan' AND jadwal_tanggal = '$tanggal'");										  
												  while($x=mysqli_fetch_array($jadwal)){																																	
												?>
												<tr>
													<td><?php echo $x['jadwal_mulai']; ?></td>
													<td><?php echo $x['jadwal_selesai']; ?></td>
													<td><?php if ($x['jadwal_status'] == "SUDAH DIPESAN"){
																	echo "<font color='#ff0000'>SUDAH DIPESAN</font>";
																}else{
																	echo "<font color='#03c03c'>TERSEDIA</font>";
																} 
														?></td>
												</tr>
												<?php												  
												} 
												?>
                              				</tbody>
                              			</table>
                              		</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="product-tab">
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab" href="#tab1">Komentar</a></li>
							</ul>
							<div class="tab-content">
								<?php 
									if(isset($_SESSION['customer_status'])){
								?>
								<form method="post" id="komen" action="komentar_act.php?id=<?php echo $id_lapangan ?>">
									<div class="col-md-12">
									<?php 
										if(isset($_GET['alert'])){
											if($_GET['alert'] == "sukses-komentar"){
												echo "<div class='alert alert-success'>Komentar berhasil ditambahkan!</div>";
											}elseif($_GET['alert'] == "sukses-hapus"){
												echo "<div class='alert alert-danger'>Komentar berhasil dihapus!</div>";
										}
									}
									?>
										<div class="form-group">
											<label>Beri Komentar</label>
											<input type="text" class="input" name="komentar" required="required">
										</div>
										<div class="form-group">
											<input type="submit" class="btn btn-md btn-primary" value="Kirim Komentar">
										</div>
									</div>
								</form>
								
								<?php
								}
								else{
									echo "<div class='alert alert-warning'>Silahkan Login untuk dapat memberikan komentar!</div>";
								}
								$komentar = mysqli_query($koneksi,"select * from komentar where komentar_lapangan='$id_lapangan'");
								while($k=mysqli_fetch_array($komentar)){
								?>
								<div class="col-lg-12">
									<div class="card text-dark bg-light ">
										<div class="card-header">
											<h5 class="card-tittle"><?php echo $k['komentar_nama'];?></h5>
											<h6 class="card-subtitle text-muted"><?php echo(DateToIndo($k['komentar_tanggal']));?></h6>											
										</div>
										<div class="card-body">											
											<p class="card-text"><?php echo $k['komentar_isi'];?></p>																																
										</div>
										<?php if ($k['komentar_customer'] == $_SESSION['customer_id']){
										?> <div class="card-footer">
												<a href="komentar_hapus.php?id=<?php echo $k['komentar_id']?>&lapangan=<?php echo $id_lapangan?>" class="card-link text-danger">Hapus</a>
											</div>	
										<?php } ?>									
									</div>
									<br>									
								</div>
								<?php } ?>
							</div>
						</div>	
					</div>
					

				</div>
				<!-- /Product Details -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>


<?php include 'footer.php'; ?>

<?php
function DateToIndo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
   // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo = array("Januari", "Februari", "Maret",
               "April", "Mei", "Juni",
               "Juli", "Agustus", "September",
               "Oktober", "November", "Desember");
  
    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
    
    $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
    return($result);
}

?>