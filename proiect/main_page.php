<?php
session_start();

include 'db_connection.php';
$conn = OpenCon();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user credentials
    $sql = "SELECT id, first_name, last_name, is_admin, manager_id FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["first_name"] = $row["first_name"];
        $_SESSION["last_name"] = $row["last_name"];
 	$_SESSION["manager_id"] = $row["manager_id"];

        if ($row["is_admin"] == 1) {
            header("Location: admin_page.php");
        } else if($row["manager_id"] == $row["id"]){
            header("Location: manag_page.php");
        } else {
	    header("Location: user_page.php");
	}
        exit();
    } else {
        $error = "Incorrect username or password.";
    }
}

CloseCon($conn);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style_main_page.css">
    </head>

    <body style="background-color:lightblue;">
        <div class="box">
            <form method="post" action="">
                <br><br>
                <input type="text" class="input_box" placeholder="username" name="username" id="username"><br>
                <input type="password" class="input_box" placeholder="password" name="password" id="password"><br><br>
                <input type="submit" value="Sign-in">
            </form>
	<?php
    	if ($error) {
        	echo "<p>Error: $error</p>";
   	}
    	?>
        </div>
    </body>
</html>