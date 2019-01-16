<?php
	function generateRandomString($length = 8) {
		return substr(str_shuffle(str_repeat($x = "0123456789\/?_-.|abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", ceil($length / strlen($x)))), 1, $length);
	}

	function createPassword($password) {
		$salt = generateRandomString();
		return "$" . $salt . "$" . hash("sha512", "sorosgyorgy" . hash("md5", $salt) . hash("md5", $password));
	}

	function verifyPassword($database, $password) {
		return $database == ("$" . explode("$", $database)[1] . "$" . hash("sha512", "sorosgyorgy" . hash("md5", explode("$", $database)[1]) . hash("md5", $password)));
	}

	function formatPhoneNumber($number) {
		$number = preg_replace("/[^\d]/", "", $number);

		if (strlen($number) == 11)
			$number = preg_replace("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", "$1 $2 $3 $4", $number);

		return $number;
	}

	function dataFilter($data) {
		$data = trim($data);
		$data = htmlentities($data);
		$data = strip_tags($data);

		str_replace(array("\\", "\0", "\n", "\r", "'", '"', "\xla"), array("\\\\", "\\0", "\\n", "\\r", "\\'", '\\"', "\\Z"), $data);

		if (get_magic_quotes_gpc())
			$data = stripcslashes($data);

		return $data;
	}

	function getShopStatus() {
		//return true;
		return (date("H") >= 10 && date("H") < 22);
	}

	function loadHeader($page) {
		echo '<title>' . ($page == "backdoor" ? "Backdoor - " : "") . 'NetPizza</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<meta name="theme-color" content="#1d1e1e">
		<meta name="author" content="Lovász Bence">

		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.13/css/mdb.min.css">
		<link rel="stylesheet" href="plugins/notify/notify.css">

		<link rel="stylesheet" href="css/maintenance.css">
		<link rel="stylesheet" href="css/' . $page . '.css">

		<!-- Google Analytics -->
		<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131898015-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag("js", new Date());

			gtag("config", "UA-131898015-1");
		</script> -->

		<!-- Cookies (eu law) -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
		<script>
			window.addEventListener("load", function() {
				window.cookieconsent.initialise({
					"palette": {
						"popup": {
							"background": "#1d1e1e"
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
		</script>';
	}

	function loadNav($page) {
		echo '<nav class="navbar ' . ($page == "index" ? "fixed-top" : "sticky-top bg-dark") . ' navbar-expand-lg navbar-dark scrolling-navbar">
			<div class="container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-bars"></i>
				</button>

				<logo><a class="logo navbar-brand text-primary" href="javascript:void(0);"><b>NetPizza</b></a></logo>

				<!-- Links -->
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item ' . ($page == "index" ? "active" : "") . '">
							<a class="nav-link" href="index.php">Főoldal</a>
						</li>

						<li class="nav-item ' . ($page == "profile" ? "active" : "") . '">
							<a class="nav-link" href="profile.php">Profil</a>
						</li>

						<li class="nav-item ' . ($page == "order" ? "active" : "") . '">
							<a class="nav-link" href="order.php">Rendelés</a>
						</li>
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
		</nav>';
	}
?>