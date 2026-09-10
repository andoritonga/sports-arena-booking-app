<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Dashboard Admin
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Selamat datang kembali, <strong><?php echo $_SESSION['nama']; ?></strong>! Berikut ringkasan performa sistem SportKuy hari ini.
        </p>
      </div>
      <div>
        <span class="label label-success" style="padding: 8px 16px; font-size: 12px; border-radius: 9999px;">
          <i class="fa fa-circle text-white"></i> Sistem Aktif & Normal
        </span>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">

    <!-- QUICK ACTIONS TOOLBAR -->
    <div class="admin-action-bar">
      <a href="lapangan_tambah.php" class="admin-action-btn primary">
        <i class="fa fa-plus-circle"></i> Tambah Lapangan Baru
      </a>
      <a href="transaksi.php" class="admin-action-btn">
        <i class="fa fa-credit-card"></i> Kelola Transaksi
      </a>
      <a href="customer_tambah.php" class="admin-action-btn">
        <i class="fa fa-user-plus"></i> Tambah Customer
      </a>
      <a href="laporan.php" class="admin-action-btn">
        <i class="fa fa-file-text-o"></i> Cetak Laporan Penjualan
      </a>
    </div>

    <!-- METRIC STAT CARDS -->
    <div class="row">

      <!-- TOTAL OMSET / REVENUE -->
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <?php 
            $omset_q = mysqli_query($koneksi, "SELECT SUM(invoice_total_bayar) as total FROM invoice WHERE invoice_status IN ('3','4')");
            $omset_data = mysqli_fetch_assoc($omset_q);
            $total_omset = isset($omset_data['total']) ? $omset_data['total'] : 0;
            ?>
            <h3>Rp <?php echo number_format($total_omset/1000, 0); ?>k</h3>
            <p>Total Omset / Pendapatan</p>
          </div>
          <div class="icon">
            <i class="fa fa-money"></i>
          </div>
          <a href="laporan.php" class="small-box-footer">Rincian Laporan <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- TOTAL TRANSAKSI -->
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <?php 
            $invoice = mysqli_query($koneksi,"SELECT * FROM invoice");
            ?>
            <h3><?php echo mysqli_num_rows($invoice); ?></h3>
            <p>Total Transaksi Booking</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-check-o"></i>
          </div>
          <a href="transaksi.php" class="small-box-footer">Lihat Semua <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- TOTAL LAPANGAN -->
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <?php 
            $lapangan = mysqli_query($koneksi,"SELECT * FROM lapangan");
            ?>
            <h3><?php echo mysqli_num_rows($lapangan); ?></h3>
            <p>Jumlah Arena Lapangan</p>
          </div>
          <div class="icon">
            <i class="fa fa-soccer-ball-o"></i>
          </div>
          <a href="lapangan.php" class="small-box-footer">Kelola Lapangan <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- TOTAL CUSTOMER -->
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <?php 
            $customer = mysqli_query($koneksi,"SELECT * FROM customer");
            ?>
            <h3><?php echo mysqli_num_rows($customer); ?></h3>
            <p>Customer Terdaftar</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <a href="customer.php" class="small-box-footer">Data Customer <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

    </div>

    <!-- MAIN DASHBOARD CONTENT GRID -->
    <div class="row">    
      
      <!-- RECENT TRANSACTIONS TABLE (COL 8) -->
      <section class="col-lg-8">
        <div class="box box-info">
          <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="box-title"><i class="fa fa-clock-o" style="color: #2563eb;"></i> Transaksi Booking Terbaru</h3>
            <a href="transaksi.php" class="btn btn-default btn-xs" style="font-weight: 700;">Lihat Semua</a>
          </div>
          <div class="box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table" style="margin: 0;">
                <thead>
                  <tr>
                    <th>NO INVOICE</th>
                    <th>PEMESAN</th>
                    <th>TGL MAIN</th>
                    <th>TOTAL</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $recents = mysqli_query($koneksi, "SELECT * FROM invoice ORDER BY invoice_id DESC LIMIT 5");
                  if(mysqli_num_rows($recents) == 0){
                    echo "<tr><td colspan='6' class='text-center' style='padding: 20px;'>Belum ada transaksi booking.</td></tr>";
                  }
                  while($r = mysqli_fetch_array($recents)){
                  ?>
                    <tr>
                      <td style="font-weight: 700;">
                        <a href="transaksi_invoice.php?id=<?php echo $r['invoice_id']; ?>" style="color: #2563eb;">
                          #INV-<?php echo str_pad($r['invoice_id'], 5, '0', STR_PAD_LEFT); ?>
                        </a>
                      </td>
                      <td>
                        <strong><?php echo $r['invoice_nama']; ?></strong><br>
                        <small style="color: #64748b;"><?php echo $r['invoice_hp']; ?></small>
                      </td>
                      <td style="font-size: 13px; font-weight: 600;">
                        <?php echo date('d/m/Y', strtotime($r['invoice_tgl_main'])); ?><br>
                        <small style="color: #64748b;"><?php echo $r['invoice_jam_mulai'], "-", $r['invoice_jam_selesai']; ?></small>
                      </td>
                      <td style="font-weight: 700; color: #0f172a;">
                        Rp <?php echo number_format($r['invoice_total_bayar']); ?>
                      </td>
                      <td class="text-center">
                        <?php 
                        if($r['invoice_status'] == 0){
                          echo "<span class='label label-warning'>Menunggu Bayar</span>";
                        }elseif($r['invoice_status'] == 1){
                          echo "<span class='label label-info'>Konfirmasi</span>";
                        }elseif($r['invoice_status'] == 2){
                          echo "<span class='label label-danger'>Ditolak</span>";
                        }elseif($r['invoice_status'] == 3){
                          echo "<span class='label label-primary'>Dikonfirmasi</span>";
                        }elseif($r['invoice_status'] == 4){
                          echo "<span class='label label-success'>Selesai</span>";
                        }
                        ?>
                      </td>
                      <td class="text-center">
                        <a href="transaksi_invoice.php?id=<?php echo $r['invoice_id']; ?>" class="btn btn-default btn-xs" title="Lihat Rincian">
                          <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- ADMIN ACCOUNT & SYSTEM CARD (COL 4) -->
      <section class="col-lg-4">
        
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user-circle" style="color: #2563eb;"></i> Profil Administrator</h3>
          </div>
          <div class="box-body text-center" style="padding: 24px;">
            <?php 
            $id = $_SESSION['id'];
            $profil = mysqli_query($koneksi,"SELECT * FROM admin WHERE admin_id='$id'");
            $p = mysqli_fetch_assoc($profil);
            if(!empty($p['admin_foto'])){
              echo "<img src='../gambar/user/{$p['admin_foto']}' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #2563eb; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);'>";
            } else {
              echo "<img src='../gambar/sistem/user.png' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #2563eb; margin-bottom: 12px;'>";
            }
            ?>
            <h4 style="font-weight: 800; margin: 0 0 4px 0; color: #0f172a;"><?php echo $_SESSION['nama']; ?></h4>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">@<?php echo $_SESSION['username']; ?> &bull; System Administrator</p>

            <div style="background: #f8fafc; padding: 12px; border-radius: 12px; text-align: left; border: 1px solid #e2e8f0; margin-bottom: 16px;">
              <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Status Sesi</div>
              <div style="font-weight: 700; color: #166534;"><i class="fa fa-check-circle" style="color: #22c55e;"></i> Authenticated & Active</div>
            </div>

            <a href="gantipassword.php" class="btn btn-primary btn-block"><i class="fa fa-key"></i> Ganti Password</a>
          </div>
        </div>

      </section>

    </div>

  </section>

</div>
<?php include 'footer.php'; ?>