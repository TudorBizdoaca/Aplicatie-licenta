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

$user_id = $_SESSION["user_id"];
$manager_id = $_SESSION["manager_id"];

// Fetch all vacation requests for the user from the vacation_requests table
$sqlVacationRequests = "SELECT * FROM vacation_requests WHERE user_id = '$user_id'";
$resultVacationRequests = $conn->query($sqlVacationRequests);

// Close the database connection
CloseCon($conn);
?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style_all_requests.css">
    </head>

    <body style="background-color:lightblue;">
        <div class="box">
            <label>Requests Status</label>
            <div class="table_block">
                <table>
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
                        // Display vacation requests in a table
                        while ($row = $resultVacationRequests->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . $row["start_date"] . " - " . $row["end_date"] . "</td>";
                           echo "<td>" . $row["status"] . "</td>";
                           echo "</tr>";
                       }
                       ?>
                    </tbody>
                </table>
            </div>        
			<?php
            $user_role = $_SESSION['user_role'] ?? '';
        
            // Conditionally set the link in the "Back" button based on the user's role
            if ($user_id == $manager_id) {
                $back_link = 'manag_page.php';
            } else {
                $back_link = 'user_page.php';
            }
            ?>
        
            <a href="<?php echo $back_link; ?>">
                <button type="button">Back</button>
            </a>
            </div>
    </body>
</html>