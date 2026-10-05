<?php include 'header.php'; ?>
<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Login</li>
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
						<div style="width: 50px; height: 50px; background: var(--color-brand-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
							<i class="fa fa-lock"></i>
						</div>
						<h2 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Login Akun</h2>
						<p style="color: var(--color-text-muted);">Masuk ke akun Anda untuk melakukan booking atau mengelola sistem</p>
					</div>
					
					<?php 
					if(isset($_GET['alert'])){
						if($_GET['alert'] == "terdaftar"){
							echo "<div class='alert alert-success text-center' style='border-radius: 12px; font-weight: 600;'>Selamat, akun Anda telah terdaftar. Silahkan login.</div>";
						}elseif($_GET['alert'] == "gagal"){
							echo "<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Email/Username atau password salah. Silahkan coba lagi.</div>";
						}elseif($_GET['alert'] == "login-dulu-pesan"){
							echo "<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login terlebih dahulu untuk melakukan booking.</div>";
						}elseif($_GET['alert'] == "login-dulu-komentar"){
							echo "<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login terlebih dahulu untuk memberikan komentar.</div>";
						}
					}
					?>					

					<form action="masuk_act.php" method="post">
						<div class="form-group" style="margin-bottom: 18px;">
							<label for=""><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Email / Username</label>
							<input type="text" class="form-control" required="required" name="email" placeholder="Masukkan email atau username Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 24px;">
							<label for=""><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> Password</label>
							<input type="password" class="form-control" required="required" name="password" placeholder="Masukkan password Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 0;">
							<button type="submit" class="primary-btn btn-block" style="padding: 12px; font-size: 16px;"><i class="fa fa-sign-in"></i> Masuk Sekarang</button>
							<div class="text-center" style="margin-top: 18px; color: var(--color-text-muted);">
								Belum punya akun? <a href="daftar.php" style="color: var(--color-brand-primary); font-weight: 700;">Daftar Akun Baru</a>
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