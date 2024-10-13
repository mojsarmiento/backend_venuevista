<?php
// Database connection settings
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'venue_management';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'];
$new_password = $data['new_password'];

// Assume you have the admin's email or ID stored in a session or passed in the request
$email = 'venuevistaadmin@gmail.com'; // Replace with dynamic value if necessary

// Check if the current password is correct
$stmt = $conn->prepare("SELECT password FROM login WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

if ($hashed_password && password_verify($current_password, $hashed_password)) {
    // Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the login table
    $update_login_stmt = $conn->prepare("UPDATE login SET password = ? WHERE email = ?");
    $update_login_stmt->bind_param("ss", $new_hashed_password, $email);

    if ($update_login_stmt->execute()) {
        // Update the password in the users table
        $update_users_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_users_stmt->bind_param("ss", $new_hashed_password, $email);

        if ($update_users_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully in both tables.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating password in users table: ' . $update_users_stmt->error]);
        }

        $update_users_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating password in login table: ' . $update_login_stmt->error]);
    }

    $update_login_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
}

// Close the connection
$conn->close();
?>
