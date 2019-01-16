<?php
	require "misc/connection.php3";
	require "misc/util.php3";
?>

<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<?php loadHeader("backdoor"); ?>
	</head>

	<body class="bg-light">
		<?php if (!isset($_COOKIE["admin_user"])) { ?>
			<div class="col-md-5 logindiv">
				<form>
					<div class="mx-auto text-center mb-4">
					    <h3>Bejelentkezés</h3>

					    <p>A <b>backdoor</b> megtekintéséhez add meg a dolgozói jelszót</p>

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
								<a href="https://facebook.com/bence444" target="_blank" class="nav-link"><i class="fab fa-facebook text-primary"></i></a>
							</li>
		
							<li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="yung_bones__">
								<a href="https://instagram.com/yung_bones__" target="_blank" class="nav-link"><i class="fab fa-instagram"></i></a>
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
								<span class="badge badge-pill bg-primary counter">
									<?php 
										if ($result = $mysqli->query("SELECT COUNT(*) as counted FROM orders WHERE date=" . date("Ymd")))
											echo $result->fetch_assoc()["counted"];
									?>
								</span>
							</li>

							<li data-toggle="modal" data-target="#modalPayment" class="bg-light list-group-item d-flex justify-content-between align-items-center">
								Kiadás feljegyzése <i class="fas fa-money-bill-wave"></i>
							</li>

							<li data-holder="print" class="bg-light list-group-item d-flex justify-content-between align-items-center">
								Napi összesítés nyomtatása <i class="fas fa-print"></i>
							</li>

							<li data-holder="exit" class="bg-light list-group-item d-flex justify-content-between align-items-center text-danger">
								Kijelentkezés <i class="fas fa-sign-out-alt"></i>
							</li>
						</ul>
					</div>

					<div class="col-xl-8 ml-0">
						<div id="accordion" class="mb-5">
							<?php
								if ($result = $mysqli->query("SELECT orders.id as oid, orders.orderdatas as oorderdatas, orders.price as oprice, orders.userid as ouid, orders.status as ostatus, orders.time as otime, accounts.name as aname, accounts.phone as aphone, accounts.address as aadress FROM orders LEFT JOIN accounts ON accounts.id=orders.userid WHERE date=" . date("Ymd") . " ORDER BY orders.id DESC")) {
									$texts = array("30 cm", "50 cm");
									$colors = array("bg-warning", "bg-danger");
									$icons = array("far fa-check-circle text-success", "far fa-times-circle text-danger");

									while ($rows = $result->fetch_assoc()) {
										$text = "";
										foreach (json_decode($rows["oorderdatas"]) as $value)
											$text = $text . "<p class='mt-2 mb-1'><span class='badge badge-pill " . $colors[$value[1] - 1] . "'>" . $texts[$value[1] - 1] . "</span>" . ($value[3] > 1 ? ' (' . $value[3] . 'x)' : '') . " <b>" . $value[0] . "</b> - " . number_format($value[2]) . " Forint</p>";

										$text = $text . "<p class='mt-4'><i class='fas fa-phone-square'></i> Telefonszám: <b>" . formatPhoneNumber($rows["aphone"]) . "</b></p><p class='mb-1'><i class='fas fa-money-bill-alt'></i> Összesen: <b>" . number_format($rows["oprice"]) . " Forint</b></p>";

										if ($rows["ostatus"] == 0)
											$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-success start-btn'><i class='fas fa-truck'></i> Futár elindult</button>";
										else if ($rows["ostatus"] == 1)
											$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-warning finish-btn'><i class='fas fa-hourglass-end'></i> Futár megérkezett</button>";
										else if ($rows["ostatus"] == 2)
											$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-dark' disabled><i class='fas fa-check-circle'></i> Rendelés teljesítve</button>";
										else if ($rows["ostatus"] == 3)
											$button = "<button data-holder='" . $rows["oid"] . "' class='btn btn-sm btn-dark' disabled><i class='fas fa-exclamation-circle'></i> Rendelés törölve</button>";

										//if order not completed add delete button
										$icon = "";
										if ($rows["ostatus"] < 2)
											$button = $button . '<br id="br-' . $rows["oid"] . '"><button id="remove-' . $rows["oid"] . '" data-holder="' . $rows["oid"] . '" class="btn btn-sm btn-danger delete-btn mt-2"><i class="fas fa-times"></i> Sztornó</button>';
										else
											$icon = '<i class="' . $icons[$rows["ostatus"] - 2] . '"></i>';

										echo '<div class="card bg-light mb-3">
											<div class="card-header" id="order-' . $rows["oid"] . '" data-toggle="collapse" data-target="#collapse-' . $rows["oid"] . '">
												<h5 class="mb-0"><b>' . ($rows["ostatus"] < 2 ? "#" . $rows["oid"] : $icon) . '</b> - Rögzített rendelés <b>' . $rows["aname"] . '</b> névre <b>(' . $rows["aadress"] . ')</b></h5>

												' . ($rows["ostatus"] < 2 ? "<a class=\"font-small\"><i class=\"fa fa-clock\"></i> " . $rows["otime"] . "</a>" : "") . '
											</div>

											<div id="collapse-' . $rows["oid"] . '" class="collapse">
												<div class="card-body">
													<div class="row">
														<div class="col-md-6">' . $text . '</div>

														<div class="col-md-6">' . $button . '</div>
													</div>
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
		<?php } ?>

		<!-- Modals -->
		<div class="modal fade" id="modalPayment" tabindex="-2" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Kiadás feljegyzése</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<div class="md-form mt-1">
							<input type="text" class="form-control" name="value" id="value">
							<label for="value">Összeg</label>
						</div>

						<div class="md-form">
							<input type="text" class="form-control" name="desc" id="desc">
							<label for="desc">Rövid leírás</label>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-primary btn-sm btn-addpayment">Rögzítés</button>
						<button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Bezár</button>
					</div>
				</div>
			</div>
		</div>

		<?php $mysqli->close(); ?>

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