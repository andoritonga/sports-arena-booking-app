<!-- FOOTER -->
<footer id="footer" class="section section-grey">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			<!-- footer widget -->
			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<!-- footer logo -->
					<div class="footer-logo">
						<a class="logo-text" href="index.php" style="font-size: 24px; font-weight: 800; color: #fff; text-decoration: none;">
							<span class="badge-icon" style="width: 36px; height: 36px; font-size: 16px;"><i class="fa fa-trophy"></i></span>
							Sport<span class="accent">Kuy</span>
						</a>
						<p style="margin-top: 12px; color: #94a3b8; font-size: 13px;">Platform reservasi & booking lapangan olahraga online terpercaya.</p>
					</div>
					<!-- /footer logo -->
				</div>
			</div>
			<!-- /footer widget -->

			<!-- footer widget -->
			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">My Account</h3>
					<ul class="list-links">
						<li><a href="daftar.php">Daftar</a></li>
						<li><a href="masuk.php">Login</a></li>
					</ul>
				</div>
			</div>
			<!-- /footer widget -->

			<div class="clearfix visible-sm visible-xs"></div>

			<!-- footer widget -->
			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">Customer Service</h3>
					<ul class="list-links">
						<li><a href="about.php">Tentang</a></li>
					</ul>
				</div>
			</div>
			<!-- /footer widget -->

			<!-- footer subscribe -->
			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">Stay Connected</h3>
					
					<p>Follow media sosial kami untuk lebih dekat dan mendapat informasi terbaru tentang lapangan kami.</p>
					
					<!-- footer social -->
					<ul class="footer-social">
						<li><a href="#"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#"><i class="fa fa-instagram"></i></a></li>
						<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
						<li><a href="#"><i class="fa fa-pinterest"></i></a></li>
					</ul>
					<!-- /footer social -->
				</div>
			</div>
			<!-- /footer subscribe -->
		</div>
		<!-- /row -->
		<hr>
		<!-- row -->
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<!-- footer copyright -->
				<div class="footer-copyright">
					
					Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
					
				</div>
				<!-- /footer copyright -->
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</footer>
<!-- /FOOTER -->

<!-- PWA FLOATING INSTALL PROMPT -->
<div id="pwa-install-prompt" class="pwa-install-prompt">
	<div class="pwa-prompt-content">
		<img src="frontend/img/icons/icon-192x192.png" alt="SportKuy App Icon" class="pwa-prompt-icon">
		<div class="pwa-prompt-text">
			<div class="pwa-prompt-title"><?php echo __t('pwa_prompt_title'); ?></div>
			<div class="pwa-prompt-desc"><?php echo __t('pwa_prompt_desc'); ?></div>
		</div>
		<div class="pwa-prompt-actions">
			<button class="btn-pwa-install" onclick="triggerPwaInstall()"><?php echo __t('pwa_install_btn'); ?></button>
			<button class="btn-pwa-dismiss" onclick="dismissPwaInstall()" aria-label="Tutup"><i class="fa fa-times"></i></button>
		</div>
	</div>
</div>

<!-- NATIVE MOBILE BOTTOM NAVIGATION BAR -->
<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav class="app-bottom-nav">
	<a href="index.php" class="app-nav-item <?php if($current_page == 'index.php') echo 'active'; ?>">
		<i class="fa fa-home"></i>
		<span><?php echo __t('home'); ?></span>
	</a>
	<a href="#" class="app-nav-item <?php if($current_page == 'lapangan_kategori.php') echo 'active'; ?>" onclick="openCategorySheet(event)">
		<i class="fa fa-th-large"></i>
		<span><?php echo __t('categories'); ?></span>
	</a>
	<?php if(isset($_SESSION['customer_status'])){ ?>
		<a href="customer_pesanan.php" class="app-nav-item <?php if(in_array($current_page, ['customer_pesanan.php', 'customer_pembayaran.php', 'customer_invoice.php'])) echo 'active'; ?>">
			<i class="fa fa-calendar-check-o"></i>
			<span><?php echo __t('my_bookings'); ?></span>
		</a>
		<a href="customer.php" class="app-nav-item <?php if(in_array($current_page, ['customer.php', 'customer_password.php'])) echo 'active'; ?>">
			<i class="fa fa-user-circle-o"></i>
			<span><?php echo __t('account'); ?></span>
		</a>
	<?php } else { ?>
		<a href="masuk.php?alert=login-dulu-pesan" class="app-nav-item <?php if($current_page == 'customer_pesanan.php') echo 'active'; ?>">
			<i class="fa fa-calendar-check-o"></i>
			<span><?php echo __t('my_bookings'); ?></span>
		</a>
		<a href="masuk.php" class="app-nav-item <?php if(in_array($current_page, ['masuk.php', 'daftar.php'])) echo 'active'; ?>">
			<i class="fa fa-user-circle-o"></i>
			<span><?php echo __t('login'); ?></span>
		</a>
	<?php } ?>
</nav>

<!-- APP CATEGORY BOTTOM SHEET -->
<div id="app-category-sheet-overlay" class="app-bottom-sheet-overlay">
	<div class="app-bottom-sheet">
		<div class="sheet-handle"></div>
		<div class="sheet-header">
			<h3><?php echo __t('select_category_title'); ?></h3>
			<button class="sheet-close-btn" onclick="closeCategorySheet()"><i class="fa fa-times"></i></button>
		</div>

		<!-- Language Switcher in Drawer -->
		<div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 14px; margin-bottom: 18px;">
			<span style="font-size: 13px; font-weight: 700; color: #475569;">
				<i class="fa fa-globe" style="color: var(--color-brand-primary); margin-right: 6px;"></i> <?php echo __t('switch_lang_title'); ?>
			</span>
			<div class="lang-switch-toggle">
				<a href="<?php echo get_lang_switch_url('id'); ?>" class="lang-btn <?php if(get_current_lang() == 'id') echo 'active'; ?>">
					<span class="flag">🇮🇩</span> ID
				</a>
				<a href="<?php echo get_lang_switch_url('en'); ?>" class="lang-btn <?php if(get_current_lang() == 'en') echo 'active'; ?>">
					<span class="flag">🇬🇧</span> EN
				</a>
			</div>
		</div>

		<div class="sheet-category-grid">
			<a href="index.php" class="sheet-category-item" onclick="closeCategorySheet()">
				<div class="sheet-category-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
					<i class="fa fa-globe"></i>
				</div>
				<span class="sheet-category-name"><?php echo __t('all'); ?></span>
			</a>
			<?php 
			$cats_sheet = mysqli_query($koneksi, "SELECT * FROM kategori");
			$icon_map = [
				'futsal' => 'fa-soccer-ball-o',
				'badminton' => 'fa-trophy',
				'bulu tangkis' => 'fa-trophy',
				'basket' => 'fa-dribbble',
				'basketball' => 'fa-dribbble',
				'mini soccer' => 'fa-futbol-o',
				'tennis' => 'fa-circle-o',
				'tenis' => 'fa-circle-o',
				'voli' => 'fa-bullseye',
			];
			while($cs = mysqli_fetch_array($cats_sheet)){
				$cat_lower = strtolower($cs['kategori_nama']);
				$c_icon = 'fa-bolt';
				foreach($icon_map as $key => $icon){
					if(strpos($cat_lower, $key) !== false){
						$c_icon = $icon;
						break;
					}
				}
			?>
			<a href="lapangan_kategori.php?id=<?php echo $cs['kategori_id']; ?>" class="sheet-category-item" onclick="closeCategorySheet()">
				<div class="sheet-category-icon">
					<i class="fa <?php echo $c_icon; ?>"></i>
				</div>
				<span class="sheet-category-name"><?php echo $cs['kategori_nama']; ?></span>
			</a>
			<?php } ?>
		</div>
	</div>
</div>

<!-- jQuery Plugins -->
<script src="frontend/js/jquery.min.js"></script>
<script src="frontend/js/bootstrap.min.js"></script>
<script src="frontend/js/slick.min.js"></script>
<script src="frontend/js/nouislider.min.js"></script>
<script src="frontend/js/jquery.zoom.min.js"></script>
<script src="frontend/js/main.js"></script>


<script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
	$(document).ready(function(){

		// User profile dropdown toggle handler
		$(document).on('click', '.header-account .dropdown-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).closest('.header-account').toggleClass('open');
		});

		$(document).on('click', function(e) {
			if (!$(e.target).closest('.header-account').length) {
				$('.header-account.dropdown').removeClass('open');
			}
		});

		function numberWithCommas(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}

		$('.jumlah').on("keyup",function(){
			var nomor = $(this).attr('nomor');

			var jumlah = $(this).val();

			var harga = $("#harga_"+nomor).val();

			var total = jumlah*harga;

			var t = numberWithCommas(total);

			$("#total_"+nomor).text("Rp. "+t+" ,-");
		});
	});

	$('#datepicker').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
  	}).datepicker("setDate", new Date());

  	$('.datepicker2').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
  	});
</script>

<!-- PWA Native Engine -->
<script src="frontend/js/pwa-app.js?v=1.0"></script>
</body>
</html>