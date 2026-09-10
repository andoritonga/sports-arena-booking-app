<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Admin</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .admin-login-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .logo-badge {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 26px;
      margin-bottom: 16px;
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }
    .btn-login {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      color: #fff !important;
      border-radius: 12px !important;
      padding: 12px !important;
      font-weight: 700 !important;
      font-size: 16px !important;
      border: none !important;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3) !important;
      transition: all 0.2s ease !important;
    }
    .btn-login:hover {
      box-shadow: 0 8px 22px rgba(37, 99, 235, 0.5) !important;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="admin-login-card">
    <div class="text-center" style="margin-bottom: 30px;">
      <div class="logo-badge">
        <i class="fa fa-shield"></i>
      </div>
      <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Portal Admin</h2>
      <p style="color: #64748b; font-size: 14px; margin: 0;">SportKuy Management Dashboard</p>
    </div>

    <?php 
    if(isset($_GET['alert'])){
      if($_GET['alert'] == "gagal"){
        echo "<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Login gagal! Username atau password salah.</div>";
      }else if($_GET['alert'] == "logout"){
        echo "<div class='alert alert-success text-center' style='border-radius: 12px; font-weight: 600;'>Anda telah berhasil logout.</div>";
      }else if($_GET['alert'] == "belum_login"){
        echo "<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login untuk mengakses portal admin.</div>";
      }
    }
    ?>

    <form action="periksa_login.php" method="POST">
      <div class="form-group" style="margin-bottom: 20px;">
        <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;"><i class="fa fa-user" style="color: #2563eb;"></i> Username Administrator</label>
        <input type="text" class="form-control" style="border-radius: 12px; padding: 12px 16px; background: #f8fafc;" placeholder="Masukkan username..." name="username" required="required" autocomplete="off">
      </div>
      <div class="form-group" style="margin-bottom: 26px;">
        <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;"><i class="fa fa-lock" style="color: #2563eb;"></i> Password</label>
        <input type="password" class="form-control" style="border-radius: 12px; padding: 12px 16px; background: #f8fafc;" placeholder="Masukkan password..." name="password" required="required" autocomplete="off">
      </div>
      <button type="submit" class="btn btn-login btn-block"><i class="fa fa-sign-in"></i> MASUK KE DASHBOARD</button>
    </form>
    
    <div class="text-center" style="margin-top: 24px;">
      <a href="index.php" style="color: #64748b; font-size: 14px; font-weight: 600; text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali ke Halaman Utama</a>
    </div>
  </div>
  <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
