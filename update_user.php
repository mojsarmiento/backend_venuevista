<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // your username
$password = ""; // your password
$dbname = "venue_management"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the necessary data is provided
if (isset($data['user_id']) && isset($data['full_name']) && isset($data['email']) && isset($data['user_type'])) {
    // Sanitize the input data
    $userId = $data['user_id'];
    $fullName = $data['full_name'];
    $email = $data['email'];
    $userType = $data['user_type'];

    // Prepare the SQL update statement for the login table
    $sqlLogin = "UPDATE login SET full_name = ?, email = ?, user_type = ? WHERE user_id = ?";
    $stmtLogin = $conn->prepare($sqlLogin); // Use $conn to prepare the statement

    if ($stmtLogin === false) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare failed for login table: ' . $conn->error]));
    }

    // Bind parameters for login table
    $stmtLogin->bind_param("sssi", $fullName, $email, $userType, $userId); // Use bind_param for mysqli

    // Execute the login update statement
    if ($stmtLogin->execute()) {
        // Prepare the SQL update statement for the users table
        $sqlUsers = "UPDATE users SET full_name = ?, email = ? WHERE user_id = ?";
        $stmtUsers = $conn->prepare($sqlUsers); // Prepare the statement for users table

        if ($stmtUsers === false) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed for users table: ' . $conn->error]));
        }

        // Bind parameters for users table
        $stmtUsers->bind_param("ssi", $fullName, $email, $userId); // Use bind_param for mysqli

        // Execute the users update statement
        if ($stmtUsers->execute()) {
            // Return a success response
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully in both tables.']);
        } else {
            // Return an error response for users table
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user in users table.']);
        }

        // Close the users statement
        $stmtUsers->close();
    } else {
        // Return an error response for login table
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user in login table.']);
    }

    // Close the login statement
    $stmtLogin->close();
} else {
    // Return an error response if input data is missing
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}

// Close the connection
$conn->close();
?>
