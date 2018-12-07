<?php
	require "connection.php3";
	require "util.php3";

	if ($result = $mysqli->query("SELECT orders.id as oid, orders.orderdatas as oorderdatas, orders.price as oprice, orders.userid as ouid, orders.status as ostatus, orders.time as otime, accounts.name as aname, accounts.phone as aphone, accounts.address as aadress FROM orders LEFT JOIN accounts ON accounts.id=orders.userid WHERE date=" . date("Ymd") . " ORDER BY orders.id DESC")) {
		$texts = array("30 cm", "50 cm");
		$colors = array("bg-warning", "bg-danger");

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
			if ($rows["ostatus"] < 2)
				$button = $button . '<br id="br-' . $rows["oid"] . '"><button id="remove-' . $rows["oid"] . '" data-holder="' . $rows["oid"] . '" class="btn btn-sm btn-danger delete-btn mt-2"><i class="fas fa-times"></i> Sztornó</button>';

			echo '<div class="card bg-light mb-3">
				<div class="card-header" data-toggle="collapse" data-target="#collapse-' . $rows["oid"] . '">
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
				</div>
			</div>';
		}
	}
?>