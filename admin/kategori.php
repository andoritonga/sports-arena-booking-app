<?php include 'header.php'; ?>

<div class="content-wrapper">

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
                  <?php 
                  $no = 1;
                  $data = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY kategori_id DESC");
                  while($d = mysqli_fetch_array($data)){
                    $kat_id = $d['kategori_id'];
                    $count_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM lapangan WHERE lapangan_kategori='$kat_id'");
                    $count_d = mysqli_fetch_assoc($count_q);
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        <span style="width: 32px; height: 32px; background: #eff6ff; color: #2563eb; border-radius: 8px; inline-flex; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">
                          <i class="fa fa-tag"></i>
                        </span>
                        <?php echo $d['kategori_nama']; ?>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">
                        <span class="label label-info" style="font-size: 12px; padding: 6px 12px;">
                          <?php echo $count_d['total']; ?> Lapangan Registered
                        </span>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="kategori_edit.php?id=<?php echo $d['kategori_id'] ?>" title="Edit Kategori"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="kategori_hapus_konfir.php?id=<?php echo $d['kategori_id'] ?>" title="Hapus Kategori"><i class="fa fa-trash"></i></a>
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