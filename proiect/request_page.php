<?php
session_start();

// Include the db_connection.php file to get the OpenCon and CloseCon functions
include 'db_connection.php';

// Open the database connection
$conn = OpenCon();

if (!isset($_SESSION["user_id"])) {
    header("Location: main_page.php");
    exit();
}

$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$user_id = $_SESSION["user_id"];
$manager_id = $_SESSION["manager_id"];

$sqlVacationDays = "SELECT vacation_days FROM users WHERE id = '$user_id'";
$resultVacationDays = $conn->query($sqlVacationDays);

if ($resultVacationDays->num_rows > 0) {
    $rowVacationDays = $resultVacationDays->fetch_assoc();
    $vacation_days_left = $rowVacationDays['vacation_days'];
} else {
    $vacation_days_left = "N/A";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Escape user input for security
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);

    $d1 = new DateTime($start_date);
    $d2 = new DateTime($end_date);
    $working_days = 0;
    $weekday = null;
    $result_weekday = null; 

    while ($d1 <= $d2)
    {
	$formatted_date = $d1->format('Y-m-d');
	$weekday = date('w', strtotime($formatted_date));

        $query = "SELECT COUNT(*) AS freeday FROM zilelibere2024 WHERE Data='$formatted_date'";
	$result_freeday = mysqli_query($conn, $query);
	$row_freeday = mysqli_fetch_assoc($result_freeday);
        $freeday = $row_freeday['freeday'];
    
        if ($weekday != 6 && $weekday != 0 && $freeday == 0)
        {
            $working_days++;
        }
        $d1->modify('+1 day');
    }

    // Execute the SQL query
    $result = mysqli_query($conn, $query);

    // Check if the query was executed successfully
    if (!$result) {
        die("Error executing the query: " . mysqli_error($connection));
    }

    // Fetch the result
    $row = mysqli_fetch_assoc($result);

    // Prepare and execute SQL statement to insert the request into the database
    $sql = "INSERT INTO vacation_requests (user_id, manager_id, start_date, end_date, status, days_taken) VALUES ('".$_SESSION["user_id"]."', '$manager_id', '$start_date', '$end_date', 'In asteptare', $working_days)";
    echo "SQL Query: $sql";

    if ($conn->query($sql) === TRUE ) {
		if ($manager_id === $user_id) {
			header("Location: manag_page.php");
		} else {
			header("Location: user_page.php");
		}
		exit();
	}
	else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    CloseCon($conn);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style_request_page.css">
    </head>

    <body style="background-color:lightblue;">
        <div class="box">
            <form action="" class="form" method="post">
                <div>
                    <label for="left_days">Vacation days left: <?php echo $vacation_days_left; ?></label>
                </div>

                <div>
                    <label for="from" >From</label>
                    <input type="date" id="start_date" name="start_date">
                </div>

                <div>
                    <label for="to">To</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
			<?php
					// Assuming you have a session variable 'user_role' that indicates whether the user is a manager or not
					$user_role = $_SESSION['user_role'] ?? '';
				
					// Conditionally set the link in the "Back" button based on the user's role
					if ($user_id == $manager_id) {
						$back_link = 'manag_page.php';
					} else {
						$back_link = 'user_page.php';
					}
			?>
            <div class="buttons">
				<input type="submit" value="Submit">			
					<a href="<?php echo $back_link; ?>" style="text-decoration: none;">
						<button type="button">Back</button>
					</a>
			</div>
			</form>
        </div>
    </body>
</html>
