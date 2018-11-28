<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<title>NetPizza</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<meta name="theme-color" content="#1d1e1e">
		<meta name="author" content="Lovász Bence">

		<link rel="icon" type="image/x-icon" href="images/favicon.png">
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.13/css/mdb.min.css">
		<link rel="stylesheet" href="plugins/notify/notify.css">

		<link rel="stylesheet" href="css/maintenance.css">
		<link rel="stylesheet" href="css/order.css">

		<!-- Cookies (eu law) -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
		<script>
			window.addEventListener("load", function() {
				window.cookieconsent.initialise({
					"palette": {
						"popup": {
							"background": "#000000"
						},
						
						"button": {
							"background": "#4285f4"
						}
					},
					
					"content": {
						"message": "Jobb felhasználói élmény eléréséhez oldalunk sütiket használ!",
						"dismiss": "Rendben",
						"link": "Bővebben"
					}
				})
			});
		</script>

		<!-- recaptcha for login -->
		<script src="https://www.google.com/recaptcha/api.js?hl=hu" async defer></script>
	</head>

	<body class="bg-light">
		<?php
			require "misc/connection.php3";
			require "misc/util.php3";

			if (isset($_COOKIE["json_userdata"]))
				$datas = json_decode($_COOKIE["json_userdata"], true);
			else
				header("Location: profile.php");
		?>

		<!-- Navbar -->
		<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark scrolling-navbar">
			<div class="container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-bars"></i>
				</button>

				<logo><a class="logo navbar-brand text-primary" href="javascript:void(0);"><b>NetPizza</b></a></logo>

				<!-- Links -->
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="index.php">Főoldal</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="profile.php">Profil</a>
						</li>

						<li class="nav-item active">
							<a class="nav-link" href="order.php">Rendelés</a>
						</li>
					</ul>

					<!-- Right -->
					<ul class="navbar-nav nav-flex-icons">
						<li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="bence444">
							<a href="https://facebook.com/bence444" class="nav-link"><i class="fab fa-facebook text-primary"></i></a>
						</li>
	
						<li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="yung_bones__">
							<a href="https://instagram.com/yung_bones__" class="nav-link"><i class="fab fa-instagram"></i></a>
						</li>

						<li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="lovasz.666">
							<a class="nav-link"><i class="fab fa-snapchat" style="color: #fffc00;"></i></a>
						</li>
					</ul>			
				</div>
			</div>
		</nav>

		<div class="container-fluid mx-auto pt-5 mb-5">
			<div class="col-xl-10 row mx-auto">
				<div class="col-md-8">
					<h3>Kínálatunk</h3>

					<div id="accordion" class="mb-5">
						<div class="card bg-light">
							<?php
								$pizzadatas = json_decode(file_get_contents("files/menulist.json"));

								usort($pizzadatas, function ($a, $b) {
									return $b[3] <=> $a[3];
								});

								foreach ($pizzadatas as $key => $value) {
									$name = $value[3] ? $value[0] . ' <span class="badge badge-pill bg-warning text-dark">AKCIÓ</span>' : $value[0];
									$price = $value[3] ? $value[3] : $value[1];

									echo '<div class="card-header" data-toggle="collapse" data-target="#collapse-' . $key . '">
										<h5 class="mb-0">' . $name . '</h5>

										<a class="font-small"><i class="fas fa-comment-alt"></i> ' . $value[2] . '</a>
									</div>

									<div id="collapse-' . $key . '" class="collapse">
										<div class="card-body">
											<p class="mt-2 mb-1 ml-1"><span class="badge badge-pill bg-warning p-1 pl-2 pr-2">30 cm</span> - <b>' . number_format($price) . ' Forint</b><button data-holder="' . $value[0] . '" data-type="1" data-price="' . $price . '" class="btn-add btn btn-outline-primary btn-sm mr-2 float-right"><i class="fas fa-cart-plus"></i> Kosárba</button></p>
											
											<p class="mt-3 mb-1 ml-1"><span class="badge badge-pill bg-danger p-1 pl-2 pr-2">50 cm</span> - <b>' . number_format(floor($price * 2.45)) . ' Forint</b><button data-holder="' . $value[0] . '" data-type="2" data-price="' . floor($price * 2.45) . '" class="btn-add btn btn-outline-primary btn-sm mr-2 float-right"><i class="fas fa-cart-plus"></i> Kosárba</button></p>
										</div>
									</div>';
								}
							?>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<h3>Kosár</h3>

					<ul class="pl-0 pb-3 mb-3 border-bottom border-secondary" id="orders" style="list-style: none;">
						<li>Kosár tartalma jelenleg üres</li>
					</ul>

					<p id="total">Összesen: <b>0 Forint</b></p>

					<button class="btn btn-sm btn-block btn-outline-primary mb-2 clearorders"><i class="fas fa-trash-alt"></i> Kosár ürítése</button>

					<button class="btn btn-sm btn-block btn-primary completeorder">Rendelés elküldése <i class="fas fa-angle-double-right"></i></button>
				</div>
			</div>

			<!-- <div class="col-md-3">
				<h3 class="pt-0 mt-2 mb-3">Kosár</h3>

				<ul class="pl-0 pb-3 mb-3 border-bottom border-secondary" id="orders" style="list-style: none;">
					<li>Kosár tartalma jelenleg üres</li>
				</ul>

				<p id="total">Összesen: <b>0 Forint</b></p>

				<button class="btn btn-danger btn-block clearorders"><i class="fas fa-trash-alt"></i> Kosár ürítése</button>
				<button class="btn btn-success btn-block completeorder">Rendelés elküldése <i class="fas fa-angle-double-right"></i></button>
			</div> -->
		</div>

		<!-- Footer -->
		<footer class="page-footer font-small bg-dark">
			<!-- <div class="container">
				<div class="row">
					<div class="col-md-12 py-2">
						<div class="mb-5 flex-center mx-auto">

						</div>
					</div>
				</div>
			</div> -->

			<div class="footer-copyright text-center py-3">
				© 2018 Copyright: <a href="https://github.com/yungbones"> Lovász Bence</a>
			</div>
		</footer>

		<div class="fullscreen m-0 p-0">
			<div class="loader-bg m-0 p-0">
				<div class="loader m-0 p-0 mx-auto"></div>

				<!-- <h4 class="text-center">Feldolgozás folyamatban</h4> -->
			</div>
		</div>

		<!-- Script Section -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.13/js/mdb.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
		<script src="js/order.js"></script>
		<script src="plugins/notify/notify.js"></script>
	</body>
</html>