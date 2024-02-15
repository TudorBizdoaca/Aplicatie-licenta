<?php
// Include the db_connection.php file to get the OpenCon and CloseCon functions
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user ID and updated values from the AJAX request
    $userId = $_POST['userId'];
    $updatedFirstName = $_POST['updatedFirstName'];
    $updatedLastName = $_POST['updatedLastName'];
    $updatedVacationDays = $_POST['updatedVacationDays'];
    $updatedTakenDays = $_POST['updatedTakenDays'];
    $updatedIsAdmin = $_POST['updatedIsAdmin'];
    $updatedUsername = $_POST['updatedUsername'];
    $updatedPassword = $_POST['updatedPassword'];
    $updatedManagerId = $_POST['updatedManagerId'];

    // Open the database connection
    $conn = OpenCon();

    // Your SQL query to update the user
    $updateSql = "UPDATE users SET
        first_name = '$updatedFirstName',
        last_name = '$updatedLastName',
        vacation_days = '$updatedVacationDays',
        taken_days = '$updatedTakenDays',
        is_admin = '$updatedIsAdmin',
        username = '$updatedUsername',
        password = '$updatedPassword',
        manager_id = '$updatedManagerId'
        WHERE id = $userId";

    // Execute the query
    $result = $conn->query($updateSql);

    // Close the database connection
    $conn->close();

    // Send a response to indicate success or failure
    if ($result) {
        echo "User updated successfully";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>
