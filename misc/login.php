<?php
	session_start();

    require_once "connection.php3";
    require_once "password.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        $phonenumber = $_POST["phone"];
        $password  = $_POST["password"];

        $secret = "6LcebnoUAAAAALXowQGyqfApugTvLJX2tpNkduuz";
        $captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$_POST["g-recaptcha-response"]}"));

        if ($captcha->success) {
            if ($result = $mysqli->query("SELECT * FROM accounts WHERE phone='" . $phonenumber . "'")) {
            	while ($rows = $result->fetch_assoc()) {
            		if (verifyPassword($rows["password"], $password)) {
                        //$rows["password"] = ""; //dont save password to cookie (client side)
						//setcookie("json_userdata", json_encode(array("id" => $rows["id"], "user" => $rows["name"], "datas" => $rows)), time() + 3600, "/");

						$_SESSION["userdatas"] = array("id" => $rows["id"], "user" => $rows["name"], "datas" => $rows);

                        $mysqli->query("UPDATE accounts SET lastlogin=NOW() WHERE id=" . $rows["id"]);

            			echo "logged";
            		}
            		else {
            			echo "pw";
            		}
            	}
            }
            else {
            	echo "phone";
            }
        }
        else {
            echo "captcha";
        }
    }
    else {
        header("Location: ../404.php"); 
    }

    $mysqli->close();
?>