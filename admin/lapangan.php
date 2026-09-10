<?php include 'header.php'; ?>

<div class="content-wrapper">

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
                  <?php 
                  $no = 1;
                  $data = mysqli_query($koneksi,"SELECT * FROM lapangan, kategori WHERE kategori_id=lapangan_kategori ORDER BY lapangan_id DESC");
                  while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        <?php echo $d['lapangan_nama']; ?>
                      </td>
                      <td style="white-space: nowrap;">
                        <span class="label label-info" style="font-size: 12px; padding: 6px 12px;">
                          <i class="fa fa-tag"></i> <?php echo $d['kategori_nama']; ?>
                        </span>
                      </td>
                      <td style="font-weight: 800; color: #2563eb; font-size: 15px; font-family: var(--admin-font-heading); white-space: nowrap;">
                        Rp <?php echo number_format($d['lapangan_harga']); ?> <small style="color: #64748b; font-weight: 500;">/ jam</small>
                      </td>
                      <td class="text-center">
                        <div class="venue-thumb-group">
                          <?php if(!empty($d['lapangan_foto1'])){ ?>
                            <img src="../gambar/lapangan/<?php echo $d['lapangan_foto1'] ?>" title="Foto Utama">
                          <?php } else { ?>
                            <img src="../gambar/sistem/produk.png" title="Default">
                          <?php } ?>

                          <?php if(!empty($d['lapangan_foto2'])){ ?>
                            <img src="../gambar/lapangan/<?php echo $d['lapangan_foto2'] ?>" title="Foto 2">
                          <?php } ?>

                          <?php if(!empty($d['lapangan_foto3'])){ ?>
                            <img src="../gambar/lapangan/<?php echo $d['lapangan_foto3'] ?>" title="Foto 3">
                          <?php } ?>
                        </div>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="lapangan_edit.php?id=<?php echo $d['lapangan_id'] ?>" title="Edit Data"><i class="fa fa-cog"></i> Edit</a>
                          <a class="btn btn-danger btn-sm" href="lapangan_hapus_konfir.php?id=<?php echo $d['lapangan_id'] ?>" title="Hapus Data"><i class="fa fa-trash"></i></a>
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