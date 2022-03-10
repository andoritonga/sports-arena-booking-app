<html>
    <head>
        <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <title>Template Sport Center</title>
        <link type="text/css" rel="stylesheet" href="frontend/css/bootstrap.css"/>
        <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">     
    </head>
    <body class="bg-dark text-white">
            <div class="container">
                <div class="row col-6 center-block">
                    <center>
                        <h2>Template Sport Center</h2>
                        <br/>
                    </center>
                    <div class="box-body">
                        <center>
                            <p class="box-title">Masukkan Data Sport Center</p>
                        </center>

                        <?php 
                        if(isset($_GET['alert'])){
                            if($_GET['alert'] == "berhasil"){
                                echo "<div class='alert alert-success'>Pendaftaran Berhasil! Silahkan menunggu konfirmasi dari kami!</div>";
                            }elseif($_GET['alert'] == "gagal"){
                                echo "<div class='alert alert-danger'>Pendaftaran Gagal!</div>";
                            }                                
                        }
                        ?>

                        <form action="template_act.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group has-feedback">
                                <label>Nama Sport Center</label>
                                <input type="text" class="form-control" placeholder="Nama Sport Center" name="nama" required="required" autocomplete="off">
                            </div>
                            <div class="form-group has-feedback">
                                <label>Profil Singkat</label>
                                <textarea class="form-control" placeholder="Profil Singkat" name="profil" required="required" autocomplete="off" style="resize: none" rows="2"></textarea>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Jam Operasional</label>
                                <input type="text" class="form-control" placeholder="Jam Operasional" name="jam" required="required" autocomplete="off">
                            </div>
                            <div class="form-group has-feedback">
                                <label>Alamat</label>
                                <textarea  class="form-control" placeholder="Alamat" name="alamat" required="required" autocomplete="off" style="resize: none" rows="2"></textarea>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Email</label>
                                <input type="text" class="form-control" placeholder="Email" name="email" required="email" autocomplete="off">
                            </div>
                            <div class="form-group has-feedback">
                                <label>Kontak</label>
                                <input type="text" class="form-control" placeholder="Kontak" name="kontak" required="kontak" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Logo (512x512px)</label>
                                <input type="file" name="foto">
                            </div>
                            <div class="form-group">
                                    <button type="submit" class="btn btn-danger btn-block btn-flat">DAFTAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    </body>
</html>