<?php
header("Content-Type: application/json");

$servername = "localhost"; // Change if necessary
$username = "root"; // Change to your MySQL username
$password = ""; // Change to your MySQL password
$dbname = "venue_management"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['id']; // Assuming the user ID is passed
$fullName = $data['full_name'];
$email = $data['email'];

// Update query
$sql = "UPDATE users SET full_name=?, email=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $fullName, $email, $userId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error updating profile: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
