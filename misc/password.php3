<?php
	function generateRandomString($length = 8) {
		return substr(str_shuffle(str_repeat($x = "0123456789\/?_-.|abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", ceil($length / strlen($x)) )), 1, $length);
	}

	function createPassword($password) {
		$salt = generateRandomString();
		return "$" . $salt . "$" . hash("sha512", "sorosgyorgy" . hash("md5", $salt) . hash("md5", $password));
	}

	function verifyPassword($database, $password) {
		return $database == ("$" . explode("$", $database)[1] . "$" . hash("sha512", "sorosgyorgy" . hash("md5", explode("$", $database)[1]) . hash("md5", $password)));
	}
?>