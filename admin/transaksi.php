<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Kelola Transaksi Booking
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Daftar seluruh pesanan reservasi lapangan dan konfirmasi pembayaran dari pelanggan.
        </p>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">

          <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="box-title"><i class="fa fa-credit-card" style="color: #2563eb;"></i> Data Transaksi & Status Booking</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">NO</th>
                    <th>NO INVOICE</th>
                    <th>TGL ORDER</th>
                    <th>CUSTOMER</th>
                    <th>TOTAL BAYAR</th>
                    <th class="text-center">STATUS SAAT INI</th>
                    <th class="text-center" style="white-space: nowrap;">UPDATE STATUS</th>
                    <th class="text-center" style="white-space: nowrap;">OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  $invoice = mysqli_query($koneksi,"SELECT * FROM invoice, customer WHERE customer_id=invoice_customer ORDER BY invoice_id DESC");
                  while($i = mysqli_fetch_array($invoice)){
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td style="font-weight: 800; white-space: nowrap;">
                        <a href="transaksi_invoice.php?id=<?php echo $i['invoice_id']; ?>" style="color: #2563eb;">
                          #INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?>
                        </a>
                      </td>
                      <td style="font-weight: 600; font-size: 13px; white-space: nowrap; color: #334155;">
                        <?php echo date('d/m/Y', strtotime($i['invoice_tanggal'])); ?>
                      </td>
                      <td>
                        <strong style="color: #0f172a; font-size: 14px;"><?php echo $i['customer_nama']; ?></strong><br>
                        <small style="color: #64748b;"><i class="fa fa-phone"></i> <?php echo $i['customer_hp']; ?></small>
                      </td>
                      <td style="font-weight: 800; color: #2563eb; white-space: nowrap;">
                        Rp <?php echo number_format($i['invoice_total_bayar']); ?>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">
                        <?php 
                        if($i['invoice_status'] == 0){
                          echo "<span class='label label-warning'><i class='fa fa-clock-o'></i> Menunggu Bayar</span>";
                        }elseif($i['invoice_status'] == 1){
                          echo "<span class='label label-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>";
                        }elseif($i['invoice_status'] == 2){
                          echo "<span class='label label-danger'><i class='fa fa-times-circle'></i> Ditolak</span>";
                        }elseif($i['invoice_status'] == 3){
                          echo "<span class='label label-primary'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>";
                        }elseif($i['invoice_status'] == 4){
                          echo "<span class='label label-success'><i class='fa fa-star'></i> Selesai</span>";
                        }
                        ?>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">
                        <form action="transaksi_status.php" method="post" style="display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                          <input type="hidden" value="<?php echo $i['invoice_id'] ?>" name="invoice">
                          <select name="status" class="form-control input-sm" style="width: auto; font-size: 12px; font-weight: 600;">
                            <option <?php if($i['invoice_status'] == "0"){echo "selected='selected'";} ?> value="0">Menunggu Bayar</option>
                            <option <?php if($i['invoice_status'] == "1"){echo "selected='selected'";} ?> value="1">Menunggu Konfirmasi</option>
                            <option <?php if($i['invoice_status'] == "2"){echo "selected='selected'";} ?> value="2">Ditolak</option>
                            <option <?php if($i['invoice_status'] == "3"){echo "selected='selected'";} ?> value="3">Dikonfirmasi</option>
                            <option <?php if($i['invoice_status'] == "4"){echo "selected='selected'";} ?> value="4">Selesai</option>
                          </select>                          
                          <button type="submit" class="btn btn-warning btn-sm" title="Simpan Status" style="padding: 6px 10px;">
                            <i class="fa fa-check"></i>
                          </button>
                        </form>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">    
                        <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                          <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#buktiPembayaran_<?php echo $i['invoice_id']; ?>" title="Bukti Bayar">
                            <i class="fa fa-file-image-o"></i> Bukti
                          </button>

                          <a href="transaksi_invoice.php?id=<?php echo $i['invoice_id']; ?>" class="btn btn-primary btn-xs" title="Invoice">
                            <i class="fa fa-eye"></i> Invoice
                          </a>

                          <a href="transaksi_hapus_konfir.php?id=<?php echo $i['invoice_id']; ?>" class="btn btn-danger btn-xs" title="Hapus">
                            <i class="fa fa-trash"></i>
                          </a>
                        </div>

                        <!-- MODAL BUKTI PEMBAYARAN -->
                        <div class="modal fade" id="buktiPembayaran_<?php echo $i['invoice_id']; ?>" tabindex="-1" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                              <div class="modal-header" style="background: #0f172a; color: #fff;">
                                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 1;">&times;</button>
                                <h4 class="modal-title" style="font-weight: 800;"><i class="fa fa-image"></i> Bukti Pembayaran #INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?></h4>
                              </div>
                              <div class="modal-body text-center" style="padding: 24px;">
                                <?php 
                                if($i['invoice_bukti'] == ""){
                                  echo "<div class='alert alert-warning' style='margin: 0;'><i class='fa fa-info-circle'></i> Bukti pembayaran belum diupload oleh pembeli/customer.</div>";
                                }else{
                                  ?>
                                  <div style="background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; display: inline-block; max-width: 100%;">
                                    <img src="../gambar/bukti_pembayaran/<?php echo $i['invoice_bukti']; ?>" alt="Bukti Pembayaran #INV-<?php echo $i['invoice_id']; ?>" style="max-width: 100%; max-height: 450px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); object-fit: contain;">
                                    <div style="margin-top: 12px;">
                                      <a href="../gambar/bukti_pembayaran/<?php echo $i['invoice_bukti']; ?>" target="_blank" class="btn btn-sm btn-primary" style="font-weight: 700; border-radius: 8px;">
                                        <i class="fa fa-external-link"></i> Buka Gambar Ukuran Penuh
                                      </a>
                                    </div>
                                  </div>
                                  <?php
                                }
                                ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                              </div>
                            </div>
                          </div>
                        </div>

                      </td>
                    </tr>
                    <?php 
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>
    </div>
  </section>

</div>
<?php include 'footer.php'; ?>
