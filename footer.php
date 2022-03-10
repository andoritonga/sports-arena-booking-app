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
						<a class="logo" href="#">
							<img src="frontend/img/logo.png" alt="">
						</a>
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

<!-- jQuery Plugins -->
<script src="frontend/js/jquery.min.js"></script>
<script src="frontend/js/bootstrap.min.js"></script>
<script src="frontend/js/slick.min.js"></script>
<script src="frontend/js/nouislider.min.js"></script>
<script src="frontend/js/jquery.zoom.min.js"></script>
<script src="frontend/js/main.js"></script>


<script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
</body>

<script>

	$(document).ready(function(){

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


<script>
  // REGISTER SERVICE WORKER
  if ("serviceWorker" in navigator) {
    window.addEventListener("load", function() {
      navigator.serviceWorker
        .register("sw.js")
        .then(function() {
          console.log("Pendaftaran ServiceWorker berhasil");
        })
        .catch(function() {
          console.log("Pendaftaran ServiceWorker gagal");
        });
    });
  } else {
    console.log("ServiceWorker belum didukung browser ini.");
  }
</script>