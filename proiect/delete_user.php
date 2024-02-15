<?php
// Include the db_connection.php file to get the OpenCon and CloseCon functions
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user ID from the AJAX request
    $userId = $_POST['userId'];

    // Open the database connection
    $conn = OpenCon();

	// Your SQL query to delete the user
    $deleteSql_vac = "DELETE FROM vacation_requests WHERE user_id = $userId";

    // Execute the query
    $result_vac = $conn->query($deleteSql_vac);
	
	if ($result_vac) {
        echo "Vac deleted successfully";
    } else {
        echo "Error deleting vac: " . $conn->error;
    }
	
    // Your SQL query to delete the user
    $deleteSql = "DELETE FROM users WHERE id = $userId";

    // Execute the query
    $result = $conn->query($deleteSql);

    // Close the database connection
    $conn->close();

    // Send a response to indicate success or failure (you can customize this)
    if ($result) {
        echo "User deleted successfully";
    } else {
        echo "delete.php Error deleting user: " . $conn->error;
    }
}
?>
