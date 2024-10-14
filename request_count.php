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
$query = "SELECT COUNT(*) AS count FROM requests";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

echo json_encode(['count' => $row['count']]);
?>
