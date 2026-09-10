<?php include 'header.php'; ?>

<div class="content-wrapper">

  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; font-family: var(--admin-font-heading);">
          <i class="fa fa-line-chart" style="color: #2563eb; margin-right: 8px;"></i>Laporan Penjualan & Booking
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Rekapitulasi pendapatan sewa arena, analisis performa reservasi, dan pengunduhan laporan keuangan resmi.
        </p>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <div class="col-lg-12">
        
        <!-- FILTER FORM CARD -->
        <div class="box box-info" style="border-radius: var(--admin-radius-lg); overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
          <div class="box-header" style="background: #ffffff; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 class="box-title" style="font-size: 16px; font-weight: 800; color: #0f172a; font-family: var(--admin-font-heading); margin: 0;">
              <i class="fa fa-filter" style="color: #2563eb; margin-right: 6px;"></i> Filter Periode Penjualan
            </h3>

            <!-- QUICK PRESET BUTTONS -->
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
              <?php 
                $today = date('Y-m-d');
                $first_month = date('Y-m-01');
                $thirty_days_ago = date('Y-m-d', strtotime('-30 days'));
                $first_year = date('Y-01-01');
              ?>
              <button type="button" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 700; color: #475569;" onclick="setPeriod('<?php echo $today; ?>', '<?php echo $today; ?>')">
                <i class="fa fa-calendar-o"></i> Hari Ini
              </button>
              <button type="button" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 700; color: #475569;" onclick="setPeriod('<?php echo $first_month; ?>', '<?php echo $today; ?>')">
                <i class="fa fa-calendar"></i> Bulan Ini
              </button>
              <button type="button" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 700; color: #475569;" onclick="setPeriod('<?php echo $thirty_days_ago; ?>', '<?php echo $today; ?>')">
                <i class="fa fa-history"></i> 30 Hari Terakhir
              </button>
              <button type="button" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 700; color: #475569;" onclick="setPeriod('<?php echo $first_year; ?>', '<?php echo $today; ?>')">
                <i class="fa fa-flag"></i> Tahun Ini
              </button>
            </div>
          </div>

          <div class="box-body" style="padding: 24px; background: #ffffff;">
            <form method="get" action="" id="filterForm">
              <div class="row" style="align-items: flex-end;">

                <div class="col-md-4 col-sm-6">
                  <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #0f172a; font-size: 13px; margin-bottom: 6px; display: block;">
                      <i class="fa fa-calendar-check-o" style="color: #2563eb; margin-right: 4px;"></i> Dari Tanggal
                    </label>
                    <input autocomplete="off" type="text" id="tanggal_dari" value="<?php if(isset($_GET['tanggal_dari'])){echo $_GET['tanggal_dari'];}else{echo date('Y-m-01');} ?>" name="tanggal_dari" class="form-control datepicker2" placeholder="YYYY-MM-DD" required="required" style="height: 44px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 600; box-shadow: none;">
                  </div>
                </div>

                <div class="col-md-4 col-sm-6">
                  <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #0f172a; font-size: 13px; margin-bottom: 6px; display: block;">
                      <i class="fa fa-calendar-check-o" style="color: #2563eb; margin-right: 4px;"></i> Sampai Tanggal
                    </label>
                    <input autocomplete="off" type="text" id="tanggal_sampai" value="<?php if(isset($_GET['tanggal_sampai'])){echo $_GET['tanggal_sampai'];}else{echo date('Y-m-d');} ?>" name="tanggal_sampai" class="form-control datepicker2" placeholder="YYYY-MM-DD" required="required" style="height: 44px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 600; box-shadow: none;">
                  </div>
                </div>

                <div class="col-md-4 col-sm-12" style="margin-top: 15px;">
                  <button type="submit" class="btn btn-primary btn-block" style="height: 44px; border-radius: 10px; font-weight: 800; font-family: var(--admin-font-heading); background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none; box-shadow: 0 4px 14px rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa fa-search"></i> TAMPILKAN LAPORAN
                  </button>
                </div>

              </div>
            </form>
          </div>
        </div>

        <!-- REPORT RESULTS -->
        <?php 
        if(isset($_GET['tanggal_sampai']) && isset($_GET['tanggal_dari'])){
          $tgl_dari = mysqli_real_escape_string($koneksi, $_GET['tanggal_dari']);
          $tgl_sampai = mysqli_real_escape_string($koneksi, $_GET['tanggal_sampai']);
          
          // Stats calculation
          $tot_q = mysqli_query($koneksi, "SELECT 
            COUNT(invoice_id) as total_trx,
            SUM(CASE WHEN invoice_status IN ('3','4') THEN invoice_total_bayar ELSE 0 END) as revenue_konfirmasi,
            SUM(CASE WHEN invoice_status IN ('0','1') THEN invoice_total_bayar ELSE 0 END) as revenue_pending,
            SUM(CASE WHEN invoice_status IN ('3','4') THEN 1 ELSE 0 END) as count_sukses,
            SUM(CASE WHEN invoice_status IN ('0','1') THEN 1 ELSE 0 END) as count_pending,
            SUM(CASE WHEN invoice_status = '2' THEN 1 ELSE 0 END) as count_ditolak
            FROM invoice WHERE date(invoice_tanggal) >= '$tgl_dari' AND date(invoice_tanggal) <= '$tgl_sampai'");
          
          $stats = mysqli_fetch_assoc($tot_q);
          $sum_revenue = isset($stats['revenue_konfirmasi']) ? $stats['revenue_konfirmasi'] : 0;
          $pending_revenue = isset($stats['revenue_pending']) ? $stats['revenue_pending'] : 0;
          $total_trx = isset($stats['total_trx']) ? $stats['total_trx'] : 0;
          $count_sukses = isset($stats['count_sukses']) ? $stats['count_sukses'] : 0;
          $count_pending = isset($stats['count_pending']) ? $stats['count_pending'] : 0;
          $count_ditolak = isset($stats['count_ditolak']) ? $stats['count_ditolak'] : 0;
        ?>

        <!-- EXECUTIVE SUMMARY STAT CARDS -->
        <div class="row" style="margin-bottom: 24px;">
          
          <!-- Card 1: Omset Terkonfirmasi -->
          <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 8px 24px rgba(15,23,42,0.18); position: relative; overflow: hidden; border: 1px solid #334155;">
              <div style="position: absolute; right: -10px; bottom: -10px; font-size: 80px; color: rgba(255,255,255,0.04); pointer-events: none;">
                <i class="fa fa-money"></i>
              </div>
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #38bdf8; margin-bottom: 6px;">
                <i class="fa fa-check-circle" style="margin-right: 4px;"></i> Total Omset Terkonfirmasi
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #ffffff; line-height: 1.2;">
                Rp <?php echo number_format($sum_revenue); ?>
              </div>
              <div style="font-size: 12px; color: #94a3b8; margin-top: 8px;">
                Dari <?php echo number_format($count_sukses); ?> transaksi disetujui / selesai
              </div>
            </div>
          </div>

          <!-- Card 2: Total Reservasi -->
          <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
            <div style="background: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 4px 20px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 6px;">
                <i class="fa fa-ticket" style="color: #2563eb; margin-right: 4px;"></i> Total Permintaan Reservasi
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #0f172a; line-height: 1.2;">
                <?php echo number_format($total_trx); ?> <span style="font-size: 15px; font-weight: 600; color: #64748b;">Transaksi</span>
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 8px; display: flex; gap: 12px;">
                <span><i class="fa fa-circle" style="color: #10b981; font-size: 10px;"></i> Sukses: <strong><?php echo $count_sukses; ?></strong></span>
                <span><i class="fa fa-circle" style="color: #f59e0b; font-size: 10px;"></i> Pending: <strong><?php echo $count_pending; ?></strong></span>
                <span><i class="fa fa-circle" style="color: #ef4444; font-size: 10px;"></i> Ditolak: <strong><?php echo $count_ditolak; ?></strong></span>
              </div>
            </div>
          </div>

          <!-- Card 3: Pending Potential Revenue -->
          <div class="col-md-4 col-sm-12" style="margin-bottom: 15px;">
            <div style="background: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 4px 20px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #d97706; margin-bottom: 6px;">
                <i class="fa fa-clock-o" style="margin-right: 4px;"></i> Potensi Omset Pending
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #b45309; line-height: 1.2;">
                Rp <?php echo number_format($pending_revenue); ?>
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 8px;">
                Menunggu verifikasi bukti bayar atau transfer customer
              </div>
            </div>
          </div>

        </div>

        <!-- MAIN TABLE CARD -->
        <div class="box box-info" style="border-radius: var(--admin-radius-lg); overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06); border: 1px solid #e2e8f0;">
          <div class="box-header" style="background: #ffffff; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
              <h3 class="box-title" style="font-size: 17px; font-weight: 800; color: #0f172a; font-family: var(--admin-font-heading); margin: 0;">
                <i class="fa fa-list-alt" style="color: #2563eb; margin-right: 6px;"></i> Detail Transaksi Laporan
              </h3>
              <div style="font-size: 13px; color: #64748b; margin-top: 2px;">
                Periode: <strong><?php echo date('d-m-Y', strtotime($tgl_dari)); ?></strong> s/d <strong><?php echo date('d-m-Y', strtotime($tgl_sampai)); ?></strong>
              </div>
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
              <a href="laporan_pdf.php?tanggal_dari=<?php echo $tgl_dari ?>&tanggal_sampai=<?php echo $tgl_sampai ?>" target="_blank" class="btn btn-danger btn-sm" style="font-weight: 700; border-radius: 8px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(225,29,72,0.2);">
                <i class="fa fa-file-pdf-o"></i> Ekspor PDF Statement
              </a>
              <a href="laporan_print.php?tanggal_dari=<?php echo $tgl_dari ?>&tanggal_sampai=<?php echo $tgl_sampai ?>" target="_blank" class="btn btn-default btn-sm" style="font-weight: 700; border-radius: 8px; padding: 8px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px; background: #ffffff;">
                <i class="fa fa-print" style="color: #2563eb;"></i> Cetak Laporan Formal
              </a>
            </div>
          </div>

          <div class="box-body" style="padding: 20px;">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr style="background: #f8fafc; color: #0f172a; font-weight: 700; font-size: 13px;">
                    <th width="4%" class="text-center">NO</th>
                    <th width="14%">NO INVOICE</th>
                    <th width="12%">TGL ORDER</th>
                    <th width="12%">JADWAL MAIN</th>
                    <th width="18%">CUSTOMER</th>
                    <th width="18%">ARENA / LAPANGAN</th>
                    <th class="text-center" width="12%">STATUS</th>
                    <th class="text-right" width="14%">TOTAL BAYAR</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  $grand_total = 0;
                  $data = mysqli_query($koneksi,"SELECT * FROM invoice 
                    INNER JOIN customer ON invoice_customer=customer_id 
                    LEFT JOIN lapangan ON invoice_lapangan=lapangan_id 
                    WHERE date(invoice_tanggal) >= '$tgl_dari' AND date(invoice_tanggal) <= '$tgl_sampai' 
                    ORDER BY invoice_id DESC");
                  
                  while($i = mysqli_fetch_array($data)){
                    if($i['invoice_status'] == 3 || $i['invoice_status'] == 4) {
                      $grand_total += $i['invoice_total_bayar'];
                    }
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td style="font-weight: 800; color: #2563eb; font-family: var(--admin-font-heading);">
                        #INV-<?php echo str_pad($i['invoice_id'], 5, '0', STR_PAD_LEFT); ?>
                      </td>
                      <td style="font-weight: 600; font-size: 13px; color: #334155;">
                        <i class="fa fa-calendar" style="color: #94a3b8; font-size: 11px;"></i> <?php echo date('d/m/Y', strtotime($i['invoice_tanggal'])); ?>
                      </td>
                      <td style="font-weight: 600; font-size: 13px; color: #0f172a;">
                        <i class="fa fa-clock-o" style="color: #2563eb; font-size: 11px;"></i> <?php echo date('d/m/Y', strtotime($i['invoice_tgl_main'])); ?>
                        <div style="font-size: 11px; color: #64748b;"><?php echo $i['invoice_jam_mulai']; ?> - <?php echo $i['invoice_jam_selesai']; ?></div>
                      </td>
                      <td>
                        <div style="font-weight: 700; color: #0f172a;"><?php echo $i['customer_nama']; ?></div>
                        <div style="font-size: 11px; color: #64748b;"><i class="fa fa-phone"></i> <?php echo $i['customer_hp']; ?></div>
                      </td>
                      <td>
                        <div style="font-weight: 700; color: #334155;"><?php echo isset($i['lapangan_nama']) ? $i['lapangan_nama'] : 'Lapangan SportKuy'; ?></div>
                      </td>
                      <td class="text-center">
                        <?php 
                        if($i['invoice_status'] == 0){
                          echo "<span class='label label-warning' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-clock-o'></i> Menunggu Bayar</span>";
                        }elseif($i['invoice_status'] == 1){
                          echo "<span class='label label-info' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-hourglass-half'></i> Konfirmasi</span>";
                        }elseif($i['invoice_status'] == 2){
                          echo "<span class='label label-danger' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-times'></i> Ditolak</span>";
                        }elseif($i['invoice_status'] == 3){
                          echo "<span class='label label-primary' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-check'></i> Dikonfirmasi</span>";
                        }elseif($i['invoice_status'] == 4){
                          echo "<span class='label label-success' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-star'></i> Selesai</span>";
                        }
                        ?>
                      </td>
                      <td class="text-right" style="font-weight: 800; color: #0f172a; font-size: 14px;">
                        Rp <?php echo number_format($i['invoice_total_bayar']); ?>
                      </td>
                    </tr>
                    <?php 
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                    <td colspan="7" class="text-right" style="font-weight: 800; font-size: 14px; letter-spacing: 0.05em; padding: 14px;">
                      TOTAL REKAPITULASI OMSET TERKONFIRMASI:
                    </td>
                    <td class="text-right" style="font-weight: 800; font-size: 16px; color: #38bdf8; font-family: var(--admin-font-heading); padding: 14px;">
                      Rp <?php echo number_format($grand_total); ?>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>

          </div>
        </div>

        <?php } ?>

      </div>
    </div>
  </section>

</div>

<script>
function setPeriod(from, to) {
  document.getElementById('tanggal_dari').value = from;
  document.getElementById('tanggal_sampai').value = to;
  document.getElementById('filterForm').submit();
}
</script>

<?php include 'footer.php'; ?>