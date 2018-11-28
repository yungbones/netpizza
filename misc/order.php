<?php
    require_once "connection.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        $order = array();
        $price = 0;

        foreach ($_POST["order"] as $key => $value) {
            array_push($order, array($value["name"], $value["type"], $value["price"], $value["count"]));
            $price += $value["price"];
        }
        
        $order = json_encode($order);
        //echo var_dump($order);

        try {
            if ($result = $mysqli->query("INSERT INTO orders SET orderdatas='" . $order . "', price=" . $price . ", userid=" . $_POST["user"] . ", time=NOW(), date='" . date("Ymd") . "'"))
                echo "success";
            else
                echo "fail";
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