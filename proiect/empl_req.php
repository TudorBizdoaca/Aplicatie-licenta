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



// Your SQL query
$sql = "SELECT users.first_name, users.last_name, vacation_requests.start_date, vacation_requests.end_date, vacation_requests.status, vacation_requests.id
        FROM vacation_requests
        JOIN users ON vacation_requests.user_id = users.id
        WHERE vacation_requests.status LIKE 'In asteptare' AND users.manager_id = $user_id
		ORDER BY vacation_requests.start_date DESC"; 

// Execute the query
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vacationId = $_POST['vacation_id'];

    if (isset($_POST['accept'])) {
        // Handle Accept button click
        $requestDataSql = "SELECT user_id, days_taken FROM vacation_requests WHERE id = $vacationId";
        $resultRequestData = $conn->query($requestDataSql);

        if ($resultRequestData->num_rows > 0) {
            $rowRequestData = $resultRequestData->fetch_assoc();
            $requestUserId = $rowRequestData['user_id'];
            $daysTaken = $rowRequestData['days_taken'];

            $newStatus = 'Accepted';
            $updateSql = "UPDATE vacation_requests SET status = '$newStatus' WHERE id = $vacationId";
            $result = $conn->query($updateSql);

            // Update users table with taken_days and subtract from vacation_days
            $updateUsersSql = "UPDATE users SET 
                                taken_days = taken_days + $daysTaken,
                                vacation_days = vacation_days - $daysTaken
                              WHERE id = $requestUserId";

            $resultUsers = $conn->query($updateUsersSql);

            header("Location: empl_req.php");
            exit();
        } else {
            echo "Error: Could not fetch vacation request data.";
        }
    } elseif (isset($_POST['decline'])) {
        // Handle Decline button click
        $newStatus = 'Declined';
        $updateSql = "UPDATE vacation_requests SET status = '$newStatus' WHERE id = $vacationId";
        $result = $conn->query($updateSql);

        header("Location: empl_req.php");
        exit();
    }
}


// Check if the query was successful
if ($result) {
	?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" href="style_empl_req.css">
    </head>
    <body style="background-color: lightblue;">
        <div class="box">
            <label>Status Cereri</label>
            <div class="table_block">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the results
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>{$row['first_name']} {$row['last_name']}</td>
                            <td>{$row['start_date']} - {$row['end_date']}</td>
                            <td class='status'>
                                <form method='post' action=''>
									<input type='hidden' name='vacation_id' value='{$row['id']}'>
                                    <button type='submit' name='accept' class='cell_but'>Accept</button>
                                    <button type='submit' name='decline' class='cell_but'>Decline</button>
                                </form>
                            </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="manag_page.php" >
                <button type="button" class="bottom">Back</button>
            </a>
        </div>
    </body>
    </html>
    <?php
} else {
    // Display an error message if the query fails
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
