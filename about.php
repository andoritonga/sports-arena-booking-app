<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<?php include 'header.php'; ?><!-- BREADCRUMB --><html><body><div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
            <li class="active">About Us</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div id="main">
    <div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="jumbotron">
                <header>
                    <h2 class="text-center text-uppercase" id="nama_tempat"></h2>
                </header>
                <h3 class="text-center text-uppercase">Tentang Kami</h3>
                <div class="jumbotron bg-danger text-white text-center">
                    <h3 class="text-white text-uppercase">Profil Singkat</h3>
                    <div class="panel-body">
                        <p id="profil" class="text-justify"></p>
                    </div>
                </div>
                <div class="jumbotron bg-danger text-white text-center">
                    <h3 class="text-white text-uppercase">Jam Operasional</h3>
                    <div class="panel-body">
                        <p id="operasional"></p>
                    </div>
                </div>
                <div class="jumbotron bg-danger text-white text-center">
                    <h3 class="text-white text-uppercase">Alamat</h3>
                    <div class="panel-body">
                        <p id="lokasi"></p>
                    </div>
                </div>
                <div class="jumbotron bg-danger text-white text-center">
                    <h3 class="text-white text-uppercase">Email</h3>
                    <div class="panel-body">
                        <p id="email"></p>
                    </div>                        
                </div>
                <div class="jumbotron bg-danger text-white text-center">
                    <h3 class="text-white text-uppercase">Kontak</h3>
                    <div class="panel-body">
                        <p id="kontak"></p>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body></html>
