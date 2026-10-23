import {
  escapeHtml,
  numberFormat,
  strPadLeft,
  dateToIndo,
  formatDMY,
} from './utils';

export function renderAdminLoginPage(alert?: string): string {
  let alertHtml = '';
  if (alert === 'gagal') {
    alertHtml = `<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Login gagal! Username atau password salah.</div>`;
  } else if (alert === 'logout') {
    alertHtml = `<div class='alert alert-success text-center' style='border-radius: 12px; font-weight: 600;'>Anda telah berhasil logout.</div>`;
  } else if (alert === 'belum_login') {
    alertHtml = `<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login untuk mengakses portal admin.</div>`;
  }

  return `<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Admin</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .admin-login-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .logo-badge {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 26px;
      margin-bottom: 16px;
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }
    .btn-login {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      color: #fff !important;
      border-radius: 12px !important;
      padding: 12px !important;
      font-weight: 700 !important;
      font-size: 16px !important;
      border: none !important;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3) !important;
      transition: all 0.2s ease !important;
    }
    .btn-login:hover {
      box-shadow: 0 8px 22px rgba(37, 99, 235, 0.5) !important;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="admin-login-card">
    <div class="text-center" style="margin-bottom: 30px;">
      <div class="logo-badge">
        <i class="fa fa-shield"></i>
      </div>
      <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Portal Admin</h2>
      <p style="color: #64748b; font-size: 14px; margin: 0;">SportKuy Management Dashboard</p>
    </div>

    ${alertHtml}

    <form action="periksa_login.php" method="POST">
      <div class="form-group" style="margin-bottom: 20px;">
        <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;"><i class="fa fa-user" style="color: #2563eb;"></i> Username Administrator</label>
        <input type="text" class="form-control" style="border-radius: 12px; padding: 12px 16px; background: #f8fafc;" placeholder="Masukkan username..." name="username" required="required" autocomplete="off">
      </div>
      <div class="form-group" style="margin-bottom: 26px;">
        <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;"><i class="fa fa-lock" style="color: #2563eb;"></i> Password</label>
        <input type="password" class="form-control" style="border-radius: 12px; padding: 12px 16px; background: #f8fafc;" placeholder="Masukkan password..." name="password" required="required" autocomplete="off">
      </div>
      <button type="submit" class="btn btn-login btn-block"><i class="fa fa-sign-in"></i> MASUK KE DASHBOARD</button>
    </form>
    
    <div class="text-center" style="margin-top: 24px;">
      <a href="index.php" style="color: #64748b; font-size: 14px; font-weight: 600; text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali ke Halaman Utama</a>
    </div>
  </div>
  <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>`;
}

export function renderAdminDashboard(params: {
  admin: any;
  totalOmset: number;
  totalInvoice: number;
  totalLapangan: number;
  totalCustomer: number;
  recentInvoices: Array<any>;
}): string {
  const { admin, totalOmset, totalInvoice, totalLapangan, totalCustomer, recentInvoices } = params;

  const rowsHtml = recentInvoices
    .map((r) => {
      let stBadge = '';
      if (r.invoice_status === 0) stBadge = `<span class='label label-warning'>Menunggu Bayar</span>`;
      else if (r.invoice_status === 1) stBadge = `<span class='label label-info'>Konfirmasi</span>`;
      else if (r.invoice_status === 2) stBadge = `<span class='label label-danger'>Ditolak</span>`;
      else if (r.invoice_status === 3) stBadge = `<span class='label label-primary'>Dikonfirmasi</span>`;
      else if (r.invoice_status === 4) stBadge = `<span class='label label-success'>Selesai</span>`;

      return `<tr>
                      <td style="font-weight: 700;">
                        <a href="transaksi_invoice.php?id=${r.invoice_id}" style="color: #2563eb;">
                          #INV-${strPadLeft(r.invoice_id, 5, '0')}
                        </a>
                      </td>
                      <td>
                        <strong>${escapeHtml(r.invoice_nama)}</strong><br>
                        <small style="color: #64748b;">${escapeHtml(r.invoice_hp)}</small>
                      </td>
                      <td style="font-size: 13px; font-weight: 600;">
                        ${formatDMY(r.invoice_tgl_main)}<br>
                        <small style="color: #64748b;">${escapeHtml(r.invoice_jam_mulai)} - ${escapeHtml(r.invoice_jam_selesai)}</small>
                      </td>
                      <td style="font-weight: 700; color: #0f172a;">
                        Rp ${numberFormat(r.invoice_total_bayar)}
                      </td>
                      <td class="text-center">${stBadge}</td>
                      <td class="text-center">
                        <a href="transaksi_invoice.php?id=${r.invoice_id}" class="btn btn-default btn-xs" title="Lihat Rincian">
                          <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>`;
    })
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Dashboard Admin
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Selamat datang kembali, <strong>${escapeHtml(admin.admin_nama)}</strong>! Berikut ringkasan performa sistem SportKuy hari ini.
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
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>Rp ${numberFormat(totalOmset / 1000)}k</h3>
            <p>Total Omset / Pendapatan</p>
          </div>
          <div class="icon">
            <i class="fa fa-money"></i>
          </div>
          <a href="laporan.php" class="small-box-footer">Rincian Laporan <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>${totalInvoice}</h3>
            <p>Total Transaksi Booking</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-check-o"></i>
          </div>
          <a href="transaksi.php" class="small-box-footer">Lihat Semua <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>${totalLapangan}</h3>
            <p>Jumlah Arena Lapangan</p>
          </div>
          <div class="icon">
            <i class="fa fa-soccer-ball-o"></i>
          </div>
          <a href="lapangan.php" class="small-box-footer">Kelola Lapangan <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>${totalCustomer}</h3>
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
                  ${rowsHtml || "<tr><td colspan='6' class='text-center' style='padding: 20px;'>Belum ada transaksi booking.</td></tr>"}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <section class="col-lg-4">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user-circle" style="color: #2563eb;"></i> Profil Administrator</h3>
          </div>
          <div class="box-body text-center" style="padding: 24px;">
            <img src="${admin.admin_foto ? `../gambar/user/${escapeHtml(admin.admin_foto)}` : '../gambar/sistem/user.png'}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #2563eb; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
            <h4 style="font-weight: 800; margin: 0 0 4px 0; color: #0f172a;">${escapeHtml(admin.admin_nama)}</h4>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">@${escapeHtml(admin.admin_username)} &bull; System Administrator</p>

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
</div>`;
}

export function renderAdminTransaksi(invoices: Array<any>): string {
  let no = 1;
  const rowsHtml = invoices
    .map((i) => {
      const st = Number(i.invoice_status);
      let stBadge = '';
      if (st === 0) stBadge = `<span class='label label-warning'><i class='fa fa-clock-o'></i> Menunggu Bayar</span>`;
      else if (st === 1) stBadge = `<span class='label label-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
      else if (st === 2) stBadge = `<span class='label label-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
      else if (st === 3) stBadge = `<span class='label label-primary'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
      else if (st === 4) stBadge = `<span class='label label-success'><i class='fa fa-star'></i> Selesai</span>`;

      return `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td style="font-weight: 800; white-space: nowrap;">
                        <a href="transaksi_invoice.php?id=${i.invoice_id}" style="color: #2563eb;">
                          #INV-${strPadLeft(i.invoice_id, 5, '0')}
                        </a>
                      </td>
                      <td style="font-weight: 600; font-size: 13px; white-space: nowrap; color: #334155;">
                        ${formatDMY(i.invoice_tanggal)}
                      </td>
                      <td>
                        <strong style="color: #0f172a; font-size: 14px;">${escapeHtml(i.customer_nama)}</strong><br>
                        <small style="color: #64748b;"><i class="fa fa-phone"></i> ${escapeHtml(i.customer_hp)}</small>
                      </td>
                      <td style="font-weight: 800; color: #2563eb; white-space: nowrap;">
                        Rp ${numberFormat(i.invoice_total_bayar)}
                      </td>
                      <td class="text-center" style="white-space: nowrap;">${stBadge}</td>
                      <td class="text-center" style="white-space: nowrap;">
                        <form action="transaksi_status.php" method="post" style="display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                          <input type="hidden" value="${i.invoice_id}" name="invoice">
                          <select name="status" class="form-control input-sm" style="width: auto; font-size: 12px; font-weight: 600;">
                            <option ${st === 0 ? "selected='selected'" : ''} value="0">Menunggu Bayar</option>
                            <option ${st === 1 ? "selected='selected'" : ''} value="1">Menunggu Konfirmasi</option>
                            <option ${st === 2 ? "selected='selected'" : ''} value="2">Ditolak</option>
                            <option ${st === 3 ? "selected='selected'" : ''} value="3">Dikonfirmasi</option>
                            <option ${st === 4 ? "selected='selected'" : ''} value="4">Selesai</option>
                          </select>                          
                          <button type="submit" class="btn btn-warning btn-sm" title="Simpan Status" style="padding: 6px 10px;">
                            <i class="fa fa-check"></i>
                          </button>
                        </form>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">    
                        <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                          <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#buktiPembayaran_${i.invoice_id}" title="Bukti Bayar">
                            <i class="fa fa-file-image-o"></i> Bukti
                          </button>

                          <a href="transaksi_invoice.php?id=${i.invoice_id}" class="btn btn-primary btn-xs" title="Invoice">
                            <i class="fa fa-eye"></i> Invoice
                          </a>

                          <a href="transaksi_hapus_konfir.php?id=${i.invoice_id}" class="btn btn-danger btn-xs" title="Hapus">
                            <i class="fa fa-trash"></i>
                          </a>
                        </div>

                        <!-- MODAL BUKTI PEMBAYARAN -->
                        <div class="modal fade" id="buktiPembayaran_${i.invoice_id}" tabindex="-1" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                              <div class="modal-header" style="background: #0f172a; color: #fff;">
                                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 1;">&times;</button>
                                <h4 class="modal-title" style="font-weight: 800;"><i class="fa fa-image"></i> Bukti Pembayaran #INV-${strPadLeft(i.invoice_id, 5, '0')}</h4>
                              </div>
                              <div class="modal-body text-center" style="padding: 24px;">
                                ${
                                  !i.invoice_bukti
                                    ? "<div class='alert alert-warning' style='margin: 0;'><i class='fa fa-info-circle'></i> Bukti pembayaran belum diupload oleh pembeli/customer.</div>"
                                    : `<img src="../gambar/bukti_pembayaran/${escapeHtml(i.invoice_bukti)}" style="max-width: 100%; max-height: 450px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);" onerror="this.src='../gambar/bukti/${escapeHtml(i.invoice_bukti)}'">`
                                }
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                              </div>
                            </div>
                          </div>
                        </div>

                      </td>
                    </tr>`;
    })
    .join('\n');

  return `<div class="content-wrapper">
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
                  ${rowsHtml}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminTransaksiHapusKonfir(id: number): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Hapus Transaksi
      <small>Data Transaksi</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Yakin Ingin Menghapus Transaksi ?</h3>
          </div>
          <div class="box-body">
            <p>Dengan menghapus, semua data Transaksi akan terhapus.</p>
            <br/>
            <a href="transaksi.php" class="btn btn-danger btn-sm"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
            <a href="transaksi_hapus.php?id=${id}" class="btn btn-success btn-sm pull-right"><i class="fa fa-check"></i> &nbsp Hapus</a> 
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminLapangan(lapanganList: Array<any>): string {
  let no = 1;
  const rowsHtml = lapanganList
    .map((d) => {
      return `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        ${escapeHtml(d.lapangan_nama)}
                      </td>
                      <td style="white-space: nowrap;">
                        <span class="label label-info" style="font-size: 12px; padding: 6px 12px;">
                          <i class="fa fa-tag"></i> ${escapeHtml(d.kategori_nama)}
                        </span>
                      </td>
                      <td style="font-weight: 800; color: #2563eb; font-size: 15px; font-family: var(--admin-font-heading); white-space: nowrap;">
                        Rp ${numberFormat(d.lapangan_harga)} <small style="color: #64748b; font-weight: 500;">/ jam</small>
                      </td>
                      <td class="text-center">
                        <div class="venue-thumb-group">
                          <img src="${d.lapangan_foto1 ? `../gambar/lapangan/${escapeHtml(d.lapangan_foto1)}` : '../gambar/sistem/produk.png'}" title="Foto Utama">
                          ${d.lapangan_foto2 ? `<img src="../gambar/lapangan/${escapeHtml(d.lapangan_foto2)}" title="Foto 2">` : ''}
                          ${d.lapangan_foto3 ? `<img src="../gambar/lapangan/${escapeHtml(d.lapangan_foto3)}" title="Foto 3">` : ''}
                        </div>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="lapangan_edit.php?id=${d.lapangan_id}" title="Edit Data"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="lapangan_hapus_konfir.php?id=${d.lapangan_id}" title="Hapus Data"><i class="fa fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>`;
    })
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Kelola Data Lapangan
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Daftar seluruh arena olahraga, harga sewa per jam, dan foto fasilitas lapangan.
        </p>
      </div>
      <div>
        <a href="lapangan_tambah.php" class="btn btn-primary" style="font-weight: 700; border-radius: var(--admin-radius-md);">
          <i class="fa fa-plus-circle"></i> Tambah Lapangan Baru
        </a>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-soccer-ball-o" style="color: #2563eb;"></i> Data Arena & Spesifikasi</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">NO</th>
                    <th>NAMA ARENA LAPANGAN</th>
                    <th>KATEGORI OLAHRAGA</th>
                    <th>HARGA SEWA / JAM</th>
                    <th class="text-center">FOTO GALERI</th>
                    <th class="text-center" style="white-space: nowrap;">OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  ${rowsHtml}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminLapanganTambah(categories: Array<any>): string {
  const optionsHtml = categories
    .map((d) => `<option value="${d.kategori_id}">${escapeHtml(d.kategori_nama)}</option>`)
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Lapangan
      <small>Tambah Lapangan Baru</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Tambah Lapangan</h3>
            <a href="lapangan.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="lapangan_act.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Nama Lapangan</label>
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama ..">
              </div>

              <div class="form-group">
                <label>Kategori Lapangan</label>
                <div class="row">
                  <div class="col-lg-4">
                    <select name="kategori" required="required" class="form-control">
                      <option value="">- Pilih Kategori Lapangan -</option>
                      ${optionsHtml}
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Harga</label>
                <div class="row">
                  <div class="col-lg-4">
                    <input type="number" class="form-control" name="harga" required="required" placeholder="Masukkan Harga ..">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Katerangan</label>
                <textarea name="keterangan" class="form-control textarea" required="required" style="resize: none" rows="10"></textarea>
              </div>

              <div class="form-group">
                <label>Foto 1 (Foto Utama)</label>
                <input type="file" name="foto1">
              </div>

              <div class="form-group">
                <label>Foto 2</label>
                <input type="file" name="foto2">
              </div>

              <div class="form-group">
                <label>Foto 3</label>
                <input type="file" name="foto3">
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminLapanganEdit(lapangan: any, categories: Array<any>): string {
  const d = lapangan;
  const optionsHtml = categories
    .map(
      (k) =>
        `<option ${k.kategori_id === d.lapangan_kategori ? "selected='selected'" : ''} value="${k.kategori_id}">${escapeHtml(k.kategori_nama)}</option>`
    )
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Lapangan
      <small>Edit Data Lapangan</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Edit Data Lapangan</h3>
            <a href="lapangan.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="lapangan_update.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Nama Lapangan</label>
                <input type="hidden" name="id" value="${d.lapangan_id}">
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama .." value="${escapeHtml(d.lapangan_nama)}">
              </div>

              <div class="form-group">
                <label>Kategori Lapangan</label>
                <div class="row">
                  <div class="col-lg-4">
                    <select name="kategori" required="required" class="form-control">
                      <option value="">- Pilih Kategori Lapangan -</option>
                      ${optionsHtml}
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Harga</label>
                <div class="row">
                  <div class="col-lg-4">
                    <input type="number" class="form-control" name="harga" required="required" placeholder="Masukkan Harga .." value="${d.lapangan_harga}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Katerangan</label>
                <textarea name="keterangan" class="form-control textarea" required="required" style="resize: none" rows="10">${escapeHtml(d.lapangan_keterangan)}</textarea>
              </div>

              <div class="form-group">
                <label>Foto 1 (Foto Utama)</label>
                <input type="file" name="foto1">
                <img src="${d.lapangan_foto1 ? `../gambar/lapangan/${escapeHtml(d.lapangan_foto1)}` : '../gambar/sistem/produk.png'}" style="width: 120px;height: auto">
                <br/>
                <small class="text-muted">Kosongkan Jika Tidak Ingin Mengubah Foto</small>
              </div>

              <div class="form-group">
                <label>Foto 2</label>
                <input type="file" name="foto2">
                <img src="${d.lapangan_foto2 ? `../gambar/lapangan/${escapeHtml(d.lapangan_foto2)}` : '../gambar/sistem/produk.png'}" style="width: 120px;height: auto">
                <br/>
                <small class="text-muted">Kosongkan Jika Tidak Ingin Mengubah Foto</small>
              </div>

              <div class="form-group">
                <label>Foto 3</label>
                <input type="file" name="foto3">
                <img src="${d.lapangan_foto3 ? `../gambar/lapangan/${escapeHtml(d.lapangan_foto3)}` : '../gambar/sistem/produk.png'}" style="width: 120px;height: auto">
                <br/>
                <small class="text-muted">Kosongkan Jika Tidak Ingin Mengubah Foto</small>
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminLapanganHapusKonfir(id: number): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Hapus Lapangan
      <small>Data Lapangan</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Yakin Ingin Menghapus Lapangan ?</h3>
          </div>
          <div class="box-body">
            <p>Dengan menghapus, semua data lapangan akan terhapus.</p>
            <br/>
            <a href="lapangan.php" class="btn btn-danger btn-sm"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
            <a href="lapangan_hapus.php?id=${id}" class="btn btn-success btn-sm pull-right"><i class="fa fa-check"></i> &nbsp Hapus</a> 
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminKategori(categoriesWithCount: Array<any>): string {
  let no = 1;
  const rowsHtml = categoriesWithCount
    .map(
      (d) => `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        <span style="width: 32px; height: 32px; background: #eff6ff; color: #2563eb; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">
                          <i class="fa fa-tag"></i>
                        </span>
                        ${escapeHtml(d.kategori_nama)}
                      </td>
                      <td class="text-center" style="white-space: nowrap;">
                        <span class="label label-info" style="font-size: 12px; padding: 6px 12px;">
                          ${d.total} Lapangan Registered
                        </span>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="kategori_edit.php?id=${d.kategori_id}" title="Edit Kategori"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="kategori_hapus_konfir.php?id=${d.kategori_id}" title="Hapus Kategori"><i class="fa fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>`
    )
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Kategori Lapangan
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Kelola jenis dan cabang olahraga untuk pengelompokan arena lapangan.
        </p>
      </div>
      <div>
        <a href="kategori_tambah.php" class="btn btn-primary" style="font-weight: 700; border-radius: var(--admin-radius-md);">
          <i class="fa fa-plus-circle"></i> Tambah Kategori Baru
        </a>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-th-large" style="color: #2563eb;"></i> Daftar Kategori Olahraga</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="5%" class="text-center">NO</th>
                    <th>NAMA KATEGORI</th>
                    <th class="text-center">JUMLAH LAPANGAN</th>
                    <th class="text-center" style="white-space: nowrap;">OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  ${rowsHtml}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminKategoriTambah(): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      kategori
      <small>Tambah kategori Baru</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Tambah kategori Baru</h3>
            <a href="kategori.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="kategori_act.php" method="post">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama Kategori..">
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminKategoriEdit(kategori: any): string {
  const d = kategori;
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      kategori
      <small>Edit kategori</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Edit kategori</h3>
            <a href="kategori.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="kategori_update.php" method="post">
              <div class="form-group">
                <label>Nama</label>
                <input type="hidden" name="id" value="${d.kategori_id}">
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama Kategori.." value="${escapeHtml(d.kategori_nama)}">
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminKategoriHapusKonfir(id: number): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Hapus kategori
      <small>Data kategori</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Yakin Ingin Menghapus kategori ?</h3>
          </div>
          <div class="box-body">
            <p>Dengan menghapus, semua data lapangan yang menggunakan kategori ini akan dialihkan ke "Tidak Berkategori".</p>
            <br/>
            <a href="kategori.php" class="btn btn-danger btn-sm"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
            <a href="kategori_hapus.php?id=${id}" class="btn btn-success btn-sm pull-right"><i class="fa fa-check"></i> &nbsp Hapus</a> 
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminCustomer(customers: Array<any>): string {
  let no = 1;
  const rowsHtml = customers
    .map(
      (d) => `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                          <div style="width: 36px; height: 36px; background: var(--admin-primary-gradient); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                            ${escapeHtml(String(d.customer_nama).slice(0, 1).toUpperCase())}
                          </div>
                          <div>
                            <strong style="color: #0f172a; font-size: 14px;">${escapeHtml(d.customer_nama)}</strong>
                          </div>
                        </div>
                      </td>
                      <td style="font-weight: 600; color: #2563eb; white-space: nowrap;">${escapeHtml(d.customer_email)}</td>
                      <td style="font-weight: 600; white-space: nowrap;"><i class="fa fa-phone" style="color: #64748b;"></i> ${escapeHtml(d.customer_hp)}</td>
                      <td style="color: #475569; font-size: 13px;">${escapeHtml(d.customer_alamat)}</td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="customer_edit.php?id=${d.customer_id}" title="Edit Customer"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="customer_hapus_konfir.php?id=${d.customer_id}" title="Hapus Customer"><i class="fa fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>`
    )
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Data Customer / Pelanggan
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Daftar seluruh akun pelanggan terdaftar di sistem SportKuy.
        </p>
      </div>
      <div>
        <a href="customer_tambah.php" class="btn btn-primary" style="font-weight: 700; border-radius: var(--admin-radius-md);">
          <i class="fa fa-user-plus"></i> Tambah Customer Baru
        </a>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-users" style="color: #2563eb;"></i> Direktori Pelanggan</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">NO</th>
                    <th>NAMA PELANGGAN</th>
                    <th>EMAIL</th>
                    <th>NO. HANDPHONE</th>
                    <th>ALAMAT</th>
                    <th class="text-center" style="white-space: nowrap;">OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  ${rowsHtml}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminCustomerTambah(): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      customer
      <small>Tambah customer Baru</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Tambah customer Baru</h3>
            <a href="customer.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="customer_act.php" method="post">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama customer..">
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" required="required" placeholder="Masukkan email customer..">
              </div>

              <div class="form-group">
                <label>HP</label>
                <input type="number" class="form-control" name="hp" required="required" placeholder="Masukkan no.hp customer..">
              </div>

              <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" name="alamat" required="required" placeholder="Masukkan alamat customer..">
              </div>

               <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required="required" placeholder="Masukkan password customer..">
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminCustomerEdit(customer: any): string {
  const d = customer;
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      customer
      <small>Edit customer</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Edit customer</h3>
            <a href="customer.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="customer_update.php" method="post">
              <div class="form-group">
                <label>Nama</label>
                <input type="hidden" name="id" value="${d.customer_id}">
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama customer.." value="${escapeHtml(d.customer_nama)}">
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" required="required" placeholder="Masukkan email customer.." value="${escapeHtml(d.customer_email)}">
              </div>

              <div class="form-group">
                <label>HP</label>
                <input type="number" class="form-control" name="hp" required="required" placeholder="Masukkan no.hp customer.." value="${escapeHtml(d.customer_hp)}">
              </div>

              <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" name="alamat" required="required" placeholder="Masukkan alamat customer.." value="${escapeHtml(d.customer_alamat)}">
              </div>

              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Masukkan password customer..">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti password</small>
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminCustomerHapusKonfir(id: number): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Hapus customer
      <small>Data customer</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Yakin Ingin Menghapus customer ?</h3>
          </div>
          <div class="box-body">
            <p>Dengan menghapus, semua data transaksi customer juga akan ikut terhapus.</p>
            <br/>
            <a href="customer.php" class="btn btn-danger btn-sm"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
            <a href="customer_hapus.php?id=${id}" class="btn btn-success btn-sm pull-right"><i class="fa fa-check"></i> &nbsp Hapus</a> 
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminList(admins: Array<any>): string {
  let no = 1;
  const rowsHtml = admins
    .map(
      (d) => `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        ${escapeHtml(d.admin_nama)}
                      </td>
                      <td style="font-weight: 700; color: #2563eb; white-space: nowrap;">@${escapeHtml(d.admin_username)}</td>
                      <td class="text-center">
                        <img src="${d.admin_foto ? `../gambar/user/${escapeHtml(d.admin_foto)}` : '../gambar/sistem/user.png'}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #2563eb;">
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="admin_edit.php?id=${d.admin_id}" title="Edit Admin"><i class="fa fa-cog"></i> Edit</a>
                          ${
                            d.admin_id !== 1
                              ? `<a class="btn btn-danger btn-sm" href="admin_hapus.php?id=${d.admin_id}" title="Hapus Admin" onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')"><i class="fa fa-trash"></i></a>`
                              : ''
                          }
                        </div>
                      </td>
                    </tr>`
    )
    .join('\n');

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Kelola Data Admin
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Daftar pengguna dengan hak akses administrator sistem SportKuy.
        </p>
      </div>
      <div>
        <a href="admin_tambah.php" class="btn btn-primary" style="font-weight: 700; border-radius: var(--admin-radius-md);">
          <i class="fa fa-user-plus"></i> Tambah Admin Baru
        </a>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user-secret" style="color: #2563eb;"></i> Administrator System</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="table-datatable">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">NO</th>
                    <th>NAMA ADMIN</th>
                    <th>USERNAME</th>
                    <th class="text-center">FOTO PROFIL</th>
                    <th class="text-center" style="white-space: nowrap;">OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  ${rowsHtml}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminTambah(): string {
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Admin
      <small>Tambah Admin Baru</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Tambah Admin</h3>
            <a href="admin.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp Kembali</a> 
          </div>
          <div class="box-body">
            <form action="admin_act.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" required="required" placeholder="Masukkan Nama ..">
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" required="required" placeholder="Masukkan Username ..">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required="required" min="5" placeholder="Masukkan Password ..">
              </div>
              <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto">
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminEdit(admin: any): string {
  const d = admin;
  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Admin
      <small>Edit Admin</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-6 col-lg-offset-3">       
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Edit Admin</h3>
            <a href="admin.php" class="btn btn-info btn-sm pull-right"><i class="fa fa-reply"></i> &nbsp; Kembali</a> 
          </div>
          <div class="box-body">
            <form action="admin_update.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" value="${escapeHtml(d.admin_nama)}" required="required">
                <input type="hidden" class="form-control" name="id" value="${d.admin_id}" required="required">
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" value="${escapeHtml(d.admin_username)}" required="required">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" min="5" placeholder="Kosong Jika tidak ingin di ganti">
                <small class="text-muted">Kosongkan Jika tidak ingin di ganti</small>
              </div>
              <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto">
                <small class="text-muted">Kosong Jika tidak ingin di ganti</small>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-sm btn-primary" value="Simpan">
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminGantiPassword(alert?: string): string {
  const alertHtml =
    alert === 'sukses'
      ? `<div class='alert alert-success' style='border-radius: 12px; font-weight: 600;'><i class='fa fa-check-circle'></i> Password Anda telah berhasil diperbarui!</div>`
      : '';

  return `<div class="content-wrapper">
  <section class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
          Keamanan & Ganti Password
        </h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
          Perbarui kata sandi akun administrator untuk menjaga keamanan portal SportKuy.
        </p>
      </div>
    </div>
  </section>

  <section class="content" style="padding-top: 20px;">
    <div class="row">
      <section class="col-lg-6">
        ${alertHtml}
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-key" style="color: #2563eb;"></i> Form Ganti Password</h3>
          </div>
          <div class="box-body" style="padding: 24px;">
            <form action="gantipassword_act.php" method="post">
              <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 700; color: #0f172a;"><i class="fa fa-lock" style="color: #2563eb;"></i> Password Baru Administrator</label>
                <input type="password" class="form-control" placeholder="Masukkan password baru Anda..." name="password" required="required" minlength="5" style="border-radius: 10px; padding: 12px;">
                <small style="color: #64748b; margin-top: 6px; display: block;">Gunakan minimal 5 karakter dengan kombinasi huruf dan angka.</small>
              </div>
              <div class="form-group" style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700; border-radius: 10px;">
                  <i class="fa fa-save"></i> SIMPAN PASSWORD BARU
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <section class="col-lg-6">
        <div class="box box-info" style="background: #f8fafc; border: 1px solid #e2e8f0;">
          <div class="box-header">
            <h3 class="box-title" style="font-size: 16px;"><i class="fa fa-shield" style="color: #2563eb;"></i> Tips Keamanan Akun</h3>
          </div>
          <div class="box-body" style="color: #475569; font-size: 14px; line-height: 1.7;">
            <ul style="padding-left: 20px; margin: 0;">
              <li style="margin-bottom: 8px;">Jangan berikan kredensial password Anda kepada siapa pun.</li>
              <li style="margin-bottom: 8px;">Gunakan kata sandi unik yang tidak digunakan di situs web lain.</li>
              <li style="margin-bottom: 8px;">Pastikan Anda melakukan <strong>Logout</strong> setelah selesai menggunakan portal admin.</li>
            </ul>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminLaporan(params: {
  tglDari?: string;
  tglSampai?: string;
  hasFilter: boolean;
  stats?: any;
  invoices?: Array<any>;
}): string {
  const { tglDari, tglSampai, hasFilter, stats, invoices = [] } = params;

  let reportContentHtml = '';
  if (hasFilter && stats) {
    let no = 1;
    let grandTotal = 0;
    const rowsHtml = invoices
      .map((i) => {
        const st = Number(i.invoice_status);
        if (st === 3 || st === 4) {
          grandTotal += Number(i.invoice_total_bayar) || 0;
        }

        let stBadge = '';
        if (st === 0) stBadge = `<span class='label label-warning' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-clock-o'></i> Menunggu Bayar</span>`;
        else if (st === 1) stBadge = `<span class='label label-info' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-hourglass-half'></i> Konfirmasi</span>`;
        else if (st === 2) stBadge = `<span class='label label-danger' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-times'></i> Ditolak</span>`;
        else if (st === 3) stBadge = `<span class='label label-primary' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-check'></i> Dikonfirmasi</span>`;
        else if (st === 4) stBadge = `<span class='label label-success' style='border-radius: 9999px; padding: 5px 10px; font-weight: 700;'><i class='fa fa-star'></i> Selesai</span>`;

        return `<tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;">${no++}</td>
                      <td style="font-weight: 800; color: #2563eb; font-family: var(--admin-font-heading);">
                        #INV-${strPadLeft(i.invoice_id, 5, '0')}
                      </td>
                      <td style="font-weight: 600; font-size: 13px; color: #334155;">
                        <i class="fa fa-calendar" style="color: #94a3b8; font-size: 11px;"></i> ${formatDMY(i.invoice_tanggal)}
                      </td>
                      <td style="font-weight: 600; font-size: 13px; color: #0f172a;">
                        <i class="fa fa-clock-o" style="color: #2563eb; font-size: 11px;"></i> ${formatDMY(i.invoice_tgl_main)}
                        <div style="font-size: 11px; color: #64748b;">${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</div>
                      </td>
                      <td>
                        <div style="font-weight: 700; color: #0f172a;">${escapeHtml(i.customer_nama)}</div>
                        <div style="font-size: 11px; color: #64748b;"><i class="fa fa-phone"></i> ${escapeHtml(i.customer_hp)}</div>
                      </td>
                      <td>
                        <div style="font-weight: 700; color: #334155;">${escapeHtml(i.lapangan_nama || 'Lapangan SportKuy')}</div>
                      </td>
                      <td class="text-center">${stBadge}</td>
                      <td class="text-right" style="font-weight: 800; color: #0f172a; font-size: 14px;">
                        Rp ${numberFormat(i.invoice_total_bayar)}
                      </td>
                    </tr>`;
      })
      .join('\n');

    reportContentHtml = `<!-- EXECUTIVE SUMMARY STAT CARDS -->
        <div class="row" style="margin-bottom: 24px;">
          <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 8px 24px rgba(15,23,42,0.18); position: relative; overflow: hidden; border: 1px solid #334155;">
              <div style="position: absolute; right: -10px; bottom: -10px; font-size: 80px; color: rgba(255,255,255,0.04); pointer-events: none;">
                <i class="fa fa-money"></i>
              </div>
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #38bdf8; margin-bottom: 6px;">
                <i class="fa fa-check-circle" style="margin-right: 4px;"></i> Total Omset Terkonfirmasi
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #ffffff; line-height: 1.2;">
                Rp ${numberFormat(stats.sum_revenue)}
              </div>
              <div style="font-size: 12px; color: #94a3b8; margin-top: 8px;">
                Dari ${numberFormat(stats.count_sukses)} transaksi disetujui / selesai
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
            <div style="background: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 4px 20px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 6px;">
                <i class="fa fa-ticket" style="color: #2563eb; margin-right: 4px;"></i> Total Permintaan Reservasi
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #0f172a; line-height: 1.2;">
                ${numberFormat(stats.total_trx)} <span style="font-size: 15px; font-weight: 600; color: #64748b;">Transaksi</span>
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 8px; display: flex; gap: 12px;">
                <span><i class="fa fa-circle" style="color: #10b981; font-size: 10px;"></i> Sukses: <strong>${stats.count_sukses}</strong></span>
                <span><i class="fa fa-circle" style="color: #f59e0b; font-size: 10px;"></i> Pending: <strong>${stats.count_pending}</strong></span>
                <span><i class="fa fa-circle" style="color: #ef4444; font-size: 10px;"></i> Ditolak: <strong>${stats.count_ditolak}</strong></span>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-12" style="margin-bottom: 15px;">
            <div style="background: #ffffff; padding: 22px; border-radius: var(--admin-radius-lg); box-shadow: 0 4px 20px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #d97706; margin-bottom: 6px;">
                <i class="fa fa-clock-o" style="margin-right: 4px;"></i> Potensi Omset Pending
              </div>
              <div style="font-size: 28px; font-weight: 800; font-family: var(--admin-font-heading); color: #b45309; line-height: 1.2;">
                Rp ${numberFormat(stats.pending_revenue)}
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
                Periode: <strong>${formatDMY(tglDari, '-')}</strong> s/d <strong>${formatDMY(tglSampai, '-')}</strong>
              </div>
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
              <a href="laporan_pdf.php?tanggal_dari=${tglDari}&tanggal_sampai=${tglSampai}" target="_blank" class="btn btn-danger btn-sm" style="font-weight: 700; border-radius: 8px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(225,29,72,0.2);">
                <i class="fa fa-file-pdf-o"></i> Ekspor PDF Statement
              </a>
              <a href="laporan_print.php?tanggal_dari=${tglDari}&tanggal_sampai=${tglSampai}" target="_blank" class="btn btn-default btn-sm" style="font-weight: 700; border-radius: 8px; padding: 8px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px; background: #ffffff;">
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
                  ${rowsHtml}
                </tbody>
                <tfoot>
                  <tr style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                    <td colspan="7" class="text-right" style="font-weight: 800; font-size: 14px; letter-spacing: 0.05em; padding: 14px;">
                      TOTAL REKAPITULASI OMSET TERKONFIRMASI:
                    </td>
                    <td class="text-right" style="font-weight: 800; font-size: 16px; color: #38bdf8; font-family: var(--admin-font-heading); padding: 14px;">
                      Rp ${numberFormat(grandTotal)}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>`;
  }

  return `<div class="content-wrapper">
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
        <div class="box box-info" style="border-radius: var(--admin-radius-lg); overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
          <div class="box-header" style="background: #ffffff; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 class="box-title" style="font-size: 16px; font-weight: 800; color: #0f172a; font-family: var(--admin-font-heading); margin: 0;">
              <i class="fa fa-filter" style="color: #2563eb; margin-right: 6px;"></i> Filter Periode Penjualan
            </h3>
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
              <button type="button" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 700; color: #475569;" onclick="setPeriodToday()">
                <i class="fa fa-calendar-o"></i> Hari Ini
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
                    <input autocomplete="off" type="text" id="tanggal_dari" value="${tglDari || ''}" name="tanggal_dari" class="form-control datepicker2" placeholder="YYYY-MM-DD" required="required" style="height: 44px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 600; box-shadow: none;">
                  </div>
                </div>

                <div class="col-md-4 col-sm-6">
                  <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #0f172a; font-size: 13px; margin-bottom: 6px; display: block;">
                      <i class="fa fa-calendar-check-o" style="color: #2563eb; margin-right: 4px;"></i> Sampai Tanggal
                    </label>
                    <input autocomplete="off" type="text" id="tanggal_sampai" value="${tglSampai || ''}" name="tanggal_sampai" class="form-control datepicker2" placeholder="YYYY-MM-DD" required="required" style="height: 44px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 600; box-shadow: none;">
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

        ${reportContentHtml}
      </div>
    </div>
  </section>
</div>

<script>
function setPeriodToday() {
  const now = new Date();
  const y = now.getFullYear();
  const m = String(now.getMonth() + 1).padStart(2, '0');
  const d = String(now.getDate()).padStart(2, '0');
  const today = y + '-' + m + '-' + d;
  document.getElementById('tanggal_dari').value = today;
  document.getElementById('tanggal_sampai').value = today;
  document.getElementById('filterForm').submit();
}
</script>`;
}

export function renderLaporanPrintPage(params: {
  tglDari: string;
  tglSampai: string;
  stats: any;
  invoices: Array<any>;
  isPdfStatement?: boolean;
}): string {
  const { tglDari, tglSampai, stats, invoices, isPdfStatement } = params;
  let no = 1;
  let grandTotal = 0;

  const rowsHtml = invoices
    .map((i) => {
      const st = Number(i.invoice_status);
      if (st === 3 || st === 4) {
        grandTotal += Number(i.invoice_total_bayar) || 0;
      }
      let badgeHtml = '';
      if (st === 0) badgeHtml = `<span class='badge badge-warning'>Menunggu Bayar</span>`;
      else if (st === 1) badgeHtml = `<span class='badge badge-info'>Konfirmasi</span>`;
      else if (st === 2) badgeHtml = `<span class='badge badge-danger'>Ditolak</span>`;
      else if (st === 3) badgeHtml = `<span class='badge badge-success'>Dikonfirmasi</span>`;
      else if (st === 4) badgeHtml = `<span class='badge badge-success'>Selesai</span>`;

      return `<tr>
						<td style="text-align: center; font-weight: 700; color: #64748b;">${no++}</td>
						<td style="font-weight: 800; color: #2563eb;">#INV-${strPadLeft(i.invoice_id, 5, '0')}</td>
						<td style="font-weight: 600; color: #334155;">${formatDMY(i.invoice_tanggal)}</td>
						<td style="font-weight: 600; color: #0f172a;">
							${formatDMY(i.invoice_tgl_main)}
							<div style="font-size: 11px; color: #64748b;">${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</div>
						</td>
						<td>
							<div style="font-weight: 700; color: #0f172a;">${escapeHtml(i.customer_nama)}</div>
							<div style="font-size: 11px; color: #64748b;">${escapeHtml(i.customer_hp)}</div>
						</td>
						<td>
							<div style="font-weight: 600; color: #334155;">${escapeHtml(i.lapangan_nama || 'Lapangan SportKuy')}</div>
						</td>
						<td style="text-align: center;">${badgeHtml}</td>
						<td style="text-align: right; font-weight: 800; color: #0f172a;">
							Rp ${numberFormat(i.invoice_total_bayar)}
						</td>
					</tr>`;
    })
    .join('\n');

  return `<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>${isPdfStatement ? 'Ekspor PDF Laporan Penjualan' : 'Laporan Penjualan & Reservasi Arena'} - SportKuy</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<link rel="stylesheet" href="../frontend/css/font-awesome.min.css">
	<style>
		* { box-sizing: border-box; }
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
			color: #0f172a;
			background: #f8fafc;
			margin: 0;
			padding: 40px 20px;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}
		.report-card {
			max-width: 960px;
			margin: 0 auto;
			background: #ffffff;
			border-radius: 16px;
			padding: 40px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
			border: 1px solid #e2e8f0;
		}
		.report-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			border-bottom: 2px dashed #cbd5e1;
			padding-bottom: 24px;
			margin-bottom: 24px;
		}
		.logo-title {
			font-family: 'Outfit', sans-serif;
			font-size: 26px;
			font-weight: 800;
			color: #0f172a;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.logo-icon {
			width: 40px;
			height: 40px;
			background: linear-gradient(135deg, #2563eb, #1d4ed8);
			color: #fff;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 20px;
		}
		.report-title-badge {
			font-family: 'Outfit', sans-serif;
			font-size: 22px;
			font-weight: 800;
			color: #2563eb;
			text-align: right;
		}
		.grid-3 {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 16px;
			margin-bottom: 24px;
		}
		.stat-box {
			background: #f8fafc;
			padding: 16px 20px;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.stat-box.highlight {
			background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
			color: #ffffff;
			border: none;
		}
		.stat-box h6 {
			margin: 0 0 6px 0;
			font-size: 11px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #64748b;
			font-weight: 700;
		}
		.stat-box.highlight h6 {
			color: #38bdf8;
		}
		.stat-box .val {
			font-size: 20px;
			font-weight: 800;
			font-family: 'Outfit', sans-serif;
			margin: 0;
		}
		.table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid #e2e8f0;
			margin-bottom: 24px;
		}
		.table th {
			background: #f1f5f9;
			color: #0f172a;
			font-weight: 700;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.03em;
			padding: 12px 14px;
			text-align: left;
			border-bottom: 2px solid #cbd5e1;
		}
		.table td {
			padding: 12px 14px;
			font-size: 13px;
			border-bottom: 1px solid #f1f5f9;
		}
		.table tr:last-child td {
			border-bottom: none;
		}
		.badge {
			padding: 4px 10px;
			border-radius: 9999px;
			font-weight: 700;
			font-size: 11px;
			display: inline-block;
		}
		.badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
		.badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
		.badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
		.badge-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
		
		.signature-section {
			display: flex;
			justify-content: space-between;
			align-items: flex-end;
			margin-top: 40px;
			padding-top: 20px;
		}
		.no-print-btn {
			background: #2563eb;
			color: #ffffff;
			border: none;
			padding: 12px 24px;
			border-radius: 10px;
			font-weight: 700;
			cursor: pointer;
			margin-bottom: 20px;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			box-shadow: 0 4px 12px rgba(37,99,235,0.3);
			text-decoration: none;
		}
		@media print {
			body { background: #ffffff; padding: 0; }
			.report-card { box-shadow: none; border: none; padding: 0; width: 100%; max-width: 100%; }
			.no-print { display: none !important; }
		}
	</style>
</head>
<body>

	<div style="max-width: 960px; margin: 0 auto 10px auto; text-align: right;" class="no-print">
		<button onclick="window.print()" class="no-print-btn"><i class="fa ${isPdfStatement ? 'fa-file-pdf-o' : 'fa-print'}"></i> ${isPdfStatement ? 'Simpan Sebagai PDF / Cetak' : 'Cetak Laporan Sekarang'}</button>
	</div>

	<div class="report-card">
		<!-- BRANDING HEADER -->
		<div class="report-header">
			<div>
				<div class="logo-title">
					<span class="logo-icon"><i class="fa fa-trophy"></i></span>
					SPORT<span style="color: #2563eb;">KUY</span> ${isPdfStatement ? 'FINANCIAL STATEMENT' : 'FINANCIAL'}
				</div>
				<p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">${isPdfStatement ? 'Dokumen Laporan Keuangan & Rekapitulasi Sewa Lapangan' : 'Laporan Resmi Transaksi & Pendapatan Sewa Arena'}</p>
			</div>

			<div class="report-title-badge">
				${isPdfStatement ? 'FINANCIAL STATEMENT' : 'LAPORAN PENJUALAN'}
				<div style="font-size: 13px; color: #64748b; font-weight: 600; margin-top: 4px;">
					Periode: ${dateToIndo(tglDari)} s/d ${dateToIndo(tglSampai)}
				</div>
				<div style="font-size: 11px; color: #94a3b8; font-weight: 500; margin-top: 2px;">
					Dicetak Pada: ${dateToIndo(new Date().toISOString().slice(0, 10))}
				</div>
			</div>
		</div>

		<!-- EXECUTIVE SUMMARY CARDS -->
		<div class="grid-3">
			<div class="stat-box">
				<h6>Periode Filter</h6>
				<div class="val" style="font-size: 15px; color: #0f172a;">
					${formatDMY(tglDari)} - ${formatDMY(tglSampai)}
				</div>
			</div>

			<div class="stat-box">
				<h6>Volume Reservasi</h6>
				<div class="val" style="color: #2563eb;">
					${numberFormat(stats?.total_trx || 0)} <span style="font-size: 13px; font-weight: 600; color: #64748b;">Order</span>
				</div>
			</div>

			<div class="stat-box highlight">
				<h6>Total Omset Terkonfirmasi</h6>
				<div class="val" style="color: #38bdf8;">
					Rp ${numberFormat(stats?.sum_revenue || 0)}
				</div>
			</div>
		</div>

		<!-- RECAP TABLE -->
		<table class="table">
			<thead>
				<tr>
					<th width="4%" style="text-align: center;">NO</th>
					<th width="14%">INVOICE</th>
					<th width="12%">TGL ORDER</th>
					<th width="14%">JADWAL MAIN</th>
					<th width="20%">NAMA CUSTOMER</th>
					<th width="20%">ARENA / LAPANGAN</th>
					<th width="16%" style="text-align: center;">STATUS</th>
					<th width="15%" style="text-align: right;">TOTAL</th>
				</tr>
			</thead>
			<tbody>
				${rowsHtml || "<tr><td colspan='8' style='text-align:center; padding: 20px; color: #94a3b8;'>Tidak ada data transaksi pada periode ini.</td></tr>"}
			</tbody>
			<tfoot>
				<tr style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
					<td colspan="7" style="text-align: right; font-weight: 800; font-size: 13px; letter-spacing: 0.05em; padding: 14px;">
						TOTAL PENDAPATAN TERKONFIRMASI:
					</td>
					<td style="text-align: right; font-weight: 800; font-size: 16px; color: #38bdf8; font-family: 'Outfit', sans-serif; padding: 14px;">
						Rp ${numberFormat(grandTotal)}
					</td>
				</tr>
			</tfoot>
		</table>

		<!-- SIGNATURE & STAMP FOOTER -->
		<div class="signature-section">
			<div style="font-size: 12px; color: #64748b; max-width: 400px;">
				<p style="margin: 0 0 4px 0;"><strong>Catatan Administrator:</strong></p>
				<p style="margin: 0;">Dokumen ini merupakan laporan rekapitulasi penjualan resmi yang diterbitkan secara otomatis oleh sistem manajemen arena <strong>SportKuy</strong>.</p>
			</div>

			<div style="text-align: center; width: 220px;">
				<div style="font-size: 13px; color: #334155; margin-bottom: 60px;">
					Jakarta, ${dateToIndo(new Date().toISOString().slice(0, 10))}<br/>
					<strong>Manager Operasional / Admin</strong>
				</div>
				<div style="border-bottom: 2px dashed #0f172a; width: 100%; margin: 0 auto 4px auto;"></div>
				<div style="font-weight: 800; font-size: 14px; color: #0f172a; text-transform: uppercase;">
					SportKuy Management
				</div>
			</div>
		</div>
	</div>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>
</html>`;
}

export function renderAdminTransaksiInvoice(params: {
  invoice: any;
  invoiceDetail: any;
}): string {
  const { invoice: i, invoiceDetail: d } = params;
  if (!i) {
    return `<div class="content-wrapper"><section class="content"><div class="alert alert-danger">Invoice tidak ditemukan.</div></section></div>`;
  }

  const startH = parseInt(String(i.invoice_jam_mulai || '0').slice(0, 2), 10) || 0;
  const endH = parseInt(String(i.invoice_jam_selesai || '0').slice(0, 2), 10) || 0;
  const durasi = endH > startH ? endH - startH : 1;

  let stBadge = '';
  const st = Number(i.invoice_status);
  if (st === 0) stBadge = `<span class='label label-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>`;
  else if (st === 1) stBadge = `<span class='label label-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
  else if (st === 2) stBadge = `<span class='label label-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
  else if (st === 3) stBadge = `<span class='label label-primary'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
  else if (st === 4) stBadge = `<span class='label label-success'><i class='fa fa-star'></i> Selesai</span>`;

  return `<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Invoice Transaksi
      <small>Rincian Booking Pelanggan</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="transaksi.php">Transaksi</a></li>
      <li class="active">Invoice</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
          <a href="transaksi.php" class="btn btn-default" style="border-radius: var(--admin-radius-sm); font-weight: 600;">
            <i class="fa fa-arrow-left"></i> Kembali ke Daftar Transaksi
          </a>
          <a href="transaksi_invoice_cetak.php?id=${i.invoice_id}" target="_blank" class="btn btn-primary" style="border-radius: var(--admin-radius-sm); font-weight: 700; background: #2563eb; border: none; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
            <i class="fa fa-print"></i> Cetak Invoice
          </a>
        </div>

        <div class="admin-card" style="background: #ffffff; border-radius: var(--admin-radius-lg); box-shadow: var(--admin-shadow-sm); border: 1px solid #e2e8f0; padding: 32px; margin-bottom: 30px;">
          <div class="row" style="border-bottom: 2px dashed #e2e8f0; padding-bottom: 24px; margin-bottom: 24px;">
            <div class="col-sm-6">
              <div style="font-family: var(--admin-font-heading); font-size: 24px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span style="background: #2563eb; color: #ffffff; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 16px;">
                  <i class="fa fa-trophy"></i>
                </span>
                SPORT<span style="color: #2563eb;">KUY</span>
              </div>
              <p style="color: #64748b; font-size: 13px; margin: 6px 0 0 0;">Platform Reservasi Arena & Lapangan Olahraga Modern</p>
            </div>
            <div class="col-sm-6 text-right">
              <div style="font-size: 20px; font-weight: 800; color: #2563eb; font-family: var(--admin-font-heading);">
                #INV-${strPadLeft(i.invoice_id, 5, '0')}
              </div>
              <p style="color: #64748b; font-size: 13px; margin: 4px 0 10px 0;">
                Tgl Order: ${dateToIndo(i.invoice_tanggal)}
              </p>
              <div>
                ${stBadge}
              </div>
            </div>
          </div>

          <div class="row" style="margin-bottom: 24px;">
            <div class="col-sm-6" style="margin-bottom: 15px;">
              <div style="background: #f8fafc; padding: 18px; border-radius: var(--admin-radius-md); border: 1px solid #e2e8f0; height: 100%;">
                <h5 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 10px; font-weight: 700;">
                  <i class="fa fa-user" style="color: #2563eb;"></i> Data Pemesan
                </h5>
                <h4 style="font-size: 16px; font-weight: 800; margin: 0 0 6px 0; color: #0f172a;">
                  ${escapeHtml(i.invoice_nama)}
                </h4>
                <p style="margin: 0 0 4px 0; color: #334155; font-size: 14px;">
                  <i class="fa fa-phone" style="width: 18px; color: #2563eb;"></i> ${escapeHtml(i.invoice_hp)}
                </p>
                <p style="margin: 0; color: #64748b; font-size: 13px;">
                  <i class="fa fa-map-marker" style="width: 18px; color: #2563eb;"></i> ${escapeHtml(i.invoice_alamat || 'Pelanggan Terdaftar')}
                </p>
              </div>
            </div>

            <div class="col-sm-6" style="margin-bottom: 15px;">
              <div style="background: #f8fafc; padding: 18px; border-radius: var(--admin-radius-md); border: 1px solid #e2e8f0; height: 100%;">
                <h5 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 10px; font-weight: 700;">
                  <i class="fa fa-calendar" style="color: #2563eb;"></i> Detail Pertandingan
                </h5>
                <p style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #0f172a;">
                  <i class="fa fa-calendar-check-o" style="width: 18px; color: #2563eb;"></i> ${dateToIndo(i.invoice_tgl_main)}
                </p>
                <p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: #2563eb;">
                  <i class="fa fa-clock-o" style="width: 18px;"></i> Sesi Jam: ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)} (${durasi} Jam)
                </p>
                <p style="margin: 0; color: #64748b; font-size: 13px;">
                  <i class="fa fa-tags" style="width: 18px; color: #2563eb;"></i> Kategori: ${escapeHtml(d?.kategori_nama || 'Olahraga')}
                </p>
              </div>
            </div>
          </div>

          <div class="table-responsive" style="margin-bottom: 24px;">
            <table class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th width="5%" class="text-center">#</th>
                  <th width="55%" colspan="2">Arena / Lapangan</th>
                  <th width="15%" class="text-center">Harga / Jam</th>
                  <th width="10%" class="text-center">Durasi</th>
                  <th width="15%" class="text-right">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center" style="font-weight: 700;">1</td>
                  <td width="70" style="padding: 10px;">
                    ${
                      d?.lapangan_foto1
                        ? `<img src="../gambar/lapangan/${escapeHtml(d.lapangan_foto1)}" style="width: 60px; height: 45px; object-fit: cover; border-radius: 8px;">`
                        : `<img src="../gambar/sistem/lapangan.png" style="width: 60px; height: 45px; object-fit: cover; border-radius: 8px;">`
                    }
                  </td>
                  <td>
                    <div style="font-weight: 800; font-size: 15px; color: #0f172a;">${escapeHtml(d?.lapangan_nama || 'Lapangan SportKuy')}</div>
                    <small style="color: #64748b;">${dateToIndo(i.invoice_tgl_main)} | Jam ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</small>
                  </td>
                  <td class="text-center" style="font-weight: 600;">Rp ${numberFormat(i.invoice_harga)}</td>
                  <td class="text-center" style="font-weight: 700;">${durasi} Jam</td>
                  <td class="text-right" style="font-weight: 800; color: #0f172a;">Rp ${numberFormat(i.invoice_total_bayar)}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row" style="align-items: center;">
            <div class="col-sm-6" style="margin-bottom: 15px;">
              <div style="background: #f1f5f9; padding: 14px 18px; border-radius: var(--admin-radius-md); font-size: 13px; color: #64748b;">
                <i class="fa fa-shield" style="color: #2563eb;"></i> Invoice resmi terverifikasi oleh Admin SportKuy.
              </div>
            </div>
            <div class="col-sm-6 text-right">
              <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 18px 24px; border-radius: var(--admin-radius-md); display: inline-block; min-width: 260px; text-align: right; box-shadow: 0 4px 14px rgba(15,23,42,0.15);">
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 4px;">Total Tagihan</div>
                <div style="font-size: 26px; font-weight: 800; color: #38bdf8; font-family: var(--admin-font-heading);">
                  Rp ${numberFormat(i.invoice_total_bayar)}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </section>
</div>`;
}

export function renderAdminTransaksiInvoiceCetak(params: {
  invoice: any;
  invoiceDetail: any;
}): string {
  const { invoice: i, invoiceDetail: d } = params;
  if (!i) {
    return `<div style="text-align: center; padding: 40px; color: #ef4444; font-weight: 700;">Data invoice tidak ditemukan!</div>`;
  }

  const startH = parseInt(String(i.invoice_jam_mulai || '0').slice(0, 2), 10) || 0;
  const endH = parseInt(String(i.invoice_jam_selesai || '0').slice(0, 2), 10) || 0;
  const durasi = endH > startH ? endH - startH : 1;

  let badgeHtml = '';
  const st = Number(i.invoice_status);
  if (st === 0) badgeHtml = `<span class='badge badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>`;
  else if (st === 1) badgeHtml = `<span class='badge badge-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
  else if (st === 2) badgeHtml = `<span class='badge badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
  else if (st === 3) badgeHtml = `<span class='badge badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
  else if (st === 4) badgeHtml = `<span class='badge badge-success'><i class='fa fa-star'></i> Selesai</span>`;

  return `<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Cetak Invoice Admin #INV-${strPadLeft(i.invoice_id, 5, '0')} - SportKuy</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<link rel="stylesheet" href="../frontend/css/font-awesome.min.css">
	<style>
		* { box-sizing: border-box; }
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
			color: #1e293b;
			background: #f8fafc;
			margin: 0;
			padding: 40px 20px;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}
		.invoice-card {
			max-width: 800px;
			margin: 0 auto;
			background: #ffffff;
			border-radius: 16px;
			padding: 40px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
			border: 1px solid #e2e8f0;
		}
		.invoice-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			border-bottom: 2px dashed #cbd5e1;
			padding-bottom: 24px;
			margin-bottom: 28px;
		}
		.logo-title {
			font-family: 'Outfit', sans-serif;
			font-size: 26px;
			font-weight: 800;
			color: #0f172a;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.logo-icon {
			background: #2563eb;
			color: #ffffff;
			width: 38px;
			height: 38px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 10px;
			font-size: 18px;
		}
		.invoice-num {
			text-align: right;
			font-family: 'Outfit', sans-serif;
			font-size: 22px;
			font-weight: 800;
			color: #2563eb;
		}
		.badge {
			display: inline-block;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}
		.badge-warning { background: #fef3c7; color: #d97706; }
		.badge-info { background: #e0f2fe; color: #0284c7; }
		.badge-danger { background: #fee2e2; color: #dc2626; }
		.badge-success { background: #dcfce7; color: #16a34a; }
		.grid-2 {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 20px;
			margin-bottom: 28px;
		}
		.info-box {
			background: #f8fafc;
			padding: 20px;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.info-box h5 {
			margin: 0 0 10px 0;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #64748b;
			font-weight: 700;
		}
		.info-box h4 {
			margin: 0 0 6px 0;
			font-size: 16px;
			font-weight: 800;
			color: #0f172a;
		}
		.info-box p {
			margin: 0 0 4px 0;
			font-size: 13px;
			color: #334155;
		}
		.table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 28px;
		}
		.table th {
			background: #f1f5f9;
			color: #475569;
			font-weight: 700;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			padding: 12px 14px;
			border-bottom: 1px solid #cbd5e1;
		}
		.table td {
			padding: 14px;
			border-bottom: 1px solid #e2e8f0;
			font-size: 14px;
		}
		.total-box {
			background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
			color: #ffffff;
			padding: 16px 24px;
			border-radius: 12px;
			display: inline-block;
			text-align: right;
		}
		@media print {
			body { background: #ffffff; padding: 0; }
			.invoice-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
		}
	</style>
</head>
<body>
	<div class="invoice-card">
		<div class="invoice-header">
			<div>
				<div class="logo-title">
					<span class="logo-icon"><i class="fa fa-trophy"></i></span>
					SPORT<span style="color: #2563eb;">KUY</span> ADMIN
				</div>
				<p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">Bukti Reservasi & Transaksi Resmi System</p>
			</div>

			<div class="invoice-num">
				INVOICE #INV-${strPadLeft(i.invoice_id, 5, '0')}
				<div style="font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px;">
					Tanggal Order: ${dateToIndo(i.invoice_tanggal)}
				</div>
				<div style="margin-top: 8px;">
					${badgeHtml}
				</div>
			</div>
		</div>

		<div class="grid-2">
			<div class="info-box">
				<h5><i class="fa fa-user" style="color: #2563eb;"></i> Data Pemesan</h5>
				<h4>${escapeHtml(i.invoice_nama)}</h4>
				<p><i class="fa fa-phone" style="color: #2563eb; width: 16px;"></i> ${escapeHtml(i.invoice_hp)}</p>
				<p><i class="fa fa-map-marker" style="color: #2563eb; width: 16px;"></i> ${escapeHtml(i.invoice_alamat || 'Pelanggan Terdaftar')}</p>
			</div>

			<div class="info-box">
				<h5><i class="fa fa-calendar" style="color: #2563eb;"></i> Detail Jadwal Pertandingan</h5>
				<p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
					<i class="fa fa-calendar-check-o" style="color: #2563eb; width: 16px;"></i> ${dateToIndo(i.invoice_tgl_main)}
				</p>
				<p style="font-size: 14px; font-weight: 600; color: #2563eb; margin-bottom: 6px;">
					<i class="fa fa-clock-o" style="width: 16px;"></i> Jam Sesi: ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)} (${durasi} Jam)
				</p>
				<p style="color: #64748b; font-size: 13px;">
					<i class="fa fa-tags" style="color: #2563eb; width: 16px;"></i> Kategori: ${escapeHtml(d?.kategori_nama || 'Olahraga')}
				</p>
			</div>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th width="5%" style="text-align: center;">#</th>
					<th width="55%">Arena / Lapangan Olahraga</th>
					<th width="15%" style="text-align: center;">Harga / Jam</th>
					<th width="10%" style="text-align: center;">Durasi</th>
					<th width="15%" style="text-align: right;">Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center; font-weight: 700;">1</td>
					<td>
						<div style="font-weight: 800; font-size: 15px; color: #0f172a;">${escapeHtml(d?.lapangan_nama || 'Lapangan SportKuy')}</div>
						<div style="font-size: 12px; color: #64748b;">${dateToIndo(i.invoice_tgl_main)} | Jam ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</div>
					</td>
					<td style="text-align: center; font-weight: 600;">Rp ${numberFormat(i.invoice_harga)}</td>
					<td style="text-align: center; font-weight: 700;">${durasi} Jam</td>
					<td style="text-align: right; font-weight: 800; color: #0f172a;">Rp ${numberFormat(i.invoice_total_bayar)}</td>
				</tr>
			</tbody>
		</table>

		<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
			<div style="font-size: 12px; color: #64748b; max-width: 400px;">
				Catatan: Dokumen ini dicetak dari Admin Portal <strong>SportKuy</strong> sebagai arsip transaksi resmi.
			</div>
			<div class="total-box">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 2px;">Total Tagihan</div>
				<div style="font-size: 24px; font-weight: 800; color: #38bdf8; font-family: 'Outfit', sans-serif;">
					Rp ${numberFormat(i.invoice_total_bayar)}
				</div>
			</div>
		</div>
	</div>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>
</html>`;
}

