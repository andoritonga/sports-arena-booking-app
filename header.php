<?php
include 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$file = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['customer_status'])){


	$lindungi = array('customer.php','customer_logout.php');


	if(in_array($file, $lindungi)){
		header("location:index.php");
	}

	if($file == "checkout.php"){
		header("location:masuk.php?alert=login-dulu-pesan");
	}
	
	if($file == "komentar_act.php"){
		header("location:masuk.php?alert=login-dulu-komentar");
	}

}else{

	
	$lindungi = array('masuk.php','daftar.php');

	if(in_array($file, $lindungi)){
		header("location:customer.php");
	}

}

function console_log( $dataa ){
	echo '<script>';
	echo 'console.log('. json_encode( $dataa ) .')';
	echo '</script>';
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="manifest" href="manifest.json">
	
	<title>Aplikasi Booking Lapangan</title>

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
                background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .preloader-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 16px;
                text-align: center;
            }
            .modern-spinner {
                width: 50px;
                height: 50px;
                border: 4px solid rgba(255, 255, 255, 0.12);
                border-top: 4px solid #2563eb;
                border-right: 4px solid #38bdf8;
                border-radius: 50%;
                animation: spinLoader 0.85s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
                box-shadow: 0 0 25px rgba(37, 99, 235, 0.35);
            }
            @keyframes spinLoader {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .preloader-brand {
                color: #ffffff;
                font-family: 'Outfit', sans-serif;
                font-size: 16px;
                font-weight: 800;
                letter-spacing: 0.15em;
                text-transform: uppercase;
            }
            .preloader-brand span {
                color: #38bdf8;
            }
        </style>
        <script>
            $(document).ready(function() {
                var preloaderHidden = false;
                function hidePreloader() {
                    if (!preloaderHidden) {
                        preloaderHidden = true;
                        $(".preloader").fadeOut(350);
                    }
                }
                $(window).on('load', hidePreloader);
                setTimeout(hidePreloader, 700);
            });
        </script> 
</head>

<body>
	<div class="preloader">
		<div class="preloader-content">
			<div class="modern-spinner"></div>
			<div class="preloader-brand">SPORT <span>KUY</span></div>
		</div>
	</div>

	<!-- HEADER -->
	<header id="header">
		<!-- container -->
		<div class="container">

			<!-- header -->
			<div class="header-logo">
				<a class="logo-text" href="index.php">
					<span class="badge-icon"><i class="fa fa-soccer-ball-o"></i></span>
					SPORT<span class="accent">KUY</span>
				</a>
			</div>
			<!-- /logo -->

			<div class="header-btns pull-right">
				<?php 
				if(isset($_SESSION['customer_status'])){
					$id_customer = $_SESSION['customer_id'];
					$customer = mysqli_query($koneksi,"select * from customer where customer_id='$id_customer'");
					$c = mysqli_fetch_assoc($customer);
					?>
					<div class="header-account dropdown default-dropdown">
						<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
							<div class="header-btns-icon">
								<i class="fa fa-user"></i>
							</div>
							<strong class="text-uppercase" style="color: #ffffff; font-weight: 700;"><?php echo $c['customer_nama']; ?> <i class="fa fa-caret-down" style="margin-left: 4px;"></i></strong>
						</a>
						
						<ul class="custom-menu">
							<li><a href="customer.php"><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Dashboard</a></li>
							<li><a href="customer_pesanan.php"><i class="fa fa-list-alt" style="color: var(--color-brand-primary);"></i> Pesanan Saya</a></li>
							<li><a href="customer_password.php"><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> Ganti Password</a></li>
							<li style="border-top: 1px solid #f1f5f9;"><a href="customer_logout.php" style="color: #ef4444 !important;"><i class="fa fa-sign-out" style="color: #ef4444;"></i> Keluar</a></li>
						</ul>
					</div>
					<?php
				}else{
					?>
					<div class="header-account default-dropdown">
						<a href="masuk.php" class="btn-modern btn-modern-primary" style="display: flex; align-items: center; gap: 8px;">
							<i class="fa fa-sign-in"></i> LOGIN
						</a>
					</div>
					<div class="header-account default-dropdown">
						<a href="daftar.php" class="btn-modern btn-modern-outline" style="display: flex; align-items: center; gap: 8px;">
							<i class="fa fa-user-plus"></i> DAFTAR
						</a>
					</div>
					<?php
				}
				?>

				<!-- Mobile nav toggle-->
				<li class="nav-toggle">
					<button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
				</li>
				<!-- / Mobile nav toggle -->
			</div>
		</div>
		<!-- container -->
	</header>
	<!-- /HEADER -->

	<!-- NAVIGATION -->
	<div id="navigation">
		<!-- container -->
		<div class="container">
			<div id="responsive-nav">
				<!-- category nav -->
				<div class="category-nav show-on-click">
					<span class="category-header">Kategori Lapangan <i class="fa fa-list"></i></span>
					<ul class="category-list">
						<?php 
						$data = mysqli_query($koneksi,"SELECT * FROM kategori");
						while($d = mysqli_fetch_array($data)){
							?>
							<li><a href="lapangan_kategori.php?id=<?php echo $d['kategori_id']; ?>"><?php echo $d['kategori_nama']; ?></a></li>
							<?php 
						}
						?>
						<li class="all-categories-item"><a href="index.php"><i class="fa fa-th-large"></i> Tampilkan Semua</a></li>
					</ul>
				</div>
				<!-- /category nav -->

				<!-- menu nav -->
				<div class="menu-nav">
					<span class="menu-header">Menu <i class="fa fa-bars"></i></span>
					<ul class="menu-list">
						<li><a href="index.php">Home</a></li>
						<li><a href="about.php">About Us</a></li>
					</ul>
				</div>
				<!-- menu nav -->
			</div>
		</div>
		<!-- /container -->
	</div>
	<!-- /NAVIGATION -->