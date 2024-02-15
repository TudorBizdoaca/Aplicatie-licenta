<?php
// Include the db_connection.php file to get the OpenCon and CloseCon functions
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$userId = $_POST['userId'];
	
    // Open the database connection
    $conn = OpenCon();

	// Your SQL query to delete the user
    $insertSgl = "INSERT INTO `users` (`id`, `first_name`, `last_name`, `vacation_days`, `taken_days`, `is_admin`, `username`, `password`, `manager_id`) VALUES ('0', '-', '-', '0', '0', '0', '-', '-', '0');";

    // Execute the query
    $result = $conn->query($insertSgl);
	
	// Close the database connection
    $conn->close();

    // Send a response to indicate success or failure (you can customize this)
    if ($result) {
        echo "Inserted successfully";
    } else {
        echo "Error inserting user: " . $conn->error;
    }
}
?>
