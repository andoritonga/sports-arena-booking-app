<?php include 'header.php'; ?>
<?php
$id = $_SESSION['customer_id']; 
$id_lapangan = mysqli_real_escape_string($koneksi, $_GET['id']);
$customer = mysqli_query($koneksi,"select * from customer where customer_id='$id'");
$i = mysqli_fetch_array($customer);
$data = mysqli_query($koneksi,"select * from lapangan,kategori where kategori_id=lapangan_kategori and lapangan_id='$id_lapangan'");
$d = mysqli_fetch_array($data);
?>
<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Booking Lapangan</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- section -->
<div class="section">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			
			<div class="col-md-12">
				<div class="order-summary clearfix">
					<div class="section-title">
						<h3 class="title">Booking Lapangan</h3>
					</div>

					<div class="row">
						<form method="post" action="checkout_act.php?id=<?php echo $d['lapangan_id'] ?>">
							<div class="col-lg-6">

								<div class="row">
									<div class="col-lg-12">

										<br>

										<h4 class="text-center">INFORMASI PEMESAN</h4>

										<div class="form-group">
											<label>Nama</label>
											<input type="text" class="input" name="nama" value="<?php echo $i['customer_nama']; ?>" required="required">
										</div>

										<div class="form-group">
											<label>Nomor HP</label>
											<input type="text" class="input" name="hp" value="<?php echo $i['customer_hp']; ?>" required="required">
										</div>

										<div class="form-group">
											<label>Tanggal Main</label>
											<input type="input" class="form-control datepicker2" name="tglMain" required="required">
										</div>

										<div class="form-group">
  											<label for="inputGroupSelect01" >Jam Mulai</label>
  											<select class="form-select" name="jamMulai">
    											<option selected>Choose...</option>
    											<option value="08.00">08.00</option>
    											<option value="09.00">09.00</option>
    											<option value="10.00">10.00</option>
												<option value="11.00">11.00</option>
												<option value="12.00">12.00</option>
												<option value="13.00">13.00</option>
												<option value="14.00">14.00</option>
												<option value="15.00">15.00</option>
												<option value="16.00">16.00</option>
												<option value="17.00">17.00</option>
												<option value="18.00">18.00</option>
												<option value="19.00">19.00</option>
												<option value="20.00">20.00</option>
												<option value="21.00">21.00</option>
												<option value="22.00">22.00</option>
  											</select>
											<br>
										</div>

										<div class="form-group">
  											<label for="inputGroupSelect02" >Jam Selesai</label>
  											<select class="form-select" name="jamSelesai">
    											<option selected>Choose...</option>
    											<option value="09.00">09.00</option>
    											<option value="10.00">10.00</option>
												<option value="11.00">11.00</option>
												<option value="12.00">12.00</option>
												<option value="13.00">13.00</option>
												<option value="14.00">14.00</option>
												<option value="15.00">15.00</option>
												<option value="16.00">16.00</option>
												<option value="17.00">17.00</option>
												<option value="18.00">18.00</option>
												<option value="19.00">19.00</option>
												<option value="20.00">20.00</option>
												<option value="21.00">21.00</option>
												<option value="22.00">22.00</option>
												<option value="23.00">23.00</option>
  											</select>
											<br>
										</div>						

										<div class="form-group">
											<br>
											<label>Harga Perjam</label>
											<input type="text" class="input" name="harga" readonly value="<?php echo $d['lapangan_harga']; ?>">
										</div>

										<br>

									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="center">
											<input type="submit" class="primary-btn" value="Booking Lapangan">
										</div>
									</div>
								</div>

							</div>
							<div class="col-lg-6">

								<!-- preview pesanan -->

							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /section -->
<?php include 'footer.php'; ?>
