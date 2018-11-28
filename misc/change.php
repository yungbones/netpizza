<?php
    require_once "connection.php3";
    require_once "password.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        if ($_POST["type"] == "password") {
            if ($result = $mysqli->query("SELECT password FROM accounts WHERE id=" . intval($_POST["id"]))) {
                while ($rows = $result->fetch_assoc()) {
                    if (verifyPassword($rows["password"], $_POST["current"])) {
                        if ($change = $mysqli->query("UPDATE accounts SET password='" . createPassword($_POST["new"]) . "' WHERE id=" . $_POST["id"]))
                            echo "updated";
                    }
                    else {
                        echo "old";
                    }
                }
            }
        }

        if ($_POST["type"] == "address") {
            if ($result = $mysqli->query("UPDATE accounts SET address='" . $_POST["new"] . "' WHERE id=" . intval($_POST["id"]))) {
                if ($account = $mysqli->query("SELECT * FROM accounts WHERE id=" . intval($_POST["id"]))) {
                    while ($rows = $account->fetch_assoc()) {
                        $rows["password"] = ""; //dont save password to cookie (client side)
                        setcookie("json_userdata", json_encode(array("id" => $rows["id"], "user" => $rows["name"], "datas" => $rows)), time() + 3600, "/");

                        echo "updated";
                    }
                }
            }
        }
    }
    else {
        header("Location: ../404.php"); 
    }

    $mysqli->close();
?>