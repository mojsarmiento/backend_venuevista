<?php
// Enable error reporting for debugging (can be removed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = 'localhost'; // Your server
$db_user = 'root';   // Your MySQL usern
$db_password = '';   // Your MySQL password
$db_name = 'venue_management'; // Your database name

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    // Return JSON error response and terminate the script
    echo json_encode([
        'error' => true,
        'message' => "Connection failed: " . $conn->connect_error
    ]);
    exit(); // Terminate script execution after the error
}

// Set the character set to utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    // Return JSON error response if setting charset fails
    echo json_encode([
        'error' => true,
        'message' => "Error setting charset: " . $conn->error
    ]);
    exit(); // Terminate script execution after the error
}

// Optional: If needed, this can be used for initial testing.
// But typically, the connection script should not return success response.