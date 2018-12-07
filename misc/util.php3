<?php 
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
		return (date("H") >= 10 && date("H") < 22);
	}
?>