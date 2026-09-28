<?php 
  include '../koneksi.php';
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php?alert=belum_login");
  }

  $file = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
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

            <?php if(defined('DEMO_MODE') && DEMO_MODE) { ?>
            <li class="hidden-xs" style="padding: 12px 8px;">
              <span class="badge" style="background: #f59e0b; color: #fff; font-size: 11px; padding: 6px 12px; border-radius: 20px; font-weight: 700; letter-spacing: 0.04em;">
                <i class="fa fa-shield"></i> DEMO MODE ACTIVE
              </span>
            </li>
            <?php } ?>

            <?php
            // Pending confirmations alert badge
            $pending_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM invoice WHERE invoice_status='1'");
            $pending_data = mysqli_fetch_assoc($pending_query);
            $pending_count = $pending_data['total'];
            ?>
            <li class="dropdown messages-menu">
              <a href="transaksi.php" title="Booking Menunggu Konfirmasi">
                <i class="fa fa-bell-o"></i>
                <?php if($pending_count > 0){ ?>
                  <span class="label label-warning" style="border-radius: 50%; font-size: 10px;"><?php echo $pending_count; ?></span>
                <?php } ?>
              </a>
            </li>

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php error_reporting (0);
                $id_admin = $_SESSION['id'];
                $profil = mysqli_query($koneksi,"select * from admin where admin_id='$id_admin'");
                $profil = mysqli_fetch_assoc($profil);
                if($profil['admin_foto'] == ""){ 
                  ?>
                  <img src="../gambar/sistem/user.png" class="user-image">
                <?php }else{ ?>
                  <img src="../gambar/user/<?php echo $profil['admin_foto'] ?>" class="user-image">
                <?php } ?>
                <span class="hidden-xs"><?php echo $_SESSION['nama']; ?></span>
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
            <?php 
            $id = $_SESSION['id'];
            $profil = mysqli_query($koneksi,"select * from admin where admin_id='$id'");
            $profil = mysqli_fetch_assoc($profil);
            if($profil['admin_foto'] == ""){ 
              ?>
              <img src="../gambar/sistem/user.png" class="img-circle">
            <?php }else{ ?>
              <img src="../gambar/user/<?php echo $profil['admin_foto'] ?>" class="img-circle" style="max-height:45px">
            <?php } ?>
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['nama']; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">MANAJEMEN UTAMA</li>

          <li class="<?php if($file == 'index.php'){ echo 'active'; } ?>"> 
            <a href="index.php">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
          </li>

          <li class="<?php if($file == 'lapangan.php' || $file == 'lapangan_tambah.php' || $file == 'lapangan_edit.php'){ echo 'active'; } ?>">
            <a href="lapangan.php">
              <i class="fa fa-soccer-ball-o"></i> <span>Data Lapangan</span>
            </a>
          </li>

          <li class="<?php if($file == 'kategori.php' || $file == 'kategori_tambah.php' || $file == 'kategori_edit.php'){ echo 'active'; } ?>">
            <a href="kategori.php">
              <i class="fa fa-th-large"></i> <span>Data Kategori</span>
            </a>
          </li>

          <li class="<?php if($file == 'transaksi.php' || $file == 'transaksi_invoice.php'){ echo 'active'; } ?>">
            <a href="transaksi.php">
              <i class="fa fa-credit-card"></i> <span>Transaksi Booking</span>
              <?php if($pending_count > 0){ ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?php echo $pending_count; ?> baru</span>
                </span>
              <?php } ?>
            </a>
          </li>

          <li class="<?php if($file == 'customer.php' || $file == 'customer_tambah.php' || $file == 'customer_edit.php'){ echo 'active'; } ?>">
            <a href="customer.php">
              <i class="fa fa-users"></i> <span>Data Customer</span>
            </a>
          </li>

          <li class="<?php if($file == 'laporan.php'){ echo 'active'; } ?>">
            <a href="laporan.php">
              <i class="fa fa-bar-chart"></i> <span>Laporan Penjualan</span>
            </a>
          </li> 

          <li class="header">PENGATURAN SISTEM</li>

          <li class="<?php if($file == 'admin.php' || $file == 'admin_tambah.php' || $file == 'admin_edit.php'){ echo 'active'; } ?>">
            <a href="admin.php">
              <i class="fa fa-user-secret"></i> <span>Data Admin</span>
            </a>
          </li>

          <li class="<?php if($file == 'gantipassword.php'){ echo 'active'; } ?>">
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
      <!-- /.sidebar -->
    </aside>