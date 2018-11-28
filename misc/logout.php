<?php
    if ($_POST) {
		setcookie("json_userdata", "", time() - 3600, "/");
		setcookie("admin_user", "", time() - 3600, "/");
		
        echo "success";
    }
    else {
        header("Location: ../404.php"); 
    }
?>