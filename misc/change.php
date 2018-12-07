<?php
    session_start();
    
    require_once "connection.php3";
    require_once "password.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        if ($_POST["type"] == "password") {
            if ($result = $mysqli->query("SELECT password FROM accounts WHERE id=" . $_SESSION["userdatas"]["id"])) {
                while ($rows = $result->fetch_assoc()) {
                    if (verifyPassword($rows["password"], $_POST["current"])) {
                        if ($change = $mysqli->query("UPDATE accounts SET password='" . createPassword($_POST["new"]) . "' WHERE id=" . $_SESSION["userdatas"]["id"]))
                            echo "updated";
                    }
                    else {
                        echo "old";
                    }
                }
            }
        }

        if ($_POST["type"] == "address") {
            if ($result = $mysqli->query("UPDATE accounts SET address='" . $_POST["new"] . "' WHERE id=" . $_SESSION["userdatas"]["id"])) {
                if ($account = $mysqli->query("SELECT * FROM accounts WHERE id=" . $_SESSION["userdatas"]["id"])) {
                    while ($rows = $account->fetch_assoc()) {
                        $_SESSION["userdatas"] = array("id" => $rows["id"], "user" => $rows["name"], "datas" => $rows);

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