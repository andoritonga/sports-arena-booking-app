<?php include 'header.php'; ?>

<div class="content-wrapper">

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
                  <?php 
                  $no = 1;
                  $data = mysqli_query($koneksi,"SELECT * FROM admin ORDER BY admin_id ASC");
                  while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td class="text-center" style="font-weight: 700; color: #64748b;"><?php echo $no++; ?></td>
                      <td style="font-weight: 800; color: #0f172a; font-size: 15px;">
                        <?php echo $d['admin_nama']; ?>
                      </td>
                      <td style="font-weight: 700; color: #2563eb; white-space: nowrap;">@<?php echo $d['admin_username']; ?></td>
                      <td class="text-center">
                        <?php if(!empty($d['admin_foto'])){ ?>
                          <img src="../gambar/user/<?php echo $d['admin_foto'] ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #2563eb;">
                        <?php }else{ ?>
                          <img src="../gambar/sistem/user.png" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <?php } ?>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">                        
                        <div style="display: inline-flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                          <a class="btn btn-warning btn-sm" href="admin_edit.php?id=<?php echo $d['admin_id'] ?>" title="Edit Admin"><i class="fa fa-cog"></i> Edit</a>
                          <?php if($d['admin_id'] != 1){ ?>
                            <a class="btn btn-danger btn-sm" href="admin_hapus.php?id=<?php echo $d['admin_id'] ?>" title="Hapus Admin" onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')"><i class="fa fa-trash"></i></a>
                          <?php } ?>
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