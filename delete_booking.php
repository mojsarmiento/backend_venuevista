<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (usually empty)
$dbname = "venue_management"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get the input data
$data = json_decode(file_get_contents('php://input'));

// Validate the input
if (!isset($data->id)) {
    echo json_encode(["message" => "Invalid input"]);
    exit();
}

// Prepare and execute the delete statement
$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
$stmt->bind_param("i", $data->id); // Assuming venue_id is an integer

if ($stmt->execute()) {
    echo json_encode(["message" => "Venue deleted successfully"]);
} else {
    echo json_encode(["message" => "Error deleting venue"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
