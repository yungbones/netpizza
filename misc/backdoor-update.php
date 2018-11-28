<?php
    require_once "connection.php3";

    if ($_POST) {
        $id = $_POST["id"];
        $status = $_POST["newvalue"];

        try {
            if ($status >= 2)
                $queryHandler = "UPDATE orders SET status=" . $status . ", finished=NOW() WHERE id=" . $id;
            else
                $queryHandler = "UPDATE orders SET status=" . $status . " WHERE id=" . $id;

            if ($result = $mysqli->query($queryHandler))
                echo "updated";
            else
                echo "failed";
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