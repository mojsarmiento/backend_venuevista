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

// SQL query to count users
$query = "SELECT COUNT(*) AS count FROM login";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    // Use 'count' without a leading space
    echo json_encode(['count' => $row['count']]);
} else {
    // Handle query error
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
}

// Close the database connection
$conn->close();
?>
