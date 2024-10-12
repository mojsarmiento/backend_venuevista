<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
if (!isset($data->venue_id) || !isset($data->name) || !isset($data->location)) {
    echo json_encode(["message" => "Invalid input"]);
    exit();
}

// Prepare and execute the update statement
$stmt = $conn->prepare("UPDATE venues SET name = ?, location = ?, price_per_hour = ?, availability = ?, category = ?, additional_details = ?, images = ? WHERE venue_id = ?");
$stmt->bind_param("ssissssi", $data->name, $data->location, $data->price_per_hour, $data->availability, $data->category, $data->additional_details, $data->images, $data->venue_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Venue updated successfully"]);
} else {
    echo json_encode(["message" => "Error updating venue"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
