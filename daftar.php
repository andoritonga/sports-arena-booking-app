<?php include 'header.php'; ?>

<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Daftar Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- section -->
<div class="section">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			
			<div class="col-md-6 col-md-offset-3">
				<div class="form-box">
					<div class="text-center" style="margin-bottom: 25px;">
						<div style="width: 50px; height: 50px; background: var(--color-emerald-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
							<i class="fa fa-user-plus"></i>
						</div>
						<h2 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Pendaftaran Akun Baru</h2>
						<p style="color: var(--color-text-muted);">Buat akun untuk memesan dan mengelola jadwal lapangan</p>
					</div>

					<?php 
					if(isset($_GET['alert'])){
						if($_GET['alert'] == "duplikat"){
							echo "<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Maaf, email ini sudah terdaftar. Silahkan gunakan email lain.</div>";
						}
					}
					?>

					<form action="daftar_act.php" method="post">
						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Nama Lengkap</label>
							<input type="text" class="form-control" required="required" name="nama" placeholder="Masukkan nama lengkap Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-envelope" style="color: var(--color-brand-primary);"></i> Alamat Email</label>
							<input type="email" class="form-control" required="required" name="email" placeholder="Masukkan alamat email Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-phone" style="color: var(--color-brand-primary);"></i> Nomor HP / WhatsApp</label>
							<input type="number" class="form-control" required="required" name="hp" placeholder="Contoh: 081234567890">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-map-marker" style="color: var(--color-brand-primary);"></i> Alamat Lengkap</label>
							<input type="text" class="form-control" required="required" name="alamat" placeholder="Masukkan alamat Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 24px;">
							<label for=""><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> Password</label>
							<input type="password" class="form-control" required="required" name="password" placeholder="Buat password akun Anda...">
							<small class="text-muted">Password digunakan untuk masuk ke portal pelanggan SportKuy.</small>
						</div>

						<div class="form-group" style="margin-bottom: 0;">
							<button type="submit" class="primary-btn btn-block" style="padding: 12px; font-size: 16px;"><i class="fa fa-check-circle"></i> Daftar Sekarang</button>
							<div class="text-center" style="margin-top: 18px; color: var(--color-text-muted);">
								Sudah memiliki akun? <a href="masuk.php" style="color: var(--color-brand-primary); font-weight: 700;">Login Disini</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /section -->



<?php include 'footer.php'; ?>