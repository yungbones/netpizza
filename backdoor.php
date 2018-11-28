<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<title>Backdoor - NetPizza</title>

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
		<link rel="stylesheet" href="css/backdoor.css">

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
		?>

		<?php if (!isset($_COOKIE["admin_user"])) { ?>
			<div class="col-md-5 logindiv">
				<form>
					<div class="mx-auto text-center mb-4">
					    <h3>Bejelentkezés</h3>

					    <p>Backdoor megtekintéséhez add meg a dolgozói jelszót</p>

					    <div class="md-form mt-1">
							<input type="password" class="form-control" name="password" id="password">
							<label for="password">Jelszó</label>
						</div>
				    </div>

			        <div class="mx-auto mb-4">
			        	<button class="btn-login btn btn-outline-primary btn-block">Bejelentkezés</button>
			        </div>
			    </form>
			</div>
		<?php } ?>

		<?php if (isset($_COOKIE["admin_user"])) { ?>
			<!-- Navbar -->
			<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark scrolling-navbar">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class="fas fa-bars"></i>
					</button>

					<logo><a class="logo navbar-brand text-primary" href="javascript:void(0);"><b>NetPizza - Backdoor</b></a></logo>

					<!-- Links -->
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
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

			<!-- Container -->
			<div class="container mt-4">
				<div class="row">
					<div class="col-xl-4 mr-0 mb-4">
						<ul class="list-group">
							<li data-holder="orders" class="bg-light list-group-item d-flex justify-content-between align-items-center">Mai rendelések
								<span class="badge badge-pill bg-primary">
									<?php 
										if ($result = $mysqli->query("SELECT COUNT(*) as counted FROM orders WHERE date=" . date("Ymd")))
											echo $result->fetch_assoc()["counted"];
									?>
								</span>
							</li>

							<li data-holder="addnote" class="bg-light list-group-item d-flex justify-content-between align-items-center">
								Kiadás feljegyzése <i class="fas fa-money-bill-wave"></i>
							</li>

							<li data-holder="orderprint" class="bg-light list-group-item d-flex justify-content-between align-items-center">
								Napi összesítés nyomtatása <i class="fas fa-print"></i>
							</li>

							<li data-holder="exit" class="bg-light list-group-item d-flex justify-content-between align-items-center text-danger">
								Kijelentkezés <i class="fas fa-sign-out-alt"></i>
							</li>
						</ul>
					</div>

					<div class="col-xl-8 ml-0" id="orders">
						<div id="accordion" class="mb-5">
							<div class="card bg-light">
								<?php 
									if ($result = $mysqli->query("SELECT orders.id as oid, orders.orderdatas as oorderdatas, orders.price as oprice, orders.userid as ouid, orders.status as ostatus, orders.time as otime, accounts.name as aname, accounts.phone as aphone, accounts.address as aadress FROM orders LEFT JOIN accounts ON accounts.id=orders.userid WHERE date=" . date("Ymd") . " ORDER BY orders.id DESC")) {
										$texts = array("30 cm", "50 cm");
										$colors = array("bg-warning", "bg-danger");

										while ($rows = $result->fetch_assoc()) {
											$text = "";
											foreach (json_decode($rows["oorderdatas"]) as $value)
												$text = $text . "<p class='mt-2 mb-1'><span class='badge badge-pill " . $colors[$value[1] - 1] . "'>" . $texts[$value[1] - 1] . "</span>" . ($value[3] > 1 ? ' (' . $value[3] . 'x)' : '') . " <b>" . $value[0] . "</b> - " . number_format($value[2]) . " Forint</p>";

											$text = $text . "<p class='mt-4'><i class='fas fa-phone-square'></i> Telefonszám: <b>" . phone_number_format($rows["aphone"]) . "</b></p><p class='mb-1'><i class='fas fa-money-bill-alt'></i> Összesen: <b>" . number_format($rows["oprice"]) . " Forint</b></p>";

											if ($rows["ostatus"] == 0)
												$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-success start-btn'><i class='fas fa-truck'></i> Futár elindult</button>";
											else if ($rows["ostatus"] == 1)
												$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-warning finish-btn'><i class='fas fa-hourglass-end'></i> Futár megérkezett</button>";
											else if ($rows["ostatus"] == 2)
												$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-dark' disabled><i class='fas fa-check-circle'></i> Rendelés teljesítve</button>";
											else if ($rows["ostatus"] == 3)
												$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-dark' disabled><i class='fas fa-exclamation-circle'></i> Rendelés törölve</button>";

											// if order not completed add delete button
											if ($rows["ostatus"] < 2)
												$button = $button . '<br id="br-' . $rows["oid"] . '"><button id="remove-' . $rows["oid"] . '" data-holder="' . $rows["oid"] . '" class="btn btn-sm btn-danger delete-btn mt-2"><i class="fas fa-times"></i> Sztornó</button>';

											echo '<div class="card-header" data-toggle="collapse" data-target="#collapse-' . $rows["oid"] . '">
												<h5 class="mb-0"><b>#' . $rows["oid"] . '</b> - Rögzített rendelés <b>' . $rows["aname"] . '</b> névre <b>(' . $rows["aadress"] . ')</b></h5>

												<a class="font-small"><i class="fa fa-clock"></i> ' . $rows["otime"] . '</a>
											</div>

											<div id="collapse-' . $rows["oid"] . '" class="collapse">
												<div class="card-body">
													<div class="row">
														<div class="col-md-6">' . $text . '</div>

														<div class="col-md-6">' . $button . '</div>
													</div>
												</div>
											</div>';
										}
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Footer -->
			<!-- <footer class="page-footer font-small bg-dark darken-3">
				<!/-- <div class="container">
					<div class="row">
						<div class="col-md-12 py-2">
							<div class="mb-5 flex-center mx-auto">

							</div>
						</div>
					</div>
				</div> --/>

				<div class="footer-copyright text-center py-3">
					© 2018 Copyright: <a href="https://github.com/yungbones"> Lovász Bence</a>
				</div>
			</footer> -->
		<?php } ?>

		<!-- Script Section -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.13/js/mdb.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
		<script src="js/backdoor.js"></script>
		<script src="plugins/notify/notify.js"></script>
	</body>
</html>