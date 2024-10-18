<?php
// add_venue.php

// Database connection
$host = "localhost";
$db_name = "venue_management"; // Replace with your database name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$data = json_decode(file_get_contents("php://input"));

if (isset($data->name) && isset($data->location)) {
    $name = $conn->real_escape_string($data->name);
    $location = $conn->real_escape_string($data->location);
    $pricePerHour = $conn->real_escape_string($data->price_per_hour);
    $availability = $conn->real_escape_string($data->availability);
    $category = $conn->real_escape_string($data->category);
    $additionalDetails = $conn->real_escape_string($data->additional_details);
    $images = json_encode($data->images);

    // Insert venue into the database
    $sql = "INSERT INTO venues (name, location, price_per_hour, availability, category, additional_details, images) 
            VALUES ('$name', '$location', '$pricePerHour', '$availability', '$category', '$additionalDetails', '$images')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Venue added successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Incomplete data"]);
}

$conn->close();
?>
