<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // Update if your username is different
$password = ""; // Update if you have a password
$dbname = "venue_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get request payload
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];

  // SQL query to update status to 'Done'
$query = "UPDATE requests SET status='Done' WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}

mysqli_close($conn);
?>