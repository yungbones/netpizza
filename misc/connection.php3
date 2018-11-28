<?php 
	$mysqli = new mysqli("localhost", "lovaszb", "Kurvaanyad666", "lovaszb");

	if ($mysqli->connect_errno)
		die("<div class='maintenance'>Az oldal karbantartás alatt van</div>");

	$result = $mysqli->query("SELECT * FROM sitedatas WHERE id=1");
	if ($result)
		while ($rows = $result->fetch_assoc())
			if ($rows["maintenance"] == 1)
				die("<div class='maintenance'>Az oldal karbantartás alatt van</div>");
?>