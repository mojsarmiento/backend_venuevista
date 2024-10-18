<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "venue_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['full_name']) && isset($data['email']) && isset($data['phone_number']) &&
    isset($data['venue_name']) && isset($data['location']) && isset($data['date']) &&
    isset($data['time']) && isset($data['hours']) && isset($data['total_price']) && 
    isset($data['downpayment'])) {

    $stmt = $conn->prepare("INSERT INTO bookings (full_name, email, phone_number, venue_name, location, date, time, hours, total_price, downpayment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $status = 'Pending'; // Set default status
    $stmt->bind_param("sssssssisds", 
        $data['full_name'], $data['email'], $data['phone_number'], 
        $data['venue_name'], $data['location'], $data['date'], 
        $data['time'], $data['hours'], $data['total_price'], 
        $data['downpayment'], $status
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Booking created successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Failed to create booking: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>
