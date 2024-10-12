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

$sql = "SELECT * FROM venues"; // Your query to fetch venues
$result = $conn->query($sql);

$venues = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $venues[] = [
            'venue_id' => $row['venue_id'],
            'name' => $row['name'],
            'location' => $row['location'],
            'images' => json_decode($row['images']) ?: [], // Ensure this is a JSON string or empty array
            'pricePerHour' => (float)$row['price_per_hour'], // Ensure this is a float
            'availability' => $row['availability'],
            'category' => $row['category'],
            'additionalDetails' => $row['additional_details'],
            // Check if ratings is null or empty and handle accordingly
            'ratings' => $row['ratings'] ? json_decode($row['ratings']) : [] // Handle null values
        ];
    }
} else {
    echo json_encode(['error' => 'No venues found']);
    exit;
}

// Output the venues as JSON
echo json_encode($venues); 
$conn->close();
?>
