<?php 
include 'koneksi.php';

session_start();
?>
<html>
    <head>
        <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <title>Template Owner</title>
        <script src="frontend/js/jquery.min.js"></script>
        <script src="frontend/js/bootstrap.min.js"></script>
        <link type="text/css" rel="stylesheet" href="frontend/css/bootstrap.css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style type="text/css">
            .preloader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background-color: #fff;
            }
            .preloader .loading {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%,-50%);
                font: 14px arial;
            }
            .spinner {
                display: none;
            }
        </style>
        <script>
            $(document).ready(function() {
                var preHidden = false;
                function hidePre() {
                    if(!preHidden) {
                        preHidden = true;
                        $(".preloader").fadeOut(350);
                    }
                }
                $(window).on('load', hidePre);
                setTimeout(hidePre, 700);
            });
        </script>     
    </head>
    <body class="bg-dark">
        <div class="preloader">
            <div class="loading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <br>
            <div class="container">
                <div class="row">
                    <center>
                        <h2 class="text-white text-uppercase">Template Owner</h2>
                        <br>
                    </center>
                    <?php 
                        
                    ?>
                    <?php
                        $data = mysqli_query($koneksi,"select * from template");
                        while($x=mysqli_fetch_array($data)){
                            $folder = str_replace(" ","_",$x['template_nama']);
                            if(isset($_GET['alert'])){
                                if($_GET['alert'] == "berhasil"){
                                    echo "<div class='alert alert-success'>Template Berhasil Dibuat! Untuk melihat hasilnya silahkan <a href='../$folder' class='alert-link' target='_blank' rel='nofollow'>klik disini</a></div>";
                                }elseif($_GET['alert'] == "gagal"){
                                    echo "<div class='alert alert-danger'>Template Gagal Dibuat!</div>";
                                }elseif($_GET['alert'] == "status"){
                                    echo "<div class='alert alert-warning'>Template Ditolak!</div>";
                                }                               
                            }
                    ?>
                    <form class="form-prevent" action="owner_act.php?id=<?php echo $x['template_kontak'];?>" method="POST">
                        <div class="card text-white bg-black-active form-group has-feedback">
                            <div class="card-header form-control text-uppercase" name="nama">
                                <h3 class="text-md-center">
                                    <span style='float:left;'><img src="frontend/img/<?php echo $x['template_logo'];?>" class="img-thumbnail" style="max-width:50px;"></span>
                                    <?php echo $x['template_nama'];?>
                                    <span style='float:right;'><img src="frontend/img/<?php echo $x['template_logo'];?>" class="img-thumbnail" style="max-width:50px;"></span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive table-bordered table-secondary text-justify">
                                    <tr>
                                        <th class="col-3">Profil Singkat</th>
                                        <td class="col-3-sm" name="profil"><?php echo $x['template_profil'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Jam Operasional</th>
                                        <td name="jam"><?php echo $x['template_operasional'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td name="alamat"><?php echo $x['template_alamat'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td name="email"><?php echo $x['template_email'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Kontak</th>
                                        <td name="kontak"><?php echo $x['template_kontak'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Logo</th>
                                        <td name="logo"><?php echo $x['template_logo'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td name="status"><?php echo $x['template_status'];?></td>
                                </table>
                            </div>
                            <div class="card-footer text-center">
                                <?php
                                    if ($x['template_status'] == "PENDING"){
                                ?>
                                    <button type="submit" class="btn btn-success button-prevent">
                                        <div class="spinner"><i role="status" class="spinner-border spinner-border-sm"></i> Buat Template </div>
                                        <div class="hide-text">Buat Template</div>
                                    </button>                                    
                                    <a href="owner_rej.php?id=<?php echo $x['template_kontak'];?>" class="btn btn-danger">Tolak Template</a>
                                <?php 
                                    }else{ 
                                ?>
                                    <p></p>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script>
            (function () {
                $('.form-prevent').on('submit', function () {
                    $('.button-prevent').attr('disabled', 'true');
                    $('.spinner').show();
                    $('.hide-text').hide();
                })
            })();
        </script>
    </body>
</html>