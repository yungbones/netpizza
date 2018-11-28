<?php
    require_once "connection.php3";
    require_once "password.php3";

    if ($_POST) {
        $password  = $_POST["password"];

        try {
            $result = $mysqli->query("SELECT * FROM accounts WHERE name='admin' AND adminuser='1'");

            if ($result) {
            	while ($rows = $result->fetch_assoc()) {
            		if (verifyPassword($rows["password"], $password)) {
                        $rows["password"] = "";
						setcookie("admin_user", json_encode(array("id" => $rows["id"], "user" => $rows["name"])), time() + 3600 * 12, "/");

            			echo "logged";
            		}
            		else {
            			echo "pw";
            		}
            	}
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    else {
        header("Location: ../404.php"); 
    }

    $mysqli->close();
?>