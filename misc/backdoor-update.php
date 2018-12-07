<?php
    require_once "connection.php3";

    if ($_POST) {
        //echo var_dump($_POST);

        $function = $_POST["func"];

        try {
            if ($function == "update") {
                $id = $_POST["id"];
                $status = $_POST["newvalue"];

                if ($status >= 2)
                    $queryHandler = "UPDATE orders SET status=" . $status . ", finished=NOW() WHERE id=" . $id;
                else
                    $queryHandler = "UPDATE orders SET status=" . $status . " WHERE id=" . $id;

                if ($result = $mysqli->query($queryHandler))
                    echo "updated";
                else
                    echo "failed";
            }
            elseif ($function == "insert_p") {
                $value = $_POST["value"];
                $desc = $_POST["desc"];

                if ($result = $mysqli->query("INSERT INTO payments SET value=" . intval($value) . ", description='" . $desc . "', time=NOW()"))
                    echo "success";
                else
                    echo "error";
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