<?php
// Include the db_connection.php file to get the OpenCon and CloseCon functions
include 'db_connection.php';

// Open the database connection
$conn = OpenCon();

// Your SQL query to fetch all user data
$sql = "SELECT * FROM users";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style_tables.css">
</head>

<body style="background-color: lightblue;">

    <div class="box">
        <label>Utilizatori</label>
        <div class="table_block">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Vacation days</th>
                        <th>Taken days</th>
                        <th>Is Admin</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Manager</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if the query was successful
                    if ($result) {
                        // Loop through the results and populate the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr id='row_{$row['id']}'>
                                <td><input type='text' id='ID' name='ID' value='{$row['id']}'></td>
                                <td><input type='text' id='fname' name='fname' value='{$row['first_name']}'></td>
                                <td><input type='text' id='lname' name='lname' value='{$row['last_name']}'></td>
                                <td><input type='text' id='vac_days' name='vac_days' value='{$row['vacation_days']}'></td>
                                <td><input type='text' id='taken_days' name='taken_days' value='{$row['taken_days']}'></td>
                                <td><input type='text' id='is_admin' name='is_admin' value='{$row['is_admin']}'></td>
                                <td><input type='text' id='usr' name='usr' value='{$row['username']}'></td>
                                <td><input type='text' id='pass' name='pass' value='{$row['password']}'></td>
                                <td><input type='text' id='manag' name='manag' value='{$row['manager_id']}'></td>
                                <td class='status'>
                                    <button type='button' class='cell_but' onclick='updateUser({$row['id']})'>Update</button>
                                    <button type='button' class='cell_but' onclick='deleteUser({$row['id']})'>Delete</button>
                                </td>
                            </tr>";
                        }
						echo "<tr>
                            <td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
                            <td class='status' >
                                <button type='button' class='cell_but' onclick='addUser(1)'>Add user</button>
                            </td>
                        </tr>";
                    } else {
                        // Display an error message if the query fails
                        echo "Error: " . $conn->error;
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <a href="admin_page.php" class="btn">
            <button type="button" class="bottom">Back</button>
        </a>
    </div>

    <!-- JavaScript function to handle delete confirmation and make AJAX request -->
    <script>
		function addUser(userId) {
			var xhr = new XMLHttpRequest();
            xhr.open("POST", "add_user.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle the response, e.g., refresh the table
                    alert("User added!");
                    location.reload(); // Reload the page to reflect changes
                }
            }
			xhr.send("userId=" + userId);
		}
        function deleteUser(userId) {
            var confirmDelete = confirm("Are you sure you want to delete this user?");
            if (confirmDelete) {
                // Make an AJAX request to delete_user.php with the user ID
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_user.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Handle the response, e.g., refresh the table
                        alert("User deleted! UserID: " + userId);
                        location.reload(); // Reload the page to reflect changes
                    } 
                };
                xhr.send("userId=" + userId);
            }
        }

        function updateUser(userId) {
			// Get the parent <tr> element of the clicked button
			var row = document.getElementById('row_' + userId);

			// Collect the updated values from the input fields within the row
			var updatedFirstName = encodeURIComponent(row.querySelector('[name="fname"]').value);
			var updatedLastName = encodeURIComponent(row.querySelector('[name="lname"]').value);
			var updatedVacationDays = encodeURIComponent(row.querySelector('[name="vac_days"]').value);
			var updatedTakenDays = encodeURIComponent(row.querySelector('[name="taken_days"]').value);
			var updatedIsAdmin = encodeURIComponent(row.querySelector('[name="is_admin"]').value);
			var updatedUsername = encodeURIComponent(row.querySelector('[name="usr"]').value);
			var updatedPassword = encodeURIComponent(row.querySelector('[name="pass"]').value);
			var updatedManagerId = encodeURIComponent(row.querySelector('[name="manag"]').value);

			// Prepare the request payload
			var data = new URLSearchParams();
			data.append('userId', userId);
			data.append('updatedFirstName', updatedFirstName);
			data.append('updatedLastName', updatedLastName);
			data.append('updatedVacationDays', updatedVacationDays);
			data.append('updatedTakenDays', updatedTakenDays);
			data.append('updatedIsAdmin', updatedIsAdmin);
			data.append('updatedUsername', updatedUsername);
			data.append('updatedPassword', updatedPassword);
			data.append('updatedManagerId', updatedManagerId);

			// Make an AJAX request to update_user.php with the user ID and updated values using Fetch API
			fetch('update_user.php', {
				method: 'POST',
				headers: {
					'Content-type': 'application/x-www-form-urlencoded'
				},
				body: data
			})
			.then(response => response.text())
			.then(responseText => {
				// Handle the response, e.g., show a success message
				alert("User updated! UserID: " + userId);
				location.reload(); // Reload the page to reflect changes
			})
			.catch(error => {
				// Handle errors, e.g., show an error message
				alert("Error updating user: " + error.message);
			});
		}



    </script>

</body>

</html>
