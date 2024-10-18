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

// Fetch requests
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$bookings = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// Return JSON response
echo json_encode($bookings);

$conn->close();
?>