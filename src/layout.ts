import { LangCode, createTranslator, buildLangSwitchUrl } from './i18n';
import { escapeHtml, getCategoryIcon, getWibNow } from './utils';

export interface CustomerLayoutProps {
  reqUrl: string;
  currentFile: string;
  lang: LangCode;
  customer: { customer_id: number; customer_nama: string } | null;
  categories: Array<{ kategori_id: number; kategori_nama: string }>;
  contentHtml: string;
}

export function renderCustomerLayout(props: CustomerLayoutProps): string {
  const { reqUrl, currentFile, lang, customer, categories, contentHtml } = props;
  const __t = createTranslator(lang);
  const urlId = buildLangSwitchUrl(reqUrl, currentFile, 'id');
  const urlEn = buildLangSwitchUrl(reqUrl, currentFile, 'en');

  const categoryNavItems = categories
    .map(
      (d) =>
        `<li><a href="lapangan_kategori.php?id=${d.kategori_id}">${escapeHtml(d.kategori_nama)}</a></li>`
    )
    .join('\n\t\t\t\t\t\t');

  const sheetCategoryItems = categories
    .map((cs) => {
      const cIcon = getCategoryIcon(cs.kategori_nama);
      return `
			<a href="lapangan_kategori.php?id=${cs.kategori_id}" class="sheet-category-item" onclick="closeCategorySheet()">
				<div class="sheet-category-icon">
					<i class="fa ${cIcon}"></i>
				</div>
				<span class="sheet-category-name">${escapeHtml(cs.kategori_nama)}</span>
			</a>`;
    })
    .join('');

  const accountHeaderHtml = customer
    ? `<div class="header-account dropdown default-dropdown">
						<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
							<div class="header-btns-icon">
								<i class="fa fa-user"></i>
							</div>
							<strong class="text-uppercase" style="color: #ffffff; font-weight: 700;">${escapeHtml(customer.customer_nama)} <i class="fa fa-caret-down" style="margin-left: 4px;"></i></strong>
						</a>
						
						<ul class="custom-menu">
							<li><a href="customer.php"><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> ${__t('dashboard')}</a></li>
							<li><a href="customer_pesanan.php"><i class="fa fa-list-alt" style="color: var(--color-brand-primary);"></i> ${__t('my_bookings')}</a></li>
							<li><a href="customer_password.php"><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> ${__t('change_password')}</a></li>
							<li style="border-top: 1px solid #f1f5f9;"><a href="customer_logout.php" style="color: #ef4444 !important;"><i class="fa fa-sign-out" style="color: #ef4444;"></i> ${__t('logout')}</a></li>
						</ul>
					</div>`
    : `<div class="header-account default-dropdown">
						<a href="masuk.php" class="btn-modern btn-modern-primary" style="display: flex; align-items: center; gap: 8px;">
							<i class="fa fa-sign-in"></i> ${__t('login')}
						</a>
					</div>
					<div class="header-account default-dropdown">
						<a href="daftar.php" class="btn-modern btn-modern-outline" style="display: flex; align-items: center; gap: 8px;">
							<i class="fa fa-user-plus"></i> ${__t('register')}
						</a>
					</div>`;

  const bottomNavAccountHtml = customer
    ? `<a href="customer_pesanan.php" class="app-nav-item ${['customer_pesanan.php', 'customer_pembayaran.php', 'customer_invoice.php'].includes(currentFile) ? 'active' : ''}">
			<i class="fa fa-calendar-check-o"></i>
			<span>${__t('my_bookings')}</span>
		</a>
		<a href="customer.php" class="app-nav-item ${['customer.php', 'customer_password.php'].includes(currentFile) ? 'active' : ''}">
			<i class="fa fa-user-circle-o"></i>
			<span>${__t('account')}</span>
		</a>`
    : `<a href="masuk.php?alert=login-dulu-pesan" class="app-nav-item ${currentFile === 'customer_pesanan.php' ? 'active' : ''}">
			<i class="fa fa-calendar-check-o"></i>
			<span>${__t('my_bookings')}</span>
		</a>
		<a href="masuk.php" class="app-nav-item ${['masuk.php', 'daftar.php'].includes(currentFile) ? 'active' : ''}">
			<i class="fa fa-user-circle-o"></i>
			<span>${__t('login')}</span>
		</a>`;

  return `<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
	
	<!-- PWA Meta Tags -->
	<meta name="theme-color" content="#0f172a">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="SportKuy">
	<meta name="application-name" content="SportKuy">
	<meta name="msapplication-TileColor" content="#0f172a">

	<!-- PWA Icons & Manifest -->
	<link rel="icon" type="image/png" sizes="192x192" href="frontend/img/icons/icon-192x192.png">
	<link rel="icon" type="image/png" sizes="96x96" href="frontend/img/icons/icon-96x96.png">
	<link rel="apple-touch-icon" href="frontend/img/icons/apple-touch-icon.png">
	<link rel="manifest" href="manifest.json">
	
	<title>SportKuy - Booking Lapangan Olahraga Online</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="frontend/css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="frontend/css/bootstrap.min.css" />

	<!-- Slick -->
	<link type="text/css" rel="stylesheet" href="frontend/css/slick.css" />
	<link type="text/css" rel="stylesheet" href="frontend/css/slick-theme.css" />

	<!-- nouislider -->
	<link type="text/css" rel="stylesheet" href="frontend/css/nouislider.min.css" />

	<!-- Font Awesome Icon -->
	<link rel="stylesheet" href="frontend/css/font-awesome.min.css">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="frontend/css/style.css?v=5.0" />
	<link type="text/css" rel="stylesheet" href="frontend/css/modern-custom.css?v=5.0" />
	<link type="text/css" rel="stylesheet" href="frontend/css/pwa-app.css?v=1.0" />

	<link rel="stylesheet" href="assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  	<link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<script src="frontend/js/jquery.min.js"></script>
	<script src="frontend/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<style type="text/css">
		.preloader {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 99999;
			background-color: #0f172a;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.app-preloader-inner {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 14px;
		}
		.app-spinner {
			width: 52px;
			height: 52px;
			border: 4px solid rgba(37, 99, 235, 0.2);
			border-top-color: #38bdf8;
			border-radius: 50%;
			animation: app-spin 0.75s linear infinite;
		}
		@keyframes app-spin {
			to { transform: rotate(360deg); }
		}
		.app-preloader-text {
			font-family: 'Outfit', sans-serif;
			font-size: 16px;
			font-weight: 800;
			letter-spacing: 0.04em;
			color: #ffffff;
		}
		.app-preloader-text span {
			color: #38bdf8;
		}
	</style>
	<script>
		$(window).on('load', function() {
			$(".preloader").fadeOut(250);
		});
		setTimeout(function() {
			$(".preloader").fadeOut(250);
		}, 1500);
	</script> 
</head>

<body>
	<div class="preloader">
		<div class="app-preloader-inner">
			<div class="app-spinner"></div>
			<div class="app-preloader-text">SPORT<span>KUY</span></div>
		</div>
	</div>

	<!-- HEADER -->
	<header id="header">
		<div class="container">
			<div class="header-logo">
				<a class="logo-text" href="index.php">
					<span class="badge-icon"><i class="fa fa-soccer-ball-o"></i></span>
					SPORT<span class="accent">KUY</span>
					<span class="app-status-badge hidden-xs"><span class="app-status-dot"></span> PWA</span>
				</a>
			</div>

			<div class="header-btns pull-right">
				<!-- Language Toggle Switch (ID / EN) -->
				<div class="lang-switch-toggle" style="margin-right: 4px;">
					<a href="${escapeHtml(urlId)}" class="lang-btn ${lang === 'id' ? 'active' : ''}" title="Bahasa Indonesia">
						<span class="flag">🇮🇩</span> ID
					</a>
					<a href="${escapeHtml(urlEn)}" class="lang-btn ${lang === 'en' ? 'active' : ''}" title="English">
						<span class="flag">🇬🇧</span> EN
					</a>
				</div>

				<!-- Header PWA Install Button -->
				<button id="header-install-btn" class="btn-header-install" onclick="triggerPwaInstall()" style="display: none;">
					<i class="fa fa-download"></i> <span class="hidden-xs">${__t('pwa_install_btn')}</span> App
				</button>

				${accountHeaderHtml}

				<!-- Mobile nav toggle-->
				<li class="nav-toggle">
					<button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
				</li>
			</div>
		</div>
	</header>
	<!-- /HEADER -->

	<!-- NAVIGATION -->
	<div id="navigation">
		<div class="container">
			<div id="responsive-nav">
				<div class="category-nav show-on-click">
					<span class="category-header">${__t('categories')} <i class="fa fa-list"></i></span>
					<ul class="category-list">
						${categoryNavItems}
						<li class="all-categories-item"><a href="index.php"><i class="fa fa-th-large"></i> ${__t('all_categories')}</a></li>
					</ul>
				</div>

				<div class="menu-nav">
					<span class="menu-header">${__t('menu')} <i class="fa fa-bars"></i></span>
					<ul class="menu-list">
						<li><a href="index.php">${__t('home')}</a></li>
						<li><a href="about.php">${__t('about')}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- /NAVIGATION -->

${contentHtml}

<!-- FOOTER -->
<footer id="footer" class="section section-grey">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<div class="footer-logo">
						<a class="logo-text" href="index.php" style="font-size: 24px; font-weight: 800; color: #fff; text-decoration: none;">
							<span class="badge-icon" style="width: 36px; height: 36px; font-size: 16px;"><i class="fa fa-trophy"></i></span>
							Sport<span class="accent">Kuy</span>
						</a>
						<p style="margin-top: 12px; color: #94a3b8; font-size: 13px;">Platform reservasi & booking lapangan olahraga online terpercaya.</p>
					</div>
				</div>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">My Account</h3>
					<ul class="list-links">
						<li><a href="daftar.php">Daftar</a></li>
						<li><a href="masuk.php">Login</a></li>
					</ul>
				</div>
			</div>

			<div class="clearfix visible-sm visible-xs"></div>

			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">Customer Service</h3>
					<ul class="list-links">
						<li><a href="about.php">Tentang</a></li>
					</ul>
				</div>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-6">
				<div class="footer">
					<h3 class="footer-header">Stay Connected</h3>
					<p>Follow media sosial kami untuk lebih dekat dan mendapat informasi terbaru tentang lapangan kami.</p>
					<ul class="footer-social">
						<li><a href="#"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#"><i class="fa fa-instagram"></i></a></li>
						<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
						<li><a href="#"><i class="fa fa-pinterest"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<div class="footer-copyright">
					Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- /FOOTER -->

<!-- PWA FLOATING INSTALL PROMPT -->
<div id="pwa-install-prompt" class="pwa-install-prompt">
	<div class="pwa-prompt-content">
		<img src="frontend/img/icons/icon-192x192.png" alt="SportKuy App Icon" class="pwa-prompt-icon">
		<div class="pwa-prompt-text">
			<div class="pwa-prompt-title">${__t('pwa_prompt_title')}</div>
			<div class="pwa-prompt-desc">${__t('pwa_prompt_desc')}</div>
		</div>
		<div class="pwa-prompt-actions">
			<button class="btn-pwa-install" onclick="triggerPwaInstall()">${__t('pwa_install_btn')}</button>
			<button class="btn-pwa-dismiss" onclick="dismissPwaInstall()" aria-label="Tutup"><i class="fa fa-times"></i></button>
		</div>
	</div>
</div>

<!-- NATIVE MOBILE BOTTOM NAVIGATION BAR -->
<nav class="app-bottom-nav">
	<a href="index.php" class="app-nav-item ${currentFile === 'index.php' ? 'active' : ''}">
		<i class="fa fa-home"></i>
		<span>${__t('home')}</span>
	</a>
	<a href="#" class="app-nav-item ${currentFile === 'lapangan_kategori.php' ? 'active' : ''}" onclick="openCategorySheet(event)">
		<i class="fa fa-th-large"></i>
		<span>${__t('categories')}</span>
	</a>
	${bottomNavAccountHtml}
</nav>

<!-- APP CATEGORY BOTTOM SHEET -->
<div id="app-category-sheet-overlay" class="app-bottom-sheet-overlay">
	<div class="app-bottom-sheet">
		<div class="sheet-handle"></div>
		<div class="sheet-header">
			<h3>${__t('select_category_title')}</h3>
			<button class="sheet-close-btn" onclick="closeCategorySheet()"><i class="fa fa-times"></i></button>
		</div>

		<!-- Language Switcher in Drawer -->
		<div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 14px; margin-bottom: 18px;">
			<span style="font-size: 13px; font-weight: 700; color: #475569;">
				<i class="fa fa-globe" style="color: var(--color-brand-primary); margin-right: 6px;"></i> ${__t('switch_lang_title')}
			</span>
			<div class="lang-switch-toggle">
				<a href="${escapeHtml(urlId)}" class="lang-btn ${lang === 'id' ? 'active' : ''}">
					<span class="flag">🇮🇩</span> ID
				</a>
				<a href="${escapeHtml(urlEn)}" class="lang-btn ${lang === 'en' ? 'active' : ''}">
					<span class="flag">🇬🇧</span> EN
				</a>
			</div>
		</div>

		<div class="sheet-category-grid">
			<a href="index.php" class="sheet-category-item" onclick="closeCategorySheet()">
				<div class="sheet-category-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
					<i class="fa fa-globe"></i>
				</div>
				<span class="sheet-category-name">${__t('all')}</span>
			</a>
			${sheetCategoryItems}
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
			return x.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, ",");
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
</html>`;
}

export function renderCustomerSidebar(lang: LangCode): string {
  const __t = createTranslator(lang);
  return `<!-- ASIDE -->
<div id="aside" class="col-md-3">
	<div class="form-box" style="padding: 20px;">
		<h4 style="font-size: 16px; font-weight: 800; margin-bottom: 15px; color: var(--color-text-main);"><i class="fa fa-user-circle" style="color: var(--color-brand-primary);"></i> ${__t('customer_menu')}</h4>
		<ul style="list-style: none; padding: 0; margin: 0;">
			<li style="margin-bottom: 8px;"><a class="main-btn btn-block text-left" href="customer.php"><i class="fa fa-home" style="width: 20px; text-align: center;"></i> ${__t('dashboard')}</a></li>
			<li style="margin-bottom: 8px;"><a class="main-btn btn-block text-left" href="customer_pesanan.php"><i class="fa fa-list-alt" style="width: 20px; text-align: center;"></i> ${__t('my_bookings')}</a></li>
			<li style="margin-bottom: 8px;"><a class="main-btn btn-block text-left" href="customer_password.php"><i class="fa fa-lock" style="width: 20px; text-align: center;"></i> ${__t('change_password')}</a></li>
			<li style="margin-bottom: 0;"><a class="main-btn btn-block text-left" href="customer_logout.php" style="color: #ef4444;"><i class="fa fa-sign-out" style="width: 20px; text-align: center;"></i> ${__t('logout')}</a></li>
		</ul>
	</div>
</div>
<!-- /ASIDE -->`;
}

export interface AdminLayoutProps {
  currentFile: string;
  admin: { admin_id: number; admin_nama: string; admin_username: string; admin_foto: string | null };
  pendingCount: number;
  contentHtml: string;
}

export function renderAdminLayout(props: AdminLayoutProps): string {
  const { currentFile, admin, pendingCount, contentHtml } = props;
  const fotoSrc = admin.admin_foto ? `../gambar/user/${escapeHtml(admin.admin_foto)}` : '../gambar/sistem/user.png';
  const year = getWibNow().getUTCFullYear();

  return `<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dashboard Admin - SportKuy</title>
  
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../assets/bower_components/morris.js/morris.css">
  <link rel="stylesheet" href="../assets/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="../assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="../frontend/css/admin-modern.css?v=5.3">

</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header header-logo">
      <a href="index.php" class="logo">
        <span class="logo-mini"><i class="fa fa-trophy"></i></span>
        <span class="logo-lg"><i class="fa fa-trophy" style="color: #60a5fa;"></i> SPORT<b>KUY</b></span>
      </a>
      <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown messages-menu">
              <a href="transaksi.php" title="Booking Menunggu Konfirmasi">
                <i class="fa fa-bell-o"></i>
                ${pendingCount > 0 ? `<span class="label label-warning" style="border-radius: 50%; font-size: 10px;">${pendingCount}</span>` : ''}
              </a>
            </li>

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="${fotoSrc}" class="user-image">
                <span class="hidden-xs">${escapeHtml(admin.admin_nama)}</span>
              </a>
            </li>
            <li>
              <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')"><i class="fa fa-sign-out"></i> KELUAR</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="${fotoSrc}" class="img-circle" style="max-height:45px">
          </div>
          <div class="pull-left info">
            <p>${escapeHtml(admin.admin_nama)}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">MANAJEMEN UTAMA</li>

          <li class="${currentFile === 'index.php' ? 'active' : ''}"> 
            <a href="index.php">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
          </li>

          <li class="${['lapangan.php', 'lapangan_tambah.php', 'lapangan_edit.php', 'lapangan_hapus_konfir.php'].includes(currentFile) ? 'active' : ''}">
            <a href="lapangan.php">
              <i class="fa fa-soccer-ball-o"></i> <span>Data Lapangan</span>
            </a>
          </li>

          <li class="${['kategori.php', 'kategori_tambah.php', 'kategori_edit.php', 'kategori_hapus_konfir.php'].includes(currentFile) ? 'active' : ''}">
            <a href="kategori.php">
              <i class="fa fa-th-large"></i> <span>Data Kategori</span>
            </a>
          </li>

          <li class="${['transaksi.php', 'transaksi_invoice.php', 'transaksi_hapus_konfir.php'].includes(currentFile) ? 'active' : ''}">
            <a href="transaksi.php">
              <i class="fa fa-credit-card"></i> <span>Transaksi Booking</span>
              ${pendingCount > 0 ? `<span class="pull-right-container"><span class="label label-warning pull-right">${pendingCount} baru</span></span>` : ''}
            </a>
          </li>

          <li class="${['customer.php', 'customer_tambah.php', 'customer_edit.php', 'customer_hapus_konfir.php'].includes(currentFile) ? 'active' : ''}">
            <a href="customer.php">
              <i class="fa fa-users"></i> <span>Data Customer</span>
            </a>
          </li>

          <li class="${currentFile === 'laporan.php' ? 'active' : ''}">
            <a href="laporan.php">
              <i class="fa fa-bar-chart"></i> <span>Laporan Penjualan</span>
            </a>
          </li> 

          <li class="header">PENGATURAN SISTEM</li>

          <li class="${['admin.php', 'admin_tambah.php', 'admin_edit.php'].includes(currentFile) ? 'active' : ''}">
            <a href="admin.php">
              <i class="fa fa-user-secret"></i> <span>Data Admin</span>
            </a>
          </li>

          <li class="${currentFile === 'gantipassword.php' ? 'active' : ''}">
            <a href="gantipassword.php">
              <i class="fa fa-key"></i> <span>Ganti Password</span>
            </a>
          </li>

          <li>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
              <i class="fa fa-sign-out" style="color: #ef4444;"></i> <span style="color: #ef4444;">Keluar</span>
            </a>
          </li>
          
        </ul>
      </section>
    </aside>

${contentHtml}

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
    </div>
    <strong>Copyright &copy; ${year}</strong>
  </footer>

</div>

<script src="../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="../assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/bower_components/raphael/raphael.min.js"></script>
<script src="../assets/bower_components/morris.js/morris.min.js"></script>
<script src="../assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="../assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<script src="../assets/bower_components/moment/min/moment.min.js"></script>
<script src="../assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="../assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="../assets/dist/js/adminlte.min.js"></script>
<script src="../assets/dist/js/pages/dashboard.js"></script>
<script src="../assets/dist/js/demo.js"></script>
<script src="../assets/bower_components/ckeditor/ckeditor.js"></script>

<script>
  $(document).ready(function(){
   $('#table-datatable').DataTable({
    'paging'      : true,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : false,
    'info'        : true,
    'autoWidth'   : true,
    "pageLength": 50
  });
 });
  
  $('#datepicker').datepicker({
    autoclose: true,
    format: 'dd/mm/yyyy',
  }).datepicker("setDate", new Date());

  $('.datepicker2').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
  });

  $(function () {
    if (document.getElementById('editor1')) {
      CKEDITOR.replace('editor1');
    }
  });
</script>

</body>
</html>`;
}
