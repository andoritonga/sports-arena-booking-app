<?php include 'header.php'; ?>

<div class="content-wrapper">

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

        <?php 
        if(isset($_GET['alert'])){
          if($_GET['alert'] == "sukses"){
            echo "<div class='alert alert-success' style='border-radius: 12px; font-weight: 600;'><i class='fa fa-check-circle'></i> Password Anda telah berhasil diperbarui!</div>";
          }
        }
        ?>

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

</div>
<?php include 'footer.php'; ?>