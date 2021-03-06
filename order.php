<?php
	session_start();

	require "misc/util.php3";

	if (isset($_SESSION["userdatas"]))
		$datas = $_SESSION["userdatas"];
	else
		header("Location: profile.php");
?>

<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<?php loadHeader("order"); ?>
	</head>

	<body class="bg-light">
		<!-- Navbar -->
		<?php loadNav("order"); ?>

		<div class="container-fluid mx-auto pt-5 mb-5">
			<div class="col-xl-10 row mx-auto">
				<div class="col-md-8">
					<h3>Kínálatunk</h3>

					<?php 
						if (!getShopStatus())
							echo '<div class="alert alert-primary border-primary">
								Üzletünk jelenleg zárva van, így nem adhatsz le rendelést!
							</div>';
					?>

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
											<p class="mt-2 mb-1 ml-1"><span class="badge badge-pill bg-warning p-1 pl-2 pr-2">30 cm</span> - <b>' . number_format($price) . ' Forint</b><button data-holder="' . $value[0] . '" data-type="1" data-price="' . $price . '" class="btn-add btn btn-outline-primary btn-sm mr-2 float-right ' . (getShopStatus() ? "" : "disabled") . '"><i class="fas fa-cart-plus"></i> Kosárba</button></p>
											
											<p class="mt-3 mb-1 ml-1"><span class="badge badge-pill bg-danger p-1 pl-2 pr-2">50 cm</span> - <b>' . number_format(floor($price * 2.45)) . ' Forint</b><button data-holder="' . $value[0] . '" data-type="2" data-price="' . floor($price * 2.45) . '" class="btn-add btn btn-outline-primary btn-sm mr-2 float-right ' . (getShopStatus() ? "" : "disabled") . '"><i class="fas fa-cart-plus"></i> Kosárba</button></p>
										</div>
									</div>';
								}
							?>
						</div>
					</div>
				</div>

				<div class="col-md-4 order-first order-md-2 mb-4">
					<h3>Kosár</h3>

					<ul class="pl-0 pb-3 mb-3 border-bottom border-secondary" id="orders" style="list-style: none;">
						<li>Kosár tartalma jelenleg üres</li>
					</ul>

					<p id="total">Összesen: <b>0 Forint</b></p>

					<button class="btn btn-sm btn-block btn-outline-primary mb-2 clearorders disabled"><i class="fas fa-trash-alt"></i> Kosár ürítése</button>

					<button class="btn btn-sm btn-block btn-primary completeorder disabled">Rendelés elküldése <i class="fas fa-angle-double-right"></i></button>
				</div>
			</div>
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

				<h4 class="text-center text-white mt-4">Feldolgozás folyamatban...</h4>
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