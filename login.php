<?php
// Suppress error output for production but log errors
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error.log');

// Database connection settings
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'venue_management';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    sendResponse(false, "Connection failed: " . $conn->connect_error);
}

// Set the character set to utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    sendResponse(false, "Error setting charset: " . $conn->error);
}

// Send JSON header
header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from request body or POST
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : (isset($_POST['email']) ? trim($_POST['email']) : '');
    $password = isset($input['password']) ? trim($input['password']) : (isset($_POST['password']) ? trim($_POST['password']) : '');

    // Validate that both email and password are provided
    if (empty($email) || empty($password)) {
        $conn->close(); // Close the connection before sending response
        sendResponse(false, 'Email and password are required.');
    }

    // Prepare and bind statement to prevent SQL injection
    if ($stmt = $conn->prepare("SELECT id, email, password, user_type FROM login WHERE email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            // Fetch user details
            $stmt->bind_result($id, $db_email, $db_password_hash, $user_type);
            $stmt->fetch();

            // Verify password (assuming password is hashed)
            if (password_verify($password, $db_password_hash)) {
                // Success - include user_type in the response
                $stmt->close();
                $conn->close(); // Close the connection before sending response
                sendResponse(true, 'Login successful.', [
                    'id' => $id,
                    'email' => $db_email,
                    'user_type' => $user_type
                ]);
            } else {
                // Wrong password
                $stmt->close();
                $conn->close(); // Close the connection before sending response
                sendResponse(false, 'Wrong Password.');
            }
        } else {
            // Wrong email
            $stmt->close();
            $conn->close(); // Close the connection before sending response
            sendResponse(false, 'Wrong Email.');
        }
    } else {
        // Database query error
        $conn->close(); // Close the connection before sending response
        sendResponse(false, 'Database query failed: ' . $conn->error);
    }
} else {
    // Invalid request method
    sendResponse(false, 'Invalid request method. Only POST is allowed.');
}

// Function to send JSON response
function sendResponse($success, $message = '', $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}
?>
