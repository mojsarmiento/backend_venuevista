<?php
// upload_permit.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection file
    include 'db_connection.php';

    // Get the user ID from POST request
    $userId = $_POST['user_id']; // Assuming you're passing user_id in the POST request

    // Handle file upload
    $targetDir = "uploads/";
    
    // Create the directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["business_permit"]["name"]); // Unique file name
    $fileUploadSuccess = move_uploaded_file($_FILES["business_permit"]["tmp_name"], $targetFile);

    if ($fileUploadSuccess) {
        // Save application in database
        $query = "INSERT INTO venue_owner_applications (user_id, business_permit, status) VALUES ('$userId', '$targetFile', 'pending')";

        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Business permit uploaded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
