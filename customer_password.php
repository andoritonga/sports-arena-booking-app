<?php include 'header.php'; ?>


<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Ganti Password Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			
			<?php 
			include 'customer_sidebar.php'; 
			?>

			<div id="main" class="col-md-9">
				
				<h4>GANTI PASSWORD</h4>

				<div id="store">
					<div class="row">

						<div class="col-lg-12">
							<?php 
							if(isset($_GET['alert'])){
								if($_GET['alert'] == "sukses"){
									echo "<div class='alert alert-success' style='border-radius: var(--radius-md); font-weight: 600;'><i class='fa fa-check-circle'></i> Password Anda berhasil diganti!</div>";
								}elseif($_GET['alert'] == "demo_mode"){
									echo "<div class='alert alert-warning' style='border-radius: var(--radius-md); font-weight: 600;'><i class='fa fa-shield'></i> <strong>Mode Demo Publik Aktif:</strong> Akun demo utama ini dilindungi dan tidak dapat diubah passwordnya untuk menjaga ketersediaan akses demo portfolio.</div>";
								}
							}
							?>

							<form action="customer_password_act.php" method="post">
								<div class="form-group">
									<label for="">Masukkan Password Baru</label>
									<input type="password" class="input" required="required" name="password" placeholder="Masukkan password .." min="5">
								</div>

								<div class="form-group">
									<input type="submit" class="primary-btn" value="Ganti Password">
								</div>
							</form>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>