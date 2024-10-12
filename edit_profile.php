<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$host = 'localhost';  // Your database host
$db = 'venue_management'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Handle GET request to fetch user profile
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $sql = "SELECT user_id, full_name, email FROM users WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

// Handle PUT request to update user profile
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT); // Get the PUT data
    $user_id = intval($_PUT['user_id']);
    $full_name = $conn->real_escape_string($_PUT['full_name']);
    $email = $conn->real_escape_string($_PUT['email']);

    $sql = "UPDATE users SET full_name='$full_name', email='$email' WHERE user_id=$user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => 'Profile updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update profile']);
    }
}

// Close the connection
$conn->close();
?>
