<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "venue_management");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT user_id, full_name, email, user_type FROM login"; // Adjust your table and fields as necessary
$result = $mysqli->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
$mysqli->close();
?>
