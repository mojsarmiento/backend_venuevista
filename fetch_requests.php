<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

header('Content-Type: application/json');
$host = 'localhost'; // Your database host
$db = 'venue_management'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Fetch requests
$sql = "SELECT id, venue_name, location, request_date, request_time, status FROM requests";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'error' => 'SQL error: ' . $conn->error]);
    $conn->close();
    exit;
}

$requests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// Prepare the response
$response = [
    'success' => true,
    'requests' => $requests,
    'message' => empty($requests) ? 'No requests found' : null,
];

echo json_encode($response);
$conn->close();
?>
