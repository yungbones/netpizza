<?php
	session_start();

	require "misc/connection.php3";
	require "misc/util.php3";
	
	$datas = $_SESSION["userdatas"];
?>

<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<?php loadHeader("profile"); ?>

		<!-- recaptcha for login -->
		<script src="https://www.google.com/recaptcha/api.js?hl=hu" async defer></script>
	</head>

	<body class="bg-light">
		<!-- Navbar -->
		<?php loadNav("profile"); ?>

		<div class="container-fluid mx-auto pt-5 mb-5">
			<!-- Login / Register Card -->
			<?php if (!isset($_SESSION["userdatas"])) { ?>
				<div class="col-md-5 mx-auto">
					<form id="login-form">
						<div class="card">
							<h5 class="card-header primary-color white-text text-center py-4">
								<b>Bejelentkezés</b>
							</h5>

							<div class="card-body px-lg-5 pt-0">
								<div class="md-form mt-4">
									<input type="text" class="form-control" name="phone" id="phone">
									<label for="phone">Telefonszám</label>
								</div>

								<div class="md-form mt-4">
									<input type="password" class="form-control" name="password" id="password">
									<label for="password">Jelszó</label>
								</div>

								<div class="d-flex justify-content-around text-left">			
									<div>					
										<a href="javascript:void(0);">Elfelejtetted a jelszavad?</a>
									</div>
								</div>

								<button type="submit" data-sitekey="6LcebnoUAAAAAKQyfd1j6faxAh4FcfOlrW2FDPml" data-callback="submitForm" class="g-recaptcha btn-login btn btn-primary btn-block waves-effect mb-2 mt-5">Bejelentkezés</button>

								<a class="btn btn-success btn-block btn-sm waves-effect mb-4" onclick="changeTo('register');">Nincs még felhasználód?</a>
							</div>
						</div>
					</form>

					<form id="register-form" style="display: none;">
						<div class="card">
							<h5 class="card-header primary-color white-text text-center py-4">
								<b>Regisztráció</b>
							</h5>

							<div class="card-body px-lg-5 pt-0">
								<div class="md-form mt-4">
									<input type="text" class="form-control" name="reg-name" id="reg-name">
									<label for="reg-name">Név</label>
								</div>

								<div class="md-form">
									<input type="text" class="form-control" name="reg-phone" id="reg-phone">
									<label for="reg-phone">Telefonszám</label>
								</div>

								<div class="md-form">
									<input type="text" class="form-control" name="reg-address" id="reg-address">
									<label for="reg-address">Lakcím</label>
								</div>

								<div class="md-form">
									<input type="password" class="form-control" name="reg-password" id="reg-password">
									<label for="reg-password">Jelszó</label>
								</div>

								<div class="md-form">
									<input type="password" class="form-control" name="reg-password2" id="reg-password2">
									<label for="reg-password2">Jelszó újra</label>
								</div>
								
								<button type="submit" data-sitekey="6LcebnoUAAAAAKQyfd1j6faxAh4FcfOlrW2FDPml" data-callback="submitRegister" class="g-recaptcha btn-register btn btn-primary btn-block waves-effect mb-2 mt-5">Regisztráció</button>

								<a class="btn btn-success btn-block btn-sm waves-effect mb-4" onclick="changeTo('login');">Van már felhasználóm</a>
							</div>
						</div>
					</form>
				</div>
			<?php } ?>

			<!-- Profil -->
			<?php if (isset($_SESSION["userdatas"])) { ?>
				<div class="col-xl-10 mx-auto">
					<div class="row">
						<div class="col-xl-4 mb-5">
							<h3>Profil</h3>

							<!-- <div class="row col-md-6 mx-auto">
								<img src="images/unknown.png" class="w-100" style="height: 100%;">
							</div> -->	

							<?php 
								echo "<div class='row text-center d-block'><h5>" . $datas["user"] . "</h5></div>";

								if ($result = $mysqli->query("SELECT COUNT(*) as counted FROM orders WHERE userid=" . $datas["id"]))
									while ($rows = $result->fetch_assoc())
										echo $rows["counted"] > 0 ? "<div class='row d-block text-center'><p>Eddigi rendelések <span class='badge badge-pill primary-color'>" . $rows["counted"] . "</span></p></div>" : "<div class='row d-block text-center'><p>Nem volt még rögzített rendelésed</p></div>";
							?>

							<!-- <hr class="col-md-10 mt-4"> -->

							<div class="row col-md-10 mx-auto border-top border-primary pt-4">
								<ul class="pl-0" style="list-style: none;">
									<li id="myaddress">
										<i class="fas fa-map-marker-alt text-primary pr-3"></i> <?php echo $datas["datas"]["address"]; ?>
									</li>

									<li class="mt-2">
										<i class="fas fa-phone text-primary pr-3"></i> <?php echo formatPhoneNumber($datas["datas"]["phone"]); ?>
									</li>
								</ul>	
							</div>

							<div class="row col-md-10 mx-auto p-0">
								<button class="btn btn-outline-primary btn-sm btn-block waves-effect" data-toggle="modal" data-target="#modalPassword">Jelszó módosítása</button>
								<button class="btn btn-outline-primary btn-sm btn-block waves-effect mt-2" data-toggle="modal" data-target="#modalAddress">Lakcím módosítása</button>

								<button class="btn-logout btn btn-primary btn-sm btn-block waves-effect mt-4">Kijelentkezés</button>
							</div>
						</div>

						<div class="col-xl-8">
							<h3>Előző rendeléseim</h3>

							<ul class="list-group">								
								<?php 
									if ($result = $mysqli->query("SELECT id, time, status FROM orders WHERE userid=" . $datas["id"] . " ORDER BY id DESC"))
										while ($rows = $result->fetch_assoc())
											echo '<li class="list-group-item bg-light"><p class="float-left mt-2 pt-1 mb-0 pb-0">#' . $rows["id"] . ' - ' . (str_replace("-", ".", $rows["time"]) . ($rows["status"] == 0 ? " - <b>Folyamatban</b>" : "")) . '</p> <button class="btn btn-outline-primary btn-sm float-right">Számla nyomtatása</button></li>';
								?>
							</ul>
						</div>
					</div>	
				</div>
			<?php } ?>
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

		<!-- Modals -->
		<div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Jelszó módosítása</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<div class="md-form mt-1">
							<input type="password" class="form-control" name="password-current" id="password-current">
							<label for="password-current">Jelenlegi jelszó</label>
						</div>

						<div class="md-form">
							<input type="password" class="form-control" name="password-new" id="password-new">
							<label for="password-new">Új jelszó</label>
						</div>

						<div class="md-form">
							<input type="password" class="form-control" name="password-new2" id="password-new2">
							<label for="password-new2">Új jelszó újra</label>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn-changepassword btn btn-primary btn-sm">Módosítások mentése</button>
						<button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Bezár</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalAddress" tabindex="-2" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Lakcím módosítása</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<div class="md-form mt-1">
							<input type="text" class="form-control" name="newaddress" id="newaddress">
							<label for="newaddress">Új lakcím megadása</label>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn-changeaddress btn btn-primary btn-sm">Módosítások mentése</button>
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
		<script src="js/profile.js"></script>
		<script src="plugins/notify/notify.js"></script>
	</body>
</html>