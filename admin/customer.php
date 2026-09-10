<?php include 'header.php'; ?>

<div class="content-wrapper">

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
                  <?php 
                  $no = 1;
                  $data = mysqli_query($koneksi,"SELECT * FROM customer ORDER BY customer_id DESC");
                  while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                          <div style="width: 36px; height: 36px; background: var(--admin-primary-gradient); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                            <?php echo strtoupper(substr($d['customer_nama'], 0, 1)); ?>
                          </div>
                          <div>
                            <strong style="color: #0f172a; font-size: 14px;"><?php echo $d['customer_nama']; ?></strong>
                          </div>
                        </div>
                      </td>
                      <td style="font-weight: 600; color: #2563eb; white-space: nowrap;"><?php echo $d['customer_email']; ?></td>
                      <td style="font-weight: 600; white-space: nowrap;"><i class="fa fa-phone" style="color: #64748b;"></i> <?php echo $d['customer_hp']; ?></td>
                      <td style="color: #475569; font-size: 13px;"><?php echo $d['customer_alamat']; ?></td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="customer_edit.php?id=<?php echo $d['customer_id'] ?>" title="Edit Customer"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="customer_hapus_konfir.php?id=<?php echo $d['customer_id'] ?>" title="Hapus Customer"><i class="fa fa-trash"></i></a>
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