<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "venue_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Log received data
    file_put_contents('php://stdout', json_encode($data)); // This will log to the server's stdout (check your server logs)
}

if (isset($data['venue_name']) && isset($data['location']) && isset($data['full_name']) &&
    isset($data['email']) && isset($data['mobile_number']) &&
    isset($data['request_date']) && isset($data['request_time'])) {

    $stmt = $conn->prepare("INSERT INTO requests (venue_name, location, full_name, email, mobile_number, request_date, request_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $data['venue_name'], $data['location'], $data['full_name'],
                    $data['email'], $data['mobile_number'], $data['request_date'], $data['request_time']);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Request submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit request: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>
