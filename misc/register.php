<?php
    require_once "connection.php3";
    require_once "util.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        $name = $_POST["reg-name"];
        $phonenumber = $_POST["reg-phone"];
        $password  = createPassword($_POST["reg-password"]);
        $address = $_POST["reg-address"];

        $secret = "6LcebnoUAAAAALXowQGyqfApugTvLJX2tpNkduuz";
        $captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$_POST["g-recaptcha-response"]}"));

        if ($captcha->success) {
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
            echo "captcha";
        }
    }
    else {
        header("Location: ../404.php"); 
    }

    $mysqli->close();
?>