<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: main_page.php");
    exit();
}

$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$manager_id = $_SESSION["manager_id"];
$user_id = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style_user_page.css">
    </script>
    </head>
    <body style="background-color: lightblue;">
        <div class="box">
	    <a href="request_page.php" class="item">
                <button type="button">Request Vacation</button>
	    </a>
	    <a href="all_requests.php" class="item">
                <button type="button">All requests</button>
	    </a>
	    <a href="main_page.php" class="item">
                <button type="button">Sign out</button>
	    </a>
        </div>
    </body>
</html>