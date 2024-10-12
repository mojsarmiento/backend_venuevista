<?php
// Database connection settings
require 'vendor/autoload.php';

$host = 'localhost';
$user = 'root'; // Default XAMPP username
$pass = '';     // Default XAMPP password (empty)
$db = 'venue_management';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Validate password strength (add your own criteria here if needed)
    if (strlen($new_password) < 8 || !preg_match("/[A-Za-z]/", $new_password) || !preg_match("/\d/", $new_password)) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long and contain at least one letter and one number.']);
        exit();
    }

    // Check token validity and reset password
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password reset successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid token or email.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>




