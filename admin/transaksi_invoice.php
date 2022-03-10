<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Transaksi
      <small>Data Transaksi / Pesanan</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">

          <div class="box-header">
            <h3 class="box-title">Invoice Pesanan</h3>
          </div>
          <div class="box-body">

           <?php 
           // include '../skoneksi';
           $id_invoice = $_GET['id'];
           $invoice = mysqli_query($koneksi,"select * from invoice where invoice_id='$id_invoice' order by invoice_id desc");
           while($i = mysqli_fetch_array($invoice)){
            ?>


            <div class="col-lg-12">

              <a href="transaksi.php" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> KEMBALI</a>
              <a href="transaksi_invoice_cetak.php?id=<?php echo $_GET['id'] ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> CETAK</a>

              <br/>
              <br/>

              <h4>INVOICE-00<?php echo $i['invoice_id'] ?></h4>


              <br/>
              Nama Pemesan   : <?php echo $i['invoice_nama']; ?><br/>
              Hp Pemesan     : <?php echo $i['invoice_hp']; ?><br/>
              Tanggal Main   : <?php echo date('d-m-Y', strtotime($i['invoice_tgl_main'])); ?><br/>
              Jam Main       : <?php echo $i['invoice_jam_mulai'], " - ", $i['invoice_jam_selesai']; ?><br/>
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
    </section>
  </div>
</section>

</div>
<?php include 'footer.php'; ?>