<!DOCTYPE html>
<html>
<head>
	<title>Cetak Invoice Customer</title>
</head>
<body>

	<?php 
	session_start();
	include '../koneksi.php';
	?>

	<style>
		body{
			font-family: sans-serif;
		}

		.table{
			border-collapse: collapse;
		}
		.table th,
		.table td{
			padding: 5px 10px;
			border: 1px solid black;
		}
	</style>

	<div>

		<?php 
		$id_invoice = $_GET['id'];
		$invoice = mysqli_query($koneksi,"select * from invoice where invoice_id='$id_invoice' order by invoice_id desc");
		while($i = mysqli_fetch_array($invoice)){
			?>


			<div>

				<center>
					<h3>Customer Invoice</h3>
				</center>

				<h4>INVOICE-00<?php echo $i['invoice_id'] ?></h4>


				<br/>
				Nama Pemesan   : <?php echo $i['invoice_nama']; ?><br/>
              	Hp Pemesan     : <?php echo $i['invoice_hp']; ?><br/>
              	Tanggal Main   : <?php echo date('d-m-Y', strtotime($i['invoice_tgl_main'])); ?><br/>
              	Jam Main       : <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?><br/>
				<br/>

				<table class="table">
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
										<img src="../gambar/sistem/produk.png" style="width: 50px;height: auto">
									<?php }else{ ?>
										<img src="../gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" style="width: 50px;height: auto">
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
	<script>
		window.print();
	</script>
</body>
</html>