<?php include 'header.php'; ?>

<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Invoice Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">

			<?php 
			include 'customer_sidebar.php'; 
			?>

			<div id="main" class="col-md-9">

				<h4>INVOICE</h4>

				<div id="store">
					<div class="row">

						<?php 
						$id_invoice = $_GET['id'];
						$id = $_SESSION['customer_id'];
						$invoice = mysqli_query($koneksi,"select * from invoice where invoice_customer='$id' and invoice_id='$id_invoice' order by invoice_id desc");
						while($i = mysqli_fetch_array($invoice)){
							?>
							<div class="col-lg-12">
								<a href="customer_invoice_cetak.php?id=<?php echo $_GET['id'] ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> CETAK</a>
								<br/>
								<br/>
								<h4>INVOICE-00<?php echo $i['invoice_id'] ?></h4>
								<br/>
								Nama Pemesan   : <?php echo $i['invoice_nama']; ?><br/>
              					Hp Pemesan     : <?php echo $i['invoice_hp']; ?><br/>
								Tanggal Booking : <?php echo DateToIndo($i['invoice_tanggal']); ?><br/>
              					Tanggal Main   :  <?php echo DateToIndo($i['invoice_tgl_main']); ?><br/>
              					Jam Main       :  <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?><br/>
								<br/>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center" width="1%">NO</th>
												<th colspan="2">Lapangan</th>
												<th class="text-center">Harga</th>
												<th class="text-center">Jumlah Jam</th>
												<th class="text-center">Total Harga</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$no = 1;											
											$transaksi = mysqli_query($koneksi,"select * from lapangan where lapangan_id = '$i[invoice_lapangan]'");
											$d=mysqli_fetch_array($transaksi);
												?>
												<tr>
													<td class="text-center"><?php echo $no++; ?></td>
													<td>
														<center>
															<?php if($d['lapangan_foto1'] == ""){ ?>
																<img src="gambar/sistem/produk.png" style="width: 50px;height: auto">
															<?php }else{ ?>
																<img src="gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="width: 50px;height: auto">
															<?php } ?>
														</center>
													</td>
													<td><?php echo $d['lapangan_nama']; ?></td>
													<td class="text-center"><?php echo "Rp. ".number_format($i['invoice_harga']).",-"; ?></td>
													<td class="text-center"><?php echo number_format($i['invoice_jam_selesai'] - $i['invoice_jam_mulai']); ?></td>
													<td class="text-center"><?php echo "Rp. ".number_format($i['invoice_total_bayar'])." ,-"; ?></td>
												</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4" style="border: none"></td>
												<th>Total Bayar</th>
												<td class="text-center"><?php echo "Rp. ".number_format($i['invoice_total_bayar'])." ,-"; ?></td>
											</tr>
										</tfoot>
									</table>
								</div>

								<h5>STATUS :</h5> 
								<?php 
								if($i['invoice_status'] == 0){
									echo "<span class='label label-warning'>Menunggu Pembayaran</span>";
								}elseif($i['invoice_status'] == 1){
									echo "<span class='label label-default'>Menunggu Konfirmasi</span>";
								}elseif($i['invoice_status'] == 2){
									echo "<span class='label label-danger'>Ditolak</span>";
								}elseif($i['invoice_status'] == 3){
									echo "<span class='label label-primary'>Dikonfirmasi</span>";
								}elseif($i['invoice_status'] == 4){
									echo "<span class='label label-success'>Selesai</span>";
								}
								?>
							</div>	
							<?php 
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
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