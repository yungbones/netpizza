<?php
    require_once "connection.php3";
    require_once "password.php3";

    if ($_POST) {
        $name = $_POST["name"];
        $phonenumber = $_POST["phone"];
        $password  = createPassword($_POST["password"]);
        $address = $_POST["address"];

        try {
            if ($mysqli->query("SELECT phone FROM accounts WHERE phone='" . $phonenumber . "'")->num_rows == 0)
                if ($result = $mysqli->query("INSERT INTO accounts (name, phone, password, address, regdate, lastlogin) VALUES('" . $name . "', '" . $phonenumber . "', '" . $password . "', '" . $address . "', NOW(), NOW())")) {
                    echo "registered";
                }
                else
                    echo "fail";
            else
                echo "phone";
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